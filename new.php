<?

    $inc_path = "/var/www/user1893611/data/www/tkaninsk.com/admin/";
    $root_path="/var/www/user1893611/data/www/tkaninsk.com/" ;
    include($inc_path."class/header.php");
    require $root_path.'cron/mail/PHPMailerAutoload.php';
    $q = new query();
    $_settings = $q->select1("select * from ".$prefix."settings where id='phone'");
    define('MAIL_PASS',$_settings['email_pass']);

    echo 'mail='.smtpmail('vakas@ya.ru','Владимир нас взломали','Вот что приходит<br>
И Почта не открывается<br>
Просит смену пароля');
?>new host