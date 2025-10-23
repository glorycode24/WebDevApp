// admin_script.js
$(document).ready(function() {

    // 1. INITIALIZE THE DATATABLE AND STORE IT IN THE productTable VARIABLE
    var productTable = $('#productTable').DataTable({
        "processing": true,
        "serverSide": true, // Tells DataTables to load data via AJAX, page by page
        "ajax": {
            "url": "product_api.php", // This is the file that will return the product data
            "type": "POST"
        },
        "columns": [
            // Corresponds to the columns returned by the API
            { "data": "product_id" }, 
            { "data": "name" },
            { "data": "price" },
            { "data": "stock_quantity" },
            { "data": "description" },
            { 
                "data": null, // Use null to indicate this column doesn't map directly to a data property
                "render": function (data, type, row) {
                    // Create Edit and Delete buttons
                    return '<button class="btn btn-sm btn-info editBtn" data-id="'+row.product_id+'">Edit</button> ' +
                           '<button class="btn btn-sm btn-danger deleteBtn" data-id="'+row.product_id+'">Delete</button>';
                }
            }
        ]
    });

    // admin_script.js (Add this after the productTable setup)

// Initialize User Management Table
var userTable = $('#userTable').DataTable({
    "processing": true,
    "serverSide": true, 
    "ajax": {
        "url": "user_api.php", // NEW API file for user data
        "type": "POST"
    },
    "columns": [
        // Corresponds to the column names in your 'users' table
        { "data": "user_id" }, 
        { "data": "email" },
        { 
            "data": null,
            "render": function(data, type, row) {
                // Combine first and last name for display
                return row.first_name + ' ' + row.last_name;
            }
        },
        { "data": "contact_number" },
        { "data": "role" },
        { "data": "actions" }
    ]
});
    var userRoleModal = new bootstrap.Modal(document.getElementById('userRoleModal'));
    // 2. INITIALIZE THE BOOTSTRAP MODAL
    // NOTE: This must be done AFTER the table, but BEFORE any handlers use it.
    var productModal = new bootstrap.Modal(document.getElementById('productModal'));


    // 3. EVENT HANDLERS (These now correctly use productTable and productModal)

    // Handle 'Add New Product' Button click
    $('#addProductBtn').on('click', function() {
        $('#productForm').trigger('reset'); // Clear form
        $('#product_id').val(''); // Clear ID (tells API this is a new insert)
        $('#productModalLabel').text('Add New Product');
        productModal.show();
    });


    // Handle 'Edit' Button click (Delegation required since buttons are added dynamically)
    $('#productTable tbody').on('click', '.editBtn', function() {
        var id = $(this).data('id');
        
        // AJAX to get single product data (using product_single_api.php)
        $.get('product_single_api.php?id=' + id, function(data) {
            if(data) {
                $('#product_id').val(data.product_id);
                $('#name').val(data.name);
                $('#price').val(data.price);
                $('#stock_quantity').val(data.stock_quantity);
                $('#description').val(data.description);
                $('#productModalLabel').text('Edit Product: ' + data.name);
                productModal.show();
            }
        });
    });


    // Handle Form Submission (Add/Edit)
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var action = $('#product_id').val() ? 'update' : 'insert';

        $.post('product_action.php?action=' + action, formData, function(response) {
            productModal.hide();
            productTable.ajax.reload(); // Uses the defined productTable variable
            alert(response.message);
        }, 'json'); // Expect JSON response
    });


    // Handle 'Delete' Button click
    $('#productTable tbody').on('click', '.deleteBtn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this product?')) {
            $.post('product_action.php?action=delete', { product_id: id }, function(response) {
                productTable.ajax.reload(); // Uses the defined productTable variable
                alert(response.message);
            }, 'json');
        }
    });

$('#userTable tbody').on('click', '.deleteUserBtn', function() {
    var id = $(this).data('id');
    
   

    if (confirm('WARNING: Are you sure you want to delete this user? This action is irreversible.')) {
        $.post('user_action.php?action=delete', { user_id: id }, function(response) {
            userTable.ajax.reload();
            alert(response.message);
        }, 'json').fail(function(xhr) {
             alert("Error deleting user: " + (xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText));
        });
    }
});


// Handle 'Edit Role' Button click (MUST be outside the delete handler)
$('#userTable tbody').on('click', '.editUserBtn', function() {
    var id = $(this).data('id');

    // AJAX to get single user data
    $.get('user_single_api.php?id=' + id, function(data) {
        if(data) {
            // Populate the modal fields
            $('#user_id_role').val(data.user_id);
            $('#user_name_display').text(data.first_name + ' ' + data.last_name + ' (' + data.email + ')');
            $('#role_select').val(data.role); // Set the current role

            // Show the modal
            userRoleModal.show();
        }
    }, 'json').fail(function(xhr) {
         // The error message from your screenshot 7f2afe80-43b3-4cc5-90cf-ff35d81eb219:
         alert("Error fetching user data: " + (xhr.responseJSON ? xhr.responseJSON.error : "Unknown error"));
    });
});

// Handle Role Form Submission (MUST be outside the delete handler)
$('#userRoleForm').on('submit', function(e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.post('user_action.php?action=update_role', formData, function(response) {
        userRoleModal.hide();
        userTable.ajax.reload(); // Reloads the DataTable to show the new role
        alert(response.message);
    }, 'json').fail(function(xhr) {
         alert("Error saving role: " + (xhr.responseJSON ? xhr.responseJSON.message : "Unknown error"));
    });
});

});
