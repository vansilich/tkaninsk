<?
    require 'cron/mail/PHPMailerAutoload.php';
    function smtpmail($mail_to, $subject, $message, $file = '', $headers = ''){
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
        $mail->Username = "bauhoff2013@yandex.ru";

        //Password to use for SMTP authentication
        $mail->Password = "1234567vbn";

        //Set who the message is to be sent from
        $mail->setFrom('bauhoff2013@yandex.ru', 'tkbauhoff.ru');

        //Set an alternative reply-to address
        $mail->addReplyTo('bauhoff2013@yandex.ru', 'tkbauhoff.ru');

        //Set who the message is to be sent to
        $mail->addAddress($mail_to, $mail_to);
        //$mail->addAddress('vlad_mir83@mail.ru', 'Название фирмы2');
        if (!empty($file)) {
            $filename = basename($file);
            echo $file;
            echo '<br>' . $filename;
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

    echo 'mail='.mail('vakas@yandex.ru','Заказ на сайте tkbauhoff.ru','test');
    echo '<br>smtpmail='.smtpmail('vakas@yandex.ru','Заказ на сайте tkbauhoff.ru','test');

?>