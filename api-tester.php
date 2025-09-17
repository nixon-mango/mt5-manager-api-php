<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MT5 Manager API - Simple Tester</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1000px;
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
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group textarea {
            height: 100px;
            font-family: monospace;
        }
        .btn {
            background: #007cba;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn.success {
            background: #28a745;
        }
        .btn.danger {
            background: #dc3545;
        }
        .response-panel {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .response-panel h4 {
            margin-top: 0;
            color: #333;
        }
        .response-content {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 10px;
            font-family: monospace;
            font-size: 12px;
            white-space: pre-wrap;
            max-height: 300px;
            overflow-y: auto;
        }
        .endpoint-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .endpoint-card {
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 15px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.2s;
        }
        .endpoint-card:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }
        .endpoint-card h4 {
            margin: 0 0 10px 0;
            color: #007cba;
        }
        .method-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            font-size: 11px;
            font-weight: bold;
        }
        .method-get { background: #28a745; }
        .method-post { background: #007bff; }
        .method-delete { background: #dc3545; }
        .token-display {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="nav">
            <a href="index.php">← Dashboard</a>
            <a href="swagger-ui.php">Swagger UI</a>
            <a href="api-docs.php">API Docs</a>
        </div>
        
        <h1>🧪 MT5 Manager API - Simple Tester</h1>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            <div>
                <h3>⚙️ Configuration</h3>
                <div class="form-group">
                    <label for="baseUrl">API Base URL:</label>
                    <input type="text" id="baseUrl" value="http://localhost:8080/v1">
                </div>
                
                <div class="form-group">
                    <label for="bearerToken">Bearer Token:</label>
                    <input type="text" id="bearerToken" placeholder="Get token from /init/ endpoint">
                </div>
                
                <button class="btn success" onclick="getAuthToken()">🔑 Get Auth Token</button>
                <button class="btn" onclick="testPing()">📡 Test Ping</button>
            </div>
            
            <div>
                <h3>🚀 Quick Test</h3>
                <div class="form-group">
                    <label for="endpoint">Endpoint:</label>
                    <select id="endpoint" onchange="updateEndpoint()">
                        <option value="">Select an endpoint...</option>
                        <option value="GET:/init/">GET /init/ - Get auth token</option>
                        <option value="GET:/ping/">GET /ping/ - Test connectivity</option>
                        <option value="GET:/account/12345">GET /account/{login} - Get account</option>
                        <option value="GET:/user/12345">GET /user/{login} - Get user</option>
                        <option value="GET:/positions/12345">GET /positions/{login} - Get positions</option>
                        <option value="GET:/orders/12345">GET /orders/{login} - Get orders</option>
                        <option value="GET:/symbols/">GET /symbols/ - Get symbols</option>
                        <option value="GET:/groups/">GET /groups/ - Get groups</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="requestBody">Request Body (for POST requests):</label>
                    <textarea id="requestBody" placeholder='{"login": 12345, "name": "Test User"}'></textarea>
                </div>
                
                <button class="btn" onclick="sendRequest()">🚀 Send Request</button>
                <button class="btn danger" onclick="clearResponse()">🗑️ Clear</button>
            </div>
        </div>
        
        <div id="tokenDisplay" style="display: none;">
            <h4>🔑 Authentication Token</h4>
            <div class="token-display" id="tokenContent"></div>
        </div>
        
        <div class="response-panel" id="responsePanel" style="display: none;">
            <h4>📡 API Response</h4>
            <div><strong>Status:</strong> <span id="responseStatus"></span></div>
            <div><strong>URL:</strong> <span id="responseUrl"></span></div>
            <div><strong>Response:</strong></div>
            <div class="response-content" id="responseContent"></div>
        </div>
        
        <h3>📋 Available Endpoints</h3>
        <div class="endpoint-grid">
            <div class="endpoint-card" onclick="selectEndpoint('GET:/init/')">
                <h4><span class="method-badge method-get">GET</span> /init/</h4>
                <p>Get authentication token</p>
            </div>
            
            <div class="endpoint-card" onclick="selectEndpoint('GET:/ping/')">
                <h4><span class="method-badge method-get">GET</span> /ping/</h4>
                <p>Test API connectivity</p>
            </div>
            
            <div class="endpoint-card" onclick="selectEndpoint('GET:/account/12345')">
                <h4><span class="method-badge method-get">GET</span> /account/{login}</h4>
                <p>Get account information</p>
            </div>
            
            <div class="endpoint-card" onclick="selectEndpoint('GET:/user/12345')">
                <h4><span class="method-badge method-get">GET</span> /user/{login}</h4>
                <p>Get user information</p>
            </div>
            
            <div class="endpoint-card" onclick="selectEndpoint('GET:/positions/12345')">
                <h4><span class="method-badge method-get">GET</span> /positions/{login}</h4>
                <p>Get user positions</p>
            </div>
            
            <div class="endpoint-card" onclick="selectEndpoint('GET:/orders/12345')">
                <h4><span class="method-badge method-get">GET</span> /orders/{login}</h4>
                <p>Get user orders</p>
            </div>
            
            <div class="endpoint-card" onclick="selectEndpoint('POST:/user/add')">
                <h4><span class="method-badge method-post">POST</span> /user/add</h4>
                <p>Create new user</p>
            </div>
            
            <div class="endpoint-card" onclick="selectEndpoint('POST:/user/deposit')">
                <h4><span class="method-badge method-post">POST</span> /user/deposit</h4>
                <p>Deposit to account</p>
            </div>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef;">
            <h4>💡 Usage Tips</h4>
            <ul>
                <li><strong>Authentication:</strong> First call GET /init/ to get your bearer token</li>
                <li><strong>Authorization:</strong> Use the token in subsequent requests</li>
                <li><strong>Testing:</strong> Start with /ping/ to test connectivity</li>
                <li><strong>Parameters:</strong> Replace {login} with actual user login numbers</li>
                <li><strong>POST Requests:</strong> Add JSON data in the Request Body field</li>
            </ul>
        </div>
    </div>

    <script>
        function selectEndpoint(endpoint) {
            document.getElementById('endpoint').value = endpoint;
            updateEndpoint();
        }
        
        function updateEndpoint() {
            const endpoint = document.getElementById('endpoint').value;
            const requestBodyField = document.getElementById('requestBody');
            
            // Clear request body
            requestBodyField.value = '';
            
            // Set example request body for POST endpoints
            if (endpoint.includes('POST:/user/add')) {
                requestBodyField.value = JSON.stringify({
                    "login": 12345,
                    "name": "Test User",
                    "email": "test@example.com",
                    "group": "demo",
                    "password": "SecurePass123!"
                }, null, 2);
            } else if (endpoint.includes('POST:/user/deposit')) {
                requestBodyField.value = JSON.stringify({
                    "login": "12345",
                    "balance": 100.00,
                    "comment": "Test deposit via API"
                }, null, 2);
            }
        }
        
        async function getAuthToken() {
            const baseUrl = document.getElementById('baseUrl').value;
            const url = baseUrl + '/init/';
            
            try {
                const response = await fetch(url);
                const data = await response.json();
                
                if (data.token) {
                    document.getElementById('bearerToken').value = data.token;
                    document.getElementById('tokenContent').textContent = data.token;
                    document.getElementById('tokenDisplay').style.display = 'block';
                    showResponse(response.status, url, data);
                } else {
                    showResponse(response.status, url, data);
                }
            } catch (error) {
                showResponse('ERROR', url, { error: error.message });
            }
        }
        
        async function testPing() {
            const baseUrl = document.getElementById('baseUrl').value;
            const token = document.getElementById('bearerToken').value;
            const url = baseUrl + '/ping/';
            
            const headers = { 'Content-Type': 'application/json' };
            if (token) {
                headers['Authorization'] = 'Bearer ' + token;
            }
            
            try {
                const response = await fetch(url, { headers });
                const data = await response.json();
                showResponse(response.status, url, data);
            } catch (error) {
                showResponse('ERROR', url, { error: error.message });
            }
        }
        
        async function sendRequest() {
            const baseUrl = document.getElementById('baseUrl').value;
            const token = document.getElementById('bearerToken').value;
            const endpoint = document.getElementById('endpoint').value;
            const requestBody = document.getElementById('requestBody').value;
            
            if (!endpoint) {
                alert('Please select an endpoint');
                return;
            }
            
            const [method, path] = endpoint.split(':');
            const url = baseUrl + path;
            
            const headers = { 'Content-Type': 'application/json' };
            if (token && !path.includes('/init/')) {
                headers['Authorization'] = 'Bearer ' + token;
            }
            
            const requestOptions = {
                method: method,
                headers: headers
            };
            
            if (method === 'POST' && requestBody.trim()) {
                try {
                    JSON.parse(requestBody); // Validate JSON
                    requestOptions.body = requestBody;
                } catch (error) {
                    alert('Invalid JSON in request body: ' + error.message);
                    return;
                }
            }
            
            try {
                const response = await fetch(url, requestOptions);
                let data;
                
                try {
                    data = await response.json();
                } catch {
                    data = await response.text();
                }
                
                showResponse(response.status, url, data);
            } catch (error) {
                showResponse('ERROR', url, { error: error.message });
            }
        }
        
        function showResponse(status, url, data) {
            document.getElementById('responseStatus').textContent = status;
            document.getElementById('responseUrl').textContent = url;
            document.getElementById('responseContent').textContent = 
                typeof data === 'object' ? JSON.stringify(data, null, 2) : data;
            document.getElementById('responsePanel').style.display = 'block';
        }
        
        function clearResponse() {
            document.getElementById('responsePanel').style.display = 'none';
            document.getElementById('tokenDisplay').style.display = 'none';
        }
        
        // Load environment variables
        document.addEventListener('DOMContentLoaded', function() {
            <?php
            $host = getenv('MT5_API_HOST') ?: 'localhost';
            $port = getenv('MT5_API_PORT') ?: '8080';
            $protocol = ($port === '443') ? 'https' : 'http';
            echo "document.getElementById('baseUrl').value = '{$protocol}://{$host}:{$port}/v1';\n";
            ?>
        });
    </script>
</body>
</html>