<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MT5 Manager API - PHP SDK</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
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
        }
        .status {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .status.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .status.warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 20px 0;
        }
        .info-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007cba;
        }
        .code {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            overflow-x: auto;
            margin: 10px 0;
        }
        .examples {
            background: #e9ecef;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .examples ul {
            list-style-type: none;
            padding: 0;
        }
        .examples li {
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .examples li:last-child {
            border-bottom: none;
        }
        a {
            color: #007cba;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 MT5 Manager API - PHP SDK</h1>
        
        <?php
        // Check if we're running in Docker
        $isDocker = file_exists('/.dockerenv');
        $composerInstalled = file_exists(__DIR__ . '/vendor/autoload.php');
        
        // Environment checks
        $phpVersion = PHP_VERSION;
        $requiredExtensions = ['curl', 'json', 'mbstring'];
        $missingExtensions = [];
        
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                $missingExtensions[] = $ext;
            }
        }
        ?>
        
        <h2>📊 System Status</h2>
        
        <div class="status <?php echo $isDocker ? 'success' : 'warning'; ?>">
            <strong>Environment:</strong> <?php echo $isDocker ? '🐳 Docker Container' : '💻 Local Installation'; ?>
        </div>
        
        <div class="status <?php echo version_compare($phpVersion, '7.4.0', '>=') ? 'success' : 'error'; ?>">
            <strong>PHP Version:</strong> <?php echo $phpVersion; ?> 
            <?php echo version_compare($phpVersion, '7.4.0', '>=') ? '✅' : '❌ (Requires PHP 7.4+)'; ?>
        </div>
        
        <div class="status <?php echo empty($missingExtensions) ? 'success' : 'error'; ?>">
            <strong>Required Extensions:</strong> 
            <?php 
            if (empty($missingExtensions)) {
                echo '✅ All required extensions are loaded';
            } else {
                echo '❌ Missing: ' . implode(', ', $missingExtensions);
            }
            ?>
        </div>
        
        <div class="status <?php echo $composerInstalled ? 'success' : 'error'; ?>">
            <strong>Dependencies:</strong> 
            <?php echo $composerInstalled ? '✅ Composer packages installed' : '❌ Run composer install'; ?>
        </div>
        
        <div class="info-grid">
            <div class="info-card">
                <h3>🔧 Configuration</h3>
                <p><strong>MT5 Host:</strong> <?php echo getenv('MT5_API_HOST') ?: 'Not configured'; ?></p>
                <p><strong>MT5 Port:</strong> <?php echo getenv('MT5_API_PORT') ?: '443 (default)'; ?></p>
                <p><strong>Username:</strong> <?php echo getenv('MT5_API_USERNAME') ? '✅ Set' : '❌ Not set'; ?></p>
            </div>
            
            <div class="info-card">
                <h3>📁 SDK Info</h3>
                <p><strong>API Version:</strong> 0.0.3-oas3</p>
                <p><strong>SDK Namespace:</strong> D4T\MT5Sdk</p>
                <p><strong>Generated by:</strong> Swagger Codegen</p>
            </div>
        </div>
        
        <h2>🚀 Quick Start</h2>
        
        <?php if ($isDocker): ?>
        <div class="examples">
            <h3>Docker Commands</h3>
            <ul>
                <li><strong>Run Basic Example:</strong> <code>./examples/docker_run_examples.sh basic</code></li>
                <li><strong>Run User Management:</strong> <code>./examples/docker_run_examples.sh user</code></li>
                <li><strong>Run Trading Operations:</strong> <code>./examples/docker_run_examples.sh trading</code></li>
                <li><strong>Run All Examples:</strong> <code>./examples/docker_run_examples.sh all</code></li>
            </ul>
        </div>
        <?php endif; ?>
        
        <div class="code">
            <strong>Basic PHP Usage:</strong><br>
&lt;?php<br>
require_once(__DIR__ . '/vendor/autoload.php');<br><br>

use D4T\MT5Sdk\MT5Manager\BasicApi;<br>
use D4T\MT5Sdk\Configuration;<br>
use GuzzleHttp\Client;<br><br>

$config = Configuration::getDefaultConfiguration();<br>
$config->setHost('http://your-mt5-server.com/v1');<br><br>

$client = new Client();<br>
$basicApi = new BasicApi($client, $config);<br><br>

// Initialize and get token<br>
$initResult = $basicApi->initGet();<br>
$token = $initResult['token'];<br>
$config->setAccessToken($token);<br>
?&gt;
        </div>
        
        <h2>📚 Available Examples</h2>
        
        <div class="examples">
            <ul>
                <li>
                    <strong><a href="examples/basic_usage.php">basic_usage.php</a></strong><br>
                    API initialization, authentication, and basic operations
                </li>
                <li>
                    <strong><a href="examples/user_management.php">user_management.php</a></strong><br>
                    User CRUD operations, deposits, withdrawals, and group management
                </li>
                <li>
                    <strong><a href="examples/trading_operations.php">trading_operations.php</a></strong><br>
                    Positions, orders, trade history, and market symbols
                </li>
            </ul>
        </div>
        
        <h2>📖 Documentation</h2>
        
        <p>Complete API documentation is available in the <a href="docs/">docs/</a> directory:</p>
        <ul>
            <li><a href="docs/Api/">API Endpoints</a> - All available API methods</li>
            <li><a href="docs/Model/">Data Models</a> - Request/response object structures</li>
            <li><a href="examples/README.md">Examples Documentation</a> - Detailed example usage</li>
        </ul>
        
        <h2>🔗 Useful Links</h2>
        
        <ul>
            <li><a href="https://github.com/dev4traders/mt5-manager-api" target="_blank">GitHub Repository</a></li>
            <li><a href="https://www.metatrader5.com/en/terminal/help/manager_api" target="_blank">MT5 Manager API Documentation</a></li>
            <li><a href="https://swagger.io/" target="_blank">Swagger/OpenAPI</a></li>
        </ul>
        
        <hr style="margin: 40px 0;">
        
        <p style="text-align: center; color: #666;">
            <strong>MT5 Manager API PHP SDK</strong><br>
            Generated by Swagger Codegen | Contact: mikha.dev@gmail.com
        </p>
    </div>
</body>
</html>