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
  <title>Shine - Bootstrap</title>

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
                    <li class="nav-item"><a class="nav-link active" href="bootstrap.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="Products.php">Products</a></li>
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

  <!-- HERO -->
  <header class="text-center text-white py-5 bg-dark" style="background:url('https://picsum.photos/1200/400?business') center/cover no-repeat;">
    <div class="container">
      <h1 class="display-4">Welcome to Shine</h1>
      <p class="lead">Discover premium products for your business and lifestyle</p>
    </div>
  </header>

  <!-- PRODUCTS -->
  <main class="container my-5">
    <h2 class="text-center mb-4">Featured Products</h2>
    <p class="text-center lead">Our selection of popular and newly added items.</p>

    <div id="featured-product-list" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <div id="loading-indicator-home" class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading featured products...</span>
            </div>
            <p class="mt-2">Loading featured products...</p>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="Products.php" class="btn btn-outline-primary btn-lg">View All Products</a>
    </div>
  </main>

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

    $(document).ready(function () {
      
      function updateCartCount(newCount) {
        // Find the badge based on the cart icon's sibling and update its text
        const cartBadge = document.querySelector('.navbar .fa-shopping-cart').nextElementSibling;
        if (cartBadge) {
            cartBadge.textContent = newCount;
        }
    }
      
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

        const productList = $('#featured-product-list'); // Changed ID for homepage

        function fetchAndRenderFeaturedProducts() {
            // Show loading indicator
            $('#loading-indicator-home').show();
            productList.empty(); // Clear existing content

        
            $.ajax({
                url: 'product_store_api.php?limit=4', // Fetch only 4 featured products
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#loading-indicator-home').hide();
                    if (response.products && response.products.length > 0) {
                        response.products.forEach(product => {
                            // Determine button state based on stock (API only returns in-stock)
                            const buttonText = '<i class="fa fa-shopping-cart me-2"></i>Add to Cart';
                            const buttonClass = 'btn-primary add-to-cart-btn'; // Use existing class for the handler

                            const productCard = `
                                <div class="col">
                                    <div class="card h-100 shadow-sm">
                                        <img src="${product.image_url}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">${product.name}</h5>
                                            <p class="card-text text-muted">${product.description ? product.description.substring(0, 50) + '...' : ''}</p>
                                            <div class="mt-auto">
                                                <p class="card-text fs-4 fw-bold text-primary">$${parseFloat(product.price).toFixed(2)}</p>
                                                <button class="btn ${buttonClass} w-100" data-product-id="${product.product_id}"> 
                                                    ${buttonText}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            productList.append(productCard);
                        });
                    } else {
                        productList.html('<div class="col-12 text-center"><p class="lead text-warning">No featured products available yet.</p></div>');
                    }
                },
                error: function() {
                    $('#loading-indicator-home').hide();
                    productList.html('<div class="col-12 text-center"><p class="lead text-danger">Error loading featured products.</p></div>');
                }
            });
        }
        
        // Load featured products when the page is ready
        fetchAndRenderFeaturedProducts();

        $(document).on('click', '.add-to-cart-btn', function() {
        // Get the product ID from the button's data attribute
        var productId = $(this).data('product-id');
        
        // You might want to get quantity from an input field if you have one, 
        // but we default to 1 for a simple "Add to Cart" button.
        var quantity = 1; 

        if (!productId) {
            alert("Error: Missing product information.");
            return;
        }

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
                    updateCartCount(response.total_items);
                    // TODO: Update the cart count icon in the header here!
                    // updateCartCount(response.total_items); 
                } else {
                    alert("Cart Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                // If the user isn't logged in, the server returns 401
                if (xhr.status === 401) {
                    alert("Error: " + xhr.responseJSON.message);
                } else {
                    alert("An unknown error occurred while adding to cart.");
                    console.error("AJAX Error:", error);
                }
            },
            complete: function() {
                // Re-enable button
                $button.prop('disabled', false).text('Add to Cart');
            }
        });
    });
      
    });
    </script>
    
</body>
</html>