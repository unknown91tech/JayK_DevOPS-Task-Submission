output "ecs_service_name" {
  description = "Name of the ECS service"
  value       = aws_ecs_service.blumex_service.name
}

output "rds_endpoint" {
  description = "Endpoint of the RDS instance"
  value       = aws_db_instance.blumex_rds.endpoint
}

output "blumechain_node_ip" {
  description = "Private IP of the BLUMECHAIN node"
  value       = aws_instance.blumechain_node.private_ip
}