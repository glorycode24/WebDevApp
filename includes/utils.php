<?php
// CRITICAL: Database Configuration Constants
// NOTE: Use your exact credentials here. I am using the password you provided previously.
define('DB_SERVERNAME', "localhost:3306");
define('DB_USERNAME', "root"); 
define('DB_PASSWORD', "Asdasfssdafsadfadas123!");     
define('DB_NAME', "webappdata");

/**
 * Creates and returns a new MySQLi database connection.
 * Handles and logs connection errors.
 * @return mysqli|null The database connection object or null on failure.
 */
function get_db_connection() {
    // Use @mysqli to suppress default warning, we handle errors manually
    $conn = @new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) { 
        // Log the error (optional, but good practice)
        error_log("Database Connection Failed: " . $conn->connect_error);
        return null; // Return null to signal failure
    }
    return $conn;
}

/**
 * Checks if a user is currently authenticated by checking the session.
 * @return bool True if the user is logged in, false otherwise.
 */
function is_user_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Calculates the total number of items in the user's cart.
 * @return int The total number of items.
 */
function get_cart_item_count() {
    if (!is_user_logged_in()) {
        return 0;
    }

    $user_id = $_SESSION['user_id'];
    $conn = get_db_connection();

    if ($conn === null) {
        return 0; // Cannot check cart without connection
    }

    // Prepare statement to sum the quantities for the user's cart
    $sql = "SELECT SUM(quantity) AS total_items FROM carts WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $count = 0;
    if ($row = $result->fetch_assoc()) {
        $count = (int) ($row['total_items'] ?? 0);
    }
    
    $stmt->close();
    $conn->close();
    
    return $count;
}

?>
