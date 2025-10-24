<?php

// --- CRITICAL: Start the session at the very beginning ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once 'includes/utils.php';

$current_user_name = $_SESSION['user_name'] ?? 'Guest';
$cart_item_count = get_cart_item_count(); // Calculate the count for display

// Redirect to login if not logged in
if (!is_user_logged_in()) {
    header('Location: bootstrap.php');
    exit;
}

// Get the user ID for fetching cart data
$user_id = $_SESSION['user_id'];

/**
 * Fetches all cart items for the current user.
 * @param int $user_id The ID of the logged-in user.
 * @return array An array of cart items, or an empty array on failure.
 */
function get_user_cart_items($user_id) {
    $conn = get_db_connection();
    if ($conn === null) return [];

    // SQL to join carts with products to get details
    $sql = "SELECT 
                c.product_id, 
                c.quantity, 
                p.name, 
                p.price, 
                p.image_url,
                p.stock_quantity
            FROM carts c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.user_id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Cart Fetch SQL Prepare Failed: " . $conn->error);
        $conn->close();
        return [];
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        error_log("Cart Fetch SQL Execute Failed: " . $stmt->error);
        $stmt->close();
        $conn->close();
        return [];
    }

    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();
    
    return $cart_items;
}

$cart_items = get_user_cart_items($user_id);
$cart_total = 0.00; // Initialize cart total
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart - Shine</title>
  <!-- Bootstrap CSS (comment out to see plain HTML) -->
<link href="./node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    /* Add a custom style for validation error messages if you want them to be red */
    .error {
      color: #dc3545; /* Bootstrap's danger color */
      font-size: 0.875em;
      margin-top: 0.25rem;
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="bootstrap.php">Shine</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="bootstrap.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="Products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="About.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="Contact.php">Contact</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <!-- CART LINK WITH DYNAMIC COUNT -->
                    <li class="nav-item">
  <a class="nav-link active position-relative" href="Cart.php">
    <i class="fas fa-shopping-cart"></i> Cart
    <!-- Dynamic Cart Count Badge -->
    <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle">
      <?php echo $cart_item_count; ?>
    </span>
  </a>
</li>
                    
                    <!-- DYNAMIC AUTH SECTION -->
                    <?php if (is_user_logged_in()): ?>
            <!-- Logged In User Links -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="logout_handler.php">Logout</a></li>
              </ul>
            </li>
          <?php else: ?>
            <!-- Guest Links -->
            <li class="nav-item">
              <!-- data-bs-toggle and data-bs-target are used by Bootstrap to open the modal -->
              <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fas fa-sign-in-alt"></i> Login
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="fas fa-user-plus"></i> Register
              </a>
            </li>
          <?php endif; ?>
                </ul>
            </div>
    </div>
  </nav>

  <!-- CART HEADER -->
  <header class="text-center text-white py-5 bg-dark" style="background:url('https://picsum.photos/1200/400?cart') center/cover no-repeat;">
    <div class="container">
      <h1 class="display-4">Your Shopping Cart</h1>
      <p class="lead">Review your selected items and proceed to checkout</p>
    </div>
  </header>

  <!-- CART CONTENT -->
  <div class="container my-5">
    <h1 class="mb-4 text-center">Your Shopping Cart</h1>
    <div id="cart-message" class="alert d-none" role="alert"></div>

    <?php if (empty($cart_items)): ?>
        <div class="card shadow-sm p-5 text-center">
            <i class="fas fa-box-open fa-3x text-secondary mb-3"></i>
            <p class="lead">Your cart is empty!</p>
            <a href="Products.php" class="btn btn-primary mt-3 w-50 mx-auto">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="table-responsive bg-white shadow-sm p-4">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col" class="text-center">Price</th>
                        <th scope="col" class="text-center">Quantity</th>
                        <th scope="col" class="text-end">Subtotal</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="cart-table-body">
                    <?php foreach ($cart_items as $item): 
                        $subtotal = $item['quantity'] * $item['price'];
                        $cart_total += $subtotal;
                    ?>
                    <tr id="cart-item-<?php echo $item['product_id']; ?>" data-product-id="<?php echo $item['product_id']; ?>" data-price="<?php echo htmlspecialchars($item['price']); ?>">
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="<?php echo htmlspecialchars($item['image_url'] ?? 'https://placehold.co/80x80/007bff/ffffff?text=Product'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-img me-3" onerror="this.onerror=null;this.src='https://placehold.co/80x80/007bff/ffffff?text=Product';">
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                    <small class="text-muted">In stock: <?php echo htmlspecialchars($item['stock_quantity']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">$<span class="product-price"><?php echo number_format($item['price'], 2); ?></span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center">
                                <button class="btn btn-sm btn-outline-secondary decrease-qty" data-product-id="<?php echo $item['product_id']; ?>" type="button" aria-label="Decrease quantity">-</button>
                                <input type="number" 
                                       class="form-control qty-input mx-1" 
                                       value="<?php echo htmlspecialchars($item['quantity']); ?>" 
                                       min="1" 
                                       max="<?php echo htmlspecialchars($item['stock_quantity']); ?>"
                                       data-product-id="<?php echo $item['product_id']; ?>"
                                       onchange="updateCartItem(this)">
                                <button class="btn btn-sm btn-outline-secondary increase-qty" data-product-id="<?php echo $item['product_id']; ?>" type="button" aria-label="Increase quantity">+</button>
                            </div>
                        </td>
                        <td class="text-end fw-bold">$<span class="item-subtotal-display" id="subtotal-<?php echo $item['product_id']; ?>"><?php echo number_format($subtotal, 2); ?></span></td>
                        <td>
                            <button class="btn btn-outline-danger btn-sm remove-item" data-product-id="<?php echo $item['product_id']; ?>" aria-label="Remove item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th class="text-end fw-bold fs-5">$<span id="cart-total-display"><?php echo number_format($cart_total, 2); ?></span></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="d-flex justify-content-end mt-4">
            <a href="Products.php" class="btn btn-secondary me-2">Continue Shopping</a>
            <button class="btn btn-success" id="checkout-button">
                <i class="fas fa-credit-card me-2"></i> Checkout ($<span id="checkout-total-display"><?php echo number_format($cart_total, 2); ?></span>)
            </button>
        </div>
    <?php endif; ?>
  </div>

  <!-- LOGIN MODAL -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Log In</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

        <?php 
            include 'login_form.php';
        ?>
        
        </div>
        <div class="modal-footer justify-content-center">
          <p class="mb-0">Not have an account yet? <a href="#" class="text-decoration-none" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#registerModal">Sign up</a></p>
        </div>
      </div>
    </div>
  </div>

  <!-- REGISTRATION MODAL -->
  <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">Sign Up</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
        <?php 
            include 'signup_form.php'; 
        ?>

        </div>
        <div class="modal-footer justify-content-center">
          <p class="mb-0">Already have an account? <a href="#" class="text-decoration-none" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">Log in</a></p>
        </div>
      </div>
    </div>
  </div>

  <!-- FORGOT PASSWORD MODAL -->
  <div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="forgotPasswordModalLabel">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-4">Enter your email address and we'll send you a link to reset your password.</p>
          <form id="forgotPasswordForm">
            <div class="mb-3">
              <label for="resetEmail" class="form-label">Email address</label>
              <input type="email" class="form-control" id="resetEmail" name="resetEmail" required> 
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-center">
          <p class="mb-0">Remember your password? <a href="#" class="text-decoration-none" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">Log in</a></p>
        </div>
      </div>
    </div>
  </div>

<!-- REGISTRATION SUCCESS MODAL -->
<div class="modal fade" id="registrationSuccessModal" tabindex="-1" aria-labelledby="registrationSuccessModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-body py-5">
        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
        <h4 class="mb-2" id="registrationSuccessModalLabel">Registration Successful!</h4>
        <p class="text-muted mb-4">🎉 Your account has been created. You can now log in.</p>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">
          Go to Login
        </button>
      </div>
    </div>
  </div>
</div>

  <!-- FOOTER -->
  <footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">© 2025 Shine | Designed for demo purposes</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- jQuery and jQuery Validation -->
  <script src="./node_modules/jquery/dist/jquery.min.js"></script>
  <script src="./node_modules/jquery-validation/dist/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
  
  <!-- Custom JavaScript for modal interactions -->
  <script>

    const processingProducts = new Set();
    const cartTotalDisplay = document.getElementById('cart-total-display');
    const checkoutTotalDisplay = document.getElementById('checkout-total-display');
    const cartCountDisplay = document.getElementById('cart-count');
    const cartMessageDisplay = document.getElementById('cart-message');

    /**
     * Updates the main cart count in the header.
     * @param {number} count The new total number of items.
     */
    function updateCartCount(count) {
        if (cartCountDisplay) {
            cartCountDisplay.textContent = count;
        }
    }

    /**
     * Shows a feedback message to the user.
     * @param {string} message The message to display.
     * @param {boolean} isSuccess Whether the message is a success or error.
     */
    function showCartMessage(message, isSuccess = true) {
        cartMessageDisplay.textContent = message;
        cartMessageDisplay.classList.remove('d-none', 'alert-success', 'alert-danger');
        cartMessageDisplay.classList.add(isSuccess ? 'alert-success' : 'alert-danger');
        // Auto-hide the message after 5 seconds
        setTimeout(() => {
            cartMessageDisplay.classList.add('d-none');
        }, 5000);
    }
    
    /**
     * Recalculates the cart total based on current visible subtotals.
     */
    function recalculateCartTotal() {
        let total = 0.00;
        document.querySelectorAll('.item-subtotal-display').forEach(el => {
            // Note: We parse the text content which is formatted, so we remove commas
            total += parseFloat(el.textContent.replace(/,/g, ''));
        });
        
        const formattedTotal = total.toFixed(2);
        
        if (cartTotalDisplay) {
            cartTotalDisplay.textContent = formattedTotal;
        }
        if (checkoutTotalDisplay) {
            checkoutTotalDisplay.textContent = formattedTotal;
        }
    }

    /**
     * Handles the AJAX call to update the cart in the database.
     * @param {number} productId The ID of the product.
     * @param {string} action The action: 'update', 'remove'.
     * @param {number} quantity The new quantity (for 'update' action).
     * @param {HTMLInputElement} inputEl The quantity input element (for 'update' action).
     */
    function processCartAction(productId, action, quantity = 0, inputEl = null) {
        if (processingProducts.has(productId)) {
            showCartMessage("Please wait for the current action to finish.", false);
            return;
        }
        
        processingProducts.add(productId);
        const $row = $(`#cart-item-${productId}`);
        const productPrice = parseFloat($row.data('price'));
        
        // Disable row controls while processing
        $row.find('button, input').prop('disabled', true);
        $row.addClass('table-active');

        const formData = new FormData();
        formData.append('product_id', productId);
        if (action === 'update') {
            formData.append('quantity', quantity);
        }
        
        fetch(`cart_action.php?action=${action}`, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                // Try to read JSON error first
                return response.json().catch(() => {
                    // Fallback to reading text if JSON fails
                    return response.text().then(text => ({ success: false, message: text || 'Server error.' }));
                }).then(json => { throw new Error(json.message) });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showCartMessage(data.message, true);
                updateCartCount(data.total_items);
                
                if (action === 'remove') {
                    // Remove the entire row
                    $row.fadeOut(300, function() { 
                        $(this).remove(); 
                        recalculateCartTotal(); 
                        // Show empty cart message if no rows left
                        if ($('#cart-table-body').children('tr').length === 0) {
                            // Reload the page to display the empty cart message correctly
                            window.location.reload(); 
                        }
                    });
                } else if (action === 'update') {
                    // Update quantity display and subtotal
                    const newQty = parseInt(data.new_quantity);
                    const newSubtotal = newQty * productPrice;
                    
                    if (inputEl) {
                        inputEl.value = newQty; // Ensure the input reflects the server's final value
                    }
                    $row.find('.item-subtotal-display').text(newSubtotal.toFixed(2));
                    recalculateCartTotal();
                    
                    // Re-enable controls
                    $row.find('button, input').prop('disabled', false);
                    $row.removeClass('table-active');
                }
                
            } else {
                showCartMessage("Cart Error: " + data.message, false);
                // Re-enable controls on error
                $row.find('button, input').prop('disabled', false);
                $row.removeClass('table-active');
            }
        })
        .catch(error => {
            showCartMessage("An unknown error occurred: " + error.message, false);
            // Re-enable controls on network/catch error
            $row.find('button, input').prop('disabled', false);
            $row.removeClass('table-active');
        })
        .finally(() => {
            processingProducts.delete(productId);
        });
    }

    /**
     * Function to call from the quantity input's onchange event.
     */
    window.updateCartItem = function(input) {
        const productId = parseInt(input.getAttribute('data-product-id'));
        let newQty = parseInt(input.value);
        const maxQty = parseInt(input.max);
        
        // Client-side validation
        if (isNaN(newQty) || newQty < 1) {
            newQty = 1;
            input.value = newQty;
        }
        if (newQty > maxQty) {
            newQty = maxQty;
            input.value = newQty;
            showCartMessage(`Quantity limited to ${maxQty} (stock available).`, false);
        }

        processCartAction(productId, 'update', newQty, input);
    }

    $(document).ready(function () {
      
      // Define a custom method to ensure a field contains only letters,
      // and optionally a single space, hyphen, or apostrophe between words.
      $.validator.addMethod("nameCharacters", function(value, element) {
          if (this.optional(element)) {
              return true;
          }
          
          // Pattern for valid characters and structure (e.g., "John Doe", "Mary-Ann O'Connell")
          const nameRegex = /^[a-zA-Z]+(?:[\s'-][a-zA-Z]+)*$/;
          
          return nameRegex.test(value);
      }, "Please enter a valid name (letters, single spaces, hyphens, and apostrophes only. Cannot start/end with a space or special character).");

      // === Password Toggle Setup ===
      function setupPasswordToggle(toggleBtnId, inputId, iconId) {
        $('#' + toggleBtnId).on('click', function() {
          var passwordInput = $('#' + inputId);
          var buttonIcon = $('#' + iconId);
    
          if (passwordInput.attr('type') === 'password') {
            // Show password
            passwordInput.attr('type', 'text');
            buttonIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            passwordInput.css('background-color', '#fff3cd'); // highlight
            passwordInput.attr('title', 'Password is visible');
          } else {
            // Hide password
            passwordInput.attr('type', 'password');
            buttonIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            passwordInput.css('background-color', ''); // reset
            passwordInput.attr('title', 'Password is hidden');
          }
        });
      }
      
    
      // Apply to all password fields
      setupPasswordToggle('toggleLoginPassword', 'loginPassword', 'loginPasswordIcon');
      setupPasswordToggle('toggleRegisterPassword', 'registerPassword', 'registerPasswordIcon');
      setupPasswordToggle('toggleConfirmPassword', 'confirmPassword', 'confirmPasswordIcon');
      
      // === Form Validation ===
      // Define a custom method to ensure a field is not just whitespace
      $.validator.addMethod("notWhitespaces", function(value, element) {
          return this.optional(element) || /\S/.test(value);
      }, "This field cannot be empty or contain only spaces.");

      // Centralized highlight/unhighlight logic for Bootstrap styling
      const applyBootstrapInvalid = function(element, isInvalid) {
        const $element = $(element);
        const $inputGroup = $element.parent('.input-group');
        const $formCheck = $element.closest('.form-check'); // For checkboxes/radios

        // Always apply/remove is-invalid from the element itself if it's a form-control
        if ($element.hasClass('form-control')) {
            $element.toggleClass('is-invalid', isInvalid);
        }

        // Handle elements within an input-group (like password fields)
        if ($inputGroup.length) {
            // Note: For an input-group, we only add is-invalid to the input itself,
            // but the validation error must be placed after the whole group (handled by errorPlacement)
            $element.toggleClass('is-invalid', isInvalid);
        } 
        // Handle checkboxes/radios within a form-check
        else if ($formCheck.length && ($element.attr('type') === 'checkbox' || $element.attr('type') === 'radio')) {
            $element.toggleClass('is-invalid', isInvalid);
            $formCheck.find('label').toggleClass('is-invalid-label', isInvalid); // Optional: add class to label
        }
      };

      $("#loginForm").validate({
        rules: {
          email: {
            required: true, 
            email: true
          },
          password: {
            required: true,
            minlength: 6,
            notWhitespaces: true 
          }
        },
        messages: {
          email: {
            required: "Please enter your email address",
            email: "Please enter a valid email address"
          },
          password: {
            required: "Please provide your password",
            minlength: "Password must be at least 6 characters",
            notWhitespaces: "Password cannot be empty"
          }
        },
        errorElement: 'div', // Use a div for error messages
        errorClass: 'error invalid-feedback', // Bootstrap's feedback class
        highlight: function(element, errorClass, validClass) {
            applyBootstrapInvalid(element, true);
        },
        unhighlight: function(element, errorClass, validClass) {
            applyBootstrapInvalid(element, false);
        },
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
              error.insertAfter(element.parent()); 
            } else {
              error.insertAfter(element);
          }
        },
        submitHandler: function(form) {
        
        const loginForm = document.getElementById('loginForm');
        const messageDisplay = document.getElementById('loginMessage');
        const formData = new FormData(loginForm);
        
        // Indicate loading state
        if (messageDisplay) {
            messageDisplay.textContent = 'Logging in...';
            messageDisplay.style.color = '#0d6efd';
        }

        // Perform AJAX Request
        fetch('login_handler.php', { 
            method: 'POST',
            body: formData
        })
        .then(response => response.text()) // Get the raw text response
        .then(data => {
            if (data.trim() === 'success') {
                if (messageDisplay) {
                    messageDisplay.style.color = 'green';
                    messageDisplay.textContent = 'Login successful! Redirecting...';
                }
                
                // Hide modal and redirect on success
                const loginModal = bootstrap.Modal.getInstance(document.getElementById("loginModal"));
                if (loginModal) loginModal.hide();
                
                setTimeout(() => {
                    window.location.href = 'bootstrap.php'; // Redirect to your homepage
                }, 500); 

            } else {
                // Display the PHP error message (e.g., "Invalid email or password.")
                if (messageDisplay) {
                    messageDisplay.style.color = 'red';
                    messageDisplay.textContent = data; 
                }
            }
        })
        .catch(error => {
            // Handle network failure
            if (messageDisplay) {
                messageDisplay.style.color = 'red';
                messageDisplay.textContent = 'Login failed: Could not connect to the server.';
            }
        });
        
        // Prevent default form submission and do not reset the form here
        return false;
        }
      });


      $("#registerForm").validate({
        rules: {
          firstName: {
            required: true, 
            notWhitespaces: true,
            nameCharacters: true
          },
          lastName: {
            required: true, 
            notWhitespaces: true,
            nameCharacters: true
          },
          email: {
            required: true, 
            email: true
          },
          homeAddress: {
            required: true, 
            notWhitespaces: true 
          },
          registerContact: {
            required: true, 
            pattern: /^\+?[0-9]+$/ 
          },
          password: {
            required: true, 
            minlength: 6,
            notWhitespaces: true 
          },
          confirmPassword: {
            required: true, 
            equalTo: "#registerPassword",
            notWhitespaces: true 
          },
          agreeTerms: {
              required: true 
          }
        },
        messages: {
          firstName: {
            required: "Please enter your first name",
            notWhitespaces: "First name cannot be empty",
            nameCharacters: "Please enter a valid first name (letters, single spaces, hyphens, or apostrophes)."
          },
          lastName: {
            required: "Please enter your last name",
            notWhitespaces: "Last name cannot be empty",
            nameCharacters: "Please enter a valid last name (letters, single spaces, hyphens, or apostrophes)."
          },
          registerEmail: {
            required: "Please enter your email address",
            email: "Please enter a valid email address"
          },
          homeAddress: {
            required: "Please provide your home address",
            notWhitespaces: "Home address cannot be empty"
          },
          registerContact: {
            required: "Please provide your contact number",
            pattern: "Only numbers and an optional '+' are allowed"
          },
          registerPassword: {
            required: "Please provide a password",
            minlength: "Password must be at least 6 characters",
            notWhitespaces: "Password cannot be empty"
          },
          confirmPassword: {
            required: "Please confirm your password",
            equalTo: "Passwords do not match",
            notWhitespaces: "Confirm password cannot be empty"
          },
          agreeTerms: {
              required: "You must agree to the terms and conditions"
          }
        },
        errorElement: 'div', // Use a div for error messages
        errorClass: 'error invalid-feedback', // Bootstrap's feedback class
        highlight: function(element, errorClass, validClass) {
            // Using the centralized function
            applyBootstrapInvalid(element, true);
        },
        unhighlight: function(element, errorClass, validClass) {
            // Using the centralized function
            applyBootstrapInvalid(element, false);
        },
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
              error.insertAfter(element.parent()); 
            } else if (element.attr("name") === "agreeTerms") { 
                // Insert the error after the parent .form-check div to keep it below the checkbox and label
                error.insertAfter(element.closest('.form-check')); 
            } else {
              error.insertAfter(element);
          }
        },
        submitHandler: function(form) {
            
            const registerForm = form; // Use the form element passed by validation
            const formData = new FormData(registerForm);
            
            // 1. Perform AJAX Request to store data
            fetch('store_data.php', { 
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                
                if (data.trim() === 'success') { // Assuming store_data.php echoes 'success' on good save

                    // 2. Success: Hide registration modal
                    var registerModal = bootstrap.Modal.getInstance(document.getElementById("registerModal"));
                    registerModal.hide();

                    // 3. Show success modal after a short delay
                    const successModal = new bootstrap.Modal(document.getElementById("registrationSuccessModal"));
                    setTimeout(() => {
                      successModal.show();
                    }, 400); 

                    // 4. Cleanup
                    registerForm.reset(); 
                    $('#registerForm').find('.is-invalid').removeClass('is-invalid');
                    $('#registerForm').find('.error').remove();
                
                } else {
                    // Handle PHP/Database failure response here (e.g., email already exists)
                    alert("Registration failed! Server response: " + data);
                }
            })
            .catch(error => {
                console.error('Network Error:', error);
                alert("An error occurred during registration. Check console for details.");
            });
            
            return false;
        }
      });
      
    });
    </script>
    
</body>
</html>