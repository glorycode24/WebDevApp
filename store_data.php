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
$first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$home_address = filter_input(INPUT_POST, 'home_address', FILTER_SANITIZE_STRING);
$contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
$plain_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// 3. **SECURITY CRITICAL:** Hash the password
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

// 4. Prepare the SQL INSERT statement with all fields
$sql = "INSERT INTO users 
        (first_name, last_name, email, home_address, contact_number, hashed_password) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// "ssssss" means six string parameters (s: string)
$stmt->bind_param(
    "ssssss", 
    $first_name, 
    $last_name, 
    $email, 
    $home_address, 
    $contact_number, 
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