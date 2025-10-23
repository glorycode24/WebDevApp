<?php
// product_action.php (Handles INSERT, UPDATE, DELETE)
session_start();
// Perform admin/login check


$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";     
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);

$action = $_GET['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid action.'];

switch ($action) {
    case 'insert':
    case 'update':
        $product_id = $_POST['product_id'] ?? null;
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock_quantity'];
        $desc = $_POST['description'];

        if ($action == 'insert') {
            $sql = "INSERT INTO products (name, price, stock_quantity, description) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdis", $name, $price, $stock, $desc);
        } else { // update
            $sql = "UPDATE products SET name=?, price=?, stock_quantity=?, description=? WHERE product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sdisi", $name, $price, $stock, $desc, $product_id);
        }

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Product ' . $action . 'ed successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Database Error: ' . $stmt->error];
        }
        $stmt->close();
        break;

    case 'delete':
        $product_id = $_POST['product_id'];
        $sql = "DELETE FROM products WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $product_id);

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Product deleted successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Database Error: ' . $stmt->error];
        }
        $stmt->close();
        break;
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>