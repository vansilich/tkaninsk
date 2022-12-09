<?
$vid = strip_tags(get_param('vid'));

$mon = array();
$mon[1] = 'Январь';
$mon[2] = 'Февраль';
$mon[3] = 'Март';
$mon[4] = 'Апрель';
$mon[5] = 'Май';
$mon[6] = 'Июнь';
$mon[7] = 'Июль';
$mon[8] = 'Август';
$mon[9] = 'Сентябрь';
$mon[10] = 'Октябрь';
$mon[11] = 'Ноябрь';
$mon[12] = 'Декабрь';

/*
echo '$q->insert("insert into ".$prefix."resume set';
foreach($_POST as $k=>$v){
	//echo '$'.$k.'=strip_tags(get_param(\''.$k.'\');<br>';	
//	echo 'ALTER TABLE  `job_resume` ADD  `'.$k.'` VARCHAR( 255 ) NOT NULL;<br>';
		echo $k.'=".to_sql($'.$k.').",<br>	';
}
*/
/*if(empty($_SESSION['user_info']['id'])){
	$temp = $q->select1("select * from ".$prefix."tmpl where id=5");
	echo '<div class="msg">'.$temp['text'].'</div>';			
}else{*/

	$added = strip_tags(get_param('added'));
	if($added == 'true'){
		$id = (int)strip_tags(get_param('id'));
		$temp = $q->select1("select * from ".$prefix."tmpl where id=4");
		$temp['text'] = str_replace('{LINK}','/anketa/?id='.$id,$temp['text']);
		echo '<div class="msg">'.$temp['text'].'</div>';	
	}else{
	$cmd = strip_tags(get_param('cmd'));
	if($cmd == 'add'){
		$surname=strip_tags(get_param('surname'));
		$name=strip_tags(get_param('name'));
		$otch=strip_tags(get_param('otch'));
		$sex=strip_tags(get_param('sex'));
		$bday=strip_tags(get_param('bday'));
		$bmon=strip_tags(get_param('bmon'));
		$byear=strip_tags(get_param('byear'));
		$mail=strip_tags(get_param('mail'));
		$phoneCountryCode=strip_tags(get_param('phoneCountryCode'));
		$phoneCode=strip_tags(get_param('phoneCode'));
		$phoneNumber=strip_tags(get_param('phoneNumber'));
		$phoneCountryCode2=strip_tags(get_param('phoneCountryCode2'));
		$phoneCode2=strip_tags(get_param('phoneCode2'));
		$phoneNumber2=strip_tags(get_param('phoneNumber2'));
		//$dolj=strip_tags(get_param('dolj'));
		//$zp=strip_tags(get_param('zp'));
		$komand=strip_tags(get_param('komand'));
	
	
	
		$opit_company=strip_tags(get_param('opit_company'));
		$opit_dolj=strip_tags(get_param('opit_dolj'));
		$opit_mon=strip_tags(get_param('opit_mon'));
		$opit_year=strip_tags(get_param('opit_year'));
		$opit_mon_to=strip_tags(get_param('opit_mon_to'));
		$opit_year_to=strip_tags(get_param('opit_year_to'));
		$opit_descr=strip_tags(get_param('opit_descr'));

		

		$ed_name=strip_tags(get_param('ed_name'));
		$ed_main=strip_tags(get_param('ed_main'));
		$ed_y_from=strip_tags(get_param('ed_y_from'));
		$ed_y_to=strip_tags(get_param('ed_y_to'));
		$ed_spec=strip_tags(get_param('ed_spec'));
		$ed_comm=strip_tags(get_param('ed_comm'));
		$dop_prof=strip_tags(get_param('dop_prof'));
		$dop_about=strip_tags(get_param('dop_about'));
		$city=strip_tags(get_param('city'));
		$metro=strip_tags(get_param('metro'));
		$profil=strip_tags(get_param('profil'));
		$rabota_city=strip_tags(get_param('rabota_city'));
		$grajd=strip_tags(get_param('grajd'));
		
		$gr_full=strip_tags(get_param('gr_full'));
		$gr_smen=strip_tags(get_param('gr_smen'));
		$gr_free=strip_tags(get_param('gr_free'));
		$gr_chast=strip_tags(get_param('gr_chast'));
		$gr_far=strip_tags(get_param('gr_far'));
		
			
		
		/*
		
		opit_company=".to_sql($opit_company).",
			opit_dolj=".to_sql($opit_dolj).",
			opit_mon=".to_sql($opit_mon).",
			opit_year=".to_sql($opit_year).",
			opit_mon_to=".to_sql($opit_mon_to).",
			opit_year_to=".to_sql($opit_year_to).",
			opit_descr=".to_sql($opit_descr).",
		*/

/*
			dolj=".to_sql($dolj).",
			zp=".to_sql($zp).",

*/		
		$new_id = $q->insert("insert into ".$prefix."resume set
			date_add = NOW(),
			user_id = ".to_sql($_SESSION['user_info']['id']).",
			vid=".to_sql($vid).",
			surname=".to_sql($surname).",
			name=".to_sql($name).",
			otch=".to_sql($otch).",
			sex=".to_sql($sex).",
			bday=".to_sql($bday).",
			bmon=".to_sql($bmon).",
			byear=".to_sql($byear).",
			mail=".to_sql($mail).",
			phoneCountryCode=".to_sql($phoneCountryCode).",
			phoneCode=".to_sql($phoneCode).",
			phoneNumber=".to_sql($phoneNumber).",
			phoneCountryCode2=".to_sql($phoneCountryCode2).",
			phoneCode2=".to_sql($phoneCode2).",
			phoneNumber2=".to_sql($phoneNumber2).",
			komand=".to_sql($komand).",
			
			
			
			ed_name=".to_sql($ed_name).",
			ed_main=".to_sql($ed_main).",
			ed_y_from=".to_sql($ed_y_from).",
			ed_y_to=".to_sql($ed_y_to).",
			ed_spec=".to_sql($ed_spec).",
			ed_comm=".to_sql($ed_comm).",
			dop_prof=".to_sql($dop_prof).",
			dop_about=".to_sql($dop_about).",
			profil=".to_sql($profil).",
			city=".to_sql($city).",
			metro=".to_sql($metro).",
			grajd=".to_sql($grajd).",
			gr_full=".to_sql($gr_full).",
			gr_smen=".to_sql($gr_smen).",
			gr_free=".to_sql($gr_free).",
			gr_chast=".to_sql($gr_chast).",
			gr_far=".to_sql($gr_far).",
			
			rabota_city=".to_sql($rabota_city)."
			
			");
		
		if(empty($_SESSION['user_info']['id'])){
			$check = $q->select1("select id from ".$prefix."members where email=".to_sql($email));
			if(empty($check)){
				$k = md5(date('d.m.y h:i').$mail.'z');
				$pass=strip_tags(get_param('pass'));
				$new_user_id = $q->insert("insert into ".$prefix."members set
				date_reg = NOW(),
				active = 0,
				person_type=".to_sql($person_type).",
				
				company_name=".to_sql($company_name).",
				phoneCountryCode=".to_sql($phoneCountryCode).",
				phoneCode=".to_sql($phoneCode).",
				phoneNumber=".to_sql($phoneNumber).",
				
				name=".to_sql($name).",
				surname=".to_sql($surname).",
				mail=".to_sql($mail).",
				pass=".to_sql($pass).",
				code = ".to_sql($k));	
				
				$_SESSION['secret_code'] = '';
				
				$temp = $q->select1("select * from ".$prefix."tmpl where id=1");
				$msg = str_replace('{NAME}',$name.' '.$surname,$temp['text']);
				$msg = str_replace('{LINK}','http://'.$domen_name.'/account/activate.php?c='.$k,$msg);
				$msg = str_replace('{EMAIL}',$mail,$msg);
				$msg = str_replace('{PASS}',$pass,$msg);
				
				
				$headers = "From: ".$_SERVER['HTTP_HOST']." <info@".$_SERVER['HTTP_HOST'].">\r\n";
				$headers .= 'Content-type: text/html; charset=windows-1251' . "\r\n";
				
				mail($mail, $temp['theme'],$msg  , $headers);
				$check = $q->select1("select * from ".$prefix."members where id=".to_sql($new_user_id));
				$_SESSION['user_info'] = $check;
				$q->exec("update ".$prefix."resume set user_id = ".to_sql($_SESSION['user_info']['id'])." where id=".to_sql($new_id));
			}else{
				$q->exec("update ".$prefix."resume set user_id = ".to_sql($check['id'])." where id=".to_sql($new_id));
			}
			
		}
		
		for($z = 1; $z<=3; $z++){
				$opit_company=strip_tags(get_param('opit_company'.$z));
				$opit_dolj=strip_tags(get_param('opit_dolj'.$z));
				$opit_mon=strip_tags(get_param('opit_mon'.$z));
				$opit_year=strip_tags(get_param('opit_year'.$z));
				$opit_mon_to=strip_tags(get_param('opit_mon_to'.$z));
				$opit_year_to=strip_tags(get_param('opit_year_to'.$z));
				$opit_descr=strip_tags(get_param('opit_descr'.$z));
				if(!empty($opit_company)){
					$q->exec("insert into ".$prefix."resume_opit set
					resume_id=".to_sql($new_id).",
					opit_company=".to_sql($opit_company).",
					opit_dolj=".to_sql($opit_dolj).",
					opit_mon=".to_sql($opit_mon).",
					opit_year=".to_sql($opit_year).",
					opit_mon_to=".to_sql($opit_mon_to).",
					opit_year_to=".to_sql($opit_year_to).",
					opit_descr=".to_sql($opit_descr)." ");
				}
		}
		
		for($z = 1; $z<=3; $z++){
			$ed_name=strip_tags(get_param('ed_name'.$z));
			$ed_y_from=strip_tags(get_param('ed_y_from'.$z));
			$ed_y_to=strip_tags(get_param('ed_y_to'.$z));
			$ed_spec=strip_tags(get_param('ed_spec'.$z));
			$ed_comm=strip_tags(get_param('ed_comm'.$z));	
			if(!empty($ed_name)){
				$q->exec("insert into ".$prefix."resume_education set
				resume_id=".to_sql($new_id).",
				ed_name=".to_sql($ed_name).",
				ed_y_from=".to_sql($ed_y_from).",
				ed_y_to=".to_sql($ed_y_to).",
				ed_spec=".to_sql($ed_spec).",
				ed_comm=".to_sql($ed_comm)." ");
			}
			
		}
	
			$uploads_dir = $root_path.'files/resume/';
			if(is_file($_FILES['foto']['tmp_name']) ){
				if ($_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
					$pict_ext = '';
					$ext = array(".gif", ".jpg", ".jpeg", ".png");
					for($i=0;$i<sizeof($ext);$i++){
						if($data = explode($ext[$i], strtolower($_FILES['foto']['name']))){										
							if(count($data) == 2){
								$pict_ext = $ext[$i];
								break;
							}
						}
					}
					$tmp_name = $_FILES["foto"]["tmp_name"];
					$name = $new_id.$pict_ext;
					$name2 = 's'.$new_id.'.jpg';
					move_uploaded_file($tmp_name, $uploads_dir.$name);
					resize_then_crop($uploads_dir.$name,$uploads_dir.$name2,118,155,255,255,255,90); 
					$q->exec("update ".$prefix."resume set img=".to_sql($pict_ext));
				}
			}
			
			header('location: ?added=true&id='.$new_id);
	
	
	}
	?>
	
	<script>
	function check_step1(){
		var surname = $('#surname').val();
		var name = $('#name').val();
		if(surname== ''){
			choose_vk_check(1);
			$('#surname_err').show();
			$('#surname').focus();
			return false;	
		}else{
			$('#surname_err').hide();	
		}
		if(name== ''){
			choose_vk_check(1);
			$('#name_err').show();
			$('#name').focus();
			return false;	
		}else{
			$('#name_err').hide();	
		}
		return true;	
	}
	function check_step2(){
		if(!check_step1()) return false;
	
		return true;	
	}
	function check_step3(){
		if(!check_step2()) return false;
	
		return true;	
	}
	function check_step4(){
		if(!check_step3()) return false;
	
		return true;	
	}
	function check_step5(){
		if(!check_step4()) return false;
	
		return true;	
	}
	function check_step6(){
		if(!check_step5()) return false;
	
		return true;	
	}
	</script>
	
	 <div class="h2" style="margin:0 0 10px 0">Редактирование анкеты:</div>     
	
	<!--div>
	<a  href="#" onclick="$('.step').hide();$('#step1').show();return false;">Шаг1</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step2').show();return false;">Шаг2</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step3').show();return false;">Шаг3</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step4').show();return false;">Шаг4</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step5').show();return false;">Шаг5</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step6').show();return false;">Шаг6</a> 
	<img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> <!--a  href="#" onclick="$('.step').hide();$('#step7').show();return false;">Шаг7</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> >
	
	</div-->
<script>
function choose_vk_check(n){
		for(i=1;i<n;i++){
			$('#vk'+i).attr('class','vk'+i);
		}
		$('#vk'+i).attr('class','vk'+i+'_ac');
		for(i=n+1;i<=6;i++){
			$('#vk'+i).attr('class','vk'+i+'_no_ac');
		}
		$('.step').hide();$('#step'+n).show();
}
function choose_vk(n){
	err = 0;
	if(n==2) if(!check_step1()) err = 1;
	if(n==3) if(!check_step2()) err = 1;
	if(n==4) if(!check_step3()) err = 1;
	if(n==5) if(!check_step4()) err = 1;
	if(n==6) if(!check_step5()) err = 1;
	
	if(err == 0){
		for(i=1;i<n;i++){
			$('#vk'+i).attr('class','vk'+i);
		}
		$('#vk'+i).attr('class','vk'+i+'_ac');
		for(i=n+1;i<=6;i++){
			$('#vk'+i).attr('class','vk'+i+'_no_ac');
		}
		$('.step').hide();$('#step'+n).show();
	}
	
}
</script>    
<div class="div_center">
<table cellpadding="0" cellspacing="0">
<tr>
<td><a href="#" onclick="choose_vk(1);return false;" class="vk1" id="vk1_ac"></a></td>
<td style="padding-left:20px;"><a href="#" onclick="choose_vk(2);return false;" id="vk2" class="vk2_no_ac"></a></td>
<td style="padding-left:20px;"><a href="#" onclick="choose_vk(3);return false;" id="vk3" class="vk3_no_ac"></a></td>
<td style="padding-left:20px;"><a href="#" onclick="choose_vk(4);return false;" id="vk4" class="vk4_no_ac"></a></td>
<td style="padding-left:20px;"><a href="#" onclick="choose_vk(5);return false;" id="vk5" class="vk5_no_ac"></a></td>
<td style="padding-left:20px;"><a href="#" onclick="choose_vk(6);return false;" id="vk6" class="vk6_no_ac"></a></td>
</tr>
</table>
</div>    
	
	  
	   <form  method="POST" name="registerForm" id="registerForm"  enctype="multipart/form-data" >
	<input type="hidden" name="cmd" value="add" />
	
	   
	   
	   
	   <div class="step" id="step1"><span class="text_grey_sm">1 шаг -</span> <span class="h3">Заполнение личных данных:</span>
       <div id="surname_err" style="color:red;display:none;">Укажите фамилию</div>
       <div id="name_err" style="color:red;display:none;">Укажите Имя</div>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		<tr>
		  <td class="tr" width="187">Фамилия,<br />
           Имя,Отчество<span class="text_red">*</span></td>
		  <td>
		  <input class="input" style="width:200px" type="text" id="surname" name="surname"  placeholder="Фамилия" value=""/>

          <br />
		  <input class="input" style="width:200px"  type="text" id="name" name="name"  placeholder="Имя" value=""/>
		  <input class="input" style="width:200px; margin-top:5px"  type="text" name="otch"  placeholder="Отчество" value=""/>
		  </td>
          
          
          
		</tr>
		<tr>
		  <td class="tr" width="187">Пол</td>
		  <td>
		  <input type="radio" name="sex" value="1" checked /> <div class="div_left" style="margin:3px 10px 0 5px">Мужской</div>
		  
		  <input type="radio" name="sex" value="2"/> <div class="div_left" style="margin:3px 0 0 5px">Женский</div>
		  <br style="clear:both" />
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">Дата рождения<span class="text_red">*</span></td>
		  <td>
		  <select name="bday" class="select" style="width:100px">
					<option value="0" >День</option>
			<?
			for($i = 1; $i <= 31; $i++){
							echo '<option value="'.$i.'">'.$i.'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="bmon" class="select" style="width:100px">
					<option value="0" >Месяц</option>
			<?
			for($i = 1; $i <= 12; $i++){
							echo '<option value="'.$i.'">'.$mon[$i].'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="byear" class="select" style="width:100px">
					<option value="0" >Год</option>
			<?
			for($i = 1930; $i <= date('Y')-14; $i++){
							echo '<option value="'.$i.'">'.$i.'</option>';
							
					}
					?>
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">Фото</td>
		  <td><input type="file" name="foto" value=""/><br />
          <div class="text_grey_sm">
          JPG, PNG, TIFF (не более 2Mb).<br />
На фотографии должно быть четко видно лицо кандидата, снимок не должен содержать рекламы (например, логотипа соцсети) и контактов соискателя.

          </div>
          
          </td>
		</tr>
		<tr>
		  <td class="tr" width="187">Место жительства<span class="text_red">*</span></td>
		  <td>
		  <select  class="select" name="city">
		  <?
		  $city = $q->select("select * from ".$prefix."city where status=1 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'">'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">Метро<span class="text_red">*</span></td>
		  <td>
		  <select class="select" name="metro">
		  <?
		  $city = $q->select("select * from ".$prefix."metro where status=1 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'">'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  </td>
		</tr>
	  </table>
      
      <div style="padding-top:15px;">
	  <a href="#" onclick="
			choose_vk(2);return false;" class="a_big" >Следующий шаг</a> <img src="/img/point_right.gif" border="0" alt="Следующий шаг"/> 
      </div>
	</div>
	
	   
	   
	   
	   <div class="step" id="step2" style="display:none;"> <span class="text_grey_sm">2 шаг -</span> <span class="h3"> Заполнение контактов:</span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
      <?
if(empty($_SESSION['user_info']['id'])){
	?>
		<tr>
		  <td class="tr" width="187">Email(Логин)<span class="text_red">*</span></td>
		  <td style="padding-left:11px"><input class="input" style="width:215px" type="text" name="mail" value=""/></td>
		</tr>
        <tr>
			<td class="tr"><label for="pass" >Пароль </label><span class="text_red">*</span></td>
			<td style="padding-left:11px"><input class="input" style="width:215px"  type="password" name="pass" id="pass" value=""></td>
			
		  </tr>
	
	<?
}else{  
?>
		<tr>
		  <td class="tr" width="187">Email<span class="text_red">*</span></td>
		  <td style="padding-left:11px"><input class="input" style="width:215px" type="text" name="mail" value="<?
          echo $_SESSION['user_info']['email'];
		  ?>"/></td>
		</tr>

<?

}
?>    
		<tr>
		  <td class="tr" width="187">Мобильный телефон</td>
		  <td><span class="phonePlus">+</span>
											<input class="input" style="width:50px"  type="text" maxlength="3" size="3" id="phoneCountryCode" name="phoneCountryCode" value="<? 
											if(!empty($phoneCountryCode))echo htmlspecialchars($phoneCountryCode);
											else echo '7';
											?>"/><b>(</b>
											<input class="input" style="width:50px" type="text" class="input" style="width:100px"  maxlength="6" size="5" id="phoneCode" name="phoneCode" placeholder="код"     value="<? echo htmlspecialchars($phoneCode);?>" /> <b>)</b>
									 <input  class="input" style="width:247px" type="text" maxlength="7" name="phoneNumber" id="phoneNumber" placeholder="номер" value="<? echo htmlspecialchars($phoneNumber);?>"/></td>
	</td>
		</tr>
		<tr>
		  <td class="tr" width="187">Домашний телефон</td>
	   <td><span class="phonePlus">+</span>
											<input class="input" style="width:50px"  type="text" maxlength="3" size="3" id="phoneCountryCode" name="phoneCountryCode" value="<? 
											if(!empty($phoneCountryCode))echo htmlspecialchars($phoneCountryCode);
											else echo '7';
											?>"/><b>(</b>
											<input class="input" style="width:50px" type="text" class="input" style="width:100px"  maxlength="6" size="5" id="phoneCode" name="phoneCode" placeholder="код"     value="<? echo htmlspecialchars($phoneCode);?>" /> <b>)</b>
									 <input  class="input" style="width:247px" type="text" maxlength="7" name="phoneNumber" id="phoneNumber" placeholder="номер" value="<? echo htmlspecialchars($phoneNumber);?>"/></td>
	</td>
		</tr>
		<tr>
		  <td class="tr" width="187">Предпочтительный способ связи </td>
		  <td style="padding-left:12px"> 
		  <input type="checkbox" name="s_email" value="1" /> <div class="div_left" style="margin:3px 10px 0 5px">Email</div>
		  <input type="checkbox" name="s_mob" value="1" /> <div class="div_left" style="margin:3px 10px 0 5px">Мобильный</div>
		  <input type="checkbox" name="s_home" value="1" /> <div class="div_left" style="margin:3px 0 0 5px">Домашний</div>
		  <br style="clear:both" />
			</td>
		</tr>
	  </table>
      
      <div style="padding-top:15px;">
	  <img src="/img/point_left.gif" border="0" alt="Предыдущий шаг"/> <a href="#" onclick="choose_vk(1);return false;" class="a_big">Предыдущий шаг</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	  <a href="#" onclick="choose_vk(3);return false;" class="a_big" >Следующий шаг</a> <img src="/img/point_right.gif" border="0" alt="Следующий шаг"/>
      </div>
	  
	</div>
	
	   
	   
	   
	   <div class="step" id="step3" style="display:none;">  <span class="text_grey_sm">3 шаг -</span> <span class="h3"> Пожелания к работе:</span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		
		<tr>
		  <td class="tr" width="187">Профессиональная сфера</td>
		  <td>
		  
		  
		  <select class="select"  name="profil">
		  <?
		  $city = $q->select("select * from ".$prefix."profil where status=1 and parent=0 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'">'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  
		  
		  
		  <!--a href="" class="a_in">Выбрать</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /--></td>
		</tr>
		
	
	
		
	  </table>
      <div style="padding-top:15px;">
		<img src="/img/point_left.gif" border="0" alt="Предыдущий шаг"/> <a href="#" onclick="choose_vk(2);return false;" class="a_big">Предыдущий шаг</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	  <a href="#" onclick="choose_vk(4);return false;" class="a_big" >Следующий шаг</a> <img src="/img/point_right.gif" border="0" alt="Следующий шаг"/>
      </div>
	</div>
	
	   
	   
	   
	   <div class="step" id="step4" style="display:none;"> <span class="text_grey_sm">4 шаг -</span> <span class="h3"> Опыт работы:</span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		<tr>
		  <td class="tr" width="187">Название компании</td>
		  <td><input class="input" style="width:247px" type="text" name="opit_company" value=""/></td>
		</tr>
		<!--tr>
		  <td class="tr" width="187">Отрасль бизнеса</td>
		  <td><a href="" class="a_in">Выбрать</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /></td>
		</tr-->
		<tr>
		  <td class="tr" width="187">Должность</td>
		  <td><input  class="input" style="width:247px" type="text" name="opit_dolj" value=""/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">Период работы</td>
		  <td>
		  с
		   <select name="opit_mon" class="select" style="width:100px">
					<option value="0" >Месяц</option>
			<?
			for($i = 1; $i <= 12; $i++){
							echo '<option value="'.$i.'">'.$mon[$i].'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="opit_year" class="select" style="width:100px">
					<option value="0" >Год</option>
			<?
			for($i = date('Y'); $i >= 1930; $i--){
							echo '<option value="'.$i.'">'.$i.'</option>';
							
					}
					?>
		  </select>
		  
		  по
		  
		   <select name="opit_mon_to" class="select" style="width:100px">
					<option value="0" >Месяц</option>
			<?
			for($i = 1; $i <= 12; $i++){
							echo '<option value="'.$i.'">'.$mon[$i].'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="opit_year_to" class="select" style="width:100px">
					<option value="0" >Год</option>
			<?
			for($i = date('Y'); $i >= 1930; $i--){
							echo '<option value="'.$i.'">'.$i.'</option>';
							
					}
					?>
		  </select>
		  
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">Описание работы</td>
		  <td><textarea class="textarea"  style="width:247px" type="text" name="opit_descr"></textarea></td>
		</tr>
        
        
        












<?
for($z = 1; $z<=3; $z++){
?>
<tr>
		  <td colspan="2"><hr /></td>
		</tr>
<tr>
		  <td class="tr" width="187">Название компании</td>
		  <td><input class="input" style="width:247px" type="text" name="opit_company<? echo $z;?>" value=""/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">Должность</td>
		  <td><input  class="input" style="width:247px" type="text" name="opit_dolj<? echo $z;?>" value=""/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">Период работы</td>
		  <td>
		  с
		   <select name="opit_mon<? echo $z;?>" class="select" style="width:100px">
					<option value="0" >Месяц</option>
			<?
			for($i = 1; $i <= 12; $i++){
							echo '<option value="'.$i.'">'.$mon[$i].'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="opit_year<? echo $z;?>" class="select" style="width:100px">
					<option value="0" >Год</option>
			<?
			for($i = date('Y'); $i >= 1930; $i--){
							echo '<option value="'.$i.'">'.$i.'</option>';
							
					}
					?>
		  </select>
		  
		  по
		  
		   <select name="opit_mon_to<? echo $z;?>" class="select" style="width:100px">
					<option value="0" >Месяц</option>
			<?
			for($i = 1; $i <= 12; $i++){
							echo '<option value="'.$i.'">'.$mon[$i].'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="opit_year_to<? echo $z;?>" class="select" style="width:100px">
					<option value="0" >Год</option>
			<?
			for($i = date('Y'); $i >= 1930; $i--){
							echo '<option value="'.$i.'">'.$i.'</option>';
							
					}
					?>
		  </select>
		  
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">Описание работы</td>
		  <td><textarea class="textarea"  style="width:247px" type="text" name="opit_descr<? echo $z;?>"></textarea></td>
		</tr>

<?
}
?>
        
        <?
        /*
		TODO
		ДОбавить еще место работы
		
		Если добавить описания разных мест работы, они автоматически сгруппируются по дате: в резюме работодатель увидит первым описание последнего места работы.
		*/
		?>
	  <!--  <tr>
		  <td>Рекомендации (ФИО, должность, телефон, комментарий)</td>
		  <td><input type="text" name="" value=""/></td>
		</tr>
			-->
	  </table>
      <div style="padding-top:15px;">
		<img src="/img/point_left.gif" border="0" alt="Предыдущий шаг"/> <a href="#" onclick="choose_vk(3);return false;" class="a_big">Предыдущий шаг</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	  <a href="#" onclick="choose_vk(5);return false;" class="a_big" >Следующий шаг</a> <img src="/img/point_right.gif" border="0" alt="Следующий шаг"/>
      </div>
	</div>
	
	   
	   
	   
	   <div class="step" id="step5" style="display:none;"> <span class="text_grey_sm">5 шаг -</span> <span class="h3"> Образование: </span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		<tr>
		  <td class="tr" width="187">Основное</td>
		  <td>
	<input type="radio" name="ed_main" value="высшее" checked/><div class="div_left" style="margin:3px 0 0 5px">высшее</div><div style="clear:both; margin-bottom:5px"></div>
	<input type="radio" name="ed_main" value="неполное высшее"/><div class="div_left" style="margin:3px 0 0 5px">неполное высшее</div><div style="clear:both; margin-bottom:5px"></div>
	<input type="radio" name="ed_main" value="среднее специальное"/><div class="div_left" style="margin:3px 0 0 5px">среднее специальное</div><div style="clear:both; margin-bottom:5px"></div>
	<input type="radio" name="ed_main" value="среднее"/><div class="div_left" style="margin:3px 0 0 5px">среднее</div><div style="clear:both; margin-bottom:5px"></div>
    
    <div class="text_grey_sm">
    Основное образование — то образование, на котором вы делаете упор в своей профессиональной деятельности.
    </div>
	</td>
		</tr>
		<tr>
		  <td class="tr" width="187">Название учебного заведения<span class="text_red">*</span></td>
		  <td><input class="input" style="width:247px" type="text" name="ed_name" value=""/></td>
		</tr>
		<!--tr>
		  <td class="tr" width="187">Полученное образование</td>
		  <td><select name="ed_get" class="select" style="width:250px" >
		  
		  </select></td>
		</tr-->
		<tr>
		  <td class="tr" width="187">Годы обучения<span class="text_red">*</span></td>
		  <td>с <input class="input" style="width:50px;text-align:center;" type="text" name="ed_y_from" value="" /> по  <input class="input" style="width:50px;text-align:center;"  type="text" name="ed_y_to" value="" /> </td>
		</tr>
		<tr>
		  <td class="tr" width="187">Специальность</td>
		  <td><input  class="input" style="width:247px"type="text" name="ed_spec" value=""/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">Комментарий</td>
		  <td><textarea class="textarea" style="width:247px" type="text" name="ed_comm"></textarea></td>
		</tr>
        
        
<?
for($z = 1; $z <= 3; $z++){
?>
<tr><td colspan="2"><hr /></td></tr>
<tr>
		  <td class="tr" width="187">Название учебного заведения</td>
		  <td><input class="input" style="width:247px" type="text" name="ed_name<? echo $z;?>" value=""/></td>
		</tr>

		<tr>
		  <td class="tr" width="187">Годы обучения</td>
		  <td>с <input class="input" style="width:50px;text-align:center;" type="text" name="ed_y_from<? echo $z;?>" value="" /> по  <input class="input" style="width:50px;text-align:center;"  type="text" name="ed_y_to<? echo $z;?>" value="" /> </td>
		</tr>
		<tr>
		  <td class="tr" width="187">Специальность</td>
		  <td><input  class="input" style="width:247px"type="text" name="ed_spec<? echo $z;?>" value=""/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">Комментарий</td>
		  <td><textarea class="textarea" style="width:247px" type="text" name="ed_comm<? echo $z;?>"></textarea></td>
		</tr>
<?	
}
?>        
        
        
	  </table>
      
      <?
	  /*
	  Полученное образование
	  
	  TODO
      Если добавить описания разных мест учебы, они автоматически сгруппируются по дате: в резюме работодатель увидит первым описание последнего места учебы.
*/
	  ?>
      
      <div style="padding-top:15px;">
		<img src="/img/point_left.gif" border="0" alt="Предыдущий шаг"/> <a href="#" onclick="choose_vk(4);return false;" class="a_big">Предыдущий шаг</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	  <a href="#" onclick="choose_vk(6);return false;" class="a_big" >Следующий шаг</a> <img src="/img/point_right.gif" border="0" alt="Следующий шаг"/>
      </div>
	</div>
	
	   
	   
	   
	   <div class="step" id="step6" style="display:none;"><span class="text_grey_sm">6 шаг -</span> <span class="h3">Дополнительная информация:</span>
	
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
	
	   <!-- <tr>
		  <td>Родной язык (Выпадающий список)</td>
		  <td><input type="text" name="" value=""/></td>
		</tr>
		<tr>
		  <td>Другие языки (Выпадающий список)</td>
		  <td><input type="text" name="" value=""/></td>
		</tr>-->
		<tr>
		  <td class="tr" width="187">Профессиональные навыки </td>
		  <td><textarea class="textarea" style="width:247px;	margin-bottom:10px;" type="text" name="dop_prof"></textarea></td>
		</tr>
		<tr>
		  <td class="tr" width="187">О себе </td>
		  <td><textarea class="textarea" style="width:247px" type="text" name="dop_about"></textarea></td>
		</tr>
		<!--tr>
		  <td class="tr" width="187">Гражданство</td>
		  <td>
		  <select class="select"  name="grajd">
		  <?
		  $city = $q->select("select * from ".$prefix."grajd where status=1 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'">'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  
		</tr-->
		<tr>
		<td></td>
		<td style="padding-top:15px">
		<input type="image" src="/img/button_add.png" />
		</td>
		</tr>
	   <!-- <tr>
		  <td>Семейное положение</td>
		  <td><input type="text" name="" value=""/></td>
		</tr>
		<tr>
		  <td>Водительские права</td>
		  <td><input type="text" name="" value=""/></td>
		</tr>
		<tr>
		  <td>Рекомендации</td>
		  <td><input type="text" name="" value=""/></td>
		</tr>
			-->
	  </table>
	  <div style="padding-top:15px;">
		<img src="/img/point_left.gif" border="0" alt="Предыдущий шаг"/> <a href="#" onclick="choose_vk(5);return false;" class="a_big">Предыдущий шаг</a> 
	  <!--<img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> <a href="#" onclick="$('#step6').hide();$('#step7').show();return false;" class="a_big" >Следующий шаг</a>-->
      </div>
	</div>
	
	<!--
	   
	   
	   
	   <div class="step" id="step7" style="display:none;"> 7) <strong>Настройки размещения</strong>
	  <table>
		<tr>
		  <td>Кто увидит ваше резюме</td>
		  <td><input type="text" name="" value=""/></td>
		</tr>
		<tr>
		  <td>Скрывать фамилию и контактные данные</td>
		  <td><input type="text" name="" value=""/></td>
		</tr>
	  </table>
		<a href="#" onclick="choose_vk(6);return false;" class="a_big">Предыдущий шаг</a>
	
	</div> -->
	</form>
	
	<?
	}//else if($added == 'true'){
//}//if(empty($_SESSION['user_info']['id'])){

?>