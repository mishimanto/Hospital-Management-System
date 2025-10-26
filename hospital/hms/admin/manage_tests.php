<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit();
}

require_once('include/test_functions.php');
$testFunctions = new TestFunctions($con);

// Handle Add
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

// Handle Update
if(isset($_POST['update_test'])) {
    $test_id = intval($_POST['test_id']);
    $name = $_POST['name'];
    $category_id = intval($_POST['category_id']);
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $preparation = $_POST['preparation'];
    $normal_range = $_POST['normal_range'];

    $query = "UPDATE diagnostic_tests 
          SET name = '$name', category_id = $category_id, description = '$description', 
              price = $price, preparation = '$preparation', normal_range = '$normal_range',
              start_time = '$start_time', end_time = '$end_time', updated_at = NOW()
          WHERE id = $test_id";

    mysqli_query($con, $query);
    $_SESSION['success'] = "Test updated successfully";
    header("Location: manage_tests.php");
    exit();
}

// Handle Delete
if(isset($_GET['delete_test'])) {
    $test_id = intval($_GET['delete_test']);
    mysqli_query($con, "DELETE FROM diagnostic_tests WHERE id = $test_id");
    $_SESSION['success'] = "Test deleted successfully";
    header("Location: manage_tests.php");
    exit();
}

// Fetch tests
$tests = mysqli_query($con, "SELECT dt.*, tc.name as category_name 
                            FROM diagnostic_tests dt
                            JOIN test_categories tc ON dt.category_id = tc.id
                            ORDER BY dt.id DESC");

// Fetch categories
$categories = $testFunctions->getTestCategories();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Manage Diagnostic Tests</title>
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
                            <h1 class="mainTitle">Manage Diagnostic Tests</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Manage Diagnostic Tests</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">

                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Add / Edit Diagnostic Test</h5>
                                </div>
                                <div class="panel-body">

                                    <?php
                                    $edit_test = null;
                                    if(isset($_GET['edit_test'])) {
                                        $test_id = intval($_GET['edit_test']);
                                        $res = mysqli_query($con, "SELECT * FROM diagnostic_tests WHERE id = $test_id");
                                        $edit_test = mysqli_fetch_assoc($res);
                                    }
                                    ?>

                                    <form method="post">
                                        <input type="hidden" name="test_id" value="<?php echo $edit_test ? $edit_test['id'] : ''; ?>">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Test Name</label>
                                                    <input type="text" class="form-control" name="name" required value="<?php echo $edit_test ? $edit_test['name'] : ''; ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Category</label>
                                                    <select name="category_id" class="form-control" required>
                                                        <option value="">Select Category</option>
                                                        <?php foreach($categories as $cat): ?>
                                                            <option value="<?php echo $cat['id']; ?>" <?php if($edit_test && $edit_test['category_id'] == $cat['id']) echo 'selected'; ?>>
                                                                <?php echo $cat['name']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control" rows="3" required><?php echo $edit_test ? $edit_test['description'] : ''; ?></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Price (৳)</label>
                                                <input type="number" step="0.01" name="price" class="form-control" required value="<?php echo $edit_test ? $edit_test['price'] : ''; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Preparation</label>
                                                <input type="text" name="preparation" class="form-control" required value="<?php echo $edit_test ? $edit_test['preparation'] : ''; ?>">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Normal Range</label>
                                                <input type="text" name="normal_range" class="form-control" required value="<?php echo $edit_test ? $edit_test['normal_range'] : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Start Time</label>
                                                    <input type="time" name="start_time" class="form-control" required value="<?php echo $edit_test ? $edit_test['start_time'] : ''; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>End Time</label>
                                                    <input type="time" name="end_time" class="form-control" required value="<?php echo $edit_test ? $edit_test['end_time'] : ''; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <br>
                                        <div class="text-end">
                                            <?php if($edit_test): ?>
                                                <button type="submit" name="update_test" class="btn btn-primary">Update</button>
                                                <a href="manage_tests.php" class="btn btn-danger">Cancel</a>
                                            <?php else: ?>
                                                <button type="submit" name="add_test" class="btn btn-success">Add Test</button>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">All Diagnostic Tests</h5>
                                </div>
                                <div class="panel-body table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Test Name</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Normal Range</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1; while($test = mysqli_fetch_assoc($tests)): ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $test['name']; ?></td>
                                                <td><?php echo $test['category_name']; ?></td>
                                                <td>৳<?php echo $test['price']; ?></td>
                                                <td><?php echo $test['normal_range']; ?></td>
                                                <td>
                                                    <a href="manage_tests.php?edit_test=<?php echo $test['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <a href="manage_tests.php?delete_test=<?php echo $test['id']; ?>" onclick="return confirm('Confirm delete?')" class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
        <?php include('include/footer.php'); ?>
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
