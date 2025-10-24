document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Stop the form from submitting normally

    const password = document.getElementById('registerPassword').value;
    const confirm_password = document.getElementById('confirmPassword').value;

    const messageDisplay = document.getElementById('message');

    // ** Client-Side Validation: Check if passwords match **
    if (password !== confirm_password) {
        messageDisplay.style.color = 'red';
        messageDisplay.textContent = 'Passwords do not match!';
        return; // Stop the AJAX submission
    }

        const form = document.getElementById('registerForm');
         const formData = new FormData(form);

            // 2. AJAX Request using Fetch API (modern AJAX)
            fetch('store_data.php', {
                method: 'POST',
                body: formData // The data to send to the server
            })
            .then(response => response.text())
            .then(data => {
                // 3. Handle the server's response
                if (data.trim() === 'success') {
                    messageDisplay.style.color = 'green';
                    messageDisplay.textContent = 'Signed up successfully! 🎉';
                    document.getElementById('registerForm').reset(); // Clear the form
                } else {
                    messageDisplay.style.color = 'red';
                    messageDisplay.textContent = 'Error saving data: ' + data;
                }
            })
            .catch(error => {
                // Handle network or other errors
                messageDisplay.style.color = 'red';
                messageDisplay.textContent = 'Network error: ' + error;
            });
        });