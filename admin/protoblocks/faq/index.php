<h1>������� � ������</h1><?
$sendoffer = get_param('sendoffer');
if(!empty($sendoffer)){
	$q = new query();

	echo '<br><div style="font-size:16px;color:green;">��� ������ ������� ��������.</div>';

	$mes2='���: '.strip_tags(get_param('fio')).'<br>
	E-mail: '.strip_tags(get_param('mail')).'<br>
	�����: '.strip_tags(get_param('text'));

	$to      = $_settings['email'];
	//$to      = '';
	$q->insert("insert into ".$prefix."faq set 
	date_add = NOW(),
	name=".to_sql(get_param('fio')).", 
	email=".to_sql(get_param('mail'))." , 
	text=".to_sql(get_param('text')));
	$subject = '�������� ����� �� ����� cheldomik.ru';
	$message = $mes2;

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= "From: � ����� <info@cheldomik.ru>\r\n";
	$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
	mail($to, $subject, $message, $headers);

}


?>
<form action="" method="post">
		�.�.�.<br>
		<input type="text" name="fio" size="60" class="fld"><br><br>		
		E-mail<br>
		<input type="text" name="mail" size="60" class="fld"><br><br>
		�����:<br>
		<textarea cols="61" rows="8" name="text"></textarea>
		<br><br><input type="submit" name="sendoffer" value="���������!" class="btn">
</form>



<?
    $query = new query("select * from ".$prefix."faq where status=1 ");
    $data = $query->select();
    foreach ($data as $k => $v) {
	echo '<div class="faqblock">
			<div class="q"><b>'.$v['name'].'</b> </div>';
	echo $v['text'];	

	if ($v['answer']) echo '<div class="a"><b>�����:</b> '.$v['answer'].'</div>';

	echo '</div><HR width="100%" color=#dfd4be noShade SIZE=1>';



 }   

?>

