<?
$action = get_param('action');
$err = 1;
if($action == 'send'){
	$err = 0;
	$err_msg = '';
	$company = get_param('company');
	$face = trim(get_param('face'));
	$email = trim(get_param('email'));
	$phone = get_param('phone');
	$site = get_param('site');
	$city = get_param('city');
	$obl = get_param('obl');
	$like = get_param('like');
	$task = trim(get_param('task'));
	if(empty($face)){
		$err = 1;
		$err_msg .= 'На заполнено поле "Контактное лицо"<br>';
	}
	if(empty($email)){
		$err = 1;
		$err_msg .= 'На заполнено поле "E-mail"<br>';
	}
	if(empty($task)){
		$err = 1;
		$err_msg .= 'На заполнено поле "Общее описание задачи"<br>';
	}
	if($err == 1){
		echo '<br><br><div class="error_msg">'.$err_msg.'</div>';
	}else{
	
		$msg = '<table style="margin-top:20px;" border="0" cellpadding="4" cellspacing="0">
		  <tr>
			<td colspan="2" align="right"><span class="star">*</span> - обязательные поля</td>
		  </tr>
			<tr style="height:30px;">
			<td class="text_zakas">Название компании:</td>
			<td>'.$company.'</td>
		  </tr>
		  <tr style="height:30px;">
			<td class="text_zakas">Контактное лицо<span class="star">*</span>:</td>
			<td>'.$face.'</td>
		  </tr>
		  <tr style="height:30px;">
			<td class="text_zakas">E-mail<span class="star">*</span>:</td>
			<td>'.$email.'</td>
		  </tr>
		  <tr style="height:30px;">
			<td class="text_zakas">Телефон:</td>
			<td>'.$phone.'</td>
		  </tr>
		   <tr style="height:30px;">
			<td class="text_zakas">Url сайта:</td>
			<td>'.$site.'</td>
		  </tr> 
		  <tr style="height:30px;">
			<td class="text_zakas">Ваш город:</td>
			<td>'.$city.'</td>
		  </tr>  
		  <tr valign="top">
			<td class="text_zakas">Область Вашей деятельности:</td>
			<td>'.$obl.'</td>
		  </tr>
		  
		   <tr valign="top">
			<td class="text_zakas">Сайты со схожими задачами:</td>
			<td>'.$like.'</td>
		  </tr>
		   <tr valign="top">
			<td class="text_zakas">Общее описание задачи <span class="star">*</span>:</td>
			<td>'.$task.'</td>
		  </tr> 
		</table>';
		
		$subject = 'Заявка c vakas.ru';
		$headers = "From: vakas.ru <vakas@yandex.ru>\r\n";
		$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
		mail('vakas@yandex.ru', $subject, $msg, $headers);

		
		$q->insert("insert into ".$prefix."orders set
		company = ".to_sql($company).",
		face = ".to_sql($face).",
		email = ".to_sql($email).",
		phone = ".to_sql($phone).",
		site = ".to_sql($site).",
		city = ".to_sql($city).",
		obl = ".to_sql($obl).",
		likes = ".to_sql($like).",
		dateadd = NOW(),
		task = ".to_sql($task));		
		
		echo '<h1>On-line заявка</h1><div class="good_msg">Заказ отправлен. В ближайшее время менеджеры свяжутся с вами.</div>';
		header('location:/order/all.php?done=true');
	}	

}
$done = get_param('done');
if($done == 'true'){
	echo '<h1>On-line заявка</h1><div class="good_msg">Заказ отправлен. В ближайшее время менеджеры свяжутся с вами.</div>';
	$err = 0;
}

if($err == 1){
?>
<script>
function check(form){
	if(form.face.value==''){
		form.face.focus();
		alert('не заполнено поле "Контактное лицо"');
		return false;	
	}
	if(form.email.value==''){
		form.email.focus();
		alert('не заполнено поле "E-mail"');
		return false;	
	}
	if(form.task.value==''){
		form.task.focus();
		alert('не заполнено поле "Общее описание задачи"');
		return false;	
	}
	return true;
}
</script>

<?
$page = $q->select1("select text from ".$prefix."pages where id=".to_sql($this_page_id));
echo $page['text'];
?>
<div class="oform">
  <form method="post" name="send_form" onsubmit="return check(this);">
    <input type="hidden" name="action" value="send" />
    <table style="margin-top:20px;" border="0" cellpadding="4" cellspacing="0">
      <tr>
        <td colspan="2" align="right"><span class="star">*</span> - <i>обязательные поля</i></td>
      </tr>
      <tr style="height:30px;">
        <td class="text_zakas">Название компании:</td>
        <td>
        <div class="input_fon" ><input class="input" type="text"  name="company"/>
        </div>
        </td>
      </tr>
      <tr style="height:30px;">
        <td class="text_zakas">Контактное лицо<span class="star">*</span>:</td>
        <td><div class="input_fon" ><input class="input" type="text" name="face"/></div></td>
      </tr>
      <tr style="height:30px;">
        <td class="text_zakas">E-mail<span class="star">*</span>:</td>
        <td><div class="input_fon" ><input class="input" type="text" name="email"/></div></td>
      </tr>
      <tr style="height:30px;">
        <td class="text_zakas">Телефон:</td>
        <td><div class="input_fon" ><input class="input" type="text" name="phone"/></div></td>
      </tr>
      <tr style="height:30px;">
        <td class="text_zakas">Url сайта:</td>
        <td><div class="input_fon" ><input class="input" type="text" name="site"/></div></td>
      </tr>
      <tr style="height:30px;">
        <td class="text_zakas">Ваш город:</td>
        <td><div class="input_fon" ><input class="input" type="text" name="city"/></div></td>
      </tr>
      <tr valign="top">
        <td class="text_zakas">Область Вашей деятельности:</td>
        <td><div class="comment"><textarea class="input" rows="2" name="obl" ></textarea></div></td>
      </tr>
      <tr valign="top">
        <td class="text_zakas">Сайты со схожими задачами:</td>
        <td><div class="comment"><textarea class="input" rows="4" name="like" ></textarea></div></td>
      </tr>
      <tr valign="top">
        <td class="text_zakas">Общее описание задачи <span class="star">*</span>:</td>
        <td><div class="comment"><textarea class="input" rows="4" name="task" ></textarea></div></td>
      </tr>
      <tr>
        <td></td>
        <td align="right"><div style="margin-top:10px;">
            <input type="image" src="/img/send_butt.gif" width="108" height="29" alt="Отправить" title="Отправить" />
          </div></td>
      </tr>
    </table>
  </form>
</div>
<?
}
?>
