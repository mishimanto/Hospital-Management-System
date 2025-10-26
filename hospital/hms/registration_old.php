<?php
// Include configuration file
require_once('include/config.php');

// Process form submission
if(isset($_POST['submit'])) {
    // Validate and sanitize input data
    $fname = mysqli_real_escape_string($con, trim($_POST['full_name']));
    $address = mysqli_real_escape_string($con, trim($_POST['address']));
    $city = mysqli_real_escape_string($con, trim($_POST['city']));
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
    } else {
        // Hash password using more secure method
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
        // Insert user into database using prepared statement
        $stmt = $con->prepare("INSERT INTO users(fullname, address, city, gender, email, password) VALUES(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fname, $address, $city, $gender, $email, $password);
        
        if($stmt->execute()) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful',
                        text: 'You can now login with your credentials',
                        showConfirmButton: true,
                        timer: 3000
                    }).then(() => {
                        window.location.href = 'user-login.php';
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                        text: 'Please try again or contact support',
                    });
                  </script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    
    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: green;
            --danger-color: red;
            --border-radius: 8px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
            color: var(--dark-color);
        }
        
        .registration-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            display: flex;
            min-height: 650px;
        }
        
        .registration-hero {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        .registration-hero::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .registration-hero::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .registration-hero h2 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        
        .registration-hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .registration-hero ul {
            list-style: none;
            padding: 0;
            position: relative;
            z-index: 1;
        }
        
        .registration-hero ul li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        
        .registration-hero ul li i {
            margin-right: 10px;
            color: var(--accent-color);
        }
        
        .registration-form {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            height: 50px;
            margin-bottom: 10px;
        }
        
        .logo h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.8rem;
            margin: 0;
        }
        
        .form-control {
            height: 50px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            padding-left: 15px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        .input-group-text {
            background-color: white;
            border-right: none;
            width: 50px;
        }
        .input-group-text i {
            margin: auto;
        }
        
        .form-floating label {
            padding-left: 45px;
        }
        
        .form-floating .form-control {
            padding-left: 45px;
        }
        
        .form-floating .bi {
            position: absolute;
            top: 16px;
            left: 16px;
            z-index: 10;
            color: var(--primary-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            height: 50px;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .gender-options {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .gender-option {
            flex: 1;
        }
        
        .gender-option input[type="radio"] {
            display: none;
        }
        
        .gender-option label {
            display: block;
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }
        
        .gender-option input[type="radio"]:checked + label {
            border-color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .password-strength {
            height: 4px;
            background: #eee;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            background: var(--danger-color);
            transition: width 0.3s ease;
        }
        
        .terms-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .terms-link:hover {
            text-decoration: underline;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .login-link a {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .registration-container {
                flex-direction: column;
            }
            
            .registration-hero {
                padding: 30px 20px;
            }
            
            .registration-form {
                padding: 30px 20px;
            }
        }
        .gender-active {
            background-color: var(--primary-color);
            color: #fff;
            border-color: var(--primary-color);
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="registration-container animate__animated animate__fadeIn">
            <!-- Hero Section -->
            <div class="registration-hero">
                <h2>Join Our Healthcare Community</h2>
                <p>Register now to access personalized healthcare services and manage your medical records online.</p>
                
                <ul>
                    <li><i class="fas fa-check-circle"></i> Easy appointment scheduling</li>
                    <li><i class="fas fa-check-circle"></i> Secure medical records access</li>
                    <li><i class="fas fa-check-circle"></i> 24/7 patient support</li>
                    <li><i class="fas fa-check-circle"></i> Prescription management</li>
                    <li><i class="fas fa-check-circle"></i> Health tracking tools</li>
                </ul>
                
                <!-- <div class="mt-auto">
                    <p>Already have an account? <a href="user-login.php" style="color: var(--accent-color); font-weight: 500;">Sign in here</a></p>
                </div> -->
            </div>
            
            <!-- Registration Form -->
            <div class="registration-form">
                <div class="logo">
                    <img src="logo_no_bg.png" alt="Hospital Logo">
                    <h1 class="mb-3">User Registration</h1>
                </div>
                
                <form name="registration" id="registration" method="post" onsubmit="return validateForm()">
                    <!-- Personal Information -->
                    <div class="mb-3">
                        <!-- <label for="full_name" class="form-label"></label> -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   placeholder="Full Name" required minlength="2" maxlength="50">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <!-- <label for="address" class="form-label">Address</label> -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" class="form-control" id="address" name="address" 
                                   placeholder="Address" required minlength="5" maxlength="100">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <!-- <label for="city" class="form-label">City</label> -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-city"></i></span>
                            <input type="text" class="form-control" id="city" name="city" 
                                   placeholder="City" required minlength="2" maxlength="50">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex gap-2">
                            <div class="flex-grow-1">
                                <input type="radio" id="male" name="gender" value="male" required class="d-none">
                                <label for="male" class="btn btn-outline-primary w-100 py-2">
                                    <i class="fas fa-male me-2"></i>Male
                                </label>
                            </div>
                            <div class="flex-grow-1">
                                <input type="radio" id="female" name="gender" value="female" class="d-none">
                                <label for="female" class="btn btn-outline-primary w-100 py-2">
                                    <i class="fas fa-female me-2"></i>Female
                                </label>
                            </div>
                            <div class="flex-grow-1">
                                <input type="radio" id="other" name="gender" value="other" class="d-none">
                                <label for="other" class="btn btn-outline-primary w-100 py-2">
                                    <i class="fas fa-genderless me-2"></i>Other
                                </label>
                            </div>
                        </div>
                    </div>

                    
                    <!-- Account Information -->
                    <div class="mb-3">
                        <!-- <label for="email" class="form-label">Email Address</label> -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Email Address" required onblur="checkEmailAvailability()">
                        </div>
                        <div id="email-availability" class="form-text"></div>
                    </div>
                    
                    <div class="mb-3">
                        <!-- <label for="password" class="form-label">Password</label> -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Password" required minlength="8"
                                   onkeyup="checkPasswordStrength(this.value)">
                        </div>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="password-strength-bar"></div>
                        </div>
                        <div id="password-strength-text" class="form-text"></div>
                    </div>
                    
                    <div class="mb-4">
                        <!-- <label for="confirm_password" class="form-label">Confirm Password</label> -->
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="password_again" placeholder="Confirm Password" required minlength="8">
                        </div>
                        <div id="password-match" class="form-text"></div>
                    </div>
                    
                    <!-- Terms and Conditions -->
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" class="terms-link">Terms of Service</a> and 
                            <a href="#" class="terms-link">Privacy Policy</a>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 py-2 mb-3" name="submit">
                        <i class="fas fa-user-plus me-2"></i> Create Account
                    </button>
                    
                    <div class="login-link">
                        Already have an account? <a href="user-login.php">Sign in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Complexity checks
            if (password.match(/[a-z]/)) strength += 1;
            if (password.match(/[A-Z]/)) strength += 1;
            if (password.match(/[0-9]/)) strength += 1;
            if (password.match(/[^a-zA-Z0-9]/)) strength += 1;
            
            // Update UI
            let width = (strength / 6) * 100;
            strengthBar.style.width = width + '%';
            
            // Color and text based on strength
            if (strength <= 2) {
                strengthBar.style.backgroundColor = 'var(--danger-color)';
                strengthText.textContent = 'Weak password';
                strengthText.style.color = 'var(--danger-color)';
            } else if (strength <= 4) {
                strengthBar.style.backgroundColor = 'orange';
                strengthText.textContent = 'Moderate password';
                strengthText.style.color = 'orange';
            } else {
                strengthBar.style.backgroundColor = 'var(--success-color)';
                strengthText.textContent = 'Strong password';
                strengthText.style.color = 'var(--success-color)';
            }
        }
        
        // Password match checker
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            const matchText = document.getElementById('password-match');
            
            if (confirmPassword.length > 0) {
                if (password === confirmPassword) {
                    matchText.textContent = 'Passwords match';
                    matchText.style.color = 'var(--success-color)';
                } else {
                    matchText.textContent = 'Passwords do not match';
                    matchText.style.color = 'var(--danger-color)';
                }
            } else {
                matchText.textContent = '';
            }
        });
        
        // Email availability check
        function checkEmailAvailability() {
            const email = document.getElementById('email').value;
            const availabilityText = document.getElementById('email-availability');
            
            if (email.length > 0) {
                // Simple email format validation
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    availabilityText.textContent = 'Invalid email format';
                    availabilityText.style.color = 'var(--danger-color)';
                    return;
                }
                
                // Show loading
                availabilityText.textContent = 'Checking availability...';
                availabilityText.style.color = 'var(--dark-color)';
                
                // AJAX request to check email availability
                fetch('check_availability.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'email=' + encodeURIComponent(email)
                })
                .then(response => response.text())
                .then(data => {
                    availabilityText.textContent = data;
                    if (data.includes('available')) {
                        availabilityText.style.color = 'var(--success-color)';
                    } else {
                        availabilityText.style.color = 'var(--danger-color)';
                    }
                })
                .catch(error => {
                    availabilityText.textContent = 'Error checking email availability';
                    availabilityText.style.color = 'var(--danger-color)';
                });
            } else {
                availabilityText.textContent = '';
            }
        }
        
        // Form validation
        function validateForm() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const termsChecked = document.getElementById('terms').checked;
            
            // Check password match
            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Please make sure your passwords match',
                });
                return false;
            }
            
            // Check password strength
            if (password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Weak Password',
                    text: 'Password must be at least 8 characters long',
                });
                return false;
            }
            
            // Check terms agreement
            if (!termsChecked) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terms Not Accepted',
                    text: 'You must accept the terms and conditions',
                });
                return false;
            }
            
            return true;
        }
        // Gender button active effect
document.querySelectorAll('input[name="gender"]').forEach(radio => {
    radio.addEventListener('change', function () {
        document.querySelectorAll('label[for="male"], label[for="female"], label[for="other"]').forEach(label => {
            label.classList.remove('gender-active');
        });
        const selectedLabel = document.querySelector('label[for="' + this.id + '"]');
        selectedLabel.classList.add('gender-active');
    });
});

    </script>
</body>
</html>