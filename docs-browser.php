<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MT5 Manager API - Documentation Browser</title>
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
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            margin-right: 20px;
            padding: 8px 16px;
            background-color: #007cba;
            color: white;
            border-radius: 4px;
            text-decoration: none;
        }
        .nav a:hover {
            background-color: #0056b3;
        }
        .file-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .file-card {
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            background: #f8f9fa;
        }
        .file-card h3 {
            margin-top: 0;
            color: #007cba;
        }
        .file-card a {
            color: #007cba;
            text-decoration: none;
        }
        .file-card a:hover {
            text-decoration: underline;
        }
        .content {
            background: white;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #e9ecef;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">← Back to Dashboard</a>
            <a href="api-docs.php">Formatted API Docs</a>
            <a href="model-docs.php">Formatted Models</a>
        </div>
        
        <h1>📁 Documentation Browser</h1>
        
        <?php
        $file = isset($_GET['file']) ? $_GET['file'] : '';
        $type = isset($_GET['type']) ? $_GET['type'] : 'api';
        
        if (!empty($file)) {
            // Display specific file
            $basePath = __DIR__ . '/docs/';
            $subDir = ($type === 'model') ? 'Model/' : 'Api/';
            $filePath = $basePath . $subDir . basename($file);
            
            if (file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'md') {
                echo '<div class="nav">';
                echo '<a href="docs-browser.php">← Back to File List</a>';
                echo '</div>';
                
                echo '<h2>📄 ' . htmlspecialchars(basename($file, '.md')) . '</h2>';
                
                $content = file_get_contents($filePath);
                echo '<div class="content">' . htmlspecialchars($content) . '</div>';
            } else {
                echo '<p style="color: red;">File not found or invalid file type.</p>';
                echo '<a href="docs-browser.php">← Back to File List</a>';
            }
        } else {
            // Display simple file list
            echo '<p>Browse the raw documentation files:</p>';
            
            echo '<h2>🔌 API Endpoints</h2>';
            echo '<div class="file-list">';
            
            $apiFiles = ['AccountApi.md', 'BasicApi.md', 'GroupApi.md', 'OrderApi.md', 'SymbolApi.md', 'TradeApi.md', 'UserApi.md'];
            
            foreach ($apiFiles as $filename) {
                $title = basename($filename, '.md');
                echo '<div class="file-card">';
                echo '<h3>' . htmlspecialchars($title) . '</h3>';
                echo '<p><a href="?file=' . urlencode($filename) . '&type=api">View Raw Markdown</a></p>';
                echo '<p style="font-size: 0.9em; color: #666;">API endpoint documentation</p>';
                echo '</div>';
            }
            echo '</div>';
            
            echo '<h2>📊 Data Models</h2>';
            echo '<div class="file-list">';
            
            $modelFiles = ['Account.md', 'User.md', 'Position.md', 'Order.md', 'Deal.md', 'Symbol.md', 'Group.md', 'InitReturnType.md', 'PingReturnType.md'];
            
            foreach ($modelFiles as $filename) {
                $title = basename($filename, '.md');
                echo '<div class="file-card">';
                echo '<h3>' . htmlspecialchars($title) . '</h3>';
                echo '<p><a href="?file=' . urlencode($filename) . '&type=model">View Raw Markdown</a></p>';
                echo '<p style="font-size: 0.9em; color: #666;">Data model structure</p>';
                echo '</div>';
            }
            echo '</div>';
        }
        ?>
        
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <h2>📖 Documentation Formats</h2>
            <ul>
                <li><strong><a href="api-docs.php">Formatted API Documentation</a></strong> - Easy-to-read HTML format</li>
                <li><strong><a href="model-docs.php">Formatted Data Models</a></strong> - Structured view of data types</li>
                <li><strong>Raw Markdown Files</strong> - Original documentation files (this page)</li>
                <li><strong><a href="examples/README.md">Examples Documentation</a></strong> - Usage examples and tutorials</li>
            </ul>
        </div>
    </div>
</body>
</html>