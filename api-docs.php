<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MT5 Manager API - API Documentation</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007cba;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
            padding: 10px;
            background-color: #f8f9fa;
            border-left: 4px solid #007cba;
        }
        .api-section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .endpoint {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }
        .method.get { background-color: #28a745; }
        .method.post { background-color: #007bff; }
        .method.delete { background-color: #dc3545; }
        .code {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            margin: 10px 0;
        }
        pre {
            margin: 0;
            white-space: pre-wrap;
        }
        a {
            color: #007cba;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            margin-right: 20px;
            padding: 8px 16px;
            background-color: #007cba;
            color: white;
            border-radius: 4px;
        }
        .nav a:hover {
            background-color: #0056b3;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">← Back to Dashboard</a>
            <a href="model-docs.php">Data Models</a>
        </div>
        
        <h1>📚 MT5 Manager API - Endpoints Documentation</h1>
        
        <p>All URIs are relative to <code>/v1</code> base path.</p>
        
        <?php
        // Simple API documentation display
        $apiEndpoints = [
            'BasicApi' => [
                'title' => 'Basic API Operations',
                'methods' => [
                    ['name' => 'initGet', 'http' => 'GET /init/', 'description' => 'Initialize manager connection and get authentication token'],
                    ['name' => 'pingGet', 'http' => 'GET /ping/', 'description' => 'Test API connectivity']
                ]
            ],
            'AccountApi' => [
                'title' => 'Account Management',
                'methods' => [
                    ['name' => 'accountLoginGet', 'http' => 'GET /account/{login}', 'description' => 'Get account information by login']
                ]
            ],
            'UserApi' => [
                'title' => 'User Management',
                'methods' => [
                    ['name' => 'userUserLoginGet', 'http' => 'GET /user/{user_login}', 'description' => 'Get user by login'],
                    ['name' => 'userAddPost', 'http' => 'POST /user/add', 'description' => 'Create new user'],
                    ['name' => 'updateUser', 'http' => 'POST /user/update', 'description' => 'Update existing user'],
                    ['name' => 'userUserLoginDelete', 'http' => 'DELETE /user/{user_login}', 'description' => 'Delete user'],
                    ['name' => 'userDepositPost', 'http' => 'POST /user/deposit', 'description' => 'Deposit to user account'],
                    ['name' => 'userWithdrawPost', 'http' => 'POST /user/withdraw', 'description' => 'Withdraw from user account'],
                    ['name' => 'usersGroupGet', 'http' => 'GET /users/{group}', 'description' => 'Get users by group'],
                    ['name' => 'userResetPwdPost', 'http' => 'POST /user/reset_pwd', 'description' => 'Reset user password']
                ]
            ],
            'TradeApi' => [
                'title' => 'Trading Operations',
                'methods' => [
                    ['name' => 'positionsUserLoginGet', 'http' => 'GET /positions/{user_login}', 'description' => 'Get user positions'],
                    ['name' => 'ordersUserLoginGet', 'http' => 'GET /orders/{user_login}', 'description' => 'Get user orders'],
                    ['name' => 'dealsUserLoginGet', 'http' => 'GET /deals/{user_login}', 'description' => 'Get user deals/trades'],
                    ['name' => 'closeAllUserLoginDelete', 'http' => 'DELETE /close_all/{user_login}', 'description' => 'Close all user positions']
                ]
            ],
            'GroupApi' => [
                'title' => 'Group Management',
                'methods' => [
                    ['name' => 'groupsGet', 'http' => 'GET /groups/', 'description' => 'Get list of groups'],
                    ['name' => 'groupGroupNameGet', 'http' => 'GET /group/{group_name}', 'description' => 'Get group by name']
                ]
            ],
            'SymbolApi' => [
                'title' => 'Symbol/Market Data',
                'methods' => [
                    ['name' => 'symbolsGet', 'http' => 'GET /symbols/', 'description' => 'Get list of trading symbols']
                ]
            ]
        ];
        
        foreach ($apiEndpoints as $apiName => $apiData) {
            echo '<div class="api-section">';
            echo '<h2>' . htmlspecialchars($apiData['title']) . '</h2>';
            
            foreach ($apiData['methods'] as $method) {
                $httpParts = explode(' ', $method['http']);
                $httpMethod = strtolower(trim($httpParts[0]));
                $endpoint = isset($httpParts[1]) ? $httpParts[1] : '';
                
                echo '<div class="endpoint">';
                echo '<span class="method ' . $httpMethod . '">' . strtoupper($httpMethod) . '</span>';
                echo '<strong>' . htmlspecialchars($endpoint) . '</strong>';
                echo '<p>' . htmlspecialchars($method['description']) . '</p>';
                echo '</div>';
            }
            
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <h2>🚀 Quick Start</h2>
            <p>To use any of these endpoints:</p>
            <ol>
                <li>First call <code>GET /init/</code> to get an authentication token</li>
                <li>Use the token in subsequent requests via Authorization header</li>
                <li>All endpoints require proper MT5 Manager credentials</li>
            </ol>
            
            <div class="code">
<pre>// Basic usage pattern
$basicApi = new BasicApi($client, $config);

// 1. Get authentication token
$initResult = $basicApi->initGet();
$token = $initResult['token'];

// 2. Set token for subsequent requests  
$config->setAccessToken($token);

// 3. Use other API endpoints
$accountApi = new AccountApi($client, $config);
$account = $accountApi->accountLoginGet('12345');
</pre>
            </div>
        </div>
        
        <div class="nav" style="margin-top: 30px;">
            <a href="index.php">← Back to Dashboard</a>
            <a href="model-docs.php">View Data Models →</a>
        </div>
    </div>
</body>
</html>