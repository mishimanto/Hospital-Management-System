<?php
session_start();
include("include/config.php");

// Initialize session errmsg if not set
if (!isset($_SESSION['errmsg'])) {
    $_SESSION['errmsg'] = "";
}

// Code for updating Password
if (isset($_POST['change'])) {
    $name = $_SESSION['name'] ?? '';
    $email = $_SESSION['email'] ?? '';

    // Check if session values exist before proceeding
    if ($name && $email) {
        $newpassword = md5($_POST['password']);
        $query = mysqli_query($con, "UPDATE users SET password='$newpassword' WHERE fullName='$name' AND email='$email'");

        if ($query) {
            echo "<script>alert('Password successfully updated.');</script>";
            echo "<script>window.location.href ='user-login.php'</script>";
            exit();
        } else {
            $_SESSION['errmsg'] = "Failed to update password. Please try again.";
        }
    } else {
        $_SESSION['errmsg'] = "Session expired or invalid request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Password Reset | MEDIZEN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/png">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --border-radius: 8px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        * {
            margin: 0; 
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f5f7fa;
            color: var(--dark-color);
            line-height: 1.6;
        }
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .login-card {
            background: #e0ecff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            transition: var(--transition);
        }
        .login-card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 24px;
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            color: var(--dark-color);
        }
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
            transition: var(--transition);
        }
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: var(--transition);
            width: 100%;
        }

        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .additional-links {
            margin-top: 20px;
            text-align: center;
        }
        .additional-links a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
        }

        .additional-links a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        .copyright {
            margin-top: 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }
        .error-message {
            color: var(--accent-color);
            background: #fadbd8;
            padding: 12px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            text-align: center;
        }
        .input-icon {
            position: relative;
            display: block;
        }
        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a5a6;
        }
    </style>

    <script type="text/javascript">
        function valid() {
            var pass = document.getElementById('password').value;
            var pass_again = document.getElementById('password_again').value;
            if (pass !== pass_again) {
                alert("Password and Confirm Password fields do not match!");
                document.getElementById('password_again').focus();
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
<div class="login-container">
    <div class="login-card">
        <div class="logo">
            <a href="../index.php">MEDIZEN</a>
        </div>
        <div class="form-title">Reset Your Password</div>

        <?php if (!empty($_SESSION['errmsg'])): ?>
            <div class="error-message"><?php echo $_SESSION['errmsg']; $_SESSION['errmsg']=""; ?></div>
        <?php endif; ?>

        <form method="post" name="passwordreset" onsubmit="return valid();">
            <div class="form-group input-icon">
                <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="form-group input-icon">
                <input type="password" class="form-control" id="password_again" name="password_again" placeholder="Confirm Password" required>
                <i class="fas fa-lock"></i>
            </div>

            <button type="submit" name="change" class="btn">Change Password</button>

            <div class="additional-links">
                Already have an account? <a href="user-login.php">Log in</a>
            </div>
        </form>

        <div class="copyright">
            &copy; <span class="text-bold">MEDIZEN</span>
        </div>
    </div>
</div>
</body>
</html>
