<?php
// product_single_api.php
session_start();

// 1. Database Configuration (Use your established credentials)
$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!"; // **Ensure this is your actual DB password**
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { 
    // Fail immediately if DB connection fails
    header('Content-Type: application/json');
    die(json_encode(["error" => "Database connection failed."])); 
}

// 2. Admin/Security Check (Optional but recommended)
if (!isset($_SESSION['logged_in']) || $_SESSION['user_id'] != 2) { 
    header('Content-Type: application/json');
    die(json_encode(["error" => "Access Denied"]));
}

// 3. Get the Product ID from the URL query string
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header('Content-Type: application/json');
    die(json_encode(["error" => "Missing product ID parameter."]));
}

// 4. Prepare and Execute the SQL SELECT statement
$sql = "SELECT product_id, name, price, stock_quantity, description 
        FROM products 
        WHERE product_id = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id); // 'i' indicates an integer parameter

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // 5. Output the single product data as JSON
    header('Content-Type: application/json');
    echo json_encode($product);
} else {
    // Error during query execution
    header('Content-Type: application/json');
    error_log("Query failed for single product: " . $stmt->error);
    die(json_encode(["error" => "Query execution error."]));
}

$stmt->close();
$conn->close();
?>