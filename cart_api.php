<?php
// cart_api.php - Fetches detailed contents of the user's shopping cart

// Use the utility file for config and auth check
include_once 'includes/utils.php';
safe_session_start();
$conn = get_db_connection();

// Set JSON header for the response
header('Content-Type: application/json');

// Security check: Must be logged in
if (!is_user_logged_in()) {
    http_response_code(401);
    die(json_encode(["success" => false, "message" => "You must be logged in to view your cart."]));
}

$user_id = $_SESSION['user_id'];


if (!$conn) {
    http_response_code(500);
    die(json_encode(["success" => false, "message" => "Database Connection Failed."]));
}

// SQL to join carts with products to get all necessary details
$sql = "SELECT 
            c.product_id, 
            c.quantity, 
            p.name, 
            p.price, 
            p.image_url, 
            p.stock_quantity
        FROM carts c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    $conn->close();
    die(json_encode(["success" => false, "message" => "SQL Prepare Failed: " . $conn->error]));
}

$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    http_response_code(500);
    $stmt->close();
    $conn->close();
    die(json_encode(["success" => false, "message" => "SQL Execute Failed: " . $stmt->error]));
}

$result = $stmt->get_result();
$cart_items = [];

// Fetch all rows into an array
while ($row = $result->fetch_assoc()) {
    // Ensure numeric types are correctly cast
    $row['price'] = (float)$row['price'];
    $row['quantity'] = (int)$row['quantity'];
    $row['stock_quantity'] = (int)$row['stock_quantity'];
    $cart_items[] = $row;
}

$stmt->close();
$conn->close();

// Return the list of cart items
echo json_encode([
    "success" => true,
    "cart_items" => $cart_items,
    "total_items" => array_sum(array_column($cart_items, 'quantity')) // Return total quantity for convenience
]);
?>
