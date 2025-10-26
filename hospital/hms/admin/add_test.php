<?php
session_start();
//error_reporting(0);
//include('include/config.php');

if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit();
}

// Create instance of TestFunctions
require_once('include/test_functions.php');
$testFunctions = new TestFunctions($con);

// Fetch all categories for dropdown
$categories = $testFunctions->getTestCategories();

// Handle form submission
if(isset($_POST['add_test'])) {
    $name = $_POST['name'];
    $category_id = intval($_POST['category_id']);
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $preparation = $_POST['preparation'];
    $normal_range = $_POST['normal_range'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $query = "INSERT INTO diagnostic_tests 
        (name, category_id, description, price, preparation, normal_range, start_time, end_time) 
        VALUES 
        ('$name', $category_id, '$description', $price, '$preparation', '$normal_range', '$start_time', '$end_time')";
    
    mysqli_query($con, $query);

    $_SESSION['success'] = "Test added successfully";
    header("Location: manage_tests.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Add Diagnostic Test</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
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
                            <h1 class="mainTitle">Admin | Add Diagnostic Test</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Add Diagnostic Test</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row margin-top-30">
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Add New Diagnostic Test</h5>
                                </div>
                                <div class="panel-body">
                                    <form method="post">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Test Name</label>
                                                    <input type="text" class="form-control" name="name" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select class="form-control" name="category_id" required>
                                                        <option value="">Select Category</option>
                                                        <?php foreach($categories as $category): ?>
                                                            <option value="<?php echo $category['id']; ?>">
                                                                <?php echo htmlspecialchars($category['name']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description" rows="3" required></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Price (৳)</label>
                                                    <input type="number" step="0.01" class="form-control" name="price" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Preparation</label>
                                                    <input type="text" class="form-control" name="preparation" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Normal Range</label>
                                                    <input type="text" class="form-control" name="normal_range" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Start Time</label>
                                                    <input type="time" class="form-control" name="start_time" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>End Time</label>
                                                    <input type="time" class="form-control" name="end_time" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" name="add_test" class="btn btn-o btn-primary">
                                                <i class="fa fa-save"></i> Save Test
                                            </button>
                                            <a href="manage_tests.php" class="btn btn-o btn-danger">
                                                <i class="fa fa-times"></i> Cancel
                                            </a>
                                        </div>
                                    </form>
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
