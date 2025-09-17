<?php
/**
 * User Management Example for MT5 Manager API
 * 
 * This example demonstrates user-related operations:
 * 1. Get user information
 * 2. Create a new user
 * 3. Update user details
 * 4. Manage user deposits/withdrawals
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use D4T\MT5Sdk\MT5Manager\BasicApi;
use D4T\MT5Sdk\MT5Manager\UserApi;
use D4T\MT5Sdk\Configuration;
use D4T\MT5Sdk\Models\User;
use D4T\MT5Sdk\Models\BalanceType;
use GuzzleHttp\Client;

// Configuration
$config = Configuration::getDefaultConfiguration();
$host = getenv('MT5_API_HOST') ?: 'localhost:8080';
$config->setHost("http://{$host}/v1");

$client = new Client();
$basicApi = new BasicApi($client, $config);
$userApi = new UserApi($client, $config);

try {
    echo "=== MT5 User Management Example ===\n\n";
    
    // Initialize and get token
    echo "Initializing API...\n";
    $initResult = $basicApi->initGet();
    
    if (!$initResult || !isset($initResult['token'])) {
        throw new Exception("Failed to initialize API");
    }
    
    $config->setAccessToken($initResult['token']);
    echo "✓ API initialized successfully\n\n";
    
    // Example user login (you should replace this with actual values)
    $userLogin = getenv('MT5_EXAMPLE_USER') ?: '12345';
    $groupName = getenv('MT5_EXAMPLE_GROUP') ?: 'demo';
    
    // 1. Get user information
    echo "1. Getting user information for login: {$userLogin}\n";
    try {
        $userInfo = $userApi->userUserLoginGet($userLogin);
        echo "✓ User found:\n";
        echo "   Login: " . ($userInfo['login'] ?? 'N/A') . "\n";
        echo "   Name: " . ($userInfo['name'] ?? 'N/A') . "\n";
        echo "   Email: " . ($userInfo['email'] ?? 'N/A') . "\n";
        echo "   Group: " . ($userInfo['group'] ?? 'N/A') . "\n\n";
    } catch (Exception $e) {
        echo "⚠ User not found: " . $e->getMessage() . "\n\n";
    }
    
    // 2. Get users by group
    echo "2. Getting users in group: {$groupName}\n";
    try {
        $users = $userApi->usersGroupGet($groupName);
        echo "✓ Found " . count($users) . " users in group {$groupName}\n";
        
        if (count($users) > 0) {
            echo "   First few users:\n";
            foreach (array_slice($users, 0, 3) as $user) {
                echo "   - Login: " . ($user['login'] ?? 'N/A') . 
                     ", Name: " . ($user['name'] ?? 'N/A') . "\n";
            }
        }
        echo "\n";
    } catch (Exception $e) {
        echo "⚠ Could not retrieve users: " . $e->getMessage() . "\n\n";
    }
    
    // 3. Example: Create a new user (commented out to avoid creating actual users)
    echo "3. User creation example (dry run):\n";
    echo "   This would create a new user with the following details:\n";
    
    $newUser = new User([
        'login' => 99999,
        'name' => 'Test User',
        'email' => 'test@example.com',
        'group' => $groupName,
        'password' => 'SecurePassword123!',
        'phone' => '+1234567890',
        'country' => 'US',
        'state' => 'NY',
        'city' => 'New York',
        'address' => '123 Test Street',
        'zip_code' => '10001'
    ]);
    
    echo "   Login: " . $newUser->getLogin() . "\n";
    echo "   Name: " . $newUser->getName() . "\n";
    echo "   Email: " . $newUser->getEmail() . "\n";
    echo "   Group: " . $newUser->getGroup() . "\n";
    echo "   (User creation is commented out to prevent accidental creation)\n\n";
    
    /*
    // Uncomment to actually create the user
    try {
        $createResult = $userApi->userAddPost($newUser);
        echo "✓ User created successfully: " . json_encode($createResult) . "\n\n";
    } catch (Exception $e) {
        echo "✗ Failed to create user: " . $e->getMessage() . "\n\n";
    }
    */
    
    // 4. Example: Deposit operation
    echo "4. Deposit operation example (dry run):\n";
    echo "   This would deposit \$100 to user {$userLogin}\n";
    
    $depositData = new BalanceType([
        'login' => $userLogin,
        'type' => 'DEAL_BONUS', // or other appropriate type
        'balance' => 100.00,
        'comment' => 'Example deposit via API'
    ]);
    
    echo "   Login: " . $depositData->getLogin() . "\n";
    echo "   Amount: $" . $depositData->getBalance() . "\n";
    echo "   Comment: " . $depositData->getComment() . "\n";
    echo "   (Deposit is commented out to prevent actual transactions)\n\n";
    
    /*
    // Uncomment to actually perform the deposit
    try {
        $depositResult = $userApi->userDepositPost($depositData);
        echo "✓ Deposit successful: " . json_encode($depositResult) . "\n\n";
    } catch (Exception $e) {
        echo "✗ Deposit failed: " . $e->getMessage() . "\n\n";
    }
    */
    
    echo "=== User management example completed ===\n";
    echo "Note: Actual user creation and deposit operations are commented out.\n";
    echo "Uncomment the relevant sections to perform real operations.\n";
    
} catch (Exception $e) {
    echo "✗ Error occurred: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}