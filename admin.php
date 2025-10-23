<?php
// admin.php

session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    // If not, redirect them to the admin login page
    header('Location: admin_login.php'); 
    exit();
}

// All necessary CSS and JS links will go here
?>
<!DOCTYPE html>
<html lang="en">
    
<head>

    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <a href="logout.php" class="btn btn-danger float-end">Log Out</a>

    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css">
 
</head>
<body>
    <div class="container mt-5">
        <hr class="my-5">

<h1>User Management</h1>

<table id="userTable" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Name</th>
            <th>Contact</th>
            <th>Role</th> 
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        </tbody>
</table>
        <h1>Product Management</h1>
        <button id="addProductBtn" class="btn btn-primary mb-3">Add New Product</button>
        
        <table id="productTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>

        <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add/Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="product_id" name="product_id">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
                <div class="modal fade" id="userRoleModal" tabindex="-1" aria-labelledby="userRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userRoleModalLabel">Edit User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userRoleForm">
                    <input type="hidden" id="user_id_role" name="user_id">
                    <p>Editing role for: <strong id="user_name_display"></strong></p>
                    <div class="mb-3">
                        <label for="role_select" class="form-label">Select New Role</label>
                        <select class="form-control" id="role_select" name="role" required>
                            <option value="user">User (Standard)</option>
                            <option value="admin">Admin (Full Access)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Role</button>
                </form>
            </div>
        </div>
    </div>
</div>
        </div>
    <script src="node_modules/jquery/dist/jquery.min.js"></script>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>

    <script src="admin_script.js"></script> 
</body>
</html>