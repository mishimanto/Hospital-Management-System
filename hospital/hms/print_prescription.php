<?php
session_start();
require('include/config.php');

if(!isset($_SESSION['id'])) {
    header('location:logout.php');
    exit();
}

if(isset($_GET['id'])) {
    $appointmentId = intval($_GET['id']);
    $userId = $_SESSION['id'];

    $query = "SELECT a.*, d.doctorName, d.specilization, p.PatientName, p.PatientMedhis, p.Prescription, p.Tests 
              FROM appointment a
              JOIN doctors d ON d.id = a.doctorId
              LEFT JOIN tblpatient p ON p.appointment_number = a.appointment_number
              WHERE a.id = '$appointmentId' AND a.userId = '$userId'";
    
    $result = mysqli_query($con, $query);
    
    if(mysqli_num_rows($result) == 0) {
        $_SESSION['msg'] = "Prescription not found";
        header("Location: appointment-history.php");
        exit();
    }

    $data = mysqli_fetch_assoc($result);
} else {
    header("Location: appointment-history.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .prescription-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .hospital-name {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
        .address {
            font-size: 14px;
            margin: 5px 0;
        }
        .prescription-title {
            text-align: center;
            font-size: 20px;
            margin: 15px 0;
            text-decoration: underline;
        }
        .patient-info {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            min-width: 150px;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            border-bottom: 1px solid #ddd;
            padding-bottom: 3px;
            margin-bottom: 8px;
        }
        .footer {
            text-align: right;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
        }
        .print-btn {
            text-align: center;
            margin: 20px auto;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
        }
        @media print {
            .print-btn {
                display: none;
            }
            body {
                padding: 0;
            }
            .prescription-container {
                border: none;
                box-shadow: none;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="prescription-container">
        <div class="header">
            <div class="hospital-name">MEDIZEN HOSPITAL</div>
            <div class="address">Chasara, Narayanganj</div>
            <div class="address">Phone: 01949854504 | Email: medizen@gmail.com</div>
        </div>

        <div class="prescription-title">PRESCRIPTION</div>

        <div class="patient-info">
            <div class="info-row">
                <div class="info-label">Patient Name:</div>
                <div><?php echo htmlspecialchars($data['PatientName'] ?? 'N/A'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Appointment No:</div>
                <div><?php echo htmlspecialchars($data['appointment_number']); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Date:</div>
                <div><?php echo date('d M Y', strtotime($data['appointmentDate'])); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Doctor:</div>
                <div><?php echo htmlspecialchars($data['doctorName'] . ' (' . $data['specilization'] . ')'); ?></div>
            </div>
        </div>

        <?php if(!empty($data['PatientMedhis'])): ?>
        <div class="section">
            <div class="section-title">Medical History:</div>
            <div><?php echo nl2br(htmlspecialchars($data['PatientMedhis'])); ?></div>
        </div>
        <?php endif; ?>

        <?php if(!empty($data['Prescription'])): ?>
        <div class="section">
            <div class="section-title">Prescription:</div>
            <div><?php echo nl2br(htmlspecialchars($data['Prescription'])); ?></div>
        </div>
        <?php endif; ?>

        <?php if(!empty($data['Tests'])): ?>
        <div class="section">
            <div class="section-title">Recommended Tests:</div>
            <div><?php echo nl2br(htmlspecialchars($data['Tests'])); ?></div>
        </div>
        <?php endif; ?>

        <div class="footer">
            <div>_________________________</div>
            <div>Doctor's Signature</div>
        </div>
    </div>

    <div class="print-btn">
        <button onclick="window.print()">Print Prescription</button>
        <button onclick="window.close()">Close Window</button>
    </div>

    <script>
        window.onload = function() {
            // setTimeout(function() {
            //     window.print();
            // }, 1000);
        };
    </script>
</body>
</html>