<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

require_once 'class.user.php';
$user = new USER();

if(isset($_POST['submit'])) {
    $testIds = $_POST['test_id'];
    $userid = $_SESSION['id'];
    $appdate = $_POST['appdate'];
    $apptime = $_POST['apptime'];
    
    // Validate tests selected
    if(empty($testIds)) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'No Tests Selected',
            'message' => 'Please select at least one test to proceed.'
        ];
        header("Location: book_test.php");
        exit();
    }
    
    // Get user wallet balance
    $user_query = mysqli_query($con, "SELECT wallet_balance, email, fullName FROM users WHERE id = '$userid'");
    $user_data = mysqli_fetch_assoc($user_query);
    $wallet_balance = $user_data['wallet_balance'];
    $email = $user_data['email'];
    $fullName = $user_data['fullName'];
    
    // Calculate total amount
    $total_amount = 0;
    $test_details = [];
    foreach($testIds as $testId) {
        $test_query = mysqli_query($con, "SELECT * FROM diagnostic_tests WHERE id = '$testId'");
        if($test = mysqli_fetch_assoc($test_query)) {
            $total_amount += $test['price'];
            $test_details[] = $test;
        }
    }
    
    // Check wallet balance
    if($wallet_balance < $total_amount) {
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Insufficient Balance',
            'message' => 'You don\'t have enough balance in your wallet. Please recharge your wallet.'
        ];
        header("Location: book_test.php");
        exit();
    }
    
    // Start transaction
    mysqli_begin_transaction($con);
    
    try {
        // Deduct from wallet
        $update_wallet = mysqli_query($con, "UPDATE users SET wallet_balance = wallet_balance - $total_amount WHERE id = '$userid'");
        if(!$update_wallet) throw new Exception("Wallet deduction failed");
        
        // Create test order
        $order_number = 'TST-'.date('Ymd').'-'.strtoupper(substr(md5(uniqid()), 0, 6));
        $insert_order = mysqli_query($con, "INSERT INTO test_orders (
            order_number, user_id, total_amount, payment_method, payment_status, status, test_date, test_time, created_at
        ) VALUES (
            '$order_number', '$userid', '$total_amount', 'Wallet', 'Paid', 'Pending', '$appdate', '$apptime', NOW()
        )");
        
        if(!$insert_order) throw new Exception("Failed to create test order");
        
        $order_id = mysqli_insert_id($con);
        
        // Add ordered tests
        foreach($test_details as $test) {
            $insert_test = mysqli_query($con, "INSERT INTO ordered_tests (
                order_id, test_id, price, status
            ) VALUES (
                '$order_id', '".$test['id']."', '".$test['price']."', 'Pending'
            )");
            
            if(!$insert_test) throw new Exception("Failed to add test to order");
        }
        
        // Generate PDF slip
        require_once 'generate_test_pdf.php';
        $pdfPath = generateTestPDF($order_id, $con);
        
        // Commit transaction
        mysqli_commit($con);
        
        // Send confirmation email
        $subject = "Test Appointment Confirmation";
        $message = "
            <h2>Test Appointment Confirmation</h2>
            <p>Dear $fullName,</p>
            <p>Your diagnostic tests have been successfully booked:</p>
            <table border='1' cellpadding='5' cellspacing='0'>
                <tr><th style='text-align: left;'>Order Number</th><td>$order_number</td></tr>
                <tr><th style='text-align: left;'>Date</th><td>$appdate</td></tr>
                <tr><th style='text-align: left;'>Time</th><td>$apptime</td></tr>
                <tr><th style='text-align: left;'>Total Amount</th><td>$total_amount BDT</td></tr>
                <tr><th style='text-align: left;'>Payment</th><td>Paid (Wallet)</td></tr>
            </table>
            <h3>Tests Booked:</h3>
            <ul>
        ";
        
        foreach($test_details as $test) {
            $message .= "<li>{$test['name']} - {$test['price']} BDT</li>";
        }
        
        $message .= "
            </ul>
            <p>Your test slip is attached. Please bring it with you. Thank you for choosing MEDIZEN.</p>
        ";
        
        if($user->sendMail($email, $message, $subject, $pdfPath)) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Test Booking Confirmed!',
                'message' => 'Your tests have been booked. Confirmation email sent.'
            ];
        } else {
            $_SESSION['alert'] = [
                'type' => 'success',
                'title' => 'Test Booking Confirmed!',
                'message' => 'Your tests have been booked. <a href=\'download_test_slip.php?id='.$order_id.'\'>Download Slip</a>'
            ];
        }
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Booking Failed',
            'message' => 'Error: '.$e->getMessage()
        ];
    }
    
    header("Location: book_test.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Book Diagnostic Test</title>
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

        .test-card {
            background: var(--bg-card);
            border-radius: var(--radius);
            padding: 35px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .test-card h5 {
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 1.25rem;
        }

        form label {
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--dark);
            font-size: 20px;
        }

        .form-control, .form-select {
            border-radius: var(--radius);
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.15rem rgba(32, 201, 151, 0.25);
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

        .test-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .test-item:hover {
            border-color: var(--primary);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .test-item.selected {
            background-color: #f0f8ff;
            border-color: var(--primary);
        }

        .test-checkbox {
            transform: scale(1.3);
            margin-right: 10px;
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

        .time-slot {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .time-slot:hover {
            background-color: #f0f0f0;
        }

        .time-slot.selected {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .time-slot.disabled {
            background-color: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
        }

        .total-amount {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="test-card">
                        <h1 class="mainTitle text-center">Book Diagnostic Test</h1>
                        
                        <?php if(isset($_SESSION['msg1'])): ?>
                            <div class="alert alert-danger"><?php echo htmlentities($_SESSION['msg1']); ?></div>
                            <?php unset($_SESSION['msg1']); ?>
                        <?php endif; ?>

                        <form role="form" name="book_test" method="post">
                            <!-- Wallet Balance Display -->
                            <div class="wallet-section mb-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i style="color: #00b894;" class="fas fa-wallet me-2"></i>
                                        <span>Wallet Balance:</span>
                                    </div>
                                    <div class="wallet-balance">
                                        <?php 
                                            $stmt = $con->prepare("SELECT wallet_balance FROM users WHERE id=?");
                                            $stmt->bind_param("i", $_SESSION['id']);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $user_data = $result->fetch_assoc();
                                            $balance = $user_data['wallet_balance'] ?? 0;
                                            echo number_format($balance, 2).' BDT';
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Test Categories and Tests -->
                            <div class="mb-4">
                                <h4 class="mb-3"><i class="fas fa-vial me-2"></i> Select Tests</h4>
                                
                                <div class="row">
                                    <!-- Categories -->
                                    <div class="col-md-4">
                                        <div class="list-group">
                                            <?php
                                            $cat_query = mysqli_query($con, "SELECT * FROM test_categories");
                                            while($category = mysqli_fetch_assoc($cat_query)):
                                            ?>
                                            <a href="#" class="list-group-item list-group-item-action category-filter" 
                                               data-category="<?php echo $category['id']; ?>">
                                                <?php echo htmlentities($category['name']); ?>
                                            </a>
                                            <?php endwhile; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Tests -->
                                    <div class="col-md-8">
                                        <div id="tests-container">
                                            <?php
                                            $test_query = mysqli_query($con, "SELECT t.*, c.name as category_name 
                                                                             FROM diagnostic_tests t
                                                                             JOIN test_categories c ON t.category_id = c.id");
                                            while($test = mysqli_fetch_assoc($test_query)):
                                            ?>
                                            <div class="test-item" data-category="<?php echo $test['category_id']; ?>">
                                                <div class="form-check">
                                                    <input class="form-check-input test-checkbox" type="checkbox" 
                                                           name="test_id[]" id="test_<?php echo $test['id']; ?>" 
                                                           value="<?php echo $test['id']; ?>"
                                                           data-price="<?php echo $test['price']; ?>">
                                                    <label class="form-check-label" for="test_<?php echo $test['id']; ?>">
                                                        <strong><?php echo htmlentities($test['name']); ?></strong> - 
                                                        <?php echo number_format($test['price'], 2); ?> BDT
                                                        <br>
                                                        <small class="text-muted"><?php echo htmlentities($test['category_name']); ?></small>
                                                    </label>
                                                </div>
                                                <div class="test-details mt-2" style="display: none;">
                                                    <small><strong>Description:</strong> <?php echo htmlentities($test['description']); ?></small><br>
                                                    <small><strong>Preparation:</strong> <?php echo htmlentities($test['preparation']); ?></small>
                                                </div>
                                            </div>
                                            <?php endwhile; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Tests Summary -->
                            <div class="mb-4" id="selected-tests" style="display: none;">
                                <h4><i class="fas fa-list me-2"></i> Selected Tests</h4>
                                <div id="selected-tests-list" class="mb-2"></div>
                                <div class="total-amount">Total: <span id="total-amount">0.00</span> BDT</div>
                            </div>

                            <!-- Appointment Date -->
                            <div class="mb-4">
                                <h4 class="mb-3"><i class="far fa-calendar-alt me-2"></i> Select Date</h4>
                                <input type="text" class="form-control datepicker" name="appdate" id="appdate" required 
                                       data-date-format="yyyy-mm-dd" readonly>
                            </div>

                            <!-- Time Slots -->
                            <div class="mb-4">
                                <h4 class="mb-3"><i class="far fa-clock me-2"></i> Select Time Slot</h4>
                                <div id="time-slots">
                                    <!-- Time slots will be generated here by JavaScript -->
                                </div>
                                <input type="hidden" name="apptime" id="selected-time">
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center mt-4">
                                <button type="submit" name="submit" class="btn btn-submit">
                                    <i class="fas fa-paper-plane me-2"></i> Book Tests
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        // Initialize datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            startDate: '+1d',
            endDate: '+14d',
            autoclose: true,
            todayHighlight: true,
            daysOfWeekDisabled: [0], // Disable Sundays
            beforeShowDay: function(date) {
                var day = date.getDay();
                return {
                    enabled: day !== 0 // Disable Sundays
                };
            }
        }).on('changeDate', function(e) {
            generateTimeSlots();
        });

        // Category filter
        $('.category-filter').click(function(e) {
            e.preventDefault();
            var categoryId = $(this).data('category');
            
            $('.category-filter').removeClass('active');
            $(this).addClass('active');
            
            if(categoryId === 'all') {
                $('.test-item').show();
            } else {
                $('.test-item').hide();
                $('.test-item[data-category="'+categoryId+'"]').show();
            }
        });

        // Show first category by default
        $('.category-filter:first').click();

        // Test selection handler
        $('.test-checkbox').change(function() {
            updateSelectedTests();
        });

        // Toggle test details
        $('.test-item').click(function(e) {
            if(!$(e.target).is('input')) {
                $(this).find('.test-details').toggle();
            }
        });

        // Generate time slots (every 20 minutes from 9AM to 1PM)
        function generateTimeSlots() {
            var selectedDate = $('#appdate').val();
            if(!selectedDate) return;

            var today = new Date();
            var selected = new Date(selectedDate);
            var isToday = selected.toDateString() === today.toDateString();
            var currentHour = today.getHours();
            var currentMinute = today.getMinutes();

            var slots = '';
            var startHour = 9; // 9 AM
            var endHour = 13;  // 1 PM
            var interval = 20; // 20 minutes

            for(var hour = startHour; hour < endHour; hour++) {
                for(var minute = 0; minute < 60; minute += interval) {
                    if(isToday) {
                        // For today, disable past time slots
                        if(hour < currentHour || (hour === currentHour && minute < currentMinute)) {
                            slots += `<span class="time-slot disabled">${formatTime(hour, minute)}</span>`;
                        } else {
                            slots += `<span class="time-slot" data-time="${formatTime(hour, minute)}">${formatTime(hour, minute)}</span>`;
                        }
                    } else {
                        slots += `<span class="time-slot" data-time="${formatTime(hour, minute)}">${formatTime(hour, minute)}</span>`;
                    }
                }
            }

            $('#time-slots').html(slots);
            $('.time-slot:not(.disabled)').click(function() {
                $('.time-slot').removeClass('selected');
                $(this).addClass('selected');
                $('#selected-time').val($(this).data('time'));
            });
        }

        function formatTime(hour, minute) {
            var ampm = hour >= 12 ? 'PM' : 'AM';
            var displayHour = hour % 12;
            displayHour = displayHour ? displayHour : 12; // the hour '0' should be '12'
            return displayHour + ':' + (minute < 10 ? '0' + minute : minute) + ' ' + ampm;
        }

        // Update selected tests summary
        function updateSelectedTests() {
            var selectedTests = [];
            var totalAmount = 0;
            
            $('.test-checkbox:checked').each(function() {
                var testId = $(this).val();
                var testName = $(this).closest('.test-item').find('label').text().split(' - ')[0];
                var testPrice = parseFloat($(this).data('price'));
                
                selectedTests.push(testName);
                totalAmount += testPrice;
            });
            
            if(selectedTests.length > 0) {
                $('#selected-tests').show();
                $('#selected-tests-list').html('<ul><li>' + selectedTests.join('</li><li>') + '</li></ul>');
                $('#total-amount').text(totalAmount.toFixed(2));
            } else {
                $('#selected-tests').hide();
            }
        }

        // Form validation
        $('form[name="book_test"]').submit(function(e) {
            var selectedTests = $('.test-checkbox:checked').length;
            var selectedDate = $('#appdate').val();
            var selectedTime = $('#selected-time').val();
            
            if(selectedTests === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'No Tests Selected',
                    text: 'Please select at least one test to proceed.',
                    confirmButtonColor: '#20c997'
                });
                return false;
            }
            
            if(!selectedDate) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'No Date Selected',
                    text: 'Please select an appointment date.',
                    confirmButtonColor: '#20c997'
                });
                return false;
            }
            
            if(!selectedTime) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'No Time Selected',
                    text: 'Please select an appointment time.',
                    confirmButtonColor: '#20c997'
                });
                return false;
            }
            
            // Check wallet balance
            var totalAmount = parseFloat($('#total-amount').text());
            var balanceText = $('.wallet-balance').text().trim();
            var balance = parseFloat(balanceText.replace(/[^\d.]/g, ''));
            
            if(totalAmount > balance) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Balance',
                    html: `You don't have enough balance in your wallet.<br>Required: ${totalAmount.toFixed(2)} BDT<br>Available: ${balance.toFixed(2)} BDT`,
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
</body>
</html>