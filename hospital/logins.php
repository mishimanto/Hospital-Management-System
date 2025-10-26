<?php
include_once('hms/include/config.php');

if (isset($_POST['submit'])) {
    $name       = mysqli_real_escape_string($con, $_POST['fullname']);
    $email      = mysqli_real_escape_string($con, $_POST['emailid']);
    $mobileno   = mysqli_real_escape_string($con, $_POST['mobileno']);
    $dscrption  = mysqli_real_escape_string($con, $_POST['description']);

    $query = mysqli_query($con, "INSERT INTO tblcontactus(fullname, email, contactno, message) VALUES('$name', '$email', '$mobileno', '$dscrption')");

    if ($query) {
        echo "<script>alert('Your information was successfully submitted'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error submitting information. Please try again.');</script>";
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logins</title>

    <link rel="icon" href="assets/images/logo_no_bg.png?v=1.1" type="image/jpeg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawsom-all.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(90deg, #00b894, #00cec9);
        }

        .blog-single {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: #fff;
        }

        .blog-single img {
            transition: transform 0.3s ease;
        }

        .blog-single-det {
            background: #fff;
        }

        .blog-single:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            transform: translateY(-5px);
        }

        .blog-single:hover img {
            transform: scale(1.03);
        }

        .blog-single button {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            border-radius: 50px;
            padding: 12px 30px;
            transition: all 0.3s ease;
        }

        .blog-single:hover button {
            transform: scale(1.05);
            background-color: linear-gradient(90deg, #00b894, #00cec9);
        }

        

        @media (prefers-color-scheme: dark) {
            body {
                background: linear-gradient(90deg, #00b894, #00cec9);
                color: #e0e0e0;
            }
            .blog-single {
                background: #2c2c3e;
                box-shadow: 0 8px 20px rgba(0,0,0,0.5);
            }
            .blog-single-det {
                background: #1e1e2f;
            }
        }
        .blog-single button {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            border-radius: 50px;
            padding: 12px 30px;
            transition: all 0.4s ease;
            border: none;
            background-color: #2c2c3e;
            color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .blog-single button:hover {
            transform: translateY(-3px) scale(1.05);
            background-color: #00b894;
            color: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
        }

        .blog-single button:active {
            transform: translateY(0) scale(1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

    </style>
</head>

<body>

<!-- Header -->
<?php // include_once 'hms/include/header_logins_page.php'; ?>

<!-- Logins Section -->
<section id="logins" class="our-blog container-fluid d-flex justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="container">
        <div class="row g-4 justify-content-center">

            <!-- Patient Login -->
            <div class="col-md-4">
                <div class="blog-single">
                    <img src="assets/images/patient.jpg" alt="Patient" class="img-fluid">
                    <div class="blog-single-det text-center p-4">
                        <a href="hms/user-login.php" target="_blank">
                            <button class="btn btn-success px-4 py-2">User Login</button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Doctor Login -->
            <!-- <div class="col-md-4">
                <div class="blog-single">
                    <img src="assets/images/doctor.jpg" alt="Doctor" class="img-fluid">
                    <div class="blog-single-det text-center p-4">
                        <a href="hms/doctor" target="_blank">
                            <button class="btn btn-success px-4 py-2">Doctor Login</button>
                        </a>
                    </div>
                </div>
            </div> -->

            <!-- Admin Login -->
            <!-- <div class="col-md-4">
                <div class="blog-single">
                    <img src="assets/images/admin.jpg" alt="Admin" class="img-fluid">
                    <div class="blog-single-det text-center p-4">
                        <a href="hms/admin" target="_blank">
                            <button class="btn  px-4 py-2">Admin Login</button>
                        </a>
                    </div>
                </div>
            </div> -->

        </div>
    </div>
</section>

<!-- Footer -->
<?php // include_once 'hms/include/footer.php'; ?>

<footer class="text-muted" style="text-align: center; margin-top: 0; margin-bottom: 20px;"><small>&copy; All right reserved by MEDIZEN</small></footer>

<!-- Bootstrap & Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/plugins/scroll-nav/js/jquery.easing.min.js"></script>
<script src="assets/plugins/scroll-nav/js/scrolling-nav.js"></script>
<script src="assets/plugins/scroll-fixed/jquery-scrolltofixed-min.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>
