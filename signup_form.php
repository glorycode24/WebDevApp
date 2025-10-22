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

<p id="message"></p>