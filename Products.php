<?php
include_once 'includes/utils.php';

// --- CRITICAL: Start the session at the very beginning ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_user_name = $_SESSION['user_name'] ?? 'Guest';
$cart_item_count = get_cart_item_count(); // Calculate the count for display


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - Shine</title>
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
                    <li class="nav-item"><a class="nav-link active" href="Products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="About.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="Contact.php">Contact</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <!-- CART LINK WITH DYNAMIC COUNT -->
                    <li class="nav-item">
  <a class="nav-link position-relative" href="Cart.php">
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

  <!-- PRODUCTS HEADER -->
  <header class="text-center text-white py-5 bg-dark" style="background:url('https://picsum.photos/1200/400?products') center/cover no-repeat;">
    <div class="container">
      <h1 class="display-4">Our Products</h1>
      <p class="lead">Browse our selection of premium items</p>
    </div>
  </header>

  <!-- PRODUCTS LIST -->
  <section class="py-5">
    <div class="container">
      <h2 class="text-center mb-4">Shop All Products</h2>
      <!-- PRODUCT GRID - Dynamically populated by JavaScript -->
      <div id="product-list" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <div id="loading-indicator-home" class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading featured products...</span>
            </div>
            <p class="mt-2">Loading featured products...</p>
        </div>
    </div>
      
    </div>
  </section>

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
    function displayMessage(message, type = 'success') {
        const container = $('#statusMessageContainer');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-times-circle';

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fa ${iconClass} me-2"></i>
                <div>${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        const $alert = $(alertHtml);
        container.append($alert);

        // Auto-close after 5 seconds
        setTimeout(() => {
            $alert.alert('close');
        }, 5000);
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

    const productListContainer = $('#product-list');

        // --- NEW: Function to fetch and render products via AJAX ---
        function fetchAndRenderProducts() {
            // NOTE: This call automatically gets ALL products because no 'limit' is passed.
            $.ajax({
                url: 'product_store_api.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // *** FIX: Removed the undefined $loadingIndicator. We clear the container instead. ***
                    productListContainer.empty(); 

                    if (response.success && response.products.length > 0) {
                        // Loop through products and create HTML
                        response.products.forEach(product => {
                            // Ensure price is a valid number, defaulting to 0.00
                            const price = parseFloat(product.price) || 0.00;
                            
                            const productCard = `
                                <div class="col">
                                    <div class="card h-100 shadow-sm border-0">
                                        <img src="${product.image_url || 'https://placehold.co/400x200?text=Product+Image'}" 
                                             class="card-img-top product-card-img" 
                                             alt="${product.name}" 
                                             onerror="this.onerror=null;this.src='https://placehold.co/400x200?text=Image+Missing';">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">${product.name}</h5>
                                            <p class="card-text text-muted flex-grow-1">${product.description.substring(0, 70) + (product.description.length > 70 ? '...' : '')}</p>
                                            <p class="card-text fs-4 fw-bold mb-3">$${price.toFixed(2)}</p>
                                            <button 
                                                class="btn btn-primary add-to-cart-btn mt-auto" 
                                                data-product-id="${product.product_id}"
                                            >
                                                <i class="fas fa-cart-plus"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            productListContainer.append(productCard);
                        });
                    } else {
                        // Handle case where API works but returns no products
                        productListContainer.append(`
                            <div class="col-12">
                                <div class="alert alert-warning text-center" role="alert">
                                    <h4 class="alert-heading">No Products Available</h4>
                                    <p class="mb-0">The store currently has no products listed.</p>
                                </div>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle API failure (e.g., database connection error in product_store_api.php)
                    productListContainer.empty().append(`
                        <div class="col-12">
                            <div class="alert alert-danger text-center" role="alert">
                                <h4 class="alert-heading">Failed to Load Products!</h4>
                                <p>Could not connect to the product data source.</p>
                                <p class="mb-0"><small>Error: ${xhr.status} ${status}</small></p>
                            </div>
                        </div>
                    `);
                    console.error("Product Fetch Error:", error, xhr.responseText);
                }
            });
        }
        
        // Load products when the page is ready
        fetchAndRenderProducts();

    // === ADD TO CART BUTTON HANDLER ===
      
      // --- ADD TO CART AJAX LOGIC ---
        // Attaches a click handler to all buttons with the class 'add-to-cart-btn'
        $('.add-to-cart-btn').on('click', function() {
            // Retrieve the product ID from the data attribute
            var productId = $(this).data('product-id');
            // Default quantity is 1 for a single click
            var quantity = 1; 

            // Disable button and show loading state
            var $button = $(this);
            $button.prop('disabled', true).text('Adding...');

            $.ajax({
                url: 'cart_action.php?action=add',
                type: 'POST',
                dataType: 'json',
                data: { 
                    product_id: productId, 
                    quantity: quantity 
                },
                success: function(response) {
                    // Use a custom modal or message box instead of alert() in production
                    if (response.success) {
                        alert("Success! " + response.message);
                        
                        // TODO: Update the cart count icon in the header here!
                        updateCartCount(response.total_items); 
                    } else {
                        alert("Cart Error: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // If the user isn't logged in, the server returns 401
                    if (xhr.status === 401) {
                        // NOTE: If the PHP handler returns JSON error, this will be available in xhr.responseJSON
                        alert("Error: You must be logged in to add items to the cart.");
                    } else {
                        alert("An unknown error occurred while adding to cart.");
                        console.error("AJAX Error:", error);
                    }
                },
                complete: function() {
                    // Re-enable button
                    $button.prop('disabled', false).html('<i class="fas fa-cart-plus"></i> Add to Cart');
                }
            });
        });

    });
    </script>
    
</body>
</html>