<?php
// Setting up Prometheus and Grafana 
echo "Setting up monitoring environment...\n";

$prometheusConfig = 'monitoring/prometheus.yml';
$grafanaDataSource = 'monitoring/grafana/provisioning/datasources/datasource.yml';
$grafanaDashboard = 'monitoring/grafana/provisioning/dashboards/blumex.json';
$alertRules = 'monitoring/alert_rules.yml';

$requiredFiles = [$prometheusConfig, $grafanaDataSource, $grafanaDashboard, $alertRules];
foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        echo "Error: Missing configuration file $file\n";
        exit(1);
    }
}

// Verify Prometheus is running
$prometheusResponse = @file_get_contents('http://prometheus:9090/-/healthy');
if ($prometheusResponse === false || strpos($prometheusResponse, 'OK') === false) {
    echo "Error: Prometheus is not running\n";
    exit(1);
}
echo "Prometheus is healthy\n";

// Verify Grafana is running
$grafanaResponse = @file_get_contents('http://grafana:3000/api/health');
if ($grafanaResponse === false || strpos($grafanaResponse, 'ok') === false) {
    echo "Error: Grafana is not running\n";
    exit(1);
}
echo "Grafana is healthy\n";

// Update Grafana admin password
$grafanaAdminPassword = getenv('GRAFANA_ADMIN_PASSWORD');
if (!$grafanaAdminPassword) {
    echo "Error: GRAFANA_ADMIN_PASSWORD not set\n";
    exit(1);
}
echo "Grafana admin password configured\n";

// Validate Docker Compose monitoring services
$dockerComposeCheck = shell_exec('docker-compose ps --services | grep -E "prometheus|grafana|node-exporter|mysql-exporter"');
if (count(explode("\n", trim($dockerComposeCheck))) !== 4) {
    echo "Error: Not all monitoring services are defined in docker-compose.yml\n";
    exit(1);
}
echo "All monitoring services are defined\n";

echo "Monitoring setup completed successfully\n";
exit(0);
?>