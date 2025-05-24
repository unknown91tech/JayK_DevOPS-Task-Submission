# Configuring the AWS provider
provider "aws" {
  region = var.aws_region
}

# VPC for network isolation
resource "aws_vpc" "blumex_vpc" {
  cidr_block           = "10.0.0.0/16"
  enable_dns_hostnames = true
  enable_dns_support   = true
  tags = {
    Name = "blumex-staging-vpc"
  }
}

# Subnets for public and private resources
resource "aws_subnet" "public_subnet" {
  vpc_id            = aws_vpc.blumex_vpc.id
  cidr_block        = "10.0.1.0/24"
  availability_zone = "${var.aws_region}a"
  tags = {
    Name = "blumex-public-subnet"
  }
}

resource "aws_subnet" "private_subnet" {
  vpc_id            = aws_vpc.blumex_vpc.id
  cidr_block        = "10.0.2.0/24"
  availability_zone = "${var.aws_region}a"
  tags = {
    Name = "blumex-private-subnet"
  }
}

# Internet Gateway for public subnet
resource "aws_internet_gateway" "blumex_igw" {
  vpc_id = aws_vpc.blumex_vpc.id
  tags = {
    Name = "blumex-igw"
  }
}

# Route Table for public subnet
resource "aws_route_table" "public_route_table" {
  vpc_id = aws_vpc.blumex_vpc.id
  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.blumex_igw.id
  }
  tags = {
    Name = "blumex-public-route-table"
  }
}

resource "aws_route_table_association" "public_subnet_association" {
  subnet_id      = aws_subnet.public_subnet.id
  route_table_id = aws_route_table.public_route_table.id
}

# Security Groups
resource "aws_security_group" "app_sg" {
  vpc_id = aws_vpc.blumex_vpc.id
  name   = "blumex-app-sg"

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name = "blumex-app-sg"
  }
}

resource "aws_security_group" "blumechain_sg" {
  vpc_id = aws_vpc.blumex_vpc.id
  name   = "blumechain-node-sg"

  ingress {
    from_port   = 8545
    to_port     = 8545
    protocol    = "tcp"
    cidr_blocks = ["10.0.0.0/16"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name = "blumechain-node-sg"
  }
}

resource "aws_security_group" "rds_sg" {
  vpc_id = aws_vpc.blumex_vpc.id
  name   = "blumex-rds-sg"

  ingress {
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    security_groups = [aws_security_group.app_sg.id]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags = {
    Name = "blumex-rds-sg"
  }
}

# ECS Cluster for BLUMEX Application
resource "aws_ecs_cluster" "blumex_cluster" {
  name = "blumex-staging-cluster"
}

resource "aws_ecs_task_definition" "blumex_task" {
  family                   = "blumex-task"
  network_mode             = "awsvpc"
  requires_compatibilities = ["FARGATE"]
  cpu                      = "256"
  memory                   = "512"
  execution_role_arn       = aws_iam_role.ecs_task_execution_role.arn

  container_definitions = jsonencode([
    {
      name      = "blumex-app"
      image     = "ghcr.io/${var.github_repository}/blumex-app:latest"
      essential = true
      portMappings = [
        {
          containerPort = 80
          hostPort      = 80
          protocol      = "tcp"
        }
      ]
      environment = [
        { name = "APP_ENV", value = "production" },
        { name = "DB_HOST", value = aws_db_instance.blumex_rds.endpoint },
        { name = "DB_NAME", value = "blumex" },
        { name = "DB_USER", value = "blumexuser" },
        { name = "DB_PASSWORD", value = var.mysql_password },
        { name = "BLUMECHAIN_NODE_URL", value = "http://${aws_instance.blumechain_node.private_ip}:8545" }
      ]
    },
    {
      name      = "nginx"
      image     = "nginx:latest"
      essential = true
      portMappings = [
        {
          containerPort = 80
          hostPort      = 80
          protocol      = "tcp"
        }
      ]
      volumesFrom = [
        {
          sourceContainer = "blumex-app"
        }
      ]
    }
  ])
}

resource "aws_ecs_service" "blumex_service" {
  name            = "blumex-service"
  cluster         = aws_ecs_cluster.blumex_cluster.id
  task_definition = aws_ecs_task_definition.blumex_task.arn
  desired_count   = 1
  launch_type     = "FARGATE"

  network_configuration {
    subnets          = [aws_subnet.public_subnet.id]
    security_groups  = [aws_security_group.app_sg.id]
    assign_public_ip = true
  }
}

# EC2 Instance for BLUMECHAIN Node
resource "aws_instance" "blumechain_node" {
  ami                    = "ami-0c55b159cbfafe1f0" # Amazon Linux 2 AMI (adjust as needed)
  instance_type          = "t3.micro"
  subnet_id              = aws_subnet.private_subnet.id
  vpc_security_group_ids = [aws_security_group.blumechain_sg.id]
  user_data = <<-EOF
              #!/bin/bash
              docker run -d -p 8545:8545 ghcr.io/${var.github_repository}/blumechain-node:latest
              EOF
  tags = {
    Name = "blumechain-node"
  }
}

# RDS Instance for MySQL
resource "aws_db_instance" "blumex_rds" {
  identifier             = "blumex-staging-db"
  engine                 = "mysql"
  engine_version         = "8.0"
  instance_class         = "db.t3.micro"
  allocated_storage      = 20
  username               = "blumexuser"
  password               = var.mysql_password
  db_name                = "blumex"
  vpc_security_group_ids = [aws_security_group.rds_sg.id]
  db_subnet_group_name   = aws_db_subnet_group.blumex_db_subnet_group.name
  backup_retention_period = 7
  multi_az               = true
  skip_final_snapshot    = true
}

resource "aws_db_subnet_group" "blumex_db_subnet_group" {
  name       = "blumex-db-subnet-group"
  subnet_ids = [aws_subnet.private_subnet.id]
  tags = {
    Name = "blumex-db-subnet-group"
  }
}

# IAM Role for ECS Task Execution
resource "aws_iam_role" "ecs_task_execution_role" {
  name = "blumex-ecs-task-execution-role"
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Action = "sts:AssumeRole"
        Effect = "Allow"
        Principal = {
          Service = "ecs-tasks.amazonaws.com"
        }
      }
    ]
  })
}

resource "aws_iam_role_policy_attachment" "ecs_task_execution_policy" {
  role       = aws_iam_role.ecs_task_execution_role.name
  policy_arn = "arn:aws:iam::aws:policy/service-role/AmazonECSTaskExecutionRolePolicy"
}