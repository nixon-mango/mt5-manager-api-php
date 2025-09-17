<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MT5 Manager API - Data Models</title>
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
        .model-section {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .property {
            background: #f8f9fa;
            padding: 10px;
            margin: 5px 0;
            border-radius: 3px;
            border-left: 3px solid #007cba;
        }
        .property-name {
            font-weight: bold;
            color: #007cba;
        }
        .property-type {
            color: #28a745;
            font-family: monospace;
        }
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
        .model-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .model-card {
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            background: #f8f9fa;
        }
        .model-card h3 {
            margin-top: 0;
            color: #007cba;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">← Back to Dashboard</a>
            <a href="api-docs.php">API Endpoints</a>
        </div>
        
        <h1>📊 MT5 Manager API - Data Models</h1>
        
        <p>These are the data structures used in API requests and responses.</p>
        
        <?php
        $docsDir = __DIR__ . '/docs/Model/';
        $modelFiles = glob($docsDir . '*.md');
        
        $models = [];
        
        foreach ($modelFiles as $file) {
            $filename = basename($file, '.md');
            $content = file_get_contents($file);
            
            // Parse the markdown content
            $lines = explode("\n", $content);
            $title = '';
            $properties = [];
            
            $inPropertiesSection = false;
            
            foreach ($lines as $line) {
                $line = trim($line);
                
                if (strpos($line, '# ') === 0) {
                    $title = substr($line, 2);
                } elseif (strpos($line, '## Properties') === 0) {
                    $inPropertiesSection = true;
                    continue;
                } elseif ($inPropertiesSection && strpos($line, '### ') === 0) {
                    // Property name
                    $propName = substr($line, 4);
                    $properties[] = ['name' => $propName, 'details' => ''];
                } elseif ($inPropertiesSection && !empty($properties) && !empty($line) && strpos($line, '#') !== 0) {
                    // Property details
                    $lastIndex = count($properties) - 1;
                    if (isset($properties[$lastIndex])) {
                        $properties[$lastIndex]['details'] .= $line . "\n";
                    }
                }
            }
            
            if (!empty($title)) {
                $models[] = [
                    'name' => $title,
                    'filename' => $filename,
                    'properties' => $properties
                ];
            }
        }
        
        // Sort models alphabetically
        usort($models, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        // Display models in a grid
        echo '<div class="model-grid">';
        foreach ($models as $model) {
            echo '<div class="model-card">';
            echo '<h3>' . htmlspecialchars($model['name']) . '</h3>';
            
            if (!empty($model['properties'])) {
                echo '<p><strong>Properties:</strong></p>';
                foreach (array_slice($model['properties'], 0, 5) as $property) {
                    $details = trim($property['details']);
                    echo '<div style="font-size: 0.9em; margin: 5px 0;">';
                    echo '<code>' . htmlspecialchars($property['name']) . '</code>';
                    if (!empty($details)) {
                        $firstLine = explode("\n", $details)[0];
                        if (strlen($firstLine) > 50) {
                            $firstLine = substr($firstLine, 0, 50) . '...';
                        }
                        echo ' - ' . htmlspecialchars($firstLine);
                    }
                    echo '</div>';
                }
                
                if (count($model['properties']) > 5) {
                    echo '<div style="font-size: 0.9em; color: #666; margin-top: 5px;">';
                    echo '... and ' . (count($model['properties']) - 5) . ' more properties';
                    echo '</div>';
                }
            } else {
                echo '<p style="color: #666; font-style: italic;">No properties documented</p>';
            }
            
            echo '</div>';
        }
        echo '</div>';
        ?>
        
        <h2>📋 Common Data Models</h2>
        
        <div class="model-section">
            <h3>Key Models for API Usage</h3>
            
            <div class="property">
                <span class="property-name">Account</span> - 
                <span class="property-type">User account information</span>
                <p>Contains user login, balance, equity, margin, and other account details.</p>
            </div>
            
            <div class="property">
                <span class="property-name">User</span> - 
                <span class="property-type">User profile data</span>
                <p>User personal information, group, settings, and account configuration.</p>
            </div>
            
            <div class="property">
                <span class="property-name">Position</span> - 
                <span class="property-type">Open trading position</span>
                <p>Current open trades with symbol, volume, price, profit/loss information.</p>
            </div>
            
            <div class="property">
                <span class="property-name">Order</span> - 
                <span class="property-type">Pending order</span>
                <p>Pending orders waiting to be executed with price levels and conditions.</p>
            </div>
            
            <div class="property">
                <span class="property-name">Deal</span> - 
                <span class="property-type">Trade history record</span>
                <p>Historical trades and transactions with timestamps and results.</p>
            </div>
            
            <div class="property">
                <span class="property-name">Symbol</span> - 
                <span class="property-type">Trading instrument</span>
                <p>Market symbol information including spreads, trading sessions, and specifications.</p>
            </div>
        </div>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <h2>💡 Usage Tips</h2>
            <ul>
                <li><strong>Required Fields:</strong> Check each model for required vs optional properties</li>
                <li><strong>Data Types:</strong> Pay attention to string, integer, float, and boolean types</li>
                <li><strong>Validation:</strong> Some fields have specific format requirements (emails, phone numbers, etc.)</li>
                <li><strong>Relationships:</strong> Some models reference others (User → Group, Position → Symbol)</li>
            </ul>
            
            <div class="code">
<pre>// Example: Creating a User object
$user = new User([
    'login' => 12345,
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'group' => 'demo',
    'password' => 'SecurePass123!'
]);

// Use in API call
$result = $userApi->userAddPost($user);
</pre>
            </div>
        </div>
        
        <div class="nav" style="margin-top: 30px;">
            <a href="api-docs.php">← View API Endpoints</a>
            <a href="index.php">Back to Dashboard →</a>
        </div>
    </div>
</body>
</html>