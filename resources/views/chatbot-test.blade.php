<!DOCTYPE html>
<html>
<head>
    <title>Chatbot API Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Chatbot API Test</h1>
    <button onclick="testAPI()">Test Chatbot API</button>
    <pre id="result"></pre>

    <script>
        async function testAPI() {
            const resultEl = document.getElementById('result');
            resultEl.textContent = 'Testing...';

            try {
                const response = await fetch('/api/chatbot/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: 'halo',
                        history: []
                    })
                });

                console.log('Status:', response.status);
                const data = await response.json();
                console.log('Response:', data);

                resultEl.textContent = JSON.stringify(data, null, 2);
            } catch (error) {
                console.error('Error:', error);
                resultEl.textContent = 'Error: ' + error.message;
            }
        }
    </script>
</body>
</html>
