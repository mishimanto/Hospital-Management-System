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
    if(isset($_POST['add_bed'])){
        $ward_id = intval($_POST['ward_id']);
        $bed_number = $con->real_escape_string($_POST['bed_number']);
        $bed_type = $con->real_escape_string($_POST['bed_type']);
        $new_bed_type = isset($_POST['new_bed_type']) ? $con->real_escape_string($_POST['new_bed_type']) : '';
        $price_per_day = floatval($_POST['price_per_day']);

        // If new bed type is provided, use it instead of selected bed type
        if(!empty($new_bed_type)) {
            $bed_type = $new_bed_type;
        }

        $sql = "INSERT INTO beds (ward_id, bed_number, bed_type, price_per_day) 
                VALUES ($ward_id, '$bed_number', '$bed_type', $price_per_day)";
        if($con->query($sql)){
            $_SESSION['success'] = "Bed added successfully";
        } else {
            $_SESSION['error'] = "Error adding bed: " . $con->error;
        }
    }
    
    // Handle delete action
    if(isset($_POST['delete_bed'])){
        $bed_id = intval($_POST['bed_id']);
        $sql = "DELETE FROM beds WHERE bed_id = $bed_id";
        if($con->query($sql)){
            $_SESSION['success'] = "Bed deleted successfully";
        } else {
            $_SESSION['error'] = "Error deleting bed: " . $con->error;
        }
    }
}

// Fetch all beds with ward info
$beds = $con->query("
    SELECT b.*, w.ward_name 
    FROM beds b
    JOIN wards w ON b.ward_id = w.ward_id
    ORDER BY w.ward_name, b.bed_number
");

// Fetch wards for dropdown
$wards = $con->query("SELECT * FROM wards ORDER BY ward_name");

// Fetch distinct existing bed types for dropdown
$bed_types = $con->query("SELECT DISTINCT bed_type FROM beds ORDER BY bed_type");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Bed Management</title>
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
        .new-bed-type-container {
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
                            <h1 class="mainTitle">Admin | Bed Management</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Bed Management</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row margin-top-30">
                        <div class="col-lg-6 col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Add New Bed</h5>
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
                                                    <option value="<?php echo $ward['ward_id']; ?>">
                                                        <?php echo htmlspecialchars($ward['ward_name']) ?> (<?php echo $ward['ward_type'] ?>)
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Bed Number</label>
                                            <input type="text" name="bed_number" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Bed Type</label>
                                            <select name="bed_type" id="bed_type" class="form-control">
                                                <option value="">--Select Bed Type--</option>
                                                <?php while($type = $bed_types->fetch_assoc()): ?>
                                                    <option value="<?php echo $type['bed_type']; ?>">
                                                        <?php echo $type['bed_type']; ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                            <div class="checkbox" style="margin-top: 10px;">
                                                <label>
                                                    <input type="checkbox" id="add_new_type"> Add new bed type
                                                </label>
                                            </div>
                                            <div class="new-bed-type-container" id="new_bed_type_container">
                                                <input type="text" name="new_bed_type" id="new_bed_type" class="form-control" placeholder="Enter new bed type">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Price Per Day (BDT)</label>
                                            <input type="number" name="price_per_day" class="form-control" step="0.01" min="0" required>
                                        </div>
                                        <button type="submit" name="add_bed" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Add Bed
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Existing Beds</h5>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Ward</th>
                                                    <th>Bed No.</th>
                                                    <th>Type</th>
                                                    <th>Price/Day</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while($bed = $beds->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo $bed['bed_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($bed['ward_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($bed['bed_number']); ?></td>
                                                    <td><?php echo $bed['bed_type']; ?></td>
                                                    <td>৳<?php echo number_format($bed['price_per_day'], 2); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php
                                                            echo $bed['status'] == 'Available' ? 'success' : 
                                                                 ($bed['status'] == 'Occupied' ? 'danger' : 'warning');
                                                        ?>">
                                                            <?php echo $bed['status']; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="edit_bed.php?id=<?php echo $bed['bed_id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                                        <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this bed?');">
                                                            <input type="hidden" name="bed_id" value="<?php echo $bed['bed_id']; ?>">
                                                            <button type="submit" name="delete_bed" class="btn btn-sm btn-danger">Delete</button>
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
        
        // Toggle new bed type field
        $('#add_new_type').change(function() {
            if($(this).is(':checked')) {
                $('#new_bed_type_container').show();
                $('#bed_type').val('');
            } else {
                $('#new_bed_type_container').hide();
                $('#new_bed_type').val('');
            }
        });
        
        // When bed type is selected, uncheck the "add new" checkbox
        $('#bed_type').change(function() {
            if($(this).val() !== '') {
                $('#add_new_type').prop('checked', false);
                $('#new_bed_type_container').hide();
                $('#new_bed_type').val('');
            }
        });
    });
</script>
</body>
</html>