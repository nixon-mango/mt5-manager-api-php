#!/bin/bash

# Docker Run Examples for MT5 Manager API
# This script demonstrates how to run the example scripts inside Docker containers

echo "=== MT5 Manager API Docker Examples ==="
echo ""

# Check if Docker is running
if ! docker info >/dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Function to run examples
run_example() {
    local example_file=$1
    local description=$2
    
    echo "🚀 Running: $description"
    echo "   File: $example_file"
    echo ""
    
    # Run the example in the MT5 SDK container
    docker-compose exec -T mt5-sdk php /var/www/html/examples/$example_file
    
    echo ""
    echo "✅ Completed: $description"
    echo "----------------------------------------"
    echo ""
}

# Check if containers are running
if ! docker-compose ps | grep -q "mt5-sdk.*Up"; then
    echo "⚠️  MT5 SDK containers are not running."
    echo "   Starting containers with docker-compose up -d..."
    docker-compose up -d
    
    echo "   Waiting for containers to be ready..."
    sleep 10
fi

echo "📋 Available examples:"
echo "1. Basic Usage - API initialization and basic operations"
echo "2. User Management - User CRUD operations and account management"
echo "3. Trading Operations - Positions, orders, and trading history"
echo ""

# Check if specific example was requested
if [ "$1" ]; then
    case $1 in
        "basic"|"1")
            run_example "basic_usage.php" "Basic API Usage Example"
            ;;
        "user"|"users"|"2")
            run_example "user_management.php" "User Management Example"
            ;;
        "trading"|"trade"|"3")
            run_example "trading_operations.php" "Trading Operations Example"
            ;;
        "all")
            run_example "basic_usage.php" "Basic API Usage Example"
            run_example "user_management.php" "User Management Example"
            run_example "trading_operations.php" "Trading Operations Example"
            ;;
        *)
            echo "❌ Unknown example: $1"
            echo "   Available options: basic, user, trading, all"
            exit 1
            ;;
    esac
else
    echo "💡 Usage: $0 [example_name]"
    echo ""
    echo "Examples:"
    echo "  $0 basic     - Run basic usage example"
    echo "  $0 user      - Run user management example"
    echo "  $0 trading   - Run trading operations example"
    echo "  $0 all       - Run all examples"
    echo ""
    echo "Or run examples manually:"
    echo "  docker-compose exec mt5-sdk php /var/www/html/examples/basic_usage.php"
    echo "  docker-compose exec mt5-sdk php /var/www/html/examples/user_management.php"
    echo "  docker-compose exec mt5-sdk php /var/www/html/examples/trading_operations.php"
fi

echo ""
echo "📝 Notes:"
echo "   - Make sure to configure your .env file with proper MT5 server details"
echo "   - Some examples have operations commented out for safety"
echo "   - Check the example files for more detailed information"