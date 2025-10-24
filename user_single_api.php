<?php
// user_single_api.php
session_start();

$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";   
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);

// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Access denied. Admin privileges required.']);
    exit;
}

$user_id = $_GET['id'] ?? null;
if (!$user_id) {
    header('Content-Type: application/json');
    die(json_encode(["error" => "Missing user ID parameter."]));
}

// Select only the necessary fields, excluding the hashed password for security
$sql = "SELECT user_id, first_name, last_name, email, role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); 

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    header('Content-Type: application/json');
    echo json_encode($user);
} else {
    header('Content-Type: application/json');
    die(json_encode(["error" => "Query execution error."]));
}

$stmt->close();
$conn->close();
?>