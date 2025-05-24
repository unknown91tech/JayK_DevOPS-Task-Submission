<?php
// Provisioning infrastructure using Terraform
echo "Starting infrastructure provisioning...\n";

$awsRegion = getenv('AWS_REGION');
$githubRepo = getenv('GITHUB_REPOSITORY');
$mysqlPassword = getenv('MYSQL_PASSWORD');
$action = isset($argv[1]) ? $argv[1] : 'apply';

// Validate environment variables
if (!$awsRegion || !$githubRepo || !$mysqlPassword) {
    echo "Error: Missing required environment variables (AWS_REGION, GITHUB_REPOSITORY, MYSQL_PASSWORD).\n";
    exit(1);
}

// Change to IaC directory
chdir('iac');

// Initialize Terraform
$init = shell_exec('terraform init');
if (strpos($init, 'successfully initialized') === false) {
    echo "Terraform init failed:\n$init";
    exit(1);
}

// Apply or destroy infrastructure
$command = "terraform $action -auto-approve -var=\"aws_region=$awsRegion\" -var=\"github_repository=$githubRepo\" -var=\"mysql_password=$mysqlPassword\"";
$result = shell_exec($command);

if (strpos($result, 'Apply complete') === false && $action === 'apply') {
    echo "Infrastructure provisioning failed:\n$result";
    exit(1);
} elseif (strpos($result, 'Destroy complete') === false && $action === 'destroy') {
    echo "Infrastructure destruction failed:\n$result";
    exit(1);
}

echo "Infrastructure $action completed successfully.\n";
exit(0);
?>