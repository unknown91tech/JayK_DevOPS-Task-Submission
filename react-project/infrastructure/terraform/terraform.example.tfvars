aws_region   = "us-west-2"
project_name = "nextjs-auth"
environment  = "production"

vpc_cidr         = "10.0.0.0/16"
private_subnets  = ["10.0.1.0/24", "10.0.2.0/24", "10.0.3.0/24"]
public_subnets   = ["10.0.101.0/24", "10.0.102.0/24", "10.0.103.0/24"]
database_subnets = ["10.0.201.0/24", "10.0.202.0/24", "10.0.203.0/24"]

node_instance_types     = ["t3.medium", "t3.large"]
node_group_min_size     = 2
node_group_max_size     = 10
node_group_desired_size = 3

db_instance_class       = "db.t3.small"
db_allocated_storage    = 20
db_max_allocated_storage = 100
db_name                 = "nextjs_auth"
db_username            = "postgres"
db_password            = "your-secure-password"

domain_name     = "yourapp.com"
certificate_arn = "arn:aws:acm:us-west-2:123456789012:certificate/12345678-1234-1234-1234-123456789012"