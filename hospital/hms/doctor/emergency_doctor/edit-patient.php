<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $eid = $_GET['editid'];
        $patname = $_POST['patname'];
        $patcontact = $_POST['patcontact'];
        $patemail = $_POST['patemail'];
        $gender = $_POST['gender'];
        $pataddress = $_POST['pataddress'];
        $patage = $_POST['patage'];
        $medhis = $_POST['medhis'];
        $prescription = $_POST['prescription'];
        $tests = $_POST['tests'];

        $sql = mysqli_query($con, "UPDATE tblpatient 
            SET 
                PatientName='$patname',
                PatientContno='$patcontact',
                PatientEmail='$patemail',
                PatientGender='$gender',
                PatientAdd='$pataddress',
                PatientAge='$patage',
                PatientMedhis='$medhis',
                Prescription='$prescription',
                Tests='$tests',
                UpdationDate=NOW()
            WHERE ID='$eid'");

        if ($sql) {
            echo "<script>alert('Patient info updated Successfully');</script>";
            echo "<script>window.location.href='manage-patient.php'</script>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor | Edit Patient</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
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
            color: #fff;
        }
        .btn-submit:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        .required-field::after {
            content: " *";
            color: #e74c3c;
        }
        textarea {
            min-height: 100px;
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
                            <h1 class="mainTitle">Patient | Edit Patient</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Patient</span></li>
                            <li class="active"><span>Edit Patient</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row margin-top-30">
                                <div class="col-lg-10 col-md-12">
                                    <div class="panel panel-white">
                                        <!-- <div class="panel-heading">
                                            <h5 class="panel-title">Edit Patient Details</h5>
                                        </div> -->
                                        <div class="panel-body">
                                            <form role="form" method="post">
                                                <?php
                                                $eid = $_GET['editid'];
                                                $ret = mysqli_query($con, "SELECT * FROM tblpatient WHERE ID='$eid'");
                                                while ($row = mysqli_fetch_array($ret)) {
                                                ?>

                                                <div class="form-section">
                                                    <h4>Edit Basic Information</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="required-field">Appointment Number</label>
                                                                <input type="text" name="patname" class="form-control" value="<?php echo htmlspecialchars($row['appointment_number']); ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="required-field">Patient Name</label>
                                                                <input type="text" name="patname" class="form-control" value="<?php echo htmlspecialchars($row['PatientName']); ?>" required>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="">Contact Number</label>
                                                                <input type="text" name="patcontact" class="form-control" value="<?php echo htmlspecialchars($row['PatientContno']); ?>" maxlength="11" pattern="[0-9]+">
                                                            </div>
                                                        </div> -->
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="required-field">Email</label>
                                                                <input type="email" name="patemail" class="form-control" value="<?php echo htmlspecialchars($row['PatientEmail']); ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="required-field">Age</label>
                                                                <input type="text" name="patage" class="form-control" value="<?php echo htmlspecialchars($row['PatientAge']); ?>" required>
                                                            </div>
                                                        </div> -->
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="required-field">Gender</label>
                                                        <div class="clip-radio radio-primary">
                                                            <input type="radio" id="female" name="gender" value="Female" <?php if(strcasecmp(trim($row['PatientGender']), 'Female') == 0) echo 'checked'; ?> required>
                                                            <label for="female">Female</label>

                                                            <input type="radio" id="male" name="gender" value="Male" <?php if(strcasecmp(trim($row['PatientGender']), 'Male') == 0) echo 'checked'; ?>>
                                                            <label for="male">Male</label>

                                                            <input type="radio" id="other" name="gender" value="Other" <?php if(strcasecmp(trim($row['PatientGender']), 'Other') == 0) echo 'checked'; ?>>
                                                            <label for="other">Other</label>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="required-field">Address</label>
                                                        <textarea name="pataddress" class="form-control" required><?php echo htmlspecialchars($row['PatientAdd']); ?></textarea>
                                                    </div>
                                                </div>

                                                <div class="form-section">
                                                    <h4>Medical Information</h4>
                                                    <div class="form-group">
                                                        <label class="">Medical History</label>
                                                        <textarea name="medhis" class="form-control" required><?php echo htmlspecialchars($row['PatientMedhis']); ?></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Prescription</label>
                                                        <textarea name="prescription" class="form-control"><?php echo htmlspecialchars($row['Prescription']); ?></textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Tests</label>
                                                        <textarea name="tests" class="form-control"><?php echo htmlspecialchars($row['Tests']); ?></textarea>
                                                        <small class="text-muted">Separate multiple tests with commas.</small>
                                                    </div>
                                                </div>

                                                <?php } ?>

                                                <div class="text-right">
                                                    <a href="manage-patient.php" class="btn btn-default">Cancel</a>
                                                    <button type="submit" name="submit" class="btn btn-submit">Update Patient</button>
                                                </div>
                                            </form>
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
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>
<script>
    jQuery(document).ready(function() {
        Main.init();
    });
</script>
</body>
</html>
<?php } ?>
