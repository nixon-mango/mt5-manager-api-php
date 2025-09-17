<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MT5 Manager API - Swagger Playground</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui.css" />
    <style>
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body {
            margin: 0;
            background: #fafafa;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .header {
            background: #007cba;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
        }
        .nav {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }
        .nav a {
            margin: 0 15px;
            padding: 8px 16px;
            background-color: #007cba;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        .nav a:hover {
            background-color: #0056b3;
        }
        #swagger-ui {
            max-width: 1200px;
            margin: 0 auto;
        }
        .config-panel {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            margin: 20px;
            padding: 20px;
        }
        .config-panel h3 {
            margin-top: 0;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn {
            background: #007cba;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .alert {
            padding: 12px;
            border-radius: 4px;
            margin: 15px 0;
        }
        .alert-info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚀 MT5 Manager API - Swagger Playground</h1>
        <p>Interactive API testing environment</p>
    </div>
    
    <div class="nav">
        <a href="index.php">← Dashboard</a>
        <a href="api-docs.php">API Docs</a>
        <a href="model-docs.php">Data Models</a>
        <a href="#" onclick="loadExampleSpec()">Load Example</a>
        <a href="#" onclick="resetConfig()">Reset</a>
    </div>

    <div class="config-panel">
        <h3>⚙️ API Configuration</h3>
        <div class="alert alert-info">
            <strong>Getting Started:</strong> 
            1. Configure your MT5 server details below
            2. Click "Load Swagger Spec" to initialize the API explorer
            3. Use the "Authorize" button in Swagger UI to set your bearer token
            4. Test endpoints directly from the interface
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <div class="form-group">
                    <label for="apiHost">MT5 API Host:</label>
                    <input type="text" id="apiHost" placeholder="your-mt5-server.com" value="localhost">
                </div>
                
                <div class="form-group">
                    <label for="apiPort">API Port:</label>
                    <input type="text" id="apiPort" placeholder="443" value="8080">
                </div>
            </div>
            
            <div>
                <div class="form-group">
                    <label for="apiUsername">Manager Username:</label>
                    <input type="text" id="apiUsername" placeholder="Your MT5 manager username">
                </div>
                
                <div class="form-group">
                    <label for="apiPassword">Manager Password:</label>
                    <input type="password" id="apiPassword" placeholder="Your MT5 manager password">
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn" onclick="loadSwaggerSpec()">🔄 Load Swagger Spec</button>
            <button class="btn" onclick="getAuthToken()" style="background: #28a745;">🔑 Get Auth Token</button>
        </div>
        
        <div id="tokenInfo" style="display: none; margin-top: 15px;">
            <div class="alert alert-info">
                <strong>Bearer Token:</strong> <span id="bearerToken"></span>
                <br><small>Copy this token and use it in the "Authorize" button below</small>
            </div>
        </div>
        
        <div class="alert alert-warning">
            <strong>Note:</strong> Make sure your MT5 server is accessible and the Manager API is enabled. 
            The API will be available at: <code>http://<span id="displayHost">localhost</span>:<span id="displayPort">8080</span>/v1</code>
        </div>
    </div>

    <div id="swagger-ui"></div>

    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-bundle.js"></script>
    <script src="https://unpkg.com/swagger-ui-dist@5.9.0/swagger-ui-standalone-preset.js"></script>
    <script>
        let swaggerSpec = null;
        let ui = null;
        
        // Load environment variables or use defaults
        document.addEventListener('DOMContentLoaded', function() {
            // Try to load from PHP environment variables
            <?php
            echo "document.getElementById('apiHost').value = '" . (getenv('MT5_API_HOST') ?: 'localhost') . "';\n";
            echo "document.getElementById('apiPort').value = '" . (getenv('MT5_API_PORT') ?: '8080') . "';\n";
            echo "document.getElementById('apiUsername').value = '" . (getenv('MT5_API_USERNAME') ?: '') . "';\n";
            ?>
            updateDisplayValues();
        });
        
        function updateDisplayValues() {
            document.getElementById('displayHost').textContent = document.getElementById('apiHost').value;
            document.getElementById('displayPort').textContent = document.getElementById('apiPort').value;
        }
        
        // Update display values when inputs change
        document.getElementById('apiHost').addEventListener('input', updateDisplayValues);
        document.getElementById('apiPort').addEventListener('input', updateDisplayValues);
        
        function getApiBaseUrl() {
            const host = document.getElementById('apiHost').value;
            const port = document.getElementById('apiPort').value;
            const protocol = port === '443' ? 'https' : 'http';
            return `${protocol}://${host}:${port}/v1`;
        }
        
        async function getAuthToken() {
            const host = document.getElementById('apiHost').value;
            const port = document.getElementById('apiPort').value;
            const username = document.getElementById('apiUsername').value;
            const password = document.getElementById('apiPassword').value;
            
            if (!host || !username || !password) {
                alert('Please fill in all required fields (Host, Username, Password)');
                return;
            }
            
            try {
                const protocol = port === '443' ? 'https' : 'http';
                const url = `${protocol}://${host}:${port}/v1/init/`;
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    if (data.token) {
                        document.getElementById('bearerToken').textContent = data.token;
                        document.getElementById('tokenInfo').style.display = 'block';
                        
                        // Auto-authorize in Swagger UI if it's loaded
                        if (ui) {
                            ui.preauthorizeApiKey('bearerAuth', data.token);
                        }
                    } else {
                        alert('No token received from server');
                    }
                } else {
                    alert(`Failed to get token: ${response.status} ${response.statusText}`);
                }
            } catch (error) {
                alert(`Error connecting to API: ${error.message}`);
            }
        }
        
        function generateSwaggerSpec() {
            const baseUrl = getApiBaseUrl();
            
            return {
                "openapi": "3.0.0",
                "info": {
                    "title": "MT5 Manager API",
                    "description": "MetaTrader 5 REST API for managing trading accounts, users, and operations",
                    "version": "0.0.3-oas3",
                    "contact": {
                        "email": "mikha.dev@gmail.com"
                    }
                },
                "servers": [
                    {
                        "url": baseUrl,
                        "description": "MT5 Manager API Server"
                    }
                ],
                "components": {
                    "securitySchemes": {
                        "bearerAuth": {
                            "type": "http",
                            "scheme": "bearer",
                            "bearerFormat": "JWT",
                            "description": "Bearer token obtained from /init/ endpoint"
                        }
                    }
                },
                "security": [
                    {
                        "bearerAuth": []
                    }
                ],
                "paths": {
                    "/init/": {
                        "get": {
                            "tags": ["Authentication"],
                            "summary": "Initialize manager connection",
                            "description": "Get authentication token for API access",
                            "security": [],
                            "parameters": [
                                {
                                    "name": "server",
                                    "in": "query",
                                    "description": "MetaTrader5 server IP with port",
                                    "schema": { "type": "string", "example": "127.0.0.1:443" }
                                },
                                {
                                    "name": "login",
                                    "in": "query",
                                    "description": "MetaTrader manager login",
                                    "schema": { "type": "string" }
                                },
                                {
                                    "name": "password",
                                    "in": "query",
                                    "description": "MetaTrader manager password",
                                    "schema": { "type": "string" }
                                }
                            ],
                            "responses": {
                                "200": {
                                    "description": "Successful authentication",
                                    "content": {
                                        "application/json": {
                                            "schema": {
                                                "type": "object",
                                                "properties": {
                                                    "token": { "type": "string" },
                                                    "status": { "type": "string" }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    },
                    "/ping/": {
                        "get": {
                            "tags": ["Health"],
                            "summary": "Ping API",
                            "description": "Test API connectivity",
                            "responses": {
                                "200": {
                                    "description": "API is responsive",
                                    "content": {
                                        "application/json": {
                                            "schema": {
                                                "type": "object",
                                                "properties": {
                                                    "status": { "type": "string", "example": "ok" },
                                                    "timestamp": { "type": "string" }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    },
                    "/account/{login}": {
                        "get": {
                            "tags": ["Account"],
                            "summary": "Get account by login",
                            "description": "Retrieve account information for a specific login",
                            "parameters": [
                                {
                                    "name": "login",
                                    "in": "path",
                                    "required": true,
                                    "description": "User login ID",
                                    "schema": { "type": "string", "example": "12345" }
                                }
                            ],
                            "responses": {
                                "200": {
                                    "description": "Account information",
                                    "content": {
                                        "application/json": {
                                            "schema": {
                                                "type": "object",
                                                "properties": {
                                                    "login": { "type": "integer" },
                                                    "balance": { "type": "number" },
                                                    "equity": { "type": "number" },
                                                    "margin": { "type": "number" },
                                                    "free_margin": { "type": "number" }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    },
                    "/user/{user_login}": {
                        "get": {
                            "tags": ["User Management"],
                            "summary": "Get user by login",
                            "parameters": [
                                {
                                    "name": "user_login",
                                    "in": "path",
                                    "required": true,
                                    "schema": { "type": "string", "example": "12345" }
                                }
                            ],
                            "responses": {
                                "200": {
                                    "description": "User information",
                                    "content": {
                                        "application/json": {
                                            "schema": {
                                                "type": "object",
                                                "properties": {
                                                    "login": { "type": "integer" },
                                                    "name": { "type": "string" },
                                                    "email": { "type": "string" },
                                                    "group": { "type": "string" }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        "delete": {
                            "tags": ["User Management"],
                            "summary": "Delete user",
                            "parameters": [
                                {
                                    "name": "user_login",
                                    "in": "path",
                                    "required": true,
                                    "schema": { "type": "string" }
                                }
                            ],
                            "responses": {
                                "200": { "description": "User deleted successfully" }
                            }
                        }
                    },
                    "/user/add": {
                        "post": {
                            "tags": ["User Management"],
                            "summary": "Create new user",
                            "requestBody": {
                                "required": true,
                                "content": {
                                    "application/json": {
                                        "schema": {
                                            "type": "object",
                                            "properties": {
                                                "login": { "type": "integer", "example": 12345 },
                                                "name": { "type": "string", "example": "John Doe" },
                                                "email": { "type": "string", "example": "john@example.com" },
                                                "group": { "type": "string", "example": "demo" },
                                                "password": { "type": "string", "example": "SecurePass123!" }
                                            },
                                            "required": ["login", "name", "email", "group", "password"]
                                        }
                                    }
                                }
                            },
                            "responses": {
                                "200": { "description": "User created successfully" }
                            }
                        }
                    },
                    "/user/deposit": {
                        "post": {
                            "tags": ["User Management"],
                            "summary": "Deposit to user account",
                            "requestBody": {
                                "required": true,
                                "content": {
                                    "application/json": {
                                        "schema": {
                                            "type": "object",
                                            "properties": {
                                                "login": { "type": "string", "example": "12345" },
                                                "balance": { "type": "number", "example": 100.00 },
                                                "comment": { "type": "string", "example": "Deposit via API" }
                                            }
                                        }
                                    }
                                }
                            },
                            "responses": {
                                "200": { "description": "Deposit successful" }
                            }
                        }
                    },
                    "/positions/{user_login}": {
                        "get": {
                            "tags": ["Trading"],
                            "summary": "Get user positions",
                            "parameters": [
                                {
                                    "name": "user_login",
                                    "in": "path",
                                    "required": true,
                                    "schema": { "type": "string", "example": "12345" }
                                }
                            ],
                            "responses": {
                                "200": {
                                    "description": "List of user positions",
                                    "content": {
                                        "application/json": {
                                            "schema": {
                                                "type": "array",
                                                "items": {
                                                    "type": "object",
                                                    "properties": {
                                                        "symbol": { "type": "string", "example": "EURUSD" },
                                                        "volume": { "type": "number", "example": 1.0 },
                                                        "type": { "type": "string", "example": "buy" },
                                                        "profit": { "type": "number", "example": 15.50 }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    },
                    "/orders/{user_login}": {
                        "get": {
                            "tags": ["Trading"],
                            "summary": "Get user orders",
                            "parameters": [
                                {
                                    "name": "user_login",
                                    "in": "path",
                                    "required": true,
                                    "schema": { "type": "string", "example": "12345" }
                                }
                            ],
                            "responses": {
                                "200": {
                                    "description": "List of user orders",
                                    "content": {
                                        "application/json": {
                                            "schema": {
                                                "type": "array",
                                                "items": {
                                                    "type": "object",
                                                    "properties": {
                                                        "symbol": { "type": "string", "example": "EURUSD" },
                                                        "volume": { "type": "number", "example": 1.0 },
                                                        "type": { "type": "string", "example": "buy_limit" },
                                                        "price": { "type": "number", "example": 1.1850 }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    },
                    "/symbols/": {
                        "get": {
                            "tags": ["Market Data"],
                            "summary": "Get trading symbols",
                            "responses": {
                                "200": {
                                    "description": "List of available trading symbols",
                                    "content": {
                                        "application/json": {
                                            "schema": {
                                                "type": "array",
                                                "items": {
                                                    "type": "object",
                                                    "properties": {
                                                        "symbol": { "type": "string", "example": "EURUSD" },
                                                        "description": { "type": "string", "example": "Euro vs US Dollar" },
                                                        "digits": { "type": "integer", "example": 5 }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    },
                    "/groups/": {
                        "get": {
                            "tags": ["Group Management"],
                            "summary": "Get list of groups",
                            "responses": {
                                "200": {
                                    "description": "List of user groups",
                                    "content": {
                                        "application/json": {
                                            "schema": {
                                                "type": "array",
                                                "items": {
                                                    "type": "object",
                                                    "properties": {
                                                        "group": { "type": "string", "example": "demo" },
                                                        "description": { "type": "string", "example": "Demo accounts" }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            };
        }
        
        function loadSwaggerSpec() {
            updateDisplayValues();
            swaggerSpec = generateSwaggerSpec();
            
            ui = SwaggerUIBundle({
                spec: swaggerSpec,
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "StandaloneLayout",
                validatorUrl: null,
                tryItOutEnabled: true,
                requestInterceptor: (request) => {
                    // Add any custom headers or modifications here
                    return request;
                },
                responseInterceptor: (response) => {
                    // Handle responses here
                    return response;
                }
            });
            
            // Auto-set bearer token if available
            const token = document.getElementById('bearerToken').textContent;
            if (token) {
                setTimeout(() => {
                    ui.preauthorizeApiKey('bearerAuth', token);
                }, 1000);
            }
        }
        
        function loadExampleSpec() {
            document.getElementById('apiHost').value = 'demo-mt5-server.com';
            document.getElementById('apiPort').value = '443';
            document.getElementById('apiUsername').value = 'demo_manager';
            document.getElementById('apiPassword').value = 'demo_password';
            updateDisplayValues();
            loadSwaggerSpec();
        }
        
        function resetConfig() {
            document.getElementById('apiHost').value = 'localhost';
            document.getElementById('apiPort').value = '8080';
            document.getElementById('apiUsername').value = '';
            document.getElementById('apiPassword').value = '';
            document.getElementById('tokenInfo').style.display = 'none';
            updateDisplayValues();
            
            // Clear Swagger UI
            document.getElementById('swagger-ui').innerHTML = '';
            ui = null;
        }
        
        // Load default spec on page load
        loadSwaggerSpec();
    </script>
</body>
</html>