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
        $docsDir = __DIR__ . '/docs/Api/';
        $apiFiles = glob($docsDir . '*.md');
        
        foreach ($apiFiles as $file) {
            $filename = basename($file, '.md');
            $content = file_get_contents($file);
            
            // Parse the markdown content
            $lines = explode("\n", $content);
            $title = '';
            $description = '';
            $methods = [];
            
            $inMethodTable = false;
            $currentMethod = '';
            $currentExample = '';
            $inExample = false;
            
            foreach ($lines as $line) {
                $line = trim($line);
                
                if (strpos($line, '# ') === 0) {
                    $title = substr($line, 2);
                } elseif (strpos($line, 'Method | HTTP request | Description') !== false) {
                    $inMethodTable = true;
                    continue;
                } elseif ($inMethodTable && strpos($line, '|') !== false && !empty(trim($line, '|-'))) {
                    $parts = array_map('trim', explode('|', $line));
                    if (count($parts) >= 4) {
                        $methodName = trim($parts[1], '[]()');
                        $httpMethod = trim($parts[2], '*');
                        $methodDesc = trim($parts[3]);
                        
                        if (!empty($methodName) && !empty($httpMethod)) {
                            $methods[] = [
                                'name' => $methodName,
                                'http' => $httpMethod,
                                'description' => $methodDesc
                            ];
                        }
                    }
                } elseif (strpos($line, '# **') === 0) {
                    $inMethodTable = false;
                    $currentMethod = trim($line, '# *');
                } elseif (strpos($line, '```php') === 0) {
                    $inExample = true;
                    $currentExample = '';
                } elseif (strpos($line, '```') === 0 && $inExample) {
                    $inExample = false;
                    if (!empty($currentMethod) && !empty($currentExample)) {
                        // Find the method in our array and add the example
                        foreach ($methods as &$method) {
                            if ($method['name'] === $currentMethod) {
                                $method['example'] = $currentExample;
                                break;
                            }
                        }
                    }
                } elseif ($inExample) {
                    $currentExample .= $line . "\n";
                }
            }
            
            if (!empty($title) && !empty($methods)) {
                echo '<div class="api-section">';
                echo '<h2>' . htmlspecialchars($title) . '</h2>';
                
                foreach ($methods as $method) {
                    $httpParts = explode(' ', $method['http']);
                    $httpMethod = strtolower(trim($httpParts[0], '*'));
                    $endpoint = isset($httpParts[1]) ? $httpParts[1] : '';
                    
                    echo '<div class="endpoint">';
                    echo '<span class="method ' . $httpMethod . '">' . strtoupper($httpMethod) . '</span>';
                    echo '<strong>' . htmlspecialchars($endpoint) . '</strong>';
                    echo '<p>' . htmlspecialchars($method['description']) . '</p>';
                    
                    if (!empty($method['example'])) {
                        echo '<details>';
                        echo '<summary>Show Example Code</summary>';
                        echo '<div class="code"><pre>' . htmlspecialchars($method['example']) . '</pre></div>';
                        echo '</details>';
                    }
                    
                    echo '</div>';
                }
                
                echo '</div>';
            }
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