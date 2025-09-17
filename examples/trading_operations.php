<?php
/**
 * Trading Operations Example for MT5 Manager API
 * 
 * This example demonstrates trading-related operations:
 * 1. Get user positions
 * 2. Get user orders
 * 3. Get user deals/trades
 * 4. Close positions
 * 5. Get market symbols
 */

require_once(__DIR__ . '/../vendor/autoload.php');

use D4T\MT5Sdk\MT5Manager\BasicApi;
use D4T\MT5Sdk\MT5Manager\TradeApi;
use D4T\MT5Sdk\MT5Manager\SymbolApi;
use D4T\MT5Sdk\Configuration;
use GuzzleHttp\Client;

// Configuration
$config = Configuration::getDefaultConfiguration();
$host = getenv('MT5_API_HOST') ?: 'localhost:8080';
$config->setHost("http://{$host}/v1");

$client = new Client();
$basicApi = new BasicApi($client, $config);
$tradeApi = new TradeApi($client, $config);
$symbolApi = new SymbolApi($client, $config);

try {
    echo "=== MT5 Trading Operations Example ===\n\n";
    
    // Initialize and get token
    echo "Initializing API...\n";
    $initResult = $basicApi->initGet();
    
    if (!$initResult || !isset($initResult['token'])) {
        throw new Exception("Failed to initialize API");
    }
    
    $config->setAccessToken($initResult['token']);
    echo "✓ API initialized successfully\n\n";
    
    // Example user login
    $userLogin = getenv('MT5_EXAMPLE_USER') ?: '12345';
    
    // 1. Get market symbols
    echo "1. Getting market symbols...\n";
    try {
        $symbols = $symbolApi->symbolsGet();
        echo "✓ Found " . count($symbols) . " symbols\n";
        
        if (count($symbols) > 0) {
            echo "   First few symbols:\n";
            foreach (array_slice($symbols, 0, 5) as $symbol) {
                $symbolName = is_array($symbol) ? ($symbol['symbol'] ?? 'Unknown') : $symbol;
                echo "   - {$symbolName}\n";
            }
        }
        echo "\n";
    } catch (Exception $e) {
        echo "⚠ Could not retrieve symbols: " . $e->getMessage() . "\n\n";
    }
    
    // 2. Get user positions
    echo "2. Getting positions for user: {$userLogin}\n";
    try {
        $positions = $tradeApi->positionsUserLoginGet($userLogin);
        echo "✓ Found " . count($positions) . " open positions\n";
        
        if (count($positions) > 0) {
            echo "   Position details:\n";
            foreach ($positions as $position) {
                echo "   - Symbol: " . ($position['symbol'] ?? 'N/A') . 
                     ", Volume: " . ($position['volume'] ?? 'N/A') . 
                     ", Type: " . ($position['type'] ?? 'N/A') . 
                     ", Profit: $" . ($position['profit'] ?? 'N/A') . "\n";
            }
        } else {
            echo "   No open positions found\n";
        }
        echo "\n";
    } catch (Exception $e) {
        echo "⚠ Could not retrieve positions: " . $e->getMessage() . "\n\n";
    }
    
    // 3. Get user orders
    echo "3. Getting orders for user: {$userLogin}\n";
    try {
        $orders = $tradeApi->ordersUserLoginGet($userLogin);
        echo "✓ Found " . count($orders) . " pending orders\n";
        
        if (count($orders) > 0) {
            echo "   Order details:\n";
            foreach ($orders as $order) {
                echo "   - Symbol: " . ($order['symbol'] ?? 'N/A') . 
                     ", Volume: " . ($order['volume'] ?? 'N/A') . 
                     ", Type: " . ($order['type'] ?? 'N/A') . 
                     ", Price: $" . ($order['price_open'] ?? 'N/A') . "\n";
            }
        } else {
            echo "   No pending orders found\n";
        }
        echo "\n";
    } catch (Exception $e) {
        echo "⚠ Could not retrieve orders: " . $e->getMessage() . "\n\n";
    }
    
    // 4. Get user deals/trades history
    echo "4. Getting trade history for user: {$userLogin}\n";
    try {
        $deals = $tradeApi->dealsUserLoginGet($userLogin);
        echo "✓ Found " . count($deals) . " deals in history\n";
        
        if (count($deals) > 0) {
            echo "   Recent deals (last 3):\n";
            foreach (array_slice($deals, -3) as $deal) {
                echo "   - Symbol: " . ($deal['symbol'] ?? 'N/A') . 
                     ", Volume: " . ($deal['volume'] ?? 'N/A') . 
                     ", Type: " . ($deal['type'] ?? 'N/A') . 
                     ", Profit: $" . ($deal['profit'] ?? 'N/A') . 
                     ", Time: " . ($deal['time'] ?? 'N/A') . "\n";
            }
        } else {
            echo "   No trade history found\n";
        }
        echo "\n";
    } catch (Exception $e) {
        echo "⚠ Could not retrieve trade history: " . $e->getMessage() . "\n\n";
    }
    
    // 5. Close all positions example (commented out for safety)
    echo "5. Close all positions example (dry run):\n";
    echo "   This would close all open positions for user: {$userLogin}\n";
    echo "   (Operation is commented out to prevent accidental closures)\n\n";
    
    /*
    // Uncomment to actually close all positions
    try {
        $closeResult = $tradeApi->closeAllUserLoginDelete($userLogin);
        echo "✓ All positions closed successfully: " . json_encode($closeResult) . "\n\n";
    } catch (Exception $e) {
        echo "✗ Failed to close positions: " . $e->getMessage() . "\n\n";
    }
    */
    
    echo "=== Trading operations example completed ===\n";
    echo "Note: Position closing operations are commented out for safety.\n";
    echo "Uncomment the relevant sections to perform actual trading operations.\n";
    
    // Display summary
    echo "\nSummary:\n";
    echo "- Market symbols: Available\n";
    echo "- User positions: Checked\n";
    echo "- User orders: Checked\n";
    echo "- Trade history: Checked\n";
    echo "- Position management: Available (commented out)\n";
    
} catch (Exception $e) {
    echo "✗ Error occurred: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}