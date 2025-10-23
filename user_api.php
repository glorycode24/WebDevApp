<?php
// user_api.php

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_id'] != 2) { 
    // Return a JSON error for AJAX calls, not a standard die()
    header('Content-Type: application/json');
    die(json_encode(["draw" => 1, "recordsTotal" => 0, "recordsFiltered" => 0, "data" => [], "error" => "Access Denied"]));
}

// 1. Database Configuration
$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";   
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die(json_encode(["error" => "Connection failed: " . $conn->connect_error])); }

// DataTables request parameters (same variables as product_api.php)
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$search_value = $_POST['search']['value'];
$order_column_index = $_POST['order'][0]['column'];
$order_dir = $_POST['order'][0]['dir'];

// CRITICAL: Map DataTables column index to actual DB column names
$columns = array('user_id', 'email', 'first_name', 'contact_number', 'role'); 
$order_column = $columns[$order_column_index];

// 1. Count total records (without filter)
$total_sql = "SELECT COUNT(*) as total FROM users";
// ... (Execute total count query) ...
$total_records = $conn->query($total_sql)->fetch_assoc()['total'];

// 2. Build the SELECT query
$query = "SELECT user_id, first_name, last_name, email, contact_number, role FROM users ";
$where = "";

// Handle Search Filter (Search by name, email, or contact)
if (!empty($search_value)) {
    $where .= "WHERE email LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR contact_number LIKE ? ";
}

$query .= $where;
$query .= "ORDER BY $order_column $order_dir ";
$query .= "LIMIT ?, ?";


// 3. Prepare and execute the statement
$stmt = $conn->prepare($query);

// Determine parameter types and values for binding
$param_types = "";
$param_values = [];
$total_filtered_records = $total_records;

if (!empty($search_value)) {
    $search_term = "%$search_value%";
    $param_types .= "ssss";
    $param_values[] = $search_term;
    $param_values[] = $search_term;
    $param_values[] = $search_term;
    $param_values[] = $search_term;
    
    // Recount filtered records (implement this part accurately, similar to product_api.php)
    // ... (Execute filtered count query) ...
    $filtered_sql = "SELECT COUNT(*) as total FROM users " . $where;
    $filtered_stmt = $conn->prepare($filtered_sql);
    $filtered_stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
    $filtered_stmt->execute();
    $total_filtered_records = $filtered_stmt->get_result()->fetch_assoc()['total'];
    $filtered_stmt->close();
}

$param_types .= "ii"; // For LIMIT
$param_values[] = (int)$start;
$param_values[] = (int)$length;

// Bind all parameters
$stmt->bind_param($param_types, ...$param_values);
$stmt->execute();
$result = $stmt->get_result();

// 4. Format the output for DataTables
$data = [];
while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// DataTables required JSON structure
$response = [
    "draw" => intval($draw),
    "recordsTotal" => intval($total_records),
    "recordsFiltered" => intval($total_filtered_records),
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$conn->close();
?>