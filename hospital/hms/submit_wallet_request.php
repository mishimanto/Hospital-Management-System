<?php
session_start();
include('include/config.php');

$user_id = $_SESSION['id'];
$amount = $_POST['amount'];
$payment_method = $_POST['payment_method'];
$contact = $_POST['mobile_number'];
$pin = $_POST['transaction_pin'];

// Generate a unique transaction ID
$tnx_id = 'TXN' . time() . rand(100, 999);

// Get userName from users table
$user_result = mysqli_query($con, "SELECT fullName FROM users WHERE id='$user_id'");

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_data = mysqli_fetch_assoc($user_result);
    $userName = $user_data['fullName'];

    // Insert into wallet_requests with all fields
    $insert = mysqli_query($con, "INSERT INTO wallet_requests 
                                (user_id, userName, amount, payment_method, contact_no, pin, tnx_id, status) 
                                VALUES('$user_id', '$userName', '$amount', '$payment_method', '$contact', '$pin', '$tnx_id', 'Requested')");

    if($insert) {
        // Send email with transaction ID
        $email_result = mysqli_query($con, "SELECT email FROM users WHERE id='$user_id'");
        $email_data = mysqli_fetch_assoc($email_result);
        $email = $email_data['email'];
        
        $subject = "Your Wallet Transaction ID";
        $message = "
            <h2>Wallet Transaction Details</h2>
            <p>Thank you for your payment request. Here are your transaction details:</p>
            <p><strong>Amount:</strong> ৳".number_format($amount, 2)."</p>
            <p><strong>Payment Method:</strong> $payment_method</p>
            <p><strong>Transaction ID:</strong> $tnx_id</p>
            <p>Please verify your transaction by entering this ID on the verification page.</p>
            <p><a href='http://shimzo.xyz/hms/verify_transaction.php'>Click here to verify</a></p>
        ";
        
        // Include the class file and create USER object
        require_once 'class.user.php';
        $user = new USER();
        
        // Send email using the class method
        $mailSent = $user->sendMail($email, $message, $subject);
        
        if($mailSent) {
            $message = "Your request for ৳".number_format($amount, 2)." is submitted successfully. Please Check your email for transaction id";
            $alertType = "success";
        } else {
            $message = "Request submitted but email could not be sent. Please note your transaction ID: $tnx_id";
            $alertType = "error";
        }
        
        // Store transaction ID in session for verification
        $_SESSION['tnx_id'] = $tnx_id;
    } else {
        $message = "Failed to submit request. Please try again.";
        $alertType = "error";
    }
} else {
    $message = "User not found. Please login again.";
    $alertType = "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Status</title>
    <style>
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 30px;
            border-radius: 5px;
            color: white;
            font-family: 'Arial', sans-serif;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s forwards;
        }
        .alert.success {
            background-color: #4CAF50;
            border-left: 5px solid #388E3C;
        }
        .alert.error {
            background-color: #F44336;
            border-left: 5px solid #D32F2F;
        }
        .alert-icon {
            margin-right: 15px;
            font-size: 24px;
        }
        @keyframes slideIn {
            from { top: -100px; opacity: 0; }
            to { top: 20px; opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; visibility: hidden; }
        }
    </style>
</head>
<body>
    <div class="alert <?php echo $alertType; ?>">
        <span class="alert-icon"><?php echo $alertType === 'success' ? '✓' : '✗'; ?></span>
        <?php echo $message; ?>
    </div>

    <script>
        setTimeout(function() {
            window.location.href = 'verify_transaction.php';
        }, 4000);
    </script>
</body>
</html>