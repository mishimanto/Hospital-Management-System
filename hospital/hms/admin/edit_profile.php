<?php
session_start();
//error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit();
}

// Fetch admin details
$admin_id = $_SESSION['id'];
$query = mysqli_query($con, "SELECT * FROM admin WHERE id = '$admin_id'");
$admin = mysqli_fetch_assoc($query);

// Handle form submission
if(isset($_POST['update_profile'])) {
    $username = $_POST['username'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    if($current_password != $admin['password']) {
        $_SESSION['error'] = "Current password is incorrect";
    } 
    // Check if new password matches confirm password
    elseif($new_password != $confirm_password) {
        $_SESSION['error'] = "New password and confirm password don't match";
    } 
    // Update profile
    else {
        $update_password = !empty($new_password) ? md5($new_password) : $admin['password'];
        
        $update_query = "UPDATE admin SET 
                        username = '".mysqli_real_escape_string($con, $username)."', 
                        password = '".mysqli_real_escape_string($con, $update_password)."', 
                        updationDate = NOW() 
                        WHERE id = '$admin_id'";
        
        if(mysqli_query($con, $update_query)) {
            $_SESSION['success'] = "Profile updated successfully";
            // Refresh admin data
            $query = mysqli_query($con, "SELECT * FROM admin WHERE id = '$admin_id'");
            $admin = mysqli_fetch_assoc($query);
        } else {
            $_SESSION['error'] = "Error updating profile: ".mysqli_error($con);
        }
    }
    
    header("Location: edit_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Profile</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>
<body>
<div id="app">
    <?php include('include/sidebar.php'); ?>
    <div class="app-content">
        <?php include('include/header.php'); ?>
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Admin | Edit Profile</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Edit Profile</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row margin-top-30">
                        <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Update Profile Information</h5>
                                </div>
                                <div class="panel-body">
                                    <?php if(isset($_SESSION['error'])) { ?>
                                        <div class="alert alert-danger">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <i class="fa fa-times-circle"></i> <?php echo $_SESSION['error']; ?>
                                        </div>
                                        <?php unset($_SESSION['error']); ?>
                                    <?php } ?>
                                    
                                    <?php if(isset($_SESSION['success'])) { ?>
                                        <div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                            <i class="fa fa-check-circle"></i> <?php echo $_SESSION['success']; ?>
                                        </div>
                                        <?php unset($_SESSION['success']); ?>
                                    <?php } ?>
                                    
                                    <form method="post">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Current Password</label>
                                            <input type="password" class="form-control" name="current_password" required>
                                            <small class="text-muted">You must enter your current password to make changes</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" class="form-control" name="new_password">
                                            <small class="text-muted">Leave blank if you don't want to change password</small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Confirm New Password</label>
                                            <input type="password" class="form-control" name="confirm_password">
                                        </div>
                                        
                                        <div class="text-end">
                                            <button type="submit" name="update_profile" class="btn btn-o btn-primary">
                                                <i class="fa fa-save"></i> Update Profile
                                            </button>
                                            <a href="dashboard.php" class="btn btn-o btn-danger">
                                                <i class="fa fa-times"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Profile Information</h5>
                                </div>
                                <div class="panel-body">
                                    <div class="text-center">
                                        <div class="user-profile-pic">
                                            <img src="assets/images/images.jpg" width="120" class="img" alt="Admin">
                                        </div>
                                        <h4 class="margin-top-20"><?php echo htmlspecialchars($admin['username']); ?></h4>
                                        <p class="text-muted">Administrator</p>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="profile-info">
                                        <h5>Account Details</h5>
                                        <ul class="list-unstyled">
                                            <li>
                                                <i class="fa fa-user margin-right-10"></i>
                                                <span class="text-muted">Username:</span> <?php echo htmlspecialchars($admin['username']); ?>
                                            </li>
                                            <li>
                                                <i class="fa fa-calendar margin-right-10"></i>
                                                <span class="text-muted">Last Updated:</span> <?php echo htmlspecialchars($admin['updationDate']); ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
    </div>
    <?php include('include/footer.php'); ?>
        <?php include('include/setting.php'); ?>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>
<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="vendor/autosize/autosize.min.js"></script>
<script src="vendor/selectFx/classie.js"></script>
<script src="vendor/selectFx/selectFx.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/form-elements.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
    });
</script>
</body>
</html>