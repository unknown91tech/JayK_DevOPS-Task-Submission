<?php
// Rolling back 
echo "Initiating rollback...\n";

$awsRegion = getenv('AWS_REGION');
$cluster = 'blumex-staging-cluster';
$service = 'blumex-service';

$command = "aws ecs update-service --cluster $cluster --service $service --task-definition previous-task-definition --region $awsRegion";
$result = shell_exec($command);

if (strpos($result, 'service') === false) {
    echo "Rollback failed:\n$result";
    exit(1);
}

echo "Rollback successful.\n";
exit(0);
?>