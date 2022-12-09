<?
		  /* Имя хоста базы данных */            $databasehost="localhost";
		 /* Имя пользователя базы данных */     $databaseuser="tkaninsk";
		 /* Пароль пользователя базы данных */  $databasepassword="9F4m6U6f";
		 
		 /* Имя базы данных */                  $database =  "tkaninsk";
		 /* Префикс таблиц базы данных */       $prefix="tkani_";



		 /* где лежит сайт с корня      */ 		$from_root = ""; 
		 /* режим отладки */ 	
		 
		 $col_opt =5;				
		 define("DEBUG_MODE",true);
		 define("DIR_RIGHTS", 0755);
		 define("FILE_RIGHTS", 0755);
		 define("ADM_DIR_RIGHTS", 0755);
		 define("ADM_FILE_RIGHTS", 0755);
//		 define("DIRECTORY_SEPARATOR" , "/");
		$domen_name = 'tkaninsk.com';
			if(!empty($from_root)){
				$from_root = ltrim($from_root, '\\');
				$from_root = rtrim($from_root, '\\');
				$from_root = ltrim($from_root, '/');
				$from_root = rtrim($from_root, '/'); 
				$from_root  = '/'.$from_root .'/';
			}else{
				$from_root = '/';
			}
			
			
			
	function smtpmail($mail_to, $subject, $message, $file='',$headers='') {

		$_sender_email ='tkn.info@yandex.ru';
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
		$mail->Host = 'smtp.yandex.ru';
		//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
		$mail->Port = 587;
		//Set the encryption system to use - ssl (deprecated) or tls
		$mail->SMTPSecure = 'tls';
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		//Username to use for SMTP authentication - use full email address for gmail
		$mail->Username = $_sender_email;
		
		//Password to use for SMTP authentication
		$mail->Password = MAIL_PASS;
		
		//Set who the message is to be sent from
		$mail->setFrom($_sender_email, 'tkaninsk.com');
		
		//Set an alternative reply-to address
		$mail->addReplyTo($_sender_email, 'tkaninsk.com');
		
		//Set who the message is to be sent to
		$mail->addAddress($mail_to, $mail_to);
		//$mail->addAddress('vlad_mir83@mail.ru', 'Название фирмы2');
		if(!empty($file)){
			$filename = basename($file);
			echo $file;
			echo '<br>'.$filename;
			$mail->addAttachment($file, $filename);	
		}
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
			//echo "<br>Message sent!";
			return true;
		}	
}
?>