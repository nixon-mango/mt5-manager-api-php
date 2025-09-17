# MT5 Manager API Examples

This directory contains practical examples demonstrating how to use the MT5 Manager API SDK in a Docker environment.

## Available Examples

### 1. Basic Usage (`basic_usage.php`)
Demonstrates fundamental API operations:
- API initialization and authentication
- Getting authentication tokens
- Basic API health checks
- Error handling

### 2. User Management (`user_management.php`)
Shows user-related operations:
- Retrieving user information
- Creating new users
- Managing user groups
- Deposit and withdrawal operations

### 3. Trading Operations (`trading_operations.php`)
Covers trading and market data operations:
- Getting market symbols
- Retrieving user positions
- Managing orders
- Trade history access
- Position management

## Running Examples

### Using Docker Compose

1. **Start the containers:**
   ```bash
   docker-compose up -d
   ```

2. **Run individual examples:**
   ```bash
   # Basic usage
   docker-compose exec mt5-sdk php /var/www/html/examples/basic_usage.php
   
   # User management
   docker-compose exec mt5-sdk php /var/www/html/examples/user_management.php
   
   # Trading operations
   docker-compose exec mt5-sdk php /var/www/html/examples/trading_operations.php
   ```

3. **Use the convenience script:**
   ```bash
   # Run specific example
   ./examples/docker_run_examples.sh basic
   ./examples/docker_run_examples.sh user
   ./examples/docker_run_examples.sh trading
   
   # Run all examples
   ./examples/docker_run_examples.sh all
   ```

### Direct Docker Run

```bash
# Build the image
docker build -t mt5-manager-api .

# Run an example
docker run --rm \
  -e MT5_API_HOST=your-mt5-server.com \
  -e MT5_API_USERNAME=your-username \
  -e MT5_API_PASSWORD=your-password \
  mt5-manager-api \
  php /var/www/html/examples/basic_usage.php
```

## Configuration

### Environment Variables

Set these environment variables for proper API connection:

```bash
# Required
MT5_API_HOST=your-mt5-server.com
MT5_API_PORT=443
MT5_API_USERNAME=your-username
MT5_API_PASSWORD=your-password

# Optional
MT5_API_USE_SSL=true
MT5_EXAMPLE_LOGIN=12345
MT5_EXAMPLE_USER=demo_user
MT5_EXAMPLE_GROUP=demo
```

### Using .env File

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

2. Edit `.env` with your MT5 server details:
   ```bash
   MT5_API_HOST=your-mt5-server.com
   MT5_API_USERNAME=your-manager-username
   MT5_API_PASSWORD=your-manager-password
   ```

## Safety Notes

⚠️ **Important:** Many examples have potentially destructive operations commented out for safety:

- User creation operations
- Deposit/withdrawal transactions
- Position closing operations

To enable these operations:
1. Review the code carefully
2. Uncomment the relevant sections
3. Test in a development environment first

## Troubleshooting

### Common Issues

1. **Connection Refused:**
   - Check if your MT5 server is accessible
   - Verify the host and port configuration
   - Ensure firewall rules allow connections

2. **Authentication Failed:**
   - Verify your username and password
   - Check if the manager account has proper permissions
   - Ensure the API is enabled on your MT5 server

3. **Container Issues:**
   - Check if Docker is running: `docker info`
   - Verify containers are up: `docker-compose ps`
   - Check logs: `docker-compose logs mt5-sdk`

### Debug Mode

Enable debug mode by setting environment variables:

```bash
export PHP_MEMORY_LIMIT=512M
export PHP_MAX_EXECUTION_TIME=0
docker-compose up -d
```

Or use the development profile:

```bash
docker-compose --profile dev up -d
docker-compose exec mt5-sdk-dev php /var/www/html/examples/basic_usage.php
```

## Contributing

When adding new examples:

1. Follow the existing code structure
2. Include comprehensive error handling
3. Add safety comments for destructive operations
4. Update this README with the new example
5. Test in the Docker environment

## Support

For API-specific questions, refer to:
- [API Documentation](../docs/)
- [MT5 Manager API Documentation](https://www.metatrader5.com/en/terminal/help/manager_api)
- [Swagger/OpenAPI Specification](../docs/Api/)