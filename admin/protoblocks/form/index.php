<table width="100%">
<tr valign="top"><td>
<?
$cmd = get_param('cmd');
$err = 1;
if($cmd == 'send'){
	$err = 0;
	$err_msg = '';
	/*$date_begin = get_param('date_begin');
	$date_end = get_param('date_end');
	$contact_fio = get_param('contact_fio');
	$phone = get_param('phone');
	$email = get_param('email');
	$number_of_category = get_param('number_of_category');
	$number_of_adults = get_param('number_of_adults');
	$number_of_childrens = get_param('number_of_childrens');
	$age_of_childrens = get_param('age_of_childrens');
	$meals = get_param('meals');
	$about_us = get_param('about_us');
	
	if(empty($contact_fio)){
		$err = 1;
		$err_msg .= 'На заполнено поле "Имя"<br>';
	}
	if(empty($email) ){
		$err = 1;
		$err_msg .= 'На заполнено поле "E-mail"<br>';
	}
	if(empty($phone) ){
		$err = 1;
		$err_msg .= 'На заполнено поле "Телефон"<br>';
	}
	*/
	if($err == 1){
		echo '<br><br><div class="error_msg">'.$err_msg.'</div>';
	}else{
	
	/*	$msg = '<div>
    <b>Дата заезда (ДД.ММ.ГГГГ)*<span style="color:red"></span></b>
    <br />
    '.$date_begin.'
  </div>
  <div>
    <b>Дата выезда (ДД.ММ.ГГГГ)*<span style="color:red"></span></b>
    <br />
    '.$date_end.'
  </div>
  <div>
    <b>Контактное лицо (ФИО)*<span style="color:red"></span></b>
    <br />
    '.$contact_fio.'
  </div>
  <div>
    <b>Телефон*<span style="color:red"></span></b>
    <br />
    '.$phone.'
  </div>
  <div>
    <b>E-mail*<span style="color:red"></span></b>
    <br />
    '.$email.'
  </div>
  <div>
    <b>Категория номера*<span style="color:red"></span></b>
    <br />
    '.$number_of_category.'
      
  </div>
  <div>
    <b>Количество взрослых*<span style="color:red"></span></b>
    <br />
    '.$number_of_adults.'
  </div>
  <div>
    <b>Количество детей*<span style="color:red"></span></b>
    <br />
    '.$number_of_childrens.'
  </div>
  <div>
    <b>Возраст детей*<span style="color:red"></span></b>
    <br />
    '.$age_of_childrens.'
  </div>
  <div>
    <b>Питание*<span style="color:red"></span></b>
    <br />
    '.$meals.'
  </div>
  <div>
    <b>Как вы о нас узнали*<span style="color:red"></span></b>
    <br />
    '.$about_us.'
  </div>';
	*/	
	
	$field = $q->select("select* from ".$prefix."orders_field order by rank desc");
foreach($field as $row){
	$temp = get_param('fileld'.$row['id']);
				$msg .= '<div>
    <b>'.$row['name'].'</b>
    <br />
    '.$temp.'
  </div>';
}
		
	
  
		$subject = 'Бронирование с сайта rodniki-vip.ru';
		$headers = "From: rodniki-vip.ru <info@rodniki-vip.ru>\r\n";
		$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
		mail($_settings['email'], $subject, $msg, $headers);

		
		$q->insert("insert into ".$prefix."orders set
		fio = ".to_sql($contact_fio).",
		email = ".to_sql($email).",
		phone = ".to_sql($phone).",
		order_text = ".to_sql($msg).",
		date_add = NOW()");		
		
		echo '<div class="good_msg">Заказ отправлен. В ближайшее время менеджеры свяжутся с вами.</div>';
		header('location:/attendance/booking/?done=true');
	}	

}
$done = get_param('done');
if($done == 'true'){
	echo '<div class="good_msg">Заказ отправлен. В ближайшее время менеджеры свяжутся с вами.</div>
	Или вы можете позвонить сами по телефону: '.$_settings['phone'].'
	';
	$err = 0;
}
if($err == 1){
?>
<script>
function check(form){
	if(form.face.value==''){
		form.face.focus();
		alert('не заполнено поле "Имя"');
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
/*
var_dump($_POST);
foreach($_POST as $k=>$v){
	echo '$'.$k.' = get_param(\''.$k.'\');<br>';	
}*/
?>
<?
//$page = $q->select1("select text from ".$prefix."pages where id=".to_sql($this_page_id));
//echo $page['text'];
?>

<script>
function check(){
	/*var f = $('#date_begin');
	if(f.val()==''){
		alert('Не заполнено поле "Дата заезда"');
		f.focus();
		return false;		
	}
	var f = $('#date_end');
	if(f.val()==''){
		alert('Не заполнено поле "Дата выезда"');
		f.focus();
		return false;		
	}
	var f = $('#contact_fio');
	if(f.val()==''){
		alert('Не заполнено поле "Контактное лицо"');
		f.focus();
		return false;		
	}
	var f = $('#phone');
	if(f.val()==''){
		alert('Не заполнено поле "Телефон"');
		f.focus();
		return false;		
	}
	var f = $('#email');
	if(f.val()==''){
		alert('Не заполнено поле "E-mail"');
		f.focus();
		return false;		
	}
	
	
	var f = $('#number_of_adults');
	if(f.val()==''){
		alert('Не заполнено поле "Количество взрослых"');
		f.focus();
		return false;		
	}
	var f = $('#number_of_childrens');
	if(f.val()==''){
		alert('Не заполнено поле "Количество детей"');
		f.focus();
		return false;		
	}
	
	*/
	return true;
	
}
</script>
<form name="booking" method="post" action="" class="form" onsubmit="return check();">
  <input type="hidden" name="cmd" value="send" />
  <input type="hidden" name="nospam" value="" />
  <input type="text" name="for_phone" style="display:none" value="" />
  <? /*
  <div>
    <label>Дата заезда (ДД.ММ.ГГГГ)*<span style="color:red"></span></label>
    <br />
    <input type="text" name="date_begin" id="date_begin" value=""/>
  </div>
  <div>
    <label>Дата выезда (ДД.ММ.ГГГГ)*<span style="color:red"></span></label>
    <br />
    <input type="text" name="date_end" id="date_end"  value=""/>
  </div>
  <div>
    <label>Контактное лицо (ФИО)*<span style="color:red"></span></label>
    <br />
    <input type="text" name="contact_fio" id="contact_fio"  value=""/>
  </div>
  <div>
    <label>Телефон*<span style="color:red"></span></label>
    <br />
    <input type="text" name="phone" id="phone"  value=""/>
  </div>
  <div>
    <label>E-mail*<span style="color:red"></span></label>
    <br />
    <input type="text" name="email" id="email"  value=""/>
  </div>
  <div>
    <label>Категория номера*<span style="color:red"></span></label>
    <br />
    <select name="number_of_category" value="">
      <option  >Номер (корпус №1)</option>
      <option  >Номер (корпус №2)</option>
      <option  >Дача</option>
    </select>
  </div>
  <div>
    <label>Количество взрослых*<span style="color:red"></span></label>
    <br />
    <input type="text" name="number_of_adults" id="number_of_adults" value=""/>
  </div>
  <div>
    <label>Количество детей*<span style="color:red"></span></label>
    <br />
    <input type="text" name="number_of_childrens" id="number_of_childrens" value=""/>
  </div>
  <div>
    <label>Возраст детей*<span style="color:red"></span></label>
    <br />
    <input type="text" name="age_of_childrens" id="age_of_childrens" value=""/>
  </div>
  <div>
    <label>Питание*<span style="color:red"></span></label>
    <br />
    <select name="meals" value="">
      <option  >Завтрак, обед, ужин</option>
      <option  >Завтрак, обед / завтрак, ужин</option>
      <option  >Без питания</option>
    </select>
  </div>
  <div>
    <label>Как вы о нас узнали*<span style="color:red"></span></label>
    <br />
    <select name="about_us" value="">
      <option  >Реклама в Интернете</option>
      <option  >Из поисковых систем</option>
      <option  >Реклама в печатных изданиях</option>
      <option  >Реклама на радио</option>
      <option  >Посоветовали</option>
      <option  >Отдыхали ранее в отеле</option>
      <option  >Реклама на TV</option>
    </select>
  </div>
  <div>
    <button type="submit" name="send" value="go">Отправить</button>
  </div>
  */ ?>

<?
$field = $q->select("select* from ".$prefix."orders_field order by rank desc");
foreach($field as $row){
	if($row['types'] == 1){
		echo '<div>
		<label>'.$row['name'].'</label>
		<br />
		<input type="text" name="fileld'.$row['id'].'" value=""/>
		</div>';
	}elseif($row['types'] == 2){
		echo '  <div>
		<label>'.$row['name'].'</label>
		<br />
		<select name="fileld'.$row['id'].'" >';
		$val = $q->select("select* from ".$prefix."orders_field_value where fid=".to_sql($row['id'])." order by rank desc");
		foreach($val as $v){
			echo '<option>'.$v['name'].'</option>';	
		}
		echo '</select>
		</div>
		<div>';		
	}
	
}
?>
<div>
    <button type="submit" name="send" value="go">Отправить</button>
  </div>    
  
  
  
</form>
<script type="text/javascript" src="/assets/jquery.mask.min.js"></script> 
<script type="text/javascript">
							$(document).ready(function(){
								$("#date_begin, #date_end").mask("99.99.9999");
							});
						</script>
<?
}
?>
</td><td>
<? echo $page['text'];?>
</td></tr></table>