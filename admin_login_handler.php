<?php
session_start();

$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!"; // MUST USE YOUR PASSWORD HERE
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { 
    $_SESSION['login_error'] = "Database connection failed.";
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $input_password = $_POST['password'] ?? '';

    // 1. Fetch user data by email
    $sql = "SELECT user_id, hashed_password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // 2. Verify password (assuming you are using password_hash and password_verify)
        if (password_verify($input_password, $user['hashed_password'])) {
            
            // 3. CRITICAL: Check for Admin Role
            if ($user['role'] === 'admin') {
                // Login successful and user is admin
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role']; 
                
                $stmt->close();
                $conn->close();
                header('Location: admin.php'); // Redirect to admin dashboard
                exit;
            } else {
                // Password correct, but not an admin role
                $_SESSION['login_error'] = "Access denied. You do not have administrator privileges.";
            }
        } else {
            // Invalid password
            $_SESSION['login_error'] = "Invalid email or password.";
        }
    } else {
        // User not found
        $_SESSION['login_error'] = "Invalid email or password.";
    }
    
    $stmt->close();
}

$conn->close();
header('Location: admin_login.php'); // Redirect back to login page with error
exit;
?>
