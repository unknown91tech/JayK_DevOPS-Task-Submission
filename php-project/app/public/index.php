<?php
// Kick things off 
require_once dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Blumex\Auth;

// Load environment vars
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Grab the request details
$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

// Handle auth for all routes except the health check
if ($uri !== '/health') {
    $jwtToken = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';
    if (!Auth::verifyJWT($jwtToken)) {
        http_response_code(401);
        header('Content-Type: application/json');
        die(json_encode(['error' => 'Access denied, invalid token']));
    }
}

// Route the request like a boss
switch ($uri) {
    case '/health':
        // Quick pulse check
        header('Content-Type: application/json');
        echo json_encode(['status' => 'All good']);
        break;

    case '/swap':
        // Dive into swap logic
        require_once dirname(__DIR__) . '/src/swap.php';
        handleSwap($httpMethod);
        break;

    case '/stake':
        // Stake it up
        require_once dirname(__DIR__) . '/src/stake.php';
        handleStake($httpMethod);
        break;

    case '/assets':
        // Asset management time
        require_once dirname(__DIR__) . '/src/assets.php';
        handleAssets($httpMethod);
        break;

    case '/metrics':
        // Metrics for the curious
        require_once dirname(__DIR__, 2) . '/monitoring/metrics.php';
        handleMetrics();
        break;

    default:
        // Whoops, wrong turn
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Route not found, try again']);
        break;
}

exit;