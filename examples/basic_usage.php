<?php
/**
 * Basic usage example for MT5 Manager API
 * 
 * This example demonstrates how to:
 * 1. Initialize the API connection
 * 2. Get authentication token
 * 3. Perform basic operations
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use D4T\MT5Sdk\MT5Manager\BasicApi;
use D4T\MT5Sdk\MT5Manager\AccountApi;
use D4T\MT5Sdk\MT5Manager\UserApi;
use D4T\MT5Sdk\Configuration;
use GuzzleHttp\Client;

// Configuration from environment variables or defaults
$config = Configuration::getDefaultConfiguration();

// Set the host from environment variable or default
$host = getenv('MT5_API_HOST') ?: 'localhost:8080';
$config->setHost("http://{$host}/v1");

// Create HTTP client
$client = new Client();

// Create API instances
$basicApi = new BasicApi($client, $config);
$accountApi = new AccountApi($client, $config);
$userApi = new UserApi($client, $config);

try {
    echo "=== MT5 Manager API Basic Usage Example ===\n\n";
    
    // Step 1: Initialize and get token
    echo "1. Initializing API connection...\n";
    $initResult = $basicApi->initGet();
    
    if ($initResult && isset($initResult['token'])) {
        $token = $initResult['token'];
        echo "✓ Successfully initialized. Token: " . substr($token, 0, 20) . "...\n\n";
        
        // Set the bearer token for authentication
        $config->setAccessToken($token);
        
        // Step 2: Ping the API
        echo "2. Pinging API...\n";
        $pingResult = $basicApi->pingGet();
        echo "✓ Ping successful: " . json_encode($pingResult) . "\n\n";
        
        // Step 3: Get account information (example)
        echo "3. Example operations:\n";
        
        // You would replace 'your_login' with an actual login
        $exampleLogin = getenv('MT5_EXAMPLE_LOGIN') ?: 'demo_login';
        
        echo "   - Attempting to get account info for login: {$exampleLogin}\n";
        
        try {
            $accountInfo = $accountApi->accountLoginGet($exampleLogin);
            echo "   ✓ Account info retrieved successfully\n";
            echo "     Account details: " . json_encode($accountInfo, JSON_PRETTY_PRINT) . "\n";
        } catch (Exception $e) {
            echo "   ⚠ Account not found or insufficient permissions: " . $e->getMessage() . "\n";
        }
        
        echo "\n=== Example completed successfully ===\n";
        
    } else {
        echo "✗ Failed to initialize API connection\n";
        echo "Response: " . json_encode($initResult) . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error occurred: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nFor more examples, check the other files in the examples/ directory.\n";