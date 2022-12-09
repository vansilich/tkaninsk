<?
/*
echo '$q->insert("insert into ".$prefix."resume set';
foreach($_POST as $k=>$v){
	//echo '$'.$k.'=get_param(\''.$k.'\');<br>';	
//	echo 'ALTER TABLE  `job_resume` ADD  `'.$k.'` VARCHAR( 255 ) NOT NULL;<br>';
		echo $k.'=".to_sql($'.$k.').",<br>	';
}
*/

$cmd = get_param('cmd');
if($cmd == 'add'){
	$surname=get_param('surname');
	$name=get_param('name');
	$otch=get_param('otch');
	$sex=get_param('sex');
	$bday=get_param('bday');
	$bmon=get_param('bmon');
	$byear=get_param('byear');
	$mail=get_param('mail');
	$phoneCountryCode=get_param('phoneCountryCode');
	$phoneCode=get_param('phoneCode');
	$phoneNumber=get_param('phoneNumber');
	$phoneCountryCode2=get_param('phoneCountryCode2');
	$phoneCode2=get_param('phoneCode2');
	$phoneNumber2=get_param('phoneNumber2');
	$dolj=get_param('dolj');
	$zp=get_param('zp');
	$komand=get_param('komand');
	$opit_company=get_param('opit_company');
	$opit_dolj=get_param('opit_dolj');
	$opit_mon=get_param('opit_mon');
	$opit_year=get_param('opit_year');
	$opit_mon_to=get_param('opit_mon_to');
	$opit_year_to=get_param('opit_year_to');
	$opit_descr=get_param('opit_descr');
	$ed_name=get_param('ed_name');
	$ed_y_from=get_param('ed_y_from');
	$ed_y_to=get_param('ed_y_to');
	$ed_spec=get_param('ed_spec');
	$ed_comm=get_param('ed_comm');
	$dop_prof=get_param('dop_prof');
	$dop_about=get_param('dop_about');
	
	$new_id = $q->insert("insert into ".$prefix."resume set
		date_add = NOW(),
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
		dolj=".to_sql($dolj).",
		zp=".to_sql($zp).",
		komand=".to_sql($komand).",
		opit_company=".to_sql($opit_company).",
		opit_dolj=".to_sql($opit_dolj).",
		opit_mon=".to_sql($opit_mon).",
		opit_year=".to_sql($opit_year).",
		opit_mon_to=".to_sql($opit_mon_to).",
		opit_year_to=".to_sql($opit_year_to).",
		opit_descr=".to_sql($opit_descr).",
		ed_name=".to_sql($ed_name).",
		ed_y_from=".to_sql($ed_y_from).",
		ed_y_to=".to_sql($ed_y_to).",
		ed_spec=".to_sql($ed_spec).",
		ed_comm=".to_sql($ed_comm).",
		dop_prof=".to_sql($dop_prof).",
		dop_about=".to_sql($dop_about)."");
	

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


}
?>

<script>
function check_step1(){

	return true;	
}
function check_step2(){

	return true;	
}
function check_step3(){

	return true;	
}
function check_step4(){

	return true;	
}
function check_step5(){

	return true;	
}
function check_step6(){

	return true;	
}
</script>


<div>
<a  href="#" onclick="$('.step').hide();$('#step1').show();">Шаг1</a> | 
<a  href="#" onclick="$('.step').hide();$('#step2').show();">Шаг2</a> | 
<a  href="#" onclick="$('.step').hide();$('#step3').show();">Шаг3</a> | 
<a  href="#" onclick="$('.step').hide();$('#step4').show();">Шаг4</a> | 
<a  href="#" onclick="$('.step').hide();$('#step5').show();">Шаг5</a> | 
<a  href="#" onclick="$('.step').hide();$('#step6').show();">Шаг6</a> | 
<!--a  href="#" onclick="$('.step').hide();$('#step7').show();">Шаг7</a> | -->

</div>

<form  method="POST" name="registerForm" id="registerForm"  enctype="multipart/form-data" >
<input type="hidden" name="cmd" value="add" />
<div class="step" id="step1"> 1)<b>Заполнение личных данных:</b>
  <table>
    <tr>
      <td>ФИО</td>
      <td>
      <input type="text" name="surname"  placeholder="Фамилия"value=""/>
      <input type="text" name="name"  placeholder="Имя" value=""/>
      <input type="text" name="otch"  placeholder="Отчество"value=""/>
      </td>
    </tr>
    <tr>
      <td>Пол</td>
      <td>
      <input type="radio" name="sex" value="1" checked /> Мужской
      <input type="radio" name="sex" value="2"/> Женский
      </td>
    </tr>
    <tr>
      <td>Дата рождения (Выпадающий список)</td>
      <td>
      <select name="bday">
		<option value="0" >День</option>
        <?
        for($i = 1; $i <= 31; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
			
		}
		?>
      </select>
      
      <select name="bmon">
		<option value="0" >Месяц</option>
        <?
        for($i = 1; $i <= 12; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
			
		}
		?>
      </select>
      
      <select name="byear">
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
      <td>Фото</td>
      <td><input type="file" name="foto" value=""/></td>
    </tr>
    <tr>
      <td>Место жительства </td>
      <td>Москва</td>
    </tr>
  </table>
  <a href="#" onclick="
  if(check_step1()){
  	$('#step1').hide();$('#step2').show();
  }
  return false;">Следующий шаг</a>
</div>
<div class="step" id="step2" style="display:none;"> 2) <strong>Заполнение контактов</strong>:
  <table>
    <tr>
      <td>Email</td>
      <td><input type="text" name="mail" value=""/></td>
    </tr>
    <tr>
      <td>Мобильный телефон</td>
      <td>
      			<table style="width: 88%;">
				<tr>
				  <td><span class="phonePlus">+</span>
					<input type="text" maxlength="3" size="3" id="phoneCountryCode" name="phoneCountryCode" value="<? 
					if(!empty($phoneCountryCode))echo htmlspecialchars($phoneCountryCode);
					else echo '7';
					?>"/></td>
				  <td  style="width:77px;"> (
					<input type="text" maxlength="6" size="5" id="phoneCode" name="phoneCode" placeholder="код"	value="<? echo htmlspecialchars($phoneCode);?>" />
					) </td>
				  <td ><input type="text" maxlength="7" name="phoneNumber" id="phoneNumber" placeholder="номер" value="<? echo htmlspecialchars($phoneNumber);?>"/></td>
				</tr>
			  </table></td>
    </tr>
    <tr>
      <td>Домашний телефон</td>
      <td>
      			<table style="width: 88%;">
				<tr>
				  <td><span class="phonePlus">+</span>
					<input type="text" maxlength="3" size="3" id="phoneCountryCode2" name="phoneCountryCode2" value="<? 
					if(!empty($phoneCountryCode2))echo htmlspecialchars($phoneCountryCode2);
					else echo '7';
					?>"/></td>
				  <td  style="width:77px;"> (
					<input type="text" maxlength="6" size="5" id="phoneCode2" name="phoneCode2" placeholder="код"	value="<? echo htmlspecialchars($phoneCode2);?>" />
					) </td>
				  <td ><input type="text" maxlength="7" name="phoneNumber2" id="phoneNumber2" placeholder="номер" value="<? echo htmlspecialchars($phoneNumber2);?>"/></td>
				</tr>
			  </table>
      </td>
    </tr>
    <tr>
      <td>Предпочтительный способ связи </td>
      <td> 
      <input type="checkbox" name="s_email" value="1" />email
      <input type="checkbox" name="s_mob" value="1" />мобильный
      <input type="checkbox" name="s_home" value="1" />домашний
	</td>
    </tr>
  </table>
  <a href="#" onclick="$('#step2').hide();$('#step1').show();">Предыдущий шаг</a> | 
  <a href="#" onclick="$('#step2').hide();$('#step3').show();">Следующий шаг</a>
  
</div>
<div class="step" id="step3" style="display:none;"> 3) <strong>Пожелания к работе</strong>:
  <table>
    <tr>
      <td>Должность* </td>
      <td><input type="text" name="dolj" value=""/></td>
    </tr>
    <tr>
      <td>Профессиональная сфера </td>
      <td>Выбор</td>
    </tr>
    <tr>
      <td>Минимальная зарплата* </td>
      <td><input type="text" name="zp" value=""/></td>
    </tr>
    <tr>
      <td>График работы</td>
      <td>
        <input type="checkbox" name="gr_full" value="1" /> полный рабочий день
        <input type="checkbox" name="gr_smen" value="1" />сменный
        <input type="checkbox" name="gr_free" value="1" />свободный
        <input type="checkbox" name="gr_chast" value="1" />частичная занятость
        <input type="checkbox" name="gr_far" value="1" />удаленная работа
        
        </td>
    </tr>
    <tr>
      <td>Место работы</td>
      <td>Город</td>
    </tr>
    <tr>
      <td>Командировки</td>
      <td> 
        <input type="radio" name="komand" value="1" checked />Готов к командировкам
        <input type="radio" name="komand" value="2" checked />не готов к командировкам </td>
    </tr>
  </table>
    <a href="#" onclick="$('#step3').hide();$('#step2').show();">Предыдущий шаг</a> | 
  <a href="#" onclick="$('#step3').hide();$('#step4').show();">Следующий шаг</a>
</div>
<div class="step" id="step4" style="display:none;"> 4) <strong>Опыт работы</strong>
  <table>
    <tr>
      <td>Название компании</td>
      <td><input type="text" name="opit_company" value=""/></td>
    </tr>
    <tr>
      <td>Отрасль бизнеса</td>
      <td>Выбор</td>
    </tr>
    <tr>
      <td>Должность</td>
      <td><input type="text" name="opit_dolj" value=""/></td>
    </tr>
    <tr>
      <td>Период работы </td>
      <td>
      С
       <select name="opit_mon">
		<option value="0" >Месяц</option>
        <?
        for($i = 1; $i <= 12; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
			
		}
		?>
      </select>
      
      <select name="opit_year">
		<option value="0" >Год</option>
        <?
        for($i = 1930; $i <= date('Y')-14; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
			
		}
		?>
      </select>
      
      по
      
       <select name="opit_mon_to">
		<option value="0" >Месяц</option>
        <?
        for($i = 1; $i <= 12; $i++){
			echo '<option value="'.$i.'">'.$i.'</option>';
			
		}
		?>
      </select>
      
      <select name="opit_year_to">
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
      <td>Описание работы</td>
      <td><textarea type="text" name="opit_descr"></textarea></td>
    </tr>
  <? /*  <tr>
      <td>Рекомендации (ФИО, должность, телефон, комментарий)</td>
      <td><input type="text" name="" value=""/></td>
    </tr>
	*/ ?>
  </table>
    <a href="#" onclick="$('#step4').hide();$('#step3').show();">Предыдущий шаг</a> | 
  <a href="#" onclick="$('#step4').hide();$('#step5').show();">Следующий шаг</a>
</div>
<div class="step" id="step5" style="display:none;"> 5) <strong>Образование</strong> (Возможность добавления нескольких мест)
  <table>
    <tr>
      <td>Основное</td>
      <td>
<input type="radio" name="ed_main" value="1"/>высшее
<input type="radio" name="ed_main" value="2"/>неполное высшее
<input type="radio" name="ed_main" value="3"/>среднее специальное
<input type="radio" name="ed_main" value="4"/>среднее
</td>
    </tr>
    <tr>
      <td>Название учебного заведения</td>
      <td><input type="text" name="ed_name" value=""/></td>
    </tr>
    <tr>
      <td>Полученное образование</td>
      <td><select name="ed_get">
      
      </select></td>
    </tr>
    <tr>
      <td>Годы обучения</td>
      <td>с<input type="text" name="ed_y_from" value=""/> по <input type="text" name="ed_y_to" value=""/> </td>
    </tr>
    <tr>
      <td>Специальность</td>
      <td><input type="text" name="ed_spec" value=""/></td>
    </tr>
    <tr>
      <td>Комментарий</td>
      <td><textarea type="text" name="ed_comm"></textarea></td>
    </tr>
  </table>
    <a href="#" onclick="$('#step5').hide();$('#step4').show();">Предыдущий шаг</a> | 
  <a href="#" onclick="$('#step5').hide();$('#step6').show();">Следующий шаг</a>
</div>
<div class="step" id="step6" style="display:none;"> 6) <strong>Дополнительная информация</strong>

  <table>
   <? /* <tr>
      <td>Родной язык (Выпадающий список)</td>
      <td><input type="text" name="" value=""/></td>
    </tr>
    <tr>
      <td>Другие языки (Выпадающий список)</td>
      <td><input type="text" name="" value=""/></td>
    </tr>*/ ?>
    <tr>
      <td>Профессиональные навыки </td>
      <td><input type="text" name="dop_prof" value=""/></td>
    </tr>
    <tr>
      <td>О себе </td>
      <td><input type="text" name="dop_about" value=""/></td>
    </tr>
    <tr>
      <td>Гражданство</td>
      <td>Выбор</td>
    </tr>
   <? /* <tr>
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
	*/ ?>
  </table>
    <a href="#" onclick="$('#step6').hide();$('#step5').show();">Предыдущий шаг</a> | 
  <? /*<a href="#" onclick="$('#step6').hide();$('#step7').show();">Следующий шаг</a>*/ ?>
</div>
<input type="submit" value="Добавить" />
<? /*<div class="step" id="step7" style="display:none;"> 7) <strong>Настройки размещения</strong>
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
    <a href="#" onclick="$('#step7').hide();$('#step6').show();">Предыдущий шаг</a>

</div> */ ?>
</form>









   
      
 <div class="h2" style="margin:0 0 10px 0">Личный кабинет</div>     
   <form  method="POST" name="registerForm" id="registerForm"  enctype="multipart/form-data" >
<input type="hidden" name="cmd" value="add" />
<div class="step" id="step1"><span class="text_grey_sm">1 шаг -</span> <span class="h3">Заполнение личных данных:</span>
  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
    <tr>
      <td class="tr" width="187">Фамилия, Имя,<br />Отчество</td>
      <td>
      <input class="input" style="width:200px" type="text" name="surname"  placeholder="Фамилия" value=""/>
      <input class="input" style="width:200px"  type="text" name="name"  placeholder="Имя" value=""/><br />
      <input class="input" style="width:200px; margin-top:5px"  type="text" name="otch"  placeholder="Отчество"value=""/>
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
      <td class="tr" width="187">Дата рождения<br /><span class="text_grey_sm">(Выпадающий список)</span></td>
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
                        echo '<option value="'.$i.'">'.$i.'</option>';
                        
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
      <td><input type="file" name="foto" value=""/></td>
    </tr>
    <tr>
      <td class="tr" width="187">Место жительства </td>
      <td>Москва</td>
    </tr>
  </table>
  <a href="#" onclick="
  
        $('#step1').hide();$('#step2').show();
 
  return false;" class="a_big">Следующий шаг > </a>
</div>
<div class="step" id="step2" style="display:none1;"> <span class="text_grey_sm">2 шаг -</span> <span class="h3"> Заполнение контактов:</span>
  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
    <tr>
      <td class="tr" width="187">Email</td>
      <td style="padding-left:11px"><input class="input" style="width:215px" type="text" name="mail" value=""/></td>
    </tr>
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
  <a href="#" onclick="$('#step2').hide();$('#step1').show();">Предыдущий шаг</a> | 
  <a href="#" onclick="$('#step2').hide();$('#step3').show();">Следующий шаг</a>
  
</div>
<div class="step" id="step3" style="display:none1;">  <span class="text_grey_sm">3 шаг -</span> <span class="h3"> Пожелания к работе:</span>
  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
    <tr>
      <td class="tr" width="187">Должность<span class="text_red">*</span> </td>
      <td><input class="input" style="width:247px"  type="text" name="dolj" value=""/></td>
    </tr>
    <tr>
      <td class="tr" width="187">Профессиональная сфера </td>
      <td><a href="" class="a_in">Выбрать</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /></td>
    </tr>
    <tr>
      <td class="tr" width="187">Минимальная зарплата<span class="text_red">*</span></td>
      <td><input  class="input" style="width:247px" type="text" name="zp" value=""/></td>
    </tr>
    <tr>
      <td class="tr" width="187">График работы</td>
      <td>
        <input type="checkbox" name="gr_full" value="1" /><div class="div_left" style="margin:3px 0 0 5px">полный рабочий день</div><div style="clear:both; margin-bottom:5px"></div>
        <input type="checkbox" name="gr_smen" value="1" /><div class="div_left" style="margin:5px 0 0 5px">сменный</div><div style="clear:both; margin-bottom:5px"></div>
        <input type="checkbox" name="gr_free" value="1" /><div class="div_left" style="margin:5px 0 0 5px">свободный</div><div style="clear:both; margin-bottom:5px"></div>
        <input type="checkbox" name="gr_chast" value="1" /><div class="div_left" style="margin:5px 0 0 5px">частичная занятость</div><div style="clear:both; margin-bottom:5px"></div>
        <input type="checkbox" name="gr_far" value="1" /><div class="div_left" style="margin:5px 0 0 5px">удаленная работа</div><br style="clear:both" />
        
        </td>
    </tr>
    <tr>
      <td class="tr" width="187">Место работы</td>
      <td class="tr" width="187"><a href="" class="a_in">Выбрать</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /></td>
    </tr>
    <tr>
      <td class="tr" width="187">Командировки</td>
      <td> 
        <input type="radio" name="komand" value="1" checked /><div class="div_left" style="margin:3px 10px 0 5px">Готов к командировкам</div><div style="clear:both; margin-bottom:5px"></div>
        <input type="radio" name="komand" value="2" checked /><div class="div_left" style="margin:3px 10px 0 5px">Не готов к командировкам</div>
        <br style="clear:both" /> </td>
    </tr>
  </table>
    <a href="#" onclick="$('#step3').hide();$('#step2').show();">Предыдущий шаг</a> | 
  <a href="#" onclick="$('#step3').hide();$('#step4').show();">Следующий шаг</a>
</div>
<div class="step" id="step4" style="display:none1;"> <span class="text_grey_sm">4 шаг -</span> <span class="h3"> Опыт работы:</span>
  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
    <tr>
      <td class="tr" width="187">Название компании</td>
      <td><input class="input" style="width:247px" type="text" name="opit_company" value=""/></td>
    </tr>
    <tr>
      <td class="tr" width="187">Отрасль бизнеса</td>
      <td><a href="" class="a_in">Выбрать</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /></td>
    </tr>
    <tr>
      <td class="tr" width="187">Должность</td>
      <td><input  class="input" style="width:247px" type="text" name="opit_dolj" value=""/></td>
    </tr>
    <tr>
      <td class="tr" width="187">Период работы </td>
      <td>
      с
       <select name="opit_mon" class="select" style="width:100px">
                <option value="0" >Месяц</option>
        <?
        for($i = 1; $i <= 12; $i++){
                        echo '<option value="'.$i.'">'.$i.'</option>';
                        
                }
                ?>
      </select>
      
      <select name="opit_year" class="select" style="width:100px">
                <option value="0" >Год</option>
        <?
        for($i = 1930; $i <= date('Y')-14; $i++){
                        echo '<option value="'.$i.'">'.$i.'</option>';
                        
                }
                ?>
      </select>
      
      по
      
       <select name="opit_mon_to" class="select" style="width:100px">
                <option value="0" >Месяц</option>
        <?
        for($i = 1; $i <= 12; $i++){
                        echo '<option value="'.$i.'">'.$i.'</option>';
                        
                }
                ?>
      </select>
      
      <select name="opit_year_to" class="select" style="width:100px">
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
      <td class="tr" width="187">Описание работы</td>
      <td><textarea class="textarea"  style="width:247px" type="text" name="opit_descr"></textarea></td>
    </tr>
  <!--  <tr>
      <td>Рекомендации (ФИО, должность, телефон, комментарий)</td>
      <td><input type="text" name="" value=""/></td>
    </tr>
        -->
  </table>
    <a href="#" onclick="$('#step4').hide();$('#step3').show();">Предыдущий шаг</a> | 
  <a href="#" onclick="$('#step4').hide();$('#step5').show();">Следующий шаг</a>
</div>
<div class="step" id="step5" style="display:none1;"> <span class="text_grey_sm">5 шаг -</span> <span class="h3"> Образование: <span class="text_grey_sm">(Возможность добавления нескольких мест)</span></span>
  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
    <tr>
      <td class="tr" width="187">Основное</td>
      <td>
<input type="radio" name="ed_main" value="1"/><div class="div_left" style="margin:3px 0 0 5px">высшее</div><div style="clear:both; margin-bottom:5px"></div>
<input type="radio" name="ed_main" value="2"/><div class="div_left" style="margin:3px 0 0 5px">неполное высшее</div><div style="clear:both; margin-bottom:5px"></div>
<input type="radio" name="ed_main" value="3"/><div class="div_left" style="margin:3px 0 0 5px">среднее специальное</div><div style="clear:both; margin-bottom:5px"></div>
<input type="radio" name="ed_main" value="4"/><div class="div_left" style="margin:3px 0 0 5px">среднее</div><div style="clear:both; margin-bottom:5px"></div>
</td>
    </tr>
    <tr>
      <td class="tr" width="187">Название учебного заведения</td>
      <td><input class="input" style="width:247px" type="text" name="ed_name" value=""/></td>
    </tr>
    <tr>
      <td class="tr" width="187">Полученное образование</td>
      <td><select name="ed_get" class="select" style="width:250px" >
      
      </select></td>
    </tr>
    <tr>
      <td class="tr" width="187">Годы обучения</td>
      <td>с<input class="input" style="width:50px" type="text" name="ed_y_from" value=""/> по <input class="input" style="width:50px"  type="text" name="ed_y_to" value=""/> </td>
    </tr>
    <tr>
      <td class="tr" width="187">Специальность</td>
      <td><input  class="input" style="width:247px"type="text" name="ed_spec" value=""/></td>
    </tr>
    <tr>
      <td class="tr" width="187">Комментарий</td>
      <td><textarea class="textarea" style="width:247px" type="text" name="ed_comm"></textarea></td>
    </tr>
  </table>
    <a href="#" onclick="$('#step5').hide();$('#step4').show();">Предыдущий шаг</a> | 
  <a href="#" onclick="$('#step5').hide();$('#step6').show();">Следующий шаг</a>
</div>
<div class="step" id="step6" style="display:none1;"><span class="text_grey_sm">6 шаг -</span> <span class="h3">Дополнительная информация:</span>

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
      <td><textarea class="textarea" style="width:247px" type="text" name="ed_comm"></textarea></td>
    </tr>
    <tr>
      <td class="tr" width="187">О себе </td>
      <td><textarea class="textarea" style="width:247px" type="text" name="ed_comm"></textarea></td>
    </tr>
    <tr>
      <td class="tr" width="187">Гражданство</td>
      <td><a href="" class="a_in">Выбрать</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /></td>
    </tr>
    <tr>
    <td></td>
    <td style="padding-top:15px">
    <a href=""><img src="/img/button_add.png" width="136" height="43" alt="Добавить" title="Добавить" border="none" /></a>
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
  
    <a href="#" onclick="$('#step6').hide();$('#step5').show();">Предыдущий шаг</a> | 
  <!--<a href="#" onclick="$('#step6').hide();$('#step7').show();">Следующий шаг</a>-->
</div>

<!--<div class="step" id="step7" style="display:none;"> 7) <strong>Настройки размещения</strong>
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
    <a href="#" onclick="$('#step7').hide();$('#step6').show();">Предыдущий шаг</a>

</div> -->
</form>

