variable "aws_region" {
  description = "AWS region for deployment"
  type        = string
  default     = "us-east-1"
}

variable "github_repository" {
  description = "GitHub repository for Docker images"
  type        = string
}

variable "mysql_password" {
  description = "MySQL database password"
  type        = string
  sensitive   = true
}