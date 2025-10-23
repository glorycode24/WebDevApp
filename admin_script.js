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
});