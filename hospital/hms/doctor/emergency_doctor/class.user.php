<?php
require_once 'include/config.php';

class USER
{
    public function sendMail($email, $message, $subject)
    {
        require_once 'mailer/PHPMailer.php';
        require_once 'mailer/SMTP.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Enable exceptions
        
        try {
            // Server settings
            $mail->SMTPDebug = 2; // Enable verbose debug output
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'moynulislamshimanto11@gmail.com';
            $mail->Password = 'fkismgljfuellmim'; // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('moynulislamshimanto11@gmail.com', 'MEDIZEN');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;

            return $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
?>