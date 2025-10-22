<?php
// CRITICAL: Always start a session before setting session variables
session_start();

// Database Configuration (Ensure these match your store_data.php)
$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";     
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed: " . $conn->connect_error);
}

// 1. Get and sanitize input
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$plain_password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

if (empty($email) || empty($plain_password)) {
    http_response_code(400);
    die("Email and password are required.");
}

// 2. Prepare the SQL to find the user by email
$sql = "SELECT user_id, first_name, hashed_password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    $hashed_password = $user['hashed_password'];

    // --- TEMPORARY DEBUGGING CODE START ---
// Compare the submitted password to the retrieved hash
$is_verified = password_verify($plain_password, $hashed_password);

// If verification fails, show the hash and the submitted password (temporarily, NEVER DO THIS IN PRODUCTION)
if (!$is_verified) {
    echo "DEBUG FAIL: Verification failed. Submitted: " . $plain_password . " | Hash: " . $hashed_password;
    exit();
}

    // 3. Verify the password
    if (password_verify($plain_password, $hashed_password)) {
        
        // 4. Success: Start the session and store user info
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['first_name'];
        $_SESSION['logged_in'] = true;
        
        echo "success"; // Send success message back to AJAX

    } else {
        // Failure: Password mismatch
        echo "Invalid email or password."; 
    }
} else {
    // Failure: User not found
    echo "Invalid email or password.";
}

$stmt->close();
$conn->close();
?>