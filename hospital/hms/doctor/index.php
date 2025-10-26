<?php
session_start();
include("include/config.php");
error_reporting(0);

if (isset($_POST['submit'])) {
    $uname     = $_POST['username'];
    $dpassword = md5($_POST['password']); // Consider using password_hash later

    // Check regular doctors table
    $ret = mysqli_query($con, "SELECT * FROM doctors WHERE docEmail='$uname' AND password='$dpassword'");
    $num = mysqli_fetch_array($ret);

    if ($num) {
        $_SESSION['dlogin'] = $uname;
        $_SESSION['id']     = $num['id'];
        $_SESSION['role']   = 'doctor';
        $uid                = $num['id'];
        $uip                = $_SERVER['REMOTE_ADDR'];
        $status             = 1;

        mysqli_query($con, "INSERT INTO doctorslog(uid, username, userip, status) 
                            VALUES('$uid', '$uname', '$uip', '$status')");

        header("location:dashboard.php");
        exit();
    }

    // Check emergency doctors table
    $ret2 = mysqli_query($con, "SELECT * FROM em_doctors WHERE email='$uname' AND password='$dpassword'");
    $em = mysqli_fetch_array($ret2);

    if ($em) {
        $_SESSION['dlogin'] = $uname;
        $_SESSION['id']     = $em['id'];
        $_SESSION['role']   = 'emergency_doctor';
        $_SESSION['name']   = $em['name'];
        $uid                = $em['id'];
        $uip                = $_SERVER['REMOTE_ADDR'];
        $status             = 1;

        mysqli_query($con, "INSERT INTO doctorslog(uid, username, userip, status) 
                            VALUES('$uid', '$uname', '$uip', '$status')");

        header("location:emergency_doctor/dashboard.php");
        exit();
    }

    // If login fails
    $uip    = $_SERVER['REMOTE_ADDR'];
    $status = 0;

    mysqli_query($con, "INSERT INTO doctorslog(username, userip, status) 
                        VALUES('$uname', '$uip', '$status')");

    echo "<script>alert('Invalid username or password');</script>";
    echo "<script>window.location.href='index.php'</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --accent-color: #4fc3f7;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --error-color: #dc3545;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 2rem;
            margin: 1rem;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo a {
            text-decoration: none;
        }

        .logo h2 {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 1.8rem;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-header h3 {
            color: var(--dark-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-icon {
            position: relative;
            display: block;
        }

        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.2);
            outline: none;
        }

        .forgot-password {
            display: block;
            text-align: right;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: var(--primary-color);
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
            font-weight: 500;
            transition: all 0.3s;
            width: 100%;
        }

        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .copyright {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
            color: #6c757d;
            font-size: 0.85rem;
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 1.5rem;
            }
            .logo h2 {
                font-size: 1.5rem;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.4);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 20px rgba(67, 97, 238, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
            }
        }

        .pulse {
            animation: pulse 2.5s infinite;
        }
    </style>
</head>
<body>
    <div class="login-container pulse">
        <div class="logo">
            <a href="../../index.php">
                <h2><i class="fas fa-heartbeat"></i> Doctor Portal</h2>
            </a>
        </div>

        <form class="login-form" method="post">
            <div class="form-header">
                <h3>Welcome Back</h3>
                <!-- <?php if (isset($_SESSION['errmsg']) && !empty($_SESSION['errmsg'])) : ?>
                    <div class="error-message"><?php echo $_SESSION['errmsg']; ?></div>
                    <?php $_SESSION['errmsg'] = ""; ?>
                <?php endif; ?> -->
            </div>

            <div class="form-group">
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your email" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <a href="forgot-password.php" class="forgot-password">Forgot password?</a>
            </div>

            <button type="submit" class="btn" name="submit">Login</button>
        </form>

        <div class="copyright">
            &copy; MEDIZEN
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loginContainer = document.querySelector('.login-container');
            loginContainer.style.opacity = '0';
            loginContainer.style.transform = 'translateY(20px)';

            setTimeout(() => {
                loginContainer.style.transition = 'all 0.4s ease-out';
                loginContainer.style.opacity = '1';
                loginContainer.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>
