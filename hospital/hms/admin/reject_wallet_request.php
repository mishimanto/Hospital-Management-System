<?php
session_start();
include('include/config.php');
require_once 'class.user.php';
$user = new USER();

if(strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

if(isset($_GET['id'])) {
    $request_id = intval($_GET['id']);

    // Get the request details to know user_id and amount
    $request_result = mysqli_query($con, "SELECT user_id, amount FROM wallet_requests WHERE id='$request_id'");
    if($request_result && mysqli_num_rows($request_result) > 0) {
        $request_data = mysqli_fetch_assoc($request_result);
        $user_id = $request_data['user_id'];
        $amount = $request_data['amount'];

        // Update request status to Rejected
        mysqli_query($con, "UPDATE wallet_requests SET status='Rejected' WHERE id='$request_id'");

        // Get user details
        $user_result = mysqli_query($con, "SELECT fullName, email FROM users WHERE id='$user_id'");
        if($user_result && mysqli_num_rows($user_result) > 0) {
            $user_data = mysqli_fetch_assoc($user_result);

            // Prepare email content
            $email = $user_data['email'];
            $subject = "Top-up Request Denied";

            ob_start();
            ?>
            <div style="font-family: Arial, sans-serif; padding:20px; border:1px solid #ddd; max-width:600px; margin:0 auto;">
                <h2 style="color: #f44336;">Wallet Request Rejected!</h2>
                <p>Dear <strong><?php echo htmlspecialchars($user_data['fullName']); ?></strong>,</p>
                <p>We are sorry to inform you that your wallet request of <strong style="color:#2196F3;">৳<?php echo number_format($amount); ?></strong> has been <strong>Rejected</strong>.</p>
                <p style="margin-top:20px;">For more information, please contact us.</p>
                <p style="color:#555;">Thank you for choosing our service.</p>
                <p style="margin-top:30px; font-size:12px; color:#999;">This is an automated notification — please do not reply.</p>
            </div>
            <?php
            $message = ob_get_clean();

            // Send mail (assuming you have $user->sendMail method available)
            
            $user->sendMail($email, $message, $subject);
        }
    }
}

header('location:manage_wallet_requests.php');
exit();
?>
