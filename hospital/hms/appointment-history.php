<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

if (isset($_GET['cancel']) && isset($_GET['id'])) {
    $appointmentId = intval($_GET['id']);

    if (!isset($con) || !($con instanceof mysqli)) {
        $_SESSION['msg'] = "Database connection error!";
        header("Location: appointment-history.php");
        exit();
    }

    // Fetch appointment to verify ownership
    $getQuery = mysqli_query($con, "SELECT * FROM appointment WHERE id='$appointmentId' AND userId='".$_SESSION['id']."'");

    if (!$getQuery) {
        $_SESSION['msg'] = "Database error: " . mysqli_error($con);
        header("Location: appointment-history.php");
        exit();
    }

    $appointment = mysqli_fetch_assoc($getQuery);

    if ($appointment) {
        // Set userStatus = 0 (cancelled by user) and optionally set status='Cancelled'
        $updateQuery = mysqli_query($con, "UPDATE appointment 
            SET userStatus = 0 
            WHERE id='$appointmentId'");

        if ($updateQuery) {
            $_SESSION['msg'] = "Your appointment was successfully cancelled!";
        } else {
            $_SESSION['msg'] = "Failed to cancel appointment: " . mysqli_error($con);
        }

    } else {
        $_SESSION['msg'] = "Appointment not found or you don't have permission to cancel it!";
    }

    header("Location: appointment-history.php");
    exit();
}

// Get current date
$today = date('Y-m-d');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Appointment History</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">

    <style>
        .logout-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #ff7675, #d63031);
            color: #fff;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            z-index: 1100;
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #d63031, #ff7675);
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
        }
        
        .appointment-section {
            margin-bottom: 2rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .section-header {
            background-color: #f8f9fa;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid #dee2e6;
            font-weight: bold;
        }
        
        .today-header {
            background-color: #d4edda;
            color: #155724;
        }
        
        .previous-header {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .upcoming-header {
            background-color: #cce5ff;
            color: #004085;
        }
        
        .no-appointments {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
        }

        @media (max-width: 767.98px) {
          .wrap-content {
            padding: 1rem !important;
          }

          #page-title h1 {
            font-size: 1.5rem;
          }

          .breadcrumb {
            justify-content: center;
            flex-wrap: wrap;
            font-size: 0.875rem;
          }

          .table-responsive {
            overflow-x: auto;
          }

          .table th, .table td {
            white-space: nowrap;
            font-size: 0.85rem;
            vertical-align: middle;
          }

          .logout-btn {
            right: 1rem;
            bottom: 1rem;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
          }

          .section-header {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
          }

          .btn-sm {
            font-size: 0.75rem;
            padding: 0.3rem 0.5rem;
          }

          .no-appointments {
            font-size: 0.85rem;
          }
        }

    </style>
</head>

<body>

<!-- Header -->
    <?php include_once('include/header_logins_page.php'); ?>


<div class="app-content">
    <div class="main-content" style="min-height: 100vh;">
        <div class="wrap-content container py-4">
            <section id="page-title">
                <div class="row mb-3">
                    <!-- <div class="col-sm-8">
                        <h2 class="mainTitle">My Appointments</h2>
                    </div>
                    <ol class="breadcrumb col-sm-4 justify-content-end">
                        <li class="breadcrumb-item text-decoration-none"><a href="#">User</a></li>
                        <li style="color: blue;" class="breadcrumb-item active">Appointment History</li>
                    </ol> -->
                </div>
            </section>

            <div class="container-fluid">
                <?php if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) : ?>
                    <div class="alert alert-info text-center"><?php echo htmlentities($_SESSION['msg']); ?></div>
                    <?php unset($_SESSION['msg']); ?>
                <?php endif; ?>

                <?php
                // Verify database connection exists
                if (!isset($con) || !($con instanceof mysqli)) {
                    echo "<div class='alert alert-danger'>Database connection error!</div>";
                } else {
                    $userId = intval($_SESSION['id']);
                    
                    // 1. Today's Appointments
                    $todayQuery = mysqli_query($con, 
                        "SELECT doctors.doctorName as docname, appointment.* 
                         FROM appointment 
                         JOIN doctors ON doctors.id=appointment.doctorId 
                         WHERE appointment.userId='$userId' 
                         AND DATE(appointment.appointmentDate) = '$today'
                         ORDER BY appointment.appointment_number ASC");
                    
                    // 2. Previous Appointments (before today)
                    $previousQuery = mysqli_query($con, 
                        "SELECT doctors.doctorName as docname, appointment.* 
                         FROM appointment 
                         JOIN doctors ON doctors.id=appointment.doctorId 
                         WHERE appointment.userId='$userId' 
                         AND DATE(appointment.appointmentDate) < '$today'
                         ORDER BY appointment.appointment_number DESC");
                    
                    // 3. Upcoming Appointments (after today)
                    $upcomingQuery = mysqli_query($con, 
                        "SELECT doctors.doctorName as docname, appointment.* 
                         FROM appointment 
                         JOIN doctors ON doctors.id=appointment.doctorId 
                         WHERE appointment.userId='$userId' 
                         AND DATE(appointment.appointmentDate) > '$today'
                         ORDER BY appointment.appointment_number ASC");
                ?>
                
                <!-- Today's Appointments Section -->
                <div class="appointment-section mb-4">
                    <div class="section-header today-header">
                        <i class="fas fa-calendar-day me-2"></i> Today's Appointments
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Apt. Number</th>
                                    <th>Doctor Name</th>
                                    <th>Specialization</th>
                                    <th>Consultancy Fee</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Slip</th>
                                    <th>Prescription</th> <!-- New column -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!$todayQuery) {
                                    echo "<tr><td colspan='8' class='text-center text-danger'>Error fetching today's appointments: " . mysqli_error($con) . "</td></tr>";
                                } else {
                                    $todayCount = 0;
                                    while ($row = mysqli_fetch_array($todayQuery)) {
                                        $todayCount++;
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $todayCount; ?>.</td>
                                            <td><?php echo htmlentities($row['appointment_number']); ?></td>
                                            <td class="text-start"><?php echo htmlentities($row['docname']); ?></td>
                                            <td><?php echo htmlentities($row['doctorSpecialization']); ?></td>
                                            <td><?php echo htmlentities($row['consultancyFees']); ?></td>
                                            <td><?php echo date('h:i A', strtotime($row['appointmentTime'])); ?></td>
                                            <td>
                                                <?php
                                                if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1)
                                                    echo "<span class='text-success fw-bold'>Active</span>";
                                                elseif ($row['userStatus'] == 0 && $row['doctorStatus'] == 1)
                                                    echo "<span class='text-danger fw-bold'>Cancelled by You</span>";
                                                elseif ($row['userStatus'] == 1 && $row['doctorStatus'] == 0)
                                                    echo "<span class='text-danger fw-bold'>Cancelled by Doctor</span>";
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) { ?>
                                                    <a href="appointment-history.php?id=<?php echo $row['id']; ?>&cancel=1"
                                                       onClick="return confirm('Are you sure you want to cancel this appointment? This will make the time slot available for others.')"
                                                       class="btn btn-danger btn-sm">Cancel</a>
                                                <?php } else {
                                                    echo "Cancelled";
                                                } ?>
                                            </td>
                                            <td>
                                                <?php if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) { ?>
                                                    <a href="download_slip.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                <?php } else {
                                                    echo "N/A";
                                                } ?>
                                            </td>
                                            <td>
                                                <?php if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) { 
                                                    // Check if prescription exists in tblpatient
                                                    $prescQuery = mysqli_query($con, "SELECT ID FROM tblpatient WHERE appointment_number='".$row['appointment_number']."' AND (Prescription != '' OR Tests != '')");
                                                    if(mysqli_num_rows($prescQuery) > 0) { ?>
                                                        <a href="download_prescription.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                                            <i class="fas fa-file-prescription"></i> Prescription
                                                        </a>
                                                    <?php } else {
                                                        echo "N/A";
                                                    }
                                                } else {
                                                    echo "N/A";
                                                } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    if ($todayCount == 0) {
                                        echo "<tr><td colspan='10' class='no-appointments'>No appointments for today</td></tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Upcoming Appointments Section -->
                <div class="appointment-section mb-4">
                    <div class="section-header upcoming-header">
                        <i class="fas fa-calendar-plus me-2"></i> Upcoming Appointments
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Apt. Number</th>
                                    <th>Doctor Name</th>
                                    <th>Specialization</th>
                                    <th>Consultancy Fee</th>
                                    <th>Date - Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                    <th>Slip</th>
                                    <!-- <th>Prescription</th> --> <!-- New column -->
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                if (!$upcomingQuery) {
                                    echo "<tr><td colspan='8' class='text-center text-danger'>Error fetching upcoming appointments: " . mysqli_error($con) . "</td></tr>";
                                } else {
                                    $upcomingCount = 0;
                                    while ($row = mysqli_fetch_array($upcomingQuery)) {
                                        $upcomingCount++;
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $upcomingCount; ?>.</td>
                                            <td><?php echo htmlentities($row['appointment_number']); ?></td>
                                            <td class="text-start"><?php echo htmlentities($row['docname']); ?></td>
                                            <td><?php echo htmlentities($row['doctorSpecialization']); ?></td>
                                            <td><?php echo htmlentities($row['consultancyFees']); ?></td>
                                            <td>
                                                <?php 
                                                echo date('d M Y', strtotime($row['appointmentDate'])) . ' - ' . date('h:i A', strtotime($row['appointmentTime'])); 
                                                ?>
                                            </td>

                                            <td>
                                                <?php
                                                if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1)
                                                    echo "<span class='text-success fw-bold'>Active</span>";
                                                elseif ($row['userStatus'] == 0 && $row['doctorStatus'] == 1)
                                                    echo "<span class='text-danger fw-bold'>Cancelled by You</span>";
                                                elseif ($row['userStatus'] == 1 && $row['doctorStatus'] == 0)
                                                    echo "<span class='text-danger fw-bold'>Cancelled by Doctor</span>";
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) { ?>
                                                    <a href="appointment-history.php?id=<?php echo $row['id']; ?>&cancel=1"
                                                       onClick="return confirm('Are you sure you want to cancel this appointment? This will make the time slot available for others.')"
                                                       class="btn btn-danger btn-sm">Cancel</a>
                                                <?php } else {
                                                    echo "Cancelled";
                                                } ?>
                                            </td>
                                            <td>
                                                <?php if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) { ?>
                                                    <a href="download_slip.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                <?php } else {
                                                    echo "N/A";
                                                } ?>
                                            </td>
                                            <!-- In Upcoming Appointments table body (add this cell after the Slip cell) -->
                                            <!-- <td>
                                                <?php if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1 && !empty($row['Prescription'])) { ?>
                                                    <a href="download_prescription.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-file-prescription"></i> Download
                                                    </a>
                                                <?php } else {
                                                    echo "N/A";
                                                } ?>
                                            </td> -->
                                        </tr>
                                        <?php
                                    }
                                    
                                    if ($upcomingCount == 0) {
                                        echo "<tr><td colspan='9' class='no-appointments'>No upcoming appointments</td></tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Previous Appointments Section -->
                <div class="appointment-section">
                    <div class="section-header previous-header">
                        <i class="fas fa-history me-2"></i> Previous Appointments
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Apt. Number</th>
                                    <th>Doctor Name</th>
                                    <th>Specialization</th>
                                    <th>Consultancy Fee</th>
                                    <th>Date / Time</th>
                                    <th>Status</th>
                                    <!-- <th>Slip</th> -->
                                    <th>Prescription</th> <!-- New column -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!$previousQuery) {
                                    echo "<tr><td colspan='7' class='text-center text-danger'>Error fetching previous appointments: " . mysqli_error($con) . "</td></tr>";
                                } else {
                                    $previousCount = 0;
                                    while ($row = mysqli_fetch_array($previousQuery)) {
                                        $previousCount++;
                                        ?>
                                        <tr class="text-center">
                                            <td><?php echo $previousCount; ?>.</td>
                                            <td><?php echo htmlentities($row['appointment_number']); ?></td>
                                            <td class="text-start"><?php echo htmlentities($row['docname']); ?></td>
                                            <td><?php echo htmlentities($row['doctorSpecialization']); ?></td>
                                            <td><?php echo htmlentities($row['consultancyFees']); ?></td>
                                            <td><?php echo date('d M Y h:i A', strtotime($row['appointmentDate'])); ?></td>
                                            <td>
                                                <?php
                                                if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1)
                                                    echo "<span class='text-success fw-bold'>Completed</span>";
                                                elseif ($row['userStatus'] == 0 && $row['doctorStatus'] == 1)
                                                    echo "<span class='text-danger fw-bold'>Cancelled by You</span>";
                                                elseif ($row['userStatus'] == 1 && $row['doctorStatus'] == 0)
                                                    echo "<span class='text-danger fw-bold'>Cancelled by Doctor</span>";
                                                ?>
                                            </td>
                                            <!-- <td>
                                                <?php if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) { ?>
                                                    <a href="download_slip.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-download"></i> Download
                                                    </a>
                                                <?php } else {
                                                    echo "N/A";
                                                } ?>
                                            </td> -->
                                            <!-- In Previous Appointments table body (add this cell after the Slip cell) -->
                                            <td>
                                                <?php if ($row['userStatus'] == 1 && $row['doctorStatus'] == 1) { 
                                                    // Check if prescription exists in tblpatient
                                                    $prescQuery = mysqli_query($con, "SELECT ID FROM tblpatient WHERE appointment_number='".$row['appointment_number']."' AND (Prescription != '' OR Tests != '')");
                                                    if(mysqli_num_rows($prescQuery) > 0) { ?>
                                                        <a href="download_prescription.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                                            <i class="fas fa-file-prescription"></i> Prescription
                                                        </a>
                                                    <?php } else {
                                                        echo "N/A";
                                                    }
                                                } else {
                                                    echo "N/A";
                                                } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    if ($previousCount == 0) {
                                        echo "<tr><td colspan='8' class='no-appointments'>No previous appointments</td></tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Floating Logout Button -->
    <!-- <a href="logout.php" class="logout-btn">
        <i class="fas fa-sign-out-alt"></i> Logout
    </a> -->

</div>

<?php 
// Verify footer include file exists
if (file_exists('include/footer.php')) {
    include('include/footer.php'); 
}
?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>