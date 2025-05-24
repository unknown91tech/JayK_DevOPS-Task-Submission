<?php
// Running smoke tests 
echo "Running smoke tests...\n";

$stagingUrl = getenv('STAGING_URL');

try {
    // Test application endpoint
    $ch = curl_init("$stagingUrl/health");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if ($response === false || strpos($response, 'OK') === false) {
        echo "Application health check failed.\n";
        exit(1);
    }
    echo "Application health check passed.\n";

    // Test database connectivity 
    $ch = curl_init("$stagingUrl/db-check");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if ($response === false || strpos($response, 'OK') === false) {
        echo "Database connectivity check failed.\n";
        exit(1);
    }
    echo "Database connectivity check passed.\n";

    echo "Smoke tests passed successfully.\n";
    exit(0);
} catch (Exception $e) {
    echo "Smoke tests failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>