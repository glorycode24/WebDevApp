<?php
// product_store_api.php - Fetches product data from the database and returns it as JSON.

// --- CRITICAL: Add error reporting for debugging. REMOVE OR SET TO 0 IN PRODUCTION! ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering to catch any accidental output before JSON header
ob_start();

// Set JSON header for the response (important to do BEFORE any other output)
header('Content-Type: application/json');

// --- Include your utils file first, as it contains the database connection function ---
include_once 'includes/utils.php';

// --- CRITICAL: Call the get_db_connection function to establish $conn ---
$conn = get_db_connection();

// Check if $conn was successfully established
if ($conn === null) { // get_db_connection returns null on failure
    ob_clean(); // Clean any buffered output before sending error JSON
    http_response_code(500); // Internal Server Error
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed." // Error already logged by get_db_connection
    ]));
}

// Ensure character set is set for the connection
$conn->set_charset("utf8mb4");


// --- 3. SQL QUERY SETUP ---
$limit = $_GET['limit'] ?? null; // Get optional limit parameter (used by bootstrap.php for featured products)

// Base query
$sql = "SELECT product_id, name, description, price, stock_quantity, image_url FROM products";

// Add LIMIT clause if specified (for featured products on bootstrap.php)
if (is_numeric($limit) && $limit > 0) {
    // We order by product_id DESC to get the newest (or last added) products as "featured"
    $sql .= " ORDER BY product_id DESC LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $limit);
} else {
    // For Products.php (which shows all), we simply prepare the base statement
    $sql .= " ORDER BY product_id ASC"; // Default order
    $stmt = $conn->prepare($sql);
}

if (!$stmt) {
    http_response_code(500);
    die(json_encode([
        "success" => false, 
        "message" => "SQL Prepare Failed: " . $conn->error
    ]));
}

// --- 4. EXECUTION AND DATA FETCH ---

if (!$stmt->execute()) {
    http_response_code(500);
    die(json_encode([
        "success" => false, 
        "message" => "SQL Execute Failed: " . $stmt->error
    ]));
}

$result = $stmt->get_result();
$products = [];

// Fetch all rows into an array
while ($row = $result->fetch_assoc()) {
    // Ensure price is a float for consistency
    $row['price'] = (float)$row['price'];
    $products[] = $row;
}

$stmt->close();
$conn->close();

// --- 5. FINAL JSON OUTPUT ---

// This will output the array of products, which the JavaScript on Products.php expects.
echo json_encode([
    "success" => true,
    "products" => $products,
    "count" => count($products)
]);

?>
