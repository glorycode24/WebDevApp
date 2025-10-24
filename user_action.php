<?php
// user_action.php (Handles DELETE and later, UPDATE role)
session_start();

$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";   
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { 
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'DB Connection failed.']));
}


$action = $_GET['action'];
$response = ['success' => false, 'message' => 'Invalid action.'];

if ($action == 'delete') {
    $user_id = $_POST['user_id'] ?? null;
    
    // CRITICAL: Prevent deleting the current admin account
    if ($user_id == $_SESSION['user_id']) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'Cannot delete the active admin account.']));
    }

    if ($user_id) {
        $sql = "DELETE FROM users WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'User deleted successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Database Error: ' . $stmt->error];
        }
        $stmt->close();
    } else {
        $response = ['success' => false, 'message' => 'Missing User ID.'];
    }
} 
else if ($action == 'update_role') { // <-- NEW, SEPARATE BLOCK FOR ROLE UPDATE
    $user_id = $_POST['user_id'] ?? null;
    $role = $_POST['role'] ?? null;

    if ($user_id && $role) {
        $sql = "UPDATE users SET role=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $role, $user_id);

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'User role updated to ' . $role . ' successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Database Error: ' . $stmt->error];
        }
        $stmt->close();
    } else {
         $response = ['success' => false, 'message' => 'Missing User ID or Role.'];
    }
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>