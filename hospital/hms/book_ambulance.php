<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();
require_once 'class.user.php';
$user = new USER();

$user_id = $_SESSION['id'] ?? null;

$ambulanceQuery = mysqli_query($con, "SELECT * FROM ambulances WHERE status = 'available' LIMIT 1");
$ambulance = mysqli_fetch_assoc($ambulanceQuery);

$booking_fee = $ambulance['fee'] ?? 0;
$ambulance_number = $ambulance['ambulance_number'] ?? '';

$userQuery = mysqli_query($con, "SELECT wallet_balance FROM users WHERE id = '$user_id'");
$userData = mysqli_fetch_assoc($userQuery);
$wallet = $userData['wallet_balance'] ?? 0;

$ambulanceQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM ambulances WHERE status = 'available'");
$ambulance = mysqli_fetch_assoc($ambulanceQuery);
$availableAmbulance = $ambulance['total'];

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $location = mysqli_real_escape_string($con, $_POST['location']);

    if ($booking_fee > 0 && $wallet >= $booking_fee) {
        mysqli_query($con, "UPDATE users SET wallet_balance = wallet_balance - $booking_fee WHERE id = '$user_id'");
        mysqli_query($con, "UPDATE ambulances SET status = 'booked' WHERE status = 'available' LIMIT 1");
        mysqli_query($con, "INSERT INTO ambulance_bookings(user_id, ambulance_number, pickup_location, cost, status) VALUES ('$user_id', '$ambulance_number', '$location', '$booking_fee', 'Paid')");

        $user_query = mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'");
        $user_data = mysqli_fetch_assoc($user_query);

        $ambulance_query = mysqli_query($con, "SELECT * FROM ambulance_bookings WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1");
        $ambulance_data = mysqli_fetch_assoc($ambulance_query);

        $email = $user_data['email'];
        $subject = "Ambulance Booked";

        $message_body = "
            <h2>Ambulance booked successfully</h2>
            <p>Dear ".$user_data['fullName'].",</p>
            <p>Your booked ambulance's details:</p>
            <table border='1' cellpadding='5' cellspacing='0'>
                <tr><th>Contact No</th><td>".$ambulance_data['ambulance_number']."</td></tr>
                <tr><th>Pickup Location</th><td>".$ambulance_data['pickup_location']."</td></tr>
                <tr><th>Date</th><td>".date('h:i A', strtotime($ambulance_data['booking_time']))."</td></tr>
                <tr><th>Fees</th><td>".$ambulance_data['cost']." BDT</td></tr>
                <tr><th>Payment Status</th><td>Paid (via Wallet)</td></tr>
            </table>
            <p>Your ambulance will arrive soon. Please be ready at your pickup location.</p>
            <p>Thank you for choosing our service.</p>
        ";

        if($user->sendMail($email, $message_body, $subject)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Ambulance Booked!',
                'message' => 'Your ambulance has been successfully booked. A confirmation email has been sent.'
            ];
        }

        $wallet -= $booking_fee;
        $message = "<div class='alert alert-success mt-3'><i class='fas fa-check-circle me-2'></i>Ambulance booked successfully!</div>";
    } elseif ($booking_fee > 0) {
        $message = "<div class='alert alert-danger mt-3'><i class='fas fa-times-circle me-2'></i>Insufficient wallet balance to book an ambulance.</div>";
    } else {
        $message = "<div class='alert alert-warning mt-3'><i class='fas fa-exclamation-triangle me-2'></i>No ambulances available at the moment.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Book Ambulance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ancizar+Serif:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">

    <style>
        :root {
            --primary: #0866ff;
            --primary-dark: #1877f2;
            --dark: #212529;
            --light: #f8f9fa;
            --danger: #dc3545;
            --muted: #6c757d;
            --border-color: #dee2e6;
            --bg-card: #ffffff;
            --shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            --radius: 12px;
            --ambulance-red: #d90429;
        }

        body {
            font-family: 'Ancizar Serif', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            font-size: 16px;
        }

        .container {
            padding: 50px 0;
        }

        .mainTitle {
            font-weight: 700;
            color: var(--dark);
            font-size: 2.25rem;
            margin-bottom: 30px;
        }

        .appointment-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 35px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        .appointment-card h5 {
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.25rem;
        }

        form label {
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--dark);
            font-size: 1rem;
        }

        .form-control, .form-select {
            border-radius: var(--radius);
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.15rem rgba(8, 102, 255, 0.25);
        }

        .form-select:disabled, .form-control:disabled {
            background-color: #e9ecef;
        }

        .btn-submit {
            background: var(--primary);
            color: #fff;
            padding: 14px 32px;
            border: none;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(8, 102, 255, 0.3);
        }

        .btn-submit:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(24, 119, 242, 0.4);
        }

        .alert {
            border-radius: var(--radius);
            padding: 12px 16px;
            font-weight: 500;
        }

        .is-invalid {
            border-color: var(--danger) !important;
            box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, 0.25) !important;
        }

        .wallet-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        
        .wallet-balance {
            font-weight: 700;
            color: #28a745;
            font-size: 1.1rem;
        }

        .info-box {
            background-color: #f8f9fa;
            border-radius: var(--radius);
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 1rem;
            border: 1px solid var(--border-color);
        }

        .ambulance-icon {
            color: var(--ambulance-red);
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .text-danger {
            color: var(--danger) !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-muted {
            color: var(--muted) !important;
        }

        @media (max-width: 768px) {
            .appointment-card {
                padding: 25px;
            }

            .mainTitle {
                font-size: 1.75rem;
            }
            
            .ambulance-icon {
                font-size: 2rem;
            }
        }

        /* Make the ambulance icon pulse */
        .fa-ambulance {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div id="app py-6">
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <div class="row row w-100 justify-content-center">
                <!-- Left Side: Page Title -->
                <div class="col-md-6 d-flex justify-content-center align-items-center">
                    <a style="text-decoration: none;" href="dashboard.php"><h1 style="color: var(--ambulance-red);" class="mainTitle">Book Ambulance</h1></a> 
                </div>

                <!-- Right Side: Form Card -->
                <div class="col-md-6">
                    <div class="appointment-card py-5">
                        <div class="card-body">
                            <?php if(isset($_SESSION['msg1'])): ?>
                                <div class="alert alert-danger"><?php echo htmlentities($_SESSION['msg1']); ?></div>
                                <?php unset($_SESSION['msg1']); ?>
                            <?php endif; ?>

                            <form role="form" name="book" method="post">
                                <!-- Wallet Balance Display -->
                                <div class="wallet-section mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i style="color: #00b894;" class="fas fa-wallet me-2"></i>
                                            <span>Wallet Balance:</span>
                                        </div>
                                        <div class="wallet-balance">
                                            <?= number_format($wallet, 2) ?> BDT
                                        </div>
                                    </div>
                                </div>

                                <!-- Available Ambulances -->
                                <div class="info-box d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <i style="color: var(--ambulance-red);" class="fas fa-ambulance me-2"></i>
                                        <span>Available Ambulances:</span>
                                    </div>
                                    <div class="<?= $availableAmbulance > 0 ? 'text-success' : 'text-danger' ?>">
                                        <b style="font-size: 1.1rem;"><?= $availableAmbulance ?></b>
                                    </div>
                                </div>

                                <!-- Fees -->
                                <div class="mb-3 row align-items-center">
                                    <label for="fees" class="col-sm-5">
                                        <i style="color: #00b894;" class="fa-solid fa-bangladeshi-taka-sign me-2"></i> Fees
                                    </label>
                                    <div class="col-sm-7">
                                        <select name="fees" class="form-control" id="fees" readonly>
                                            <option value=""><?= $booking_fee; ?></option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Pickup Location -->
                                <div class="mb-3 row align-items-center">
                                    <label for="location" class="col-sm-5">
                                        <i style="color: #00b894" class="fas fa-map-marker-alt me-2"></i> Pickup Location
                                    </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="location" name="location" placeholder="Enter your current location" required>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="mt-5">
                                    <button type="submit" name="submit" class="btn btn-submit btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i> Book Ambulance
                                    </button>
                                </div>

                                <?= $message ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        $(document).ready(function() {
            // Form submission handling
            $('form[name="book"]').submit(function(e) {
                let isValid = true;
                $('.is-invalid').removeClass('is-invalid');

                // Validate required fields
                $(this).find('[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Information',
                        text: 'Please fill all required fields',
                        confirmButtonColor: '#20c997'
                    });
                    return false;
                }
                
                // Check wallet balance
                const fee = parseFloat(<?= $booking_fee ?>);
                const balance = parseFloat(<?= $wallet ?>);
                
                if (fee > balance) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Insufficient Balance',
                        html: `You don't have enough balance in your wallet.<br>Required: ${fee.toFixed(2)} BDT<br>Available: ${balance.toFixed(2)} BDT`,
                        confirmButtonColor: '#20c997'
                    });
                    return false;
                }

                return true;
            });

            <?php if(isset($_SESSION['alert'])): ?>
            Swal.fire({
                icon: '<?php echo $_SESSION['alert']['type']; ?>',
                title: '<?php echo $_SESSION['alert']['title']; ?>',
                text: '<?php echo $_SESSION['alert']['message']; ?>',
                confirmButtonColor: '#20c997'
            }).then((result) => {
                if (result.isConfirmed) {
                    <?php if ($_SESSION['alert']['type'] == 'success'): ?>
                        window.location.href = 'dashboard.php';
                    <?php endif; ?>
                }
            });
            <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>
        });
        </script>
    </div>
</body>
</html>