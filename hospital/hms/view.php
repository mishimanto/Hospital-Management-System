<?php
session_start();
include('include/config.php');
error_reporting(0);

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$patientId = intval($_GET['id']);

$query = mysqli_query($con, "SELECT * FROM tblpatient WHERE ID = '$patientId'");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    echo "Patient not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prescription - <?php echo htmlentities($row['PatientName']); ?></title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        .prescription-box {
            border: 1px solid #ccc;
            padding: 25px;
            margin: 20px auto;
            max-width: 800px;
            background: #fff;
        }
        h2 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container prescription-box">
    <h2>Prescription</h2>

    <p><strong>Patient Name:</strong> <?php echo htmlentities($row['PatientName']); ?></p>
    <p><strong>Age:</strong> <?php echo htmlentities($row['PatientAge']); ?> years</p>
    <p><strong>Gender:</strong> <?php echo htmlentities($row['PatientGender']); ?></p>
    <p><strong>Contact:</strong> <?php echo htmlentities($row['PatientContno']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlentities($row['PatientEmail']); ?></p>
    <p><strong>Address:</strong> <?php echo nl2br(htmlentities($row['PatientAdd'])); ?></p>
    <hr>
    <h4>Medical History</h4>
    <p><?php echo nl2br(htmlentities($row['PatientMedhis'])); ?></p>

    <h4>Prescription</h4>
    <p><?php echo nl2br(htmlentities($row['Prescription'])); ?></p>

    <h4>Recommended Tests</h4>
    <p><?php echo nl2br(htmlentities($row['Tests'])); ?></p>

    <p class="text-muted"><small>Created at: <?php echo htmlentities($row['CreationDate']); ?></small></p>

    <div class="text-center no-print mt-4">
        <button onclick="window.print();" class="btn btn-primary">🖨️ Print</button>
        <a href="manage-patient.php" class="btn btn-secondary">🔙 Back</a>
    </div>
</div>
</body>
</html>
