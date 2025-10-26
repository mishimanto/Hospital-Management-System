<?php
session_start();
include('include/config.php');
require_once 'class.user.php';

// Check for ID and validate
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "❌ Invalid request.";
    exit;
}

$request_id = intval($_GET['id']);

// Step 1: Get user_id and amount
$result = mysqli_query($con, "SELECT user_id, amount, userName, contact_no, payment_method, requested_at FROM wallet_requests WHERE id='$request_id' AND status='Pending'");
if (mysqli_num_rows($result) != 1) {
    echo "❌ Request not found or already handled.";
    exit;
}
$row = mysqli_fetch_assoc($result);
$user_id = $row['user_id'];
$amount = $row['amount'];
$username = $row['userName'];
$sender = $row['contact_no'];
$payment_method = $row['payment_method'];
$payment_time = date("F j, Y \a\\t g:i A", strtotime($row['requested_at']));

// Step 2: Update wallet balance in `users` table
mysqli_query($con, "UPDATE users SET wallet_balance = wallet_balance + '$amount' WHERE id='$user_id'");

// Step 3: Update request status
mysqli_query($con, "UPDATE wallet_requests SET status='Approved' WHERE id='$request_id'");

// Step 4: Fetch user email and updated balance
$userData = mysqli_fetch_assoc(mysqli_query($con, "SELECT email, wallet_balance FROM users WHERE id='$user_id'"));
$email = $userData['email'];
$balance = $userData['wallet_balance'];

// Step 5: Send email
$subject = "Wallet Approved & Updated";
$email_message = "
    <div style='font-family: Poppins, sans-serif; font-size: 15px;'>
        <h2 style='color:#4a6bff;'>💼 Wallet Update</h2>
        <p>Hi <strong>$username</strong>,</p>
        <p>Your wallet request has been approved and balance updated:</p>
        <table cellpadding='10'>
            <tr><td><strong>💳 Payment Method:</strong></td><td>$payment_method</td></tr>
            <tr><td><strong>📱 Sender:</strong></td><td>$sender</td></tr>
            <tr><td><strong>💰 Amount:</strong></td><td>৳" . number_format($amount, 2) . "</td></tr>
            <tr><td><strong>🕒 Time:</strong></td><td>$payment_time</td></tr>
            <tr><td><strong>💼 Updated Balance:</strong></td><td style='color:green;font-weight:bold;'>৳" . number_format($balance, 2) . "</td></tr>
        </table>
        <br><p>Thanks for staying with MEDIZEN.</p>
    </div>
";
$user = new USER();
$user->sendMail($email, $email_message, $subject);

// Step 6: Set session message and redirect
$_SESSION['msg'] = "✅ ৳" . number_format($amount, 2) . " has been successfully added to <strong>$username</strong>'s wallet. A confirmation email has been sent.";
header("Location: manage_wallet_requests.php");
exit;
?>

