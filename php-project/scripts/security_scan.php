<?php
// Running security scans and Docker images
echo "Running Composer security check...\n";
$composerAudit = shell_exec('composer audit');
if (strpos($composerAudit, 'vulnerabilities') !== false) {
    echo "Security vulnerabilities found in dependencies:\n$composerAudit";
    exit(1);
}

echo "Running Trivy for container scanning...\n";
$trivy = shell_exec('trivy image blumex-app:latest');
if (strpos($trivy, 'CRITICAL') !== false || strpos($trivy, 'HIGH') !== false) {
    echo "Critical or high vulnerabilities found:\n$trivy";
    exit(1);
}

echo "Security scans passed successfully.\n";
exit(0);
?>  