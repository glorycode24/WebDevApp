<?php
// Define constants for database connection
define('DB_SERVERNAME', "localhost:3306");
define('DB_USERNAME', "root"); 
define('DB_PASSWORD', "Asdasfssdafsadfadas123!");     
define('DB_NAME', "webappdata");

/**
 * Creates and returns a new mysqli database connection.
 * Handles connection errors gracefully by dying with a JSON error.
 * * @return mysqli|null The database connection object, or null on failure.
 */
function get_db_connection() {
    // Use @mysqli to suppress default PHP warning, we handle errors manually
    $conn = @new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        // Log the error for server-side debugging
        error_log("Database Connection Failed: " . $conn->connect_error);
        
        // Output a JSON error and terminate execution
        header('Content-Type: application/json', true, 500);
        die(json_encode([
            "success" => false, 
            "message" => "Server Error: Database connection failed."
        ]));
    }
    return $conn;
}

// Helper function to check login status
function is_user_logged_in() {
    // We check if the session variable exists and is not empty
    // NOTE: This assumes 'user_id' is set upon successful login.
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}
?>
