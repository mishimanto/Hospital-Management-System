<?php
session_start();
require_once('include/config.php');

// Authentication check
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit();
}

// Check if ward ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid ward ID";
    header('location:wards.php');
    exit();
}

$ward_id = intval($_GET['id']);

// Fetch ward details
$ward_query = $con->query("SELECT * FROM wards WHERE ward_id = $ward_id");
if ($ward_query->num_rows == 0) {
    $_SESSION['error'] = "Ward not found";
    header('location:wards.php');
    exit();
}

$ward = $ward_query->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_ward'])) {
    $ward_name = $con->real_escape_string($_POST['ward_name']);
    $ward_type = $con->real_escape_string($_POST['ward_type']);
    $description = $con->real_escape_string($_POST['description']);

    $update_sql = "UPDATE wards SET 
                  ward_name = '$ward_name', 
                  ward_type = '$ward_type', 
                  description = '$description' 
                  WHERE ward_id = $ward_id";
                  
    if ($con->query($update_sql)) {
        $_SESSION['success'] = "Ward updated successfully";
        header('location:wards.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating ward: " . $con->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Ward</title>
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
                            <h1 class="mainTitle">Admin | Edit Ward</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li><a href="wards.php">Ward Management</a></li>
                            <li class="active"><span>Edit Ward</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Edit Ward Details</h5>
                                </div>
                                <div class="panel-body">
                                    <?php if(isset($_SESSION['error'])): ?>
                                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                                    <?php endif; ?>

                                    <form method="post">
                                        <div class="form-group">
                                            <label>Ward Name</label>
                                            <input type="text" name="ward_name" class="form-control" 
                                                value="<?php echo htmlspecialchars($ward['ward_name']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Ward Type</label>
                                            <select name="ward_type" class="form-control" required>
                                                <!-- <option value="">Select Ward Type</option> -->
                                                <?php 
                                                // Fetch distinct ward types from database
                                                $ward_types = $con->query("SELECT DISTINCT ward_type FROM wards ORDER BY ward_type");
                                                while($type = $ward_types->fetch_assoc()): 
                                                ?>
                                                    <option value="<?php echo htmlspecialchars($type['ward_type']); ?>" 
                                                        <?php echo (isset($ward['ward_type']) && $ward['ward_type'] == $type['ward_type']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($type['ward_type']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($ward['description']); ?></textarea>
                                        </div>
                                        
                                        <button type="submit" name="update_ward" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Update Ward
                                        </button>
                                        <a href="ward-management.php" class="btn btn-default">
                                            <i class="fa fa-arrow-left"></i> Back to Wards
                                        </a>
                                    </form>
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