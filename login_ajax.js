document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    // Check if the login form exists on the page
    if (!loginForm) return;

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault(); 

        const messageDisplay = document.getElementById('loginMessage');
        messageDisplay.textContent = ''; // Clear previous messages

        const formData = new FormData(loginForm);
        
        // 1. AJAX Request
        // IMPORTANT: Ensure the path to your PHP handler is correct
        fetch('login_handler.php', { 
            method: 'POST',
            body: formData
        })
        .then(response => {
            // If the status is NOT 200 OK (e.g., 400 or 500)
            if (!response.ok) {
                // Read the error message from the PHP script
                return response.text().then(text => { throw new Error(text) });
            }
            return response.text();
        })
        .then(data => {
            // 2. Handle success or custom error message from PHP
            if (data.trim() === 'success') {
                messageDisplay.style.color = 'green';
                messageDisplay.textContent = 'Login successful! Redirecting...';
                
                // Redirect the user to their dashboard or home page
                window.location.href = 'index.php'; 
            } else {
                messageDisplay.style.color = 'red';
                // Display the "Invalid email or password" error message
                messageDisplay.textContent = data; 
            }
        })
        .catch(error => {
            // 3. Handle network or server failure
            messageDisplay.style.color = 'red';
            messageDisplay.textContent = 'Login failed: ' + error.message; 
        });
    });
});