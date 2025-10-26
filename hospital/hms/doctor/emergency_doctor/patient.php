<?php
session_start();
require_once('include/config.php');

// Check if doctor is logged in
if (!isset($_SESSION['id'])) {
    header('location:logout.php');
    exit();
}

$doctorId = $_SESSION['id'];
$doc_name = '';

// Get doctor's name
$doc_query = mysqli_query($con, "SELECT name FROM em_doctors WHERE id = '$doctorId'");
if ($doc_query && mysqli_num_rows($doc_query) > 0) {
    $doc = mysqli_fetch_assoc($doc_query);
    $doc_name = $doc['name'];
}

// Track which assignments have pending requests
$pendingRequests = array();

// Check for pending discharge requests
$pendingQuery = mysqli_query($con, "SELECT assignment_id FROM discharge_requests WHERE status = 'Pending'");
while ($row = mysqli_fetch_assoc($pendingQuery)) {
    $pendingRequests[] = $row['assignment_id'];
}

// Handle discharge request if form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request_discharge'])) {
    $assignmentId = (int)$_POST['assignment_id'];
    
    // Validate and process discharge request
    $checkSql = "SELECT * FROM bed_assignments 
                 WHERE id = ? AND status = 'Admitted'";
    $stmt = mysqli_prepare($con, $checkSql);
    if ($stmt === false) {
        die('MySQL prepare error: ' . mysqli_error($con));
    }
    
    mysqli_stmt_bind_param($stmt, 'i', $assignmentId);
    if (!mysqli_stmt_execute($stmt)) {
        die('MySQL execute error: ' . mysqli_stmt_error($stmt));
    }
    
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Insert discharge request
        $insertSql = "INSERT INTO discharge_requests 
                      (assignment_id, request_date, requested_by, status, processed_by, processed_at)
                      VALUES (?, NOW(), ?, 'Pending', NULL, NULL)";
        $stmt = mysqli_prepare($con, $insertSql);
        if ($stmt === false) {
            die('MySQL prepare error: ' . mysqli_error($con));
        }
        
        mysqli_stmt_bind_param($stmt, 'is', $assignmentId, $doc_name);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Discharge request submitted successfully";
            $pendingRequests[] = $assignmentId; // Add to pending requests array
        } else {
            $_SESSION['error'] = "Failed to submit discharge request: " . mysqli_stmt_error($stmt);
        }
    } else {
        $_SESSION['error'] = "Invalid bed assignment or already discharged";
    }
    
    header("Location: patient.php");
    exit();
}

// Get patients with bed assignments
$sql = "SELECT DISTINCT ba.id as assignment_id, p.PatientName, p.PatientContno, 
               b.bed_number, w.ward_name, ba.admission_date, ba.status
        FROM patient p
        JOIN bed_assignments ba ON p.id = ba.patient_id
        JOIN beds b ON ba.bed_id = b.bed_id
        JOIN wards w ON b.ward_id = w.ward_id
        WHERE ba.status = 'Admitted'
        ORDER BY ba.admission_date DESC";

$result = mysqli_query($con, $sql);
if (!$result) {
    die('MySQL query error: ' . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor Dashboard</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
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
        .btn-disabled {
            opacity: 0.7;
            cursor: not-allowed;
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
                            <h1 class="mainTitle">Doctor | Manage Patient</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Doctor</span></li>
                            <li class="active"><span>Admitted Patients</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success">
                                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger">
                                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>

                            <h4 class="text-center text-primary" style="margin-bottom: 40px;">Currently Admitted Patients</h4>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th class="text-center">Patient Name</th>
                                            <th class="text-center">Contact</th>
                                            <th class="text-center">Bed/Ward</th>
                                            <th class="text-center">Admission Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $counter = 1;
                                        while($row = mysqli_fetch_assoc($result)): 
                                            $isPending = in_array($row['assignment_id'], $pendingRequests);
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $counter++; ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($row['PatientName']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($row['PatientContno']); ?></td>
                                            <td class="text-center"><?php echo htmlspecialchars($row['bed_number'] . ' (' . $row['ward_name'] . ')'); ?></td>
                                            <td class="text-center"><?php echo date('d-M-Y h:i A', strtotime($row['admission_date'])); ?></td>
                                            <td class="text-center">
                                                <span class="label label-<?php echo ($row['status'] == 'Admitted') ? 'success' : 'warning'; ?>">
                                                <?php echo htmlspecialchars($row['status']); ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="assignment_id" value="<?php echo $row['assignment_id']; ?>">
                                                    <button type="submit" name="request_discharge" 
                                                            class="btn btn-sm btn-<?php echo $isPending ? 'secondary' : 'warning'; ?> btn-action <?php echo $isPending ? 'btn-disabled' : ''; ?>"
                                                            <?php echo $isPending ? 'disabled' : ''; ?>
                                                            onclick="<?php echo $isPending ? 'return false;' : 'return confirm(\'Are you sure you want to request discharge for this patient?\');'; ?>">
                                                        <i class="fas fa-sign-out-alt me-1"></i> 
                                                        <?php echo $isPending ? 'Discharge Request Sent' : 'Request discharge'; ?>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="alert alert-info">No patients currently admitted.</div>
                            <?php endif; ?>
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
<script src="assets/js/main.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
    });
</script>
</body>
</html>