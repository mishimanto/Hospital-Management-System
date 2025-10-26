<?php
session_start();
error_reporting(0);
include("include/config.php");

if (isset($_POST['submit'])) {
    $puname = mysqli_real_escape_string($con, $_POST['username']);
    $ppwd = md5($_POST['password']);

    $ret = mysqli_query($con, "SELECT * FROM users WHERE email='$puname' AND password='$ppwd'");
    $num = mysqli_fetch_array($ret);

    $uip = $_SERVER['REMOTE_ADDR'];

    if ($num > 0) {
        // Check if user is blocked
        if($num['status'] == 'blocked') {
            $_SESSION['login_error'] = "Your account has been blocked. Please contact admin.";
            $status = 0;
            mysqli_query($con, "INSERT INTO userlog(username, userip, status) VALUES ('$puname', '$uip', '$status')");
            header("location: user-login.php");
            exit;
        }
        
        $_SESSION['login'] = $puname;
        $_SESSION['id'] = $num['id'];
        $status = 1;
        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES ('{$num['id']}', '$puname', '$uip', '$status')");
        header("location: dashboard.php");
        exit;
    } else {
        $_SESSION['login'] = $puname;
        $status = 0;
        mysqli_query($con, "INSERT INTO userlog(username, userip, status) VALUES ('$puname', '$uip', '$status')");
        $_SESSION['login_error'] = "Invalid username or password.";
        header("location: user-login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --border-radius: 10px;
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

        .login-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            display: flex;
            min-height: 650px;
        }

        .login-hero {
            flex: 1;
            background: linear-gradient(90deg, #00b894, #00cec9);
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .login-hero::before,
        .login-hero::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .login-hero::before {
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
        }

        .login-hero::after {
            bottom: -80px;
            right: -80px;
            width: 300px;
            height: 300px;
        }

        .login-hero h2 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .login-hero p, .login-hero ul {
            position: relative;
            z-index: 1;
        }

        .login-hero ul {
            list-style: none;
            padding: 0;
        }

        .login-hero ul li {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .login-hero ul li i {
            margin-right: 10px;
            color: var(--accent-color);
        }

        .login-form-section {
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

        .input-group-text {
            background-color: white;
            border-right: none;
            width: 50px;
        }

        .input-group-text i {
            margin: auto;
        }

        .form-control {
            height: 50px;
            border: 1px solid #ddd;
            padding-left: 15px;
            border-radius: 0 var(--border-radius) var(--border-radius) 0;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
            border-color: var(--primary-color);
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

        .forgot-password {
            text-align: right;
            margin: 10px 0 20px;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
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

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                min-height: auto;
                margin-top: 50px;
                margin-bottom: 50px;
            }

            .login-hero {
                display: none;
            }

            .login-form-section {
                padding: 20px 20px;
                flex: unset;
                width: 100%;
                box-shadow: var(--box-shadow);
                border-radius: var(--border-radius);
                background: #fff;
            }

            .logo img {
                height: 40px;
                margin-bottom: 5px;
            }

            .logo h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
<?php if (isset($_SESSION['login_error'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?= $_SESSION['login_error'] ?>',
        timer: 5000,
        timerProgressBar: true,
        showConfirmButton: true
    });
</script>
<?php unset($_SESSION['login_error']); endif; ?>

<div class="container">
    <div class="login-container animate__animated animate__fadeIn">
        <div class="login-hero">
            <h2>Welcome Back to Our Healthcare Community</h2>
            <p>Login to access your personalized healthcare services and manage your medical records online.</p>
            <ul>
                <li><i class="fas fa-check-circle"></i> View your medical history</li>
                <li><i class="fas fa-check-circle"></i> Schedule appointments</li>
                <li><i class="fas fa-check-circle"></i> Access test results</li>
                <li><i class="fas fa-check-circle"></i> Message your doctors</li>
                <li><i class="fas fa-check-circle"></i> Manage prescriptions</li>
            </ul>
        </div>

        <div class="login-form-section">
            <div class="logo">
                <a href="../index.php"><img src="logo_no_bg.png" alt="Hospital Logo"></a> 
                <h1 class="mb-4">User Login</h1>
            </div>

            <form method="post">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="username" placeholder="Email Address" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                </div>

                <div class="forgot-password">
                    <a href="forgot-password.php">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mb-3" name="submit">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>

                <div class="login-link">
                    Don't have an account? <a href="registration.php">Create an account</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Client-side Email Validation -->
<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const email = document.querySelector('input[name="username"]').value;
    const password = document.querySelector('input[name="password"]').value;

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Invalid Email',
            text: 'Please enter a valid email address',
        });
        return false;
    }
    return true;
});
</script>
</body>
</html>