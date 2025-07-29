<!DOCTYPE html>
<html>
<head>
    <title>Test Multiple Choice Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Multiple Choice Form</h1>
    
    <form id="testForm" method="POST" action="/admin/test-mc-form">
        @csrf
        
        <h3>Options:</h3>
        <div>
            <label>
                <input type="checkbox" name="correct_option[]" value="0"> Option A
            </label>
        </div>
        <div>
            <label>
                <input type="checkbox" name="correct_option[]" value="1"> Option B
            </label>
        </div>
        <div>
            <label>
                <input type="checkbox" name="correct_option[]" value="2"> Option C
            </label>
        </div>
        <div>
            <label>
                <input type="checkbox" name="correct_option[]" value="3"> Option D
            </label>
        </div>
        
        <br>
        <button type="submit">Submit</button>
    </form>
    
    <div id="result" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc; display: none;">
        <h3>Result:</h3>
        <pre id="resultContent"></pre>
    </div>
    
    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Log what we're sending
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            fetch('/admin/test-mc-form', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').style.display = 'block';
                document.getElementById('resultContent').textContent = JSON.stringify(data, null, 2);
            });
        });
    </script>
</body>
</html>
