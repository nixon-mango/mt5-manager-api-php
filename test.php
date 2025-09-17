<?php
// Simple PHP test file
echo "<h1>PHP Test</h1>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Current Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

// Test if docs directory exists
if (is_dir(__DIR__ . '/docs')) {
    echo "<p>✅ Docs directory exists</p>";
    
    $apiFiles = glob(__DIR__ . '/docs/Api/*.md');
    echo "<p>API files found: " . count($apiFiles) . "</p>";
    
    $modelFiles = glob(__DIR__ . '/docs/Model/*.md');
    echo "<p>Model files found: " . count($modelFiles) . "</p>";
} else {
    echo "<p>❌ Docs directory not found</p>";
}

// Test basic PHP functionality
echo "<h2>PHP Extensions</h2>";
$extensions = ['curl', 'json', 'mbstring', 'zip'];
foreach ($extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "<p>$status $ext</p>";
}

phpinfo();
?>