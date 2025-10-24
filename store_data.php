<?php
// 1. Database Configuration (same as before)
$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";     
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



// 2. Get ALL data from AJAX POST request
$firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
$lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$homeAddress = filter_input(INPUT_POST, 'homeAddress', FILTER_SANITIZE_STRING);
$registerContact = filter_input(INPUT_POST, 'registerContact', FILTER_SANITIZE_STRING);
$plainPassword = isset($_POST['password']) ? trim($_POST['password']) : '';
// 3. **SECURITY CRITICAL:** Hash the password
$hashed_password = password_hash($plainPassword, PASSWORD_DEFAULT);

if (
    empty($_POST['firstName']) ||
    empty($_POST['lastName']) ||
    !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) || // Validate email format
    empty($_POST['homeAddress']) ||
    empty($_POST['registerContact']) ||
    empty($_POST['password'])
) {
    http_response_code(400);
    // Send an error message back to the fetch request
    die("Error: One or more required fields are missing or invalid.");
}

// 4. Prepare the SQL INSERT statement with all fields
$sql = "INSERT INTO users 
        (first_name, last_name, email, home_address, contact_number, hashed_password) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssss", 
    $firstName, 
    $lastName, 
    $email, 
    $homeAddress, 
    $registerContact, 
    $hashed_password
);



// 5. Execute and check (same as before)
if ($stmt->execute()) {
    echo "success";
} else {
    // You might want to log this error instead of echoing it in a production app
    echo "Error: " . $stmt->error; 
}

$stmt->close();
$conn->close();
?>