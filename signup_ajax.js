document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Stop the form from submitting normally

            const first_name = document.getElementById('first_name').value;
    const last_name = document.getElementById('last_name').value;
    const email = document.getElementById('email').value;
    const home_address = document.getElementById('home_address').value;
    const contact_number = document.getElementById('contact_number').value;
    const password = document.getElementById('password').value;
    const confirm_password = document.getElementById('confirm_password').value;

    const messageDisplay = document.getElementById('message');

    // ** Client-Side Validation: Check if passwords match **
    if (password !== confirm_password) {
        messageDisplay.style.color = 'red';
        messageDisplay.textContent = 'Passwords do not match!';
        return; // Stop the AJAX submission
    }
            // 2. Create FormData object with ALL fields
              const formData = new FormData();
             formData.append('first_name', first_name);
             formData.append('last_name', last_name);
              formData.append('email', email);
              formData.append('home_address', home_address);
             formData.append('contact_number', contact_number);
             formData.append('password', password); // Send the plain password to PHP for HASHING

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
                    messageDisplay.textContent = 'Data saved successfully! 🎉';
                    document.getElementById('dataForm').reset(); // Clear the form
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