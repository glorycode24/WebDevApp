<?php
// This file requires config.php to be included before it is used.

/**
 * Checks if a user is currently authenticated by verifying the 'user_id' in the session.
 * @return bool
 */
function is_user_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Retrieves the total quantity of all items in the logged-in user's cart.
 * If not logged in, returns 0.
 * @return int The total quantity of items in the cart.
 */
function get_cart_item_count() {
    // Return 0 immediately if not logged in
    if (!is_user_logged_in()) {
        return 0;
    }

    $user_id = $_SESSION['user_id'];
    
    // CRITICAL: The function must create its own database connection
    // since it is a reusable standalone unit.
    $conn = @new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        error_log("Database Connection Failed for Cart Count: " . $conn->connect_error);
        return 0;
    }

    // SQL to sum the 'quantity' of all items for the user
    $sql = "SELECT SUM(quantity) AS total_items FROM carts WHERE user_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Cart Count Prepare Failed: " . $conn->error);
        $conn->close();
        return 0;
    }

    $stmt->bind_param("i", $user_id);
    
    if (!$stmt->execute()) {
        error_log("Cart Count Execute Failed: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return 0;
    }

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();

    // Return the total quantity, or 0 if the cart is empty (total_items would be NULL)
    return (int)($row['total_items'] ?? 0); 
}
?>
