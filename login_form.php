<form id="loginForm">
            <div class="mb-3">
              <label for="loginEmail" class="form-label">Email address</label>
              <input type="email" class="form-control" id="loginEmail" name="email" required> 
            </div>
            <div class="mb-3">
              <label for="loginPassword" class="form-label">Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="loginPassword" name="password" required> 
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
            <p id="loginMessage" class="mt-3 text-center"></p>
            <div class="text-center mt-3">
              <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a>
            </div>
          </form>