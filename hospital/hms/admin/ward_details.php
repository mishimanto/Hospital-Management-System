<?php
session_start();
require_once('include/config.php');

// Authentication check
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit();
}

// Get ward ID
$ward_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch ward details
$ward = $con->query("SELECT * FROM wards WHERE ward_id = $ward_id")->fetch_assoc();
if(!$ward){
    $_SESSION['error'] = "Ward not found";
    header('location:wards.php');
    exit();
}

// Fetch beds in this ward
$beds = $con->query("
    SELECT b.*, 
           (SELECT COUNT(*) FROM bed_assignments ba WHERE ba.bed_id = b.bed_id AND ba.status = 'Admitted') AS is_occupied
    FROM beds b
    WHERE b.ward_id = $ward_id
    ORDER BY b.bed_number
");

// Calculate statistics
$stats = $con->query("
    SELECT 
        COUNT(*) AS total_beds,
        SUM(CASE WHEN status = 'Occupied' THEN 1 ELSE 0 END) AS occupied_beds,
        SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) AS available_beds
    FROM beds
    WHERE ward_id = $ward_id
")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ward Details: <?= htmlspecialchars($ward['ward_name']) ?></title>

    <!-- CSS and font links -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link rel="stylesheet" href="vendor/animate.css/animate.min.css">
    <link rel="stylesheet" href="vendor/perfect-scrollbar/perfect-scrollbar.min.css">
    <link rel="stylesheet" href="vendor/switchery/switchery.min.css">
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
                            <h1 class="mainTitle">Ward Details: <?= htmlspecialchars($ward['ward_name']) ?></h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li><span>Wards</span></li>
                            <li class="active"><span>Ward Details</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">

                    <!-- Ward Info + Stats -->
                    <div class="row margin-top-30">
                        <div class="col-md-6">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Ward Information</h5>
                                </div>
                                <div class="panel-body">
                                    <p><strong>Type:</strong> <?= $ward['ward_type'] ?></p>
                                    <p><strong>Description:</strong> <?= htmlspecialchars($ward['description']) ?></p>
                                    <p><strong>Created At:</strong> <?= date('d M Y h:i A', strtotime($ward['created_at'])) ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-white">
                                <div class="panel-heading">
                                    <h5 class="panel-title">Bed Statistics</h5>
                                </div>
                                <div class="panel-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <h2 class="text-primary"><?= $stats['total_beds'] ?></h2>
                                            <small>Total Beds</small>
                                        </div>
                                        <div class="col-4">
                                            <h2 class="text-success"><?= $stats['available_beds'] ?></h2>
                                            <small>Available</small>
                                        </div>
                                        <div class="col-4">
                                            <h2 class="text-danger"><?= $stats['occupied_beds'] ?></h2>
                                            <small>Occupied</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Beds List -->
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            <h5 class="panel-title">Beds in This Ward</h5>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Bed Number</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Patient</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php while($bed = $beds->fetch_assoc()): 
                                        $patient = null;
                                        if($bed['is_occupied']){
                                            $patient = $con->query("
                                                SELECT p.PatientName 
                                                FROM bed_assignments ba
                                                JOIN tblpatient p ON ba.patient_id = p.ID
                                                WHERE ba.bed_id = {$bed['bed_id']} AND ba.status = 'Admitted'
                                                LIMIT 1
                                            ")->fetch_assoc();
                                        }
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($bed['bed_number']) ?></td>
                                            <td><?= $bed['bed_type'] ?></td>
                                            <td>
                                                <span class="badge bg-<?= $bed['is_occupied'] ? 'danger' : 'success' ?>">
                                                    <?= $bed['is_occupied'] ? 'Occupied' : 'Available' ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?= $patient ? htmlspecialchars($patient['PatientName']) : '--' ?>
                                            </td>
                                            <td>
                                                <a href="bed_details.php?id=<?= $bed['bed_id'] ?>" class="btn btn-sm btn-info">View</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <a href="wards.php" class="btn btn-o btn-default">
                        <i class="fa fa-arrow-left"></i> Back to Wards
                    </a>

                </div>
            </div>
        </div>        
    </div>
    <?php include('include/footer.php'); ?>
    <?php include('include/setting.php'); ?>
</div>

<!-- JS scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
    });
</script>
</body>
</html>
