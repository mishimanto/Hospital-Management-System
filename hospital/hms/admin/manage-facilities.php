<?php
session_start();
include('include/config.php');

// Add Bed
if (isset($_POST['add_bed'])) {
    $bedCount = $_POST['bed_count'];
    mysqli_query($con, "INSERT INTO beds(count) VALUES('$bedCount')");
    header("Location: manage-facilities.php");
    exit();
}

// Add Lab
if (isset($_POST['add_lab'])) {
    $labCount = $_POST['lab_count'];
    mysqli_query($con, "INSERT INTO labs(count) VALUES('$labCount')");
    header("Location: manage-facilities.php");
    exit();
}

// Delete Bed
if (isset($_GET['del_bed'])) {
    $id = $_GET['del_bed'];
    mysqli_query($con, "DELETE FROM beds WHERE id='$id'");
    header("Location: manage-facilities.php");
    exit();
}

// Delete Lab
if (isset($_GET['del_lab'])) {
    $id = $_GET['del_lab'];
    mysqli_query($con, "DELETE FROM labs WHERE id='$id'");
    header("Location: manage-facilities.php");
    exit();
}

// Edit Bed
if (isset($_POST['edit_bed'])) {
    $id = $_POST['bed_id'];
    $count = $_POST['new_bed_count'];
    mysqli_query($con, "UPDATE beds SET count='$count' WHERE id='$id'");
    header("Location: manage-facilities.php");
    exit();
}

// Edit Lab
if (isset($_POST['edit_lab'])) {
    $id = $_POST['lab_id'];
    $count = $_POST['new_lab_count'];
    mysqli_query($con, "UPDATE labs SET count='$count' WHERE id='$id'");
    header("Location: manage-facilities.php");
    exit();
}

// Total Bed Count
$totalBedResult = mysqli_query($con, "SELECT SUM(count) AS total FROM beds");
$totalBedRow = mysqli_fetch_assoc($totalBedResult);
$totalBeds = $totalBedRow['total'] ?? 0;

// Total Lab Count
$totalLabResult = mysqli_query($con, "SELECT SUM(count) AS total FROM labs");
$totalLabRow = mysqli_fetch_assoc($totalLabResult);
$totalLabs = $totalLabRow['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Facilities</title>
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
    .facility-card {
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        border: none;
    }
    .facility-card .card-header {
        background-color: #fff;
        border-bottom: 1px solid #eee;
        padding: 20px;
        border-radius: 10px 10px 0 0 !important;
    }
    .facility-card .card-body {
        padding: 25px;
    }
    .total-badge {
        font-size: 16px;
        padding: 8px 15px;
        border-radius: 20px;
    }
    .bed-badge {
        background-color: #e3f7ff;
        color: #0062cc;
    }
    .lab-badge {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    .action-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .action-form input {
        max-width: 80px;
        text-align: center;
    }
    .table th {
        border-top: none;
        font-weight: 600;
        color: #555;
    }
    .add-form {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }
    .add-form input {
        flex: 0 0 120px;   /* fixed width */
        max-width: 120px;
        padding: 6px 10px;
        font-size: 14px;
    }

    .section-title {
        color: #444;
        margin-bottom: 25px;
        font-weight: 600;
        position: relative;
        padding-bottom: 10px;
    }
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(to right, #0062cc, #00a1ff);
    }
    .bed-title:after {
        background: linear-gradient(to right, #0062cc, #00a1ff);
    }
    .lab-title:after {
        background: linear-gradient(to right, #2e7d32, #4caf50);
    }
    .btn-bed {
        background-color: #3498db;
        border-color: #3498db;
    }
    .btn-lab {
        background-color: #2ecc71;
        border-color: #2ecc71;
    }
    .btn-edit {
        background-color: #ffc107;
        border-color: #ffc107;
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
                            <h1 class="mainTitle">Admin | Manage Facilities</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Hospital Facilities</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <!-- Bed Management -->
                        <div class="col-md-6">
                            <div class="card facility-card">
                                <div class="card-header">
                                    <h4 class="section-title bed-title">Bed Management</h4>
                                    <span class="total-badge bed-badge">Total Beds: <?php echo $totalBeds; ?></span>
                                </div>
                                <div class="card-body">
                                    <form method="post" class="add-form">
                                        <input type="number" name="bed_count" class="form-control" placeholder="Enter bed count" required>
                                        <button type="submit" name="add_bed" class="btn btn-bed text-white">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </form>

                                    <h5 class="mb-3">Existing Beds</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Count</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                $beds = mysqli_query($con, "SELECT * FROM beds ORDER BY id DESC");
                                                while ($row = mysqli_fetch_assoc($beds)) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><strong><?php echo $row['count']; ?></strong></td>
                                                        <td><?php echo date('d M Y h:i A', strtotime($row['created_at'])); ?></td>
                                                        <td>
                                                            <form method="post" class="action-form">
                                                                <input type="hidden" name="bed_id" value="<?php echo $row['id']; ?>">
                                                                <input type="number" name="new_bed_count" value="<?php echo $row['count']; ?>" class="form-control form-control-sm" required>
                                                                <button type="submit" name="edit_bed" class="btn btn-sm btn-edit text-white">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <a href="?del_bed=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this bed record?')">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lab Management -->
                        <div class="col-md-6">
                            <div class="card facility-card">
                                <div class="card-header">
                                    <h4 class="section-title lab-title">Lab Management</h4>
                                    <span class="total-badge lab-badge">Total Labs: <?php echo $totalLabs; ?></span>
                                </div>
                                <div class="card-body">
                                    <form method="post" class="add-form">
                                        <input type="number" name="lab_count" class="form-control" placeholder="Enter lab count" required>
                                        <button type="submit" name="add_lab" class="btn btn-lab text-white">
                                            <i class="fa fa-plus"></i> Add
                                        </button>
                                    </form>

                                    <h5 class="mb-3">Existing Labs</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Count</th>
                                                    <th>Created</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 1;
                                                $labs = mysqli_query($con, "SELECT * FROM labs ORDER BY id DESC");
                                                while ($row = mysqli_fetch_assoc($labs)) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $i++; ?></td>
                                                        <td><strong><?php echo $row['count']; ?></strong></td>
                                                        <td><?php echo date('d M Y h:i A', strtotime($row['created_at'])); ?></td>
                                                        <td>
                                                            <form method="post" class="action-form">
                                                                <input type="hidden" name="lab_id" value="<?php echo $row['id']; ?>">
                                                                <input type="number" name="new_lab_count" value="<?php echo $row['count']; ?>" class="form-control form-control-sm" required>
                                                                <button type="submit" name="edit_lab" class="btn btn-sm btn-edit text-white">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <a href="?del_lab=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this lab record?')">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- end #container -->
        </div> <!-- end .main-content -->

        <?php include('include/footer.php'); ?>
        <?php include('include/setting.php'); ?>

    </div> <!-- end .app-content -->
</div> <!-- end #app -->

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
        FormElements.init();
    });
</script>

</body>
</html>