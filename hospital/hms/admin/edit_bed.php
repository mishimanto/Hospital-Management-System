<?php
session_start();
require_once('include/config.php');

// Authentication check
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

$bed_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch bed details
$bed = null;
if($bed_id > 0){
    $result = $con->query("SELECT * FROM beds WHERE bed_id = $bed_id");
    $bed = $result->fetch_assoc();
}

if(!$bed){
    $_SESSION['error'] = "Bed not found";
    header('location:beds.php');
    exit();
}

// Fetch wards for dropdown
$wards = $con->query("SELECT * FROM wards ORDER BY ward_name");

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_bed'])){
    $ward_id = intval($_POST['ward_id']);
    $bed_number = $con->real_escape_string($_POST['bed_number']);
    $bed_type = $con->real_escape_string($_POST['bed_type']);
    $price_per_day = floatval($_POST['price_per_day']);
    $status = $con->real_escape_string($_POST['status']);

    $sql = "UPDATE beds SET 
            ward_id = $ward_id,
            bed_number = '$bed_number',
            bed_type = '$bed_type',
            price_per_day = $price_per_day,
            status = '$status'
            WHERE bed_id = $bed_id";

    if($con->query($sql)){
        $_SESSION['success'] = "Bed updated successfully";
        header('location:beds.php');
        exit();
    } else {
        $_SESSION['error'] = "Error updating bed: " . $con->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Bed</title>
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
                            <h1 class="mainTitle">Admin | Edit Bed</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li><a href="beds.php">Bed Management</a></li>
                            <li class="active"><span>Edit Bed</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Edit Bed Details</h5>
                                </div>
                                <div class="panel-body">
                                    <?php if(isset($_SESSION['success'])): ?>
                                        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
                                    <?php endif; ?>
                                    <?php if(isset($_SESSION['error'])): ?>
                                        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                                    <?php endif; ?>

                                    <form method="post">
                                        <div class="form-group">
                                            <label>Ward</label>
                                            <select name="ward_id" class="form-control" required>
                                                <?php while($ward = $wards->fetch_assoc()): ?>
                                                    <option value="<?php echo $ward['ward_id']; ?>" <?php echo $ward['ward_id'] == $bed['ward_id'] ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($ward['ward_name']) ?> (<?php echo $ward['ward_type'] ?>)
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Bed Number</label>
                                            <input type="text" name="bed_number" class="form-control" value="<?php echo htmlspecialchars($bed['bed_number']); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Bed Type</label>
                                            <select name="bed_type" class="form-control" required>
                                                <?php 
                                                // Fetch distinct bed types from database
                                                $bed_types = $con->query("SELECT DISTINCT bed_type FROM beds ORDER BY bed_type");
                                                while($type = $bed_types->fetch_assoc()): 
                                                ?>
                                                    <option value="<?php echo htmlspecialchars($type['bed_type']); ?>" 
                                                        <?php echo ($bed['bed_type'] == $type['bed_type']) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($type['bed_type']); ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Price Per Day (BDT)</label>
                                            <input type="number" name="price_per_day" class="form-control" step="0.01" min="0" value="<?php echo $bed['price_per_day']; ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="Available" <?php echo $bed['status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                                                <option value="Occupied" <?php echo $bed['status'] == 'Occupied' ? 'selected' : ''; ?>>Occupied</option>
                                                <option value="Maintenance" <?php echo $bed['status'] == 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                            </select>
                                        </div>
                                        <button type="submit" name="update_bed" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Update Bed
                                        </button>
                                        <a href="beds.php" class="btn btn-default">Cancel</a>
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