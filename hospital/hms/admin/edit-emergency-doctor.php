<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
} else {
    $doc_id = intval($_GET['id']); // Get doctor id

    // Update doctor details
    if (isset($_POST['submit'])) {
        $doc_name = mysqli_real_escape_string($con, $_POST['docname']);
        $doc_specialization = mysqli_real_escape_string($con, $_POST['Doctorspecialization']);
        $doc_contact = mysqli_real_escape_string($con, $_POST['contact']);
        $doc_email = mysqli_real_escape_string($con, $_POST['email']);
        $doc_qualification = mysqli_real_escape_string($con, $_POST['qualification']);
        $doc_shift = mysqli_real_escape_string($con, $_POST['shift']);
        $doc_available = intval($_POST['available']); // 1 or 0

        $stmt = mysqli_prepare($con, "UPDATE em_doctors SET name=?, contact=?, email=?, specialization=?, qualification=?, shift=?, available=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "ssssssii", $doc_name, $doc_contact, $doc_email, $doc_specialization, $doc_qualification, $doc_shift, $doc_available, $doc_id);

        if (mysqli_stmt_execute($stmt)) {
            $msg = "Doctor Details updated Successfully";
            echo "<script>
                setTimeout(function(){
                    window.location.href = 'manage-emergency-doctors.php';
                }, 3000);
            </script>";
        } else {
            $msg = "Error updating record: " . mysqli_error($con);
        }
        mysqli_stmt_close($stmt);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Edit Doctor Details</title>
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
                        <h1 class="mainTitle">Admin | Edit Emergency Doctor Details</h1>
                    </div>
                    <ol class="breadcrumb">
                        <li><span>Admin</span></li>
                        <li class="active"><span>Edit Emergency Doctor Details</span></li>
                    </ol>
                </div>
            </section>

            <div class="container-fluid container-fullw bg-white">
                <div class="row">
                    <div class="col-md-12">
                        <h5 style="color: green; font-size:18px;">
                            <?php if ($msg) echo htmlentities($msg); ?>
                        </h5>

                        <div class="row margin-top-30">
                            <div class="col-lg-8 col-md-12">
                                <div class="panel panel-white">
                                    <div class="panel-heading">
                                        <h5 class="panel-title">Edit Emergency Doctor Info</h5>
                                    </div>
                                    <div class="panel-body">

                                        <?php
                                        $sql = mysqli_query($con, "SELECT * FROM em_doctors WHERE id='$doc_id'");
                                        while ($data = mysqli_fetch_array($sql)) {
                                        ?>
                                        <h4><?php echo htmlentities($data['name']); ?>'s Profile</h4>
                                        <p><b>Profile Reg. Date:</b> <?php echo htmlentities($data['creationDate']); ?></p>
                                        <?php if ($data['updationDate']) { ?>
                                        <p><b>Last Updated:</b> <?php echo htmlentities($data['updationDate']); ?></p>
                                        <?php } ?>
                                        <hr />

                                        <form role="form" name="editdoc" method="post">
                                            <div class="form-group">
                                                <label for="DoctorSpecialization">Doctor Specialization</label>
                                                <select name="Doctorspecialization" class="form-control" required>
                                                    <option value="<?php echo htmlentities($data['specialization']); ?>">
                                                        <?php echo htmlentities($data['specialization']); ?>
                                                    </option>
                                                    <?php
                                                    $ret = mysqli_query($con, "SELECT * FROM doctorspecilization");
                                                    while ($row = mysqli_fetch_array($ret)) {
                                                    ?>
                                                    <option value="<?php echo htmlentities($row['specilization']); ?>">
                                                        <?php echo htmlentities($row['specilization']); ?>
                                                    </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="doctorname">Doctor Name</label>
                                                <input type="text" name="docname" class="form-control" value="<?php echo htmlentities($data['name']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="contact">Contact No</label>
                                                <input type="text" name="contact" class="form-control" value="<?php echo htmlentities($data['contact']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="text" name="email" class="form-control" value="<?php echo htmlentities($data['email']); ?>" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label for="qualification">Qualification</label>
                                                <input type="text" name="qualification" class="form-control" value="<?php echo htmlentities($data['qualification']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="shift">Shift</label>
                                                <input type="text" name="shift" class="form-control" value="<?php echo htmlentities($data['shift']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="available">Available</label>
                                                <select name="available" class="form-control" required>
                                                    <option value="1" <?php if ($data['available'] == 1) echo "selected"; ?>>Yes</option>
                                                    <option value="0" <?php if ($data['available'] == 0) echo "selected"; ?>>No</option>
                                                </select>
                                            </div>
                                        <?php } ?>

                                            <button type="submit" name="submit" class="btn btn-o btn-primary">Update</button>
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
</div>

<?php include('include/footer.php'); ?>
<?php include('include/setting.php'); ?>
</div>

<!-- JS Scripts -->
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
    jQuery(document).ready(function() {
        Main.init();
        FormElements.init();
    });
</script>

</body>
</html>
<?php } ?>
