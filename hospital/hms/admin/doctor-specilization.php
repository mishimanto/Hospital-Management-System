<?php
session_start();
include('include/config.php');

// Check if admin is logged in
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    $doctorspecilization = trim($_POST['doctorspecilization']);
    
    // Validate input
    if (!empty($doctorspecilization)) {
        // Use prepared statement to prevent SQL injection
        $stmt = $con->prepare("INSERT INTO doctorspecilization(specilization) VALUES(?)");
        $stmt->bind_param("s", $doctorspecilization);
        
        if ($stmt->execute()) {
            $_SESSION['msg'] = "Doctor Specialization added successfully!";
        } else {
            $_SESSION['msg'] = "Error adding specialization: " . $con->error;
        }
        $stmt->close();
    } else {
        $_SESSION['msg'] = "Please enter a specialization name";
    }
}

// Handle deletion
if (isset($_GET['del']) && isset($_GET['id'])) {
    $sid = (int)$_GET['id'];
    
    // Verify the record exists first
    $check = $con->query("SELECT id FROM doctorspecilization WHERE id = $sid");
    if ($check->num_rows > 0) {
        if ($con->query("DELETE FROM doctorspecilization WHERE id = $sid")) {
            $_SESSION['msg'] = "Specialization deleted successfully!";
        } else {
            $_SESSION['msg'] = "Error deleting specialization: " . $con->error;
        }
    } else {
        $_SESSION['msg'] = "Specialization not found!";
    }
    $check->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Doctor Specialization</title>
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
        <?php include('include/sidebar.php');?>
        <div class="app-content">
            <?php include('include/header.php');?>
            
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Admin | Add Doctor Specialization</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Add Doctor Specialization</span></li>
                            </ol>
                        </div>
                    </section>

                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (isset($_SESSION['msg'])): ?>
                                    <div class="alert alert-info">
                                        <?php 
                                        echo htmlentities($_SESSION['msg']); 
                                        unset($_SESSION['msg']);
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="row margin-top-30">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="panel panel-white">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Doctor Specialization</h5>
                                            </div>
                                            <div class="panel-body">
                                                <form role="form" name="dcotorspcl" method="post">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Doctor Specialization</label>
                                                        <input type="text" name="doctorspecilization" class="form-control" placeholder="Enter Doctor Specialization" required>
                                                    </div>
                                                    <button type="submit" name="submit" class="btn btn-o btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Doctor Specialization</span></h5>
                                        
                                        <table class="table table-hover" id="sample-table-1">
                                            <thead>
                                                <tr>
                                                    <th class="center">#</th>
                                                    <th>Specialization</th>
                                                    <th class="hidden-xs">Creation Date</th>
                                                    <th>Updation Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = $con->query("SELECT * FROM doctorspecilization");
                                                $cnt = 1;
                                                while ($row = $sql->fetch_assoc()):
                                                ?>
                                                <tr>
                                                    <td class="center"><?php echo $cnt; ?>.</td>
                                                    <td class="hidden-xs"><?php echo htmlspecialchars($row['specilization']); ?></td>
                                                    <td><?php echo $row['creationDate']; ?></td>
                                                    <td><?php echo $row['updationDate'] ?: 'N/A'; ?></td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                            <a href="edit-doctor-specialization.php?id=<?php echo $row['id']; ?>" class="btn btn-transparent btn-xs" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a href="doctor-specilization.php?id=<?php echo $row['id']; ?>&del=delete" 
                                                               onclick="return confirm('Are you sure you want to delete this specialization?')" 
                                                               class="btn btn-transparent btn-xs" title="Remove">
                                                                <i class="fa fa-times"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php 
                                                $cnt++;
                                                endwhile; 
                                                ?>
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
        
        <?php include('include/footer.php');?>
        <?php include('include/setting.php');?>
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
        jQuery(document).ready(function() {
            Main.init();
            FormElements.init();
        });
    </script>
</body>
</html>