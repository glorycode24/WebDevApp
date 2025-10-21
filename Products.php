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
      <a class="navbar-brand" href="bootstrap.html">Shine</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="bootstrap.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="Products.php">Products</a></li>
          <li class="nav-item"><a class="nav-link" href="About.php">About</a></li>
          <li class="nav-item"><a class="nav-link active" href="Contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="Cart.php">🛒 Cart</a></li>
          <li class="nav-item">
            <button class="btn btn-outline-light ms-2" data-bs-toggle="modal" data-bs-target="#loginModal">
              <i class="fas fa-sign-in-alt me-1"></i>Log In
            </button>
          </li>
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
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card h-100">
            <img src="https://picsum.photos/300/200?product1" class="card-img-top" alt="Elegant Chair">
            <div class="card-body">
              <h5 class="card-title">Elegant Chair</h5>
              <p class="card-text">Comfortable and stylish for any space.</p>
              <p class="card-text fw-bold">$99.00</p>
              <a href="#" class="btn btn-primary w-100">Add to Cart</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <img src="https://picsum.photos/300/200?product2" class="card-img-top" alt="Smart Lamp">
            <div class="card-body">
              <h5 class="card-title">Smart Lamp</h5>
              <p class="card-text">Modern lighting with smart features.</p>
              <p class="card-text fw-bold">$49.00</p>
              <a href="#" class="btn btn-primary w-100">Add to Cart</a>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card h-100">
            <img src="https://picsum.photos/300/200?product3" class="card-img-top" alt="Modern Desk">
            <div class="card-body">
              <h5 class="card-title">Modern Desk</h5>
              <p class="card-text">Spacious and sleek for productivity.</p>
              <p class="card-text fw-bold">$199.00</p>
              <a href="#" class="btn btn-primary w-100">Add to Cart</a>
            </div>
          </div>
        </div>
        <!-- Add more products as needed -->
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
          <form id="loginForm">
            <div class="mb-3">
              <label for="loginEmail" class="form-label">Email address</label>
              <input type="email" class="form-control" id="loginEmail" name="loginEmail" required> 
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="loginPassword" name="loginPassword" required> 
                <button class="btn btn-outline-secondary" type="button" id="toggleLoginPassword">
                  <i class="fas fa-eye" id="loginPasswordIcon"></i>
                </button>
              </div>
            </div>
          
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe"> 
              <label class="form-check-label" for="rememberMe">
                Remember me
              </label>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Log In</button>
            </div>
            <div class="text-center mt-3">
              <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
            </div>
          </form>
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
          <form id="registerForm">
            <div class="mb-3"> 
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required> 
            </div>
            <div class="mb-3"> 
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required> 
            </div>
            <div class="mb-3">
              <label for="registerEmail" class="form-label">Email address</label>
              <input type="email" class="form-control" id="registerEmail" name="registerEmail" required> 
            </div>
            <div class="mb-3">
              <label for="homeAddress" class="form-label">Home Address</label>
              <textarea class="form-control" id="homeAddress" name="homeAddress" rows="3" required></textarea> 
            </div>
            <div class="mb-3">
              <label for="registerContact" class="form-label">Contact Number</label>
              <input 
                type="tel" 
                class="form-control" 
                id="registerContact" 
                name="registerContact"
                required
                > 
            </div>
            <div class="mb-3">
              <label for="registerPassword" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="registerPassword" name="registerPassword" required> 
                <button class="btn btn-outline-secondary" type="button" id="toggleRegisterPassword">
                  <i class="fas fa-eye" id="registerPasswordIcon"></i>
                </button>
              </div>
            </div>
           
            <div class="mb-3">
              <label for="confirmPassword" class="form-label">Confirm Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required> 
                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                  <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                </button>
              </div>
            </div>
           
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="agreeTerms" name="agreeTerms" required> 
              <label class="form-check-label" for="agreeTerms">
                I agree to the <a href="#" class="text-decoration-none">Terms and Conditions</a>
              </label>
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-primary">Sign Up</button>
            </div>
          </form>
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
      
      // Define a custom method to ensure a field contains only letters,
      // and optionally a single space, hyphen, or apostrophe between words.
      // It does not allow leading/trailing special characters or multiple consecutive spaces.
      $.validator.addMethod("nameCharacters", function(value, element) {
          // This regex allows:
          // - Starts with a letter: ^[a-zA-Z]
          // - Followed by zero or more letters, spaces, hyphens, or apostrophes: [a-zA-Z\s'-]*
          // - Ends with a letter: [a-zA-Z]$
          // - Prevents consecutive spaces or special characters at start/end by requiring a letter at start/end
          // - We'll also specifically check for consecutive spaces or multiple special chars
          if (this.optional(element)) {
              return true;
          }
          
          // Pattern for valid characters and structure (e.g., "John Doe", "Mary-Ann O'Connell")
          // Ensures it starts and ends with a letter, and internal characters are letters, single spaces, hyphens, or apostrophes
          const nameRegex = /^[a-zA-Z]+(?:[\s'-][a-zA-Z]+)*$/;
          
          // Check for consecutive spaces or leading/trailing special chars more explicitly if needed,
          // but the nameRegex above largely covers it.
          // Example: "John  Doe" (two spaces) or " John" or "John-" would fail nameRegex.

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
            $inputGroup.toggleClass('is-invalid', isInvalid);
        } 
        // Handle checkboxes/radios within a form-check
        else if ($formCheck.length && ($element.attr('type') === 'checkbox' || $element.attr('type') === 'radio')) {
            $formCheck.toggleClass('is-invalid', isInvalid);
        }
      };

      $("#loginForm").validate({
        rules: {
          loginEmail: {
            required: true, 
            email: true
          },
          loginPassword: {
            required: true,
            minlength: 6,
            notWhitespaces: true 
          }
        },
        messages: {
          loginEmail: {
            required: "Please enter your email address",
            email: "Please enter a valid email address"
          },
          loginPassword: {
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
          console.log("Login form submitted successfully!");
          var loginModal = bootstrap.Modal.getInstance(document.getElementById("loginModal"));
          loginModal.hide();
          form.reset(); 
          // After successful submission, remove any remaining invalid highlights
          $('#loginForm').find('.is-invalid').removeClass('is-invalid');
          $('#loginForm').find('.error').remove(); // Also remove error messages
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
          registerEmail: {
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
          registerPassword: {
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
            $(element).addClass('is-invalid');
            // For input-group, add the class to the .input-group div
            if ($(element).parent('.input-group').length) {
                $(element).parent().addClass('is-invalid');
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
            // For input-group, remove the class from the .input-group div
            if ($(element).parent('.input-group').length) {
                $(element).parent().removeClass('is-invalid');
            }
        },
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
              error.insertAfter(element.parent()); 
            } else if (element.attr("type") === "checkbox") { 
                error.insertAfter(element.next('label'));
            } else {
              error.insertAfter(element);
          }
        },
        submitHandler: function(form) {
          var registerModal = bootstrap.Modal.getInstance(document.getElementById("registerModal"));
          registerModal.hide();

          const successModal = new bootstrap.Modal(document.getElementById("registrationSuccessModal"));
          setTimeout(() => {
            successModal.show();
          }, 400); 

          form.reset(); 
          // After successful submission, remove any remaining invalid highlights
          $('#registerForm').find('.is-invalid').removeClass('is-invalid');
          $('#registerForm').find('.error').remove(); // Also remove error messages
        }
      });
      
      $('input[required], textarea[required], input[type="checkbox"][required]').on('blur', function() {
        if (!this.checkValidity()) {
          $(this).addClass('is-invalid');
        } else {
          $(this).removeClass('is-invalid');
        }
      });

      $('input[required], textarea[required], input[type="checkbox"][required]').on('input change', function() {
        if (this.checkValidity()) {
          $(this).removeClass('is-invalid');
        }
      });
      
    });
    </script>
    

</body>
</html>