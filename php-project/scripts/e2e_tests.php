<?php
// Running end-to-end tests 
require_once 'vendor/autoload.php';

echo "Running E2E tests...\n";

$dbHost = getenv('DB_HOST');
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD');
$nodeUrl = getenv('BLUMECHAIN_NODE_URL');

try {
    // Test database connection
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful.\n";

    // Test BLUMECHAIN node 
    $ch = curl_init($nodeUrl . '/health');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    if ($response === false || strpos($response, 'OK') === false) {
        echo "BLUMECHAIN node health check failed.\n";
        exit(1);
    }
    echo "BLUMECHAIN node health check passed.\n";

    // Simulate a transaction flow 
    $stmt = $pdo->query("INSERT INTO transactions (user_id, amount) VALUES (1, 100)");
    if ($stmt->rowCount() > 0) {
        echo "Transaction simulation successful.\n";
    } else {
        echo "Transaction simulation failed.\n";
        exit(1);
    }

    echo "E2E tests passed successfully.\n";
    exit(0);
} catch (Exception $e) {
    echo "E2E tests failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>