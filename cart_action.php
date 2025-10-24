<?php
// cart_action.php - Handles adding/updating items in the user's cart

session_start();

// 1. Database Configuration (Use your existing credentials)
$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";   
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { 
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error])); 
}

// Set JSON header for the response
header('Content-Type: application/json');

// Security check: Must be logged in
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(["success" => false, "message" => "You must be logged in to modify your cart."]));
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? '';
$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (empty($product_id) || !is_numeric($product_id)) {
    http_response_code(400);
    die(json_encode(["success" => false, "message" => "Invalid product ID."]));
}

// Convert quantity to a safe integer, minimum of 1
$quantity = max(1, (int)$quantity);

// --- 1. HANDLE 'ADD' or 'UPDATE' ACTION ---
if ($action === 'add') {
    // Check if the product already exists in the cart for this user
    $sql_check = "SELECT quantity FROM carts WHERE user_id = ? AND product_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ii", $user_id, $product_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($row = $result_check->fetch_assoc()) {
        // Product EXISTS: Increment the quantity
        $new_quantity = $row['quantity'] + $quantity;

        // Check stock (Assuming a separate function/logic would handle real stock checks later)
        // For now, we update directly
        $sql_update = "UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("iii", $new_quantity, $user_id, $product_id);
        
        if ($stmt_update->execute()) {
            echo json_encode(["success" => true, "message" => "Product quantity updated in cart.", "new_quantity" => $new_quantity]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update cart item: " . $conn->error]);
        }
        $stmt_update->close();

    } else {
        // Product DOES NOT EXIST: Insert new item
        $sql_insert = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("iii", $user_id, $product_id, $quantity);
        
        if ($stmt_insert->execute()) {
            echo json_encode(["success" => true, "message" => "Product added to cart."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to add product to cart: " . $conn->error]);
        }
        $stmt_insert->close();
    }
    $stmt_check->close();

} else {
    // Invalid action requested
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Invalid cart action specified."]);
}

$conn->close();
?>
