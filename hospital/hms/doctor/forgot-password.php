<?php
session_start();
error_reporting(0);
include("include/config.php");

if (isset($_POST['submit'])) {
    $contactno = mysqli_real_escape_string($con, $_POST['contactno']);
    $email     = mysqli_real_escape_string($con, $_POST['email']);

    // 1. Check regular doctors
    $query1 = mysqli_query($con, "SELECT id FROM doctors WHERE contactno='$contactno' AND docEmail='$email'");
    if (mysqli_num_rows($query1) > 0) {
        $_SESSION['cnumber'] = $contactno;
        $_SESSION['email']   = $email;
        $_SESSION['usertype'] = 'doctor'; // add this flag
        header('location:reset-password.php');
        exit();
    }

    // 2. Check emergency doctors
    $query2 = mysqli_query($con, "SELECT id FROM em_doctors WHERE contact='$contactno' AND email='$email'");
    if (mysqli_num_rows($query2) > 0) {
        $_SESSION['cnumber'] = $contactno;
        $_SESSION['email']   = $email;
        $_SESSION['usertype'] = 'emergency_doctor'; // add this flag
        header('location:reset-password.php');
        exit();
    }

    $error = "Invalid details. Please try with valid information.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor Password Recovery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/png">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --border-radius: 8px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: var(--dark-color);
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo h2 {
            color: var(--primary-color);
            font-weight: 700;
        }
        .logo a {
		    text-decoration: none;
		    color: inherit;
		}

        .form-title {
            text-align: center;
            margin-bottom: 10px;
            font-size: 1.5rem;
            color: var(--dark-color);
        }

        .form-description {
            text-align: center;
            color: #7f8c8d;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 16px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
            outline: none;
        }

        .btn {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
        }

        .btn i {
            margin-left: 8px;
        }

        .additional-links {
            margin-top: 20px;
            text-align: center;
        }

        .additional-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .additional-links a:hover {
            color: var(--secondary-color);
        }

        .error-message {
            color: var(--accent-color);
            background-color: #fadbd8;
            padding: 10px 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            text-align: center;
            display: <?php echo isset($error) ? 'block' : 'none'; ?>;
        }

        .copyright {
            margin-top: 30px;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        <div class="logo">
            <a href="../../index.php"><h2>Recover your account</h2></a>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <!-- <div class="form-title">Recover Your Account</div>
            <div class="form-description">Please enter your registered contact number and email.</div> -->

            <div class="form-group">
                <input type="text" class="form-control" name="contactno" placeholder="Registered Contact Number" required>
            </div>

            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Registered Email" required>
            </div>

            <button type="submit" class="btn" name="submit">
                Reset Password <i class="fas fa-arrow-right"></i>
            </button>

            <div class="additional-links">
                Already have an account? <a href="index.php">Log in</a>
            </div>
        </form>

        <div class="copyright">
            &copy; <span class="text-bold">MEDIZEN</span>
        </div>
    </div>
</div>

</body>
</html>
