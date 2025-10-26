<?php
//error_reporting(0);

require_once 'include/config.php';

class USER
{
	
	public function sendMail($email, $message, $subject)
	{
		require_once 'mailer/PHPMailer.php';
		require_once 'mailer/SMTP.php';

		$mail = new PHPMailer\PHPMailer\PHPMailer();
		//$mail->SMTPDebug = 3;
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'moynulislamshimanto11@gmail.com';
		$mail->Password = 'fkismgljfuellmim';
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;
		$mail->setFrom('moynulislamshimanto11@gmail.com','MEDIZEN');
		$mail->addAddress($email);
		//$mail->addReplyTo('moynulislamshimanto11@gmail.com','Cogent');
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body = $message;

		if(!$mail->send())
		{
			$_SESSION['mailError'] = $mail->ErrorInfo;
			return false;
		}

		else
		{
			return true;
		}

	}

	
}


?>