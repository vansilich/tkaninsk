<?
echo realpath('.');
die();
require 'cron/mail/PHPMailerAutoload.php';


$sended = smtpmail('vakas@yandex.ru','test555','test21245','');


     
function smtpmail($mail_to, $subject, $message, $headers='') {
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host = 'smtp.mail.ru';
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = 587;
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = "nsk-tkani@mail.ru";
		
		//Password to use for SMTP authentication
		$mail->Password = "aa860320674802";
		
		//Set who the message is to be sent from
		$mail->setFrom('nsk-tkani@mail.ru', 'tkaninsk.com');
		
		//Set an alternative reply-to address
		$mail->addReplyTo('nsk-tkani@mail.ru', 'tkaninsk.com');
		
		//Set who the message is to be sent to
		$mail->addAddress($mail_to, $mail_to);
		//$mail->addAddress('vlad_mir83@mail.ru', 'Название фирмы2');
		
		//Set the subject line
		$mail->Subject = $subject;
		
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail->msgHTML($message);
		
		//send the message, check for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
		} else {
			//echo "Message sent!";
			return true;
		}	
}

echo realpath('./');
phpinfo();
?>