<?php
session_start();
include('include/config.php');

if (isset($_POST['submit'])) {
    $docid = $_SESSION['id'];
    $appointment_number = $_POST['appointment_number'];

    // Fetch userId from appointment table
    $aptQuery = mysqli_query($con, "SELECT userId FROM appointment WHERE appointment_number = '$appointment_number'");

    if (!$aptQuery) {
        echo "Query Error: " . mysqli_error($con);
        exit();
    }

    $aptData = mysqli_fetch_assoc($aptQuery);

    if (!$aptData) {
        echo "<script>alert('Invalid Appointment Number. No record found.');</script>";
        echo "<script>window.location.href='add-patient.php';</script>";
        exit();
    }

    $userId = $aptData['userId'];

    // Fetch patient details from users table
    $userQuery = mysqli_query($con, "SELECT fullName, email, gender, address FROM users WHERE id = '$userId'");

    if (!$userQuery) {
        echo "Query Error: " . mysqli_error($con);
        exit();
    }

    $userData = mysqli_fetch_assoc($userQuery);

    if (!$userData) {
        echo "<script>alert('Patient info not found in user records.');</script>";
        echo "<script>window.location.href='add-patient.php';</script>";
        exit();
    }

    // Collect form input
    $age = $_POST['age'];
    $medhis = $_POST['medhis'];
    $prescription = $_POST['prescription'];
    $tests = $_POST['tests'];

    // Insert into tblpatient
    $insert = mysqli_query($con, "INSERT INTO tblpatient(
        Docid, user_id, appointment_number, PatientName, PatientEmail, PatientGender, PatientAdd, age,
        PatientMedhis, Prescription, Tests
    ) VALUES (
        '$docid', '$userId', '$appointment_number',
        '{$userData['fullName']}',  '{$userData['email']}',
        '{$userData['gender']}', '{$userData['address']}', '$age',
        '$medhis', '$prescription', '$tests'
    )");

    if ($insert) {
        echo "<script>alert('Patient record added successfully.');</script>";
        echo "<script>window.location.href='manage-patient.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor | Add Patient Info</title>
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
        .form-section {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-section h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .form-control {
            border-radius: 3px;
            border: 1px solid #ddd;
            box-shadow: none;
        }
        .btn-submit {
            background-color: #3498db;
            border-color: #3498db;
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-submit:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .required-field::after {
            content: " *";
            color: #e74c3c;
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
                            <h1 class="mainTitle">Add Patient Medical Info</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Patient</span></li>
                            <li class="active"><span>Add Medical Info</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white">
                                <div class="panel-body">
                                    <form method="post">
                                        <div class="form-section">
                                            <h4>Appointment Selection</h4>
                                            <div class="form-group">
                                                <label class="required-field">Appointment Number</label>
                                                <select name="appointment_number" id="appointment_number" class="form-control" required>
                                                    <option value="">-- Select Appointment --</option>
                                                    <?php
                                                        date_default_timezone_set('Asia/Dhaka');
                                                        $today = date('Y-m-d');
                                                        $docid = $_SESSION['id'];  // Make sure this line exists before your query

                                                        $aptQuery = mysqli_query($con, "
                                                            SELECT appointment_number 
                                                            FROM appointment 
                                                            WHERE appointmentDate = '$today' 
                                                              AND doctorId = '$docid' 
                                                              AND appointment_number IS NOT NULL
                                                        ");

                                                        while ($row = mysqli_fetch_assoc($aptQuery)) {
                                                            echo "<option value='{$row['appointment_number']}'>{$row['appointment_number']}</option>";
                                                        }
                                                        ?>
                                                </select>
                                            </div>

                                            <div id="patient_info" style="margin-top:20px;"></div>
                                        </div>

                                        <div class="form-section">
                                            <h4>Medical Information</h4>
                                            <div class="form-group">
                                                <label>Age</label>
                                                <textarea name="age" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Medical History</label>
                                                <textarea name="medhis" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Prescription</label>
                                                <textarea name="prescription" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Recommended Tests</label>
                                                <textarea name="tests" class="form-control"></textarea>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="reset" class="btn btn-default">Reset</button>
                                            <button type="submit" name="submit" class="btn btn-submit">Save Medical Info</button>
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
<script src="vendor/select2/select2.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
    jQuery(document).ready(function() {
        Main.init();
        $('#appointment_number').change(function(){
            var aptNumber = $(this).val();
            if(aptNumber !== ''){
                $.ajax({
                    url: 'fetch-patient-info.php',
                    type: 'POST',
                    data: { appointment_number: aptNumber },
                    success: function(data){
                        $('#patient_info').html(data);
                    }
                });
            }else{
                $('#patient_info').html('');
            }
        });
    });
</script>
</body>
</html>
