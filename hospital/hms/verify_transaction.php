<?php
session_start();
include('include/config.php');

if (!isset($_SESSION['id'])) {
    header("Location: user-login.php");
    exit();
}

$user_id = $_SESSION['id'];
$message = '';
$alertType = '';


$user_query = mysqli_query($con, "SELECT fullName, email FROM users WHERE id = '$user_id'");
$user_data = mysqli_fetch_assoc($user_query);
$username = $user_data['fullName'];
$email = $user_data['email'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    $txn_id = mysqli_real_escape_string($con, $_POST['transaction_id']);

    try {
        
        $query = "SELECT * FROM wallet_requests 
                  WHERE user_id = '$user_id' 
                  AND tnx_id = '$txn_id' 
                  AND status = 'Requested' 
                  ORDER BY requested_at DESC LIMIT 1";

        $result = mysqli_query($con, $query);

        if (!$result) {
            throw new Exception("Database error: " . mysqli_error($con));
        }

        if (mysqli_num_rows($result) === 1) {
            $transaction = mysqli_fetch_assoc($result);

            $amount = $transaction['amount'];
            $payment_method = $transaction['payment_method'];
            $sender = $transaction['contact_no'];
            $payment_time = date('F j, Y \a\t g:i A', strtotime($transaction['requested_at']));

            
            mysqli_query($con, "UPDATE wallet_requests SET status = 'Pending' WHERE id = '{$transaction['id']}'");

           
            require_once 'class.user.php';
            $user = new USER();

            $subject = "Wallet Request Successful";
            $email_message = "
                <div style='font-family: Poppins, sans-serif; font-size: 15px;'>
                    <h2 style='color:#4a6bff;'>💼 Wallet requested</h2>
                    <p>Hi <strong>$username</strong>,</p>
                    <p>Your request for wallet balance has been successfully submitted. Details:</p>
                    <table cellpadding='10'>
                        <tr><td><strong>💳 Payment Method:</strong></td><td>$payment_method</td></tr>
                        <tr><td><strong>📱 Sender Number:</strong></td><td>$sender</td></tr>
                        <tr><td><strong>💰 Amount Requested:</strong></td><td>৳" . number_format($amount, 2) . "</td></tr>
                        <tr><td><strong>⏰ Time:</strong></td><td>$payment_time</td></tr>
                    </table>
                    <br><p>Thank you for using our service!</p>
                    <p>MEDIZEN</p>
                </div>";

            $user->sendMail($email, $email_message, $subject);

            $message = "✅ ৳" . number_format($amount, 2) . " requested from $sender at $payment_time. Please wait for approval. Thank You";
            $alertType = "success";
        } else {
            $message = "❌ Transaction not found or already verified.";
            $alertType = "error";
        }

    } catch (Exception $ex) {
        $message = "❌ Verification failed: " . $ex->getMessage();
        $alertType = "error";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Transaction</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" href="logo_no_bg.png?v=1.1" type="image/jpeg">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus {
            border-color: #4a6bff;
            outline: none;
        }
        button {
            background-color: #4a6bff;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 20px;
        }
        button:hover {
            background-color: #3a5bef;
        }
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 30px;
            border-radius: 5px;
            color: white;
            font-family: 'Poppins', sans-serif;
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
    <div class="container">
        <h2>Verify Transaction</h2>
        <form method="POST" action="verify_transaction.php">
            <div class="form-group">
                <!-- <label for="transaction_id">Transaction ID</label> -->
                <input type="text" id="transaction_id" name="transaction_id"
                       placeholder="Enter your transaction ID" required>
                <p style="font-size: 14px; color: #666; margin-top: 5px;">Check your email for the transaction ID</p>
            </div>
            <button type="submit" name="verify">Verify Transaction</button>
        </form>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert <?php echo $alertType; ?>">
            <span class="alert-icon"><?php echo $alertType === 'success' ? '✓' : '✗'; ?></span>
            <?php echo $message; ?>
        </div>
        <script>
            setTimeout(function() {
                window.location.href = 'dashboard.php';
            }, 4000);
        </script>
    <?php endif; ?>
</body>
</html>
