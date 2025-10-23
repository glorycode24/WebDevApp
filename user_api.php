<?php
// user_api.php

session_start();


// 1. Database Configuration
$servername = "localhost:3306";
$username = "root"; 
$password = "Asdasfssdafsadfadas123!";   
$dbname = "webappdata";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die(json_encode(["error" => "Connection failed: " . $conn->connect_error])); }

// Check if logged in (Basic security check is necessary to prevent unauthorized access)
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_SESSION['user_id'])) {
    http_response_code(403);
    die(json_encode(['data' => [], 'error' => 'Unauthorized access.']));
}
$current_user_id = $_SESSION['user_id']; // Current logged-in user ID

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
    // Start of the corrected logic block
    $temp_row = $row;
    
    // Logic to determine if this row is the currently logged-in user
    if ($row['user_id'] == $current_user_id) {
        $temp_row['actions'] = '<span class="badge bg-secondary">Self</span>';
    } else {
        // Generate the action buttons for other users
        $temp_row['actions'] = '<button class="btn btn-sm btn-warning editUserBtn" data-id="'.$row['user_id'].'">Edit Role</button> ' .
                               '<button class="btn btn-sm btn-danger deleteUserBtn" data-id="'.$row['user_id'].'">Delete</button>';
    }
    
    // Add the row (with the new 'actions' field) to the final data array
    $data[] = $temp_row;
    // End of the corrected logic block
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