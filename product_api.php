<?php
// product_api.php

session_start();


// 1. Database Configuration
$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";   
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die(json_encode(["error" => "Connection failed: " . $conn->connect_error])); }

// DataTables request parameters
$draw = $_POST['draw'];
$start = $_POST['start'];
$length = $_POST['length'];
$search_value = $_POST['search']['value'];
$order_column_index = $_POST['order'][0]['column'];
$order_dir = $_POST['order'][0]['dir'];

// Map DataTables column index to actual DB column names
$columns = array('product_id', 'name', 'price', 'stock_quantity', 'description'); 
$order_column = $columns[$order_column_index];

// 2. Count total records (without filter)
$total_sql = "SELECT COUNT(*) as total FROM products";
$total_result = $conn->query($total_sql)->fetch_assoc();
$total_records = $total_result['total'];

// 3. Build the SELECT query
$query = "SELECT * FROM products ";
$where = "";

// Handle Search Filter
if (!empty($search_value)) {
    $where .= "WHERE name LIKE ? OR description LIKE ? ";
}

$query .= $where;

// Handle Ordering
$query .= "ORDER BY $order_column $order_dir ";

// Handle Pagination
$query .= "LIMIT ?, ?";


// 4. Prepare and execute the statement
$stmt = $conn->prepare($query);

// Determine parameter types and values for binding
$param_types = "";
$param_values = [];

if (!empty($search_value)) {
    $search_term = "%$search_value%";
    $param_types .= "ss";
    $param_values[] = $search_term;
    $param_values[] = $search_term;
    
    // Recount filtered records only if a search term exists
    $filtered_sql = "SELECT COUNT(*) as total FROM products " . $where;
    $filtered_stmt = $conn->prepare($filtered_sql);
    $filtered_stmt->bind_param("ss", $search_term, $search_term);
    $filtered_stmt->execute();
    $filtered_result = $filtered_stmt->get_result()->fetch_assoc();
    $total_filtered_records = $filtered_result['total'];
    $filtered_stmt->close();

} else {
    $total_filtered_records = $total_records;
}

$param_types .= "ii"; // For LIMIT
$param_values[] = $start;
$param_values[] = $length;

// Bind all parameters
$stmt->bind_param($param_types, ...$param_values);
$stmt->execute();
$result = $stmt->get_result();

// 5. Format the output for DataTables
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