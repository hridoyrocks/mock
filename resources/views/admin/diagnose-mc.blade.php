<!DOCTYPE html>
<html>
<head>
    <title>Diagnose Multiple Choice</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 20px; border: 1px solid #ddd; }
        .result { background: #f5f5f5; padding: 10px; margin-top: 10px; }
        pre { background: #333; color: #0f0; padding: 10px; overflow: auto; }
    </style>
</head>
<body>
    <h1>Multiple Choice Diagnostic Tool</h1>
    
    <div class="test-section">
        <h2>Test 1: Normal Form Submit</h2>
        <form id="normalForm" method="POST" action="/admin/diagnose-mc">
            @csrf
            <label><input type="checkbox" name="correct_option[]" value="0"> Option A</label><br>
            <label><input type="checkbox" name="correct_option[]" value="1"> Option B</label><br>
            <label><input type="checkbox" name="correct_option[]" value="2"> Option C</label><br>
            <label><input type="checkbox" name="correct_option[]" value="3"> Option D</label><br>
            <button type="submit">Submit Normal Form</button>
        </form>
        <div id="result1" class="result"></div>
    </div>
    
    <div class="test-section">
        <h2>Test 2: FormData Submit</h2>
        <form id="formDataTest">
            @csrf
            <label><input type="checkbox" name="correct_option[]" value="0"> Option A</label><br>
            <label><input type="checkbox" name="correct_option[]" value="1"> Option B</label><br>
            <label><input type="checkbox" name="correct_option[]" value="2"> Option C</label><br>
            <label><input type="checkbox" name="correct_option[]" value="3"> Option D</label><br>
            <button type="button" onclick="testFormData()">Submit with FormData</button>
        </form>
        <div id="result2" class="result"></div>
    </div>
    
    <div class="test-section">
        <h2>Test 3: JSON Submit</h2>
        <form id="jsonTest">
            <label><input type="checkbox" class="json-cb" value="0"> Option A</label><br>
            <label><input type="checkbox" class="json-cb" value="1"> Option B</label><br>
            <label><input type="checkbox" class="json-cb" value="2"> Option C</label><br>
            <label><input type="checkbox" class="json-cb" value="3"> Option D</label><br>
            <button type="button" onclick="testJSON()">Submit as JSON</button>
        </form>
        <div id="result3" class="result"></div>
    </div>
    
    <script>
        // Normal form submit
        document.getElementById('normalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch('/admin/diagnose-mc', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(r => r.text())
            .then(data => {
                document.getElementById('result1').innerHTML = '<pre>' + data + '</pre>';
            });
        });
        
        // FormData test
        function testFormData() {
            const form = document.getElementById('formDataTest');
            const formData = new FormData();
            
            // Add CSRF
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            // Add checked values
            form.querySelectorAll('input[type="checkbox"]:checked').forEach(cb => {
                formData.append('correct_option[]', cb.value);
            });
            
            // Log what we're sending
            console.log('FormData entries:');
            for(let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            fetch('/admin/diagnose-mc', {
                method: 'POST',
                body: formData
            })
            .then(r => r.text())
            .then(data => {
                document.getElementById('result2').innerHTML = '<pre>' + data + '</pre>';
            });
        }
        
        // JSON test
        function testJSON() {
            const values = [];
            document.querySelectorAll('.json-cb:checked').forEach(cb => {
                values.push(cb.value);
            });
            
            const data = {
                _token: document.querySelector('meta[name="csrf-token"]').content,
                correct_option: values
            };
            
            console.log('JSON data:', data);
            
            fetch('/admin/diagnose-mc', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(r => r.text())
            .then(data => {
                document.getElementById('result3').innerHTML = '<pre>' + data + '</pre>';
            });
        }
    </script>
</body>
</html>
