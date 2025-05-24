<?php
// Exposing Prometheus metrics for BLUMEX application
header('Content-Type: text/plain; version=0.0.4; charset=utf-8');

// Simulated metrics (replace with actual application metrics)
$httpRequestsTotal = 100; // Example: Total HTTP requests
$httpErrorsTotal = 5;     // Example: HTTP 500 errors
$authFailuresTotal = 2;   // Example: Authentication failures
$responseTime = 0.5;      // Example: Response time in seconds
$transactionAmount = 50000; // Example: Transaction amount

echo "# HELP http_requests_total Total number of HTTP requests\n";
echo "# TYPE http_requests_total counter\n";
echo "http_requests_total $httpRequestsTotal\n";

echo "# HELP http_requests_total Total number of HTTP 500 errors\n";
echo "# TYPE http_requests_total counter\n";
echo "http_requests_total{status=\"500\"} $httpErrorsTotal\n";

echo "# HELP authentication_failures_total Total number of authentication failures\n";
echo "# TYPE authentication_failures_total counter\n";
echo "authentication_failures_total $authFailuresTotal\n";

echo "# HELP http_request_duration_seconds HTTP request duration\n";
echo "# TYPE http_request_duration_seconds histogram\n";
echo "http_request_duration_seconds_bucket{le=\"0.1\"} " . ($responseTime <= 0.1 ? 1 : 0) . "\n";
echo "http_request_duration_seconds_bucket{le=\"0.5\"} " . ($responseTime <= 0.5 ? 1 : 0) . "\n";
echo "http_request_duration_seconds_bucket{le=\"1\"} " . ($responseTime <= 1 ? 1 : 0) . "\n";
echo "http_request_duration_seconds_bucket{le=\"+Inf\"} 1\n";
echo "http_request_duration_seconds_sum $responseTime\n";
echo "http_request_duration_seconds_count 1\n";

echo "# HELP transaction_amount_total Total transaction amount\n";
echo "# TYPE transaction_amount_total gauge\n";
echo "transaction_amount_total $transactionAmount\n";
?>