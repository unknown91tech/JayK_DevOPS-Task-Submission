<?php
// Deploying BLUMEX 
echo "Deploying to AWS ECS...\n";

$awsRegion = getenv('AWS_REGION');
$cluster = 'blumex-staging-cluster';
$service = 'blumex-service';

$command = "aws ecs update-service --cluster $cluster --service $service --force-new-deployment --region $awsRegion";
$result = shell_exec($command);

if (strpos($result, 'service') === false) {
    echo "Deployment failed:\n$result";
    exit(1);
}

echo "Deployment successful.\n";
exit(0);
?>