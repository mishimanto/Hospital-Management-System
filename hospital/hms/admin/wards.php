<?php
session_start();
require_once('include/config.php');

// Authentication check
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit();
}

// Handle form submissions
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['add_ward'])){
        $ward_name = $con->real_escape_string($_POST['ward_name']);
        $ward_type = $con->real_escape_string($_POST['ward_type']);
        $new_ward_type = isset($_POST['new_ward_type']) ? $con->real_escape_string($_POST['new_ward_type']) : '';
        $description = $con->real_escape_string($_POST['description']);

        // If new ward type is provided, use it instead of selected ward type
        if(!empty($new_ward_type)) {
            $ward_type = $new_ward_type;
        }

        $sql = "INSERT INTO wards (ward_name, ward_type, description) VALUES ('$ward_name', '$ward_type', '$description')";
        if($con->query($sql)){
            $_SESSION['success'] = "Ward added successfully";
        } else {
            $_SESSION['error'] = "Error adding ward: " . $con->error;
        }
    }
    
    // Handle delete action
    if(isset($_POST['delete_ward'])){
        $ward_id = intval($_POST['ward_id']);
        $sql = "DELETE FROM wards WHERE ward_id = $ward_id";
        if($con->query($sql)){
            $_SESSION['success'] = "Ward deleted successfully";
        } else {
            $_SESSION['error'] = "Error deleting ward: " . $con->error;
        }
    }
}

// Fetch all wards
$wards = $con->query("SELECT * FROM wards ORDER BY ward_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Ward Management</title>
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
    <style>
        .new-ward-type-container {
            display: none;
            margin-top: 10px;
        }
    </style>
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
                            <h1 class="mainTitle">Admin | Ward Management</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Ward Management</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row margin-top-30">
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Add New Ward</h5>
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
                                            <label>Ward Name</label>
                                            <input type="text" name="ward_name" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Ward Type</label>
                                            <select name="ward_type" id="ward_type" class="form-control text-muted">
                                                <option value="">--Select Ward Type--</option>
                                                <?php
                                                $query = mysqli_query($con, "SELECT DISTINCT ward_type FROM wards ORDER BY ward_type ASC");
                                                while($row = mysqli_fetch_assoc($query)) {
                                                    echo '<option value="'.$row['ward_type'].'">'.$row['ward_type'].'</option>';
                                                }
                                                ?>
                                            </select>
                                            <div class="checkbox" style="margin-top: 10px;">
                                                <label>
                                                    <input type="checkbox" id="add_new_type"> Add new ward type
                                                </label>
                                            </div>
                                            <div class="new-ward-type-container" id="new_ward_type_container">
                                                <input type="text" name="new_ward_type" id="new_ward_type" class="form-control" placeholder="Enter new ward type">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="description" class="form-control" rows="3"></textarea>
                                        </div>
                                        <button type="submit" name="add_ward" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Add Ward
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Existing Wards</h5>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($ward = $wards->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo $ward['ward_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($ward['ward_name']); ?></td>
                                                    <td><?php echo $ward['ward_type']; ?></td>
                                                    <td>
                                                        <a href="ward_details.php?id=<?php echo $ward['ward_id']; ?>" class="btn btn-sm btn-info">View</a>
                                                        <a href="edit_ward.php?id=<?php echo $ward['ward_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this ward?');">
                                                            <input type="hidden" name="ward_id" value="<?php echo $ward['ward_id']; ?>">
                                                            <button type="submit" name="delete_ward" class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
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
        
        // Toggle new ward type field
        $('#add_new_type').change(function() {
            if($(this).is(':checked')) {
                $('#new_ward_type_container').show();
                $('#ward_type').val('');
            } else {
                $('#new_ward_type_container').hide();
                $('#new_ward_type').val('');
            }
        });
        
        // When ward type is selected, uncheck the "add new" checkbox
        $('#ward_type').change(function() {
            if($(this).val() !== '') {
                $('#add_new_type').prop('checked', false);
                $('#new_ward_type_container').hide();
                $('#new_ward_type').val('');
            }
        });
    });
</script>
</body>
</html>