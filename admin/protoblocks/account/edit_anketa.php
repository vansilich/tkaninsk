<?
$mon = array();
$mon[1] = '������';
$mon[2] = '�������';
$mon[3] = '����';
$mon[4] = '������';
$mon[5] = '���';
$mon[6] = '����';
$mon[7] = '����';
$mon[8] = '������';
$mon[9] = '��������';
$mon[10] = '�������';
$mon[11] = '������';
$mon[12] = '�������';

if(empty($_SESSION['user_info']['id'])){
	$temp = $q->select1("select * from ".$prefix."tmpl where id=5");
	echo '<div class="msg">'.$temp['text'].'</div>';			
}else{
	$id = strip_tags(get_param('id'));
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
		$dolj=strip_tags(get_param('dolj'));
		$zp=strip_tags(get_param('zp'));
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
		
			
		
		$new_id = $id;
		$q->exec("update ".$prefix."resume set
			user_id = ".to_sql($_SESSION['user_info']['id']).",
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
			status=0,
			rabota_city=".to_sql($rabota_city)."
			where user_id=".to_sql($_SESSION['user_info']['id'])." and id=".to_sql($id));
			
					
	
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
			
			header('location: /account/');
	
	
	}
	$row = $q->select1("select * from ".$prefix."resume where user_id=".to_sql($_SESSION['user_info']['id'])." and id=".to_sql($id));
	
	
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
	
	 <div class="h2" style="margin:0 0 10px 0">�������� ������:</div>     
	
	<!--div>
	<a  href="#" onclick="$('.step').hide();$('#step1').show();return false;">���1</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step2').show();return false;">���2</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step3').show();return false;">���3</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step4').show();return false;">���4</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step5').show();return false;">���5</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	<a  href="#" onclick="$('.step').hide();$('#step6').show();return false;">���6</a> 
	<img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> <!--a  href="#" onclick="$('.step').hide();$('#step7').show();return false;">���7</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> >
	
	</div-->
<script>
function choose_vk(n){
	for(i=1;i<n;i++){
		$('#vk'+i).attr('class','vk'+i);
	}
	$('#vk'+i).attr('class','vk'+i+'_ac');
	for(i=n+1;i<=6;i++){
		$('#vk'+i).attr('class','vk'+i+'_no_ac');
	}
	$('.step').hide();$('#step'+n).show();
	
}
</script>    
<div class="div_center">
<table cellpadding="0" cellspacing="0">
<tr>
<td><a href="#" onclick="choose_vk(1);return false;" class="vk1_ac" id="vk1"></a></td>
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
	
	   
	   
	   
	   <div class="step" id="step1"><span class="text_grey_sm">1 ��� -</span> <span class="h3">���������� ������ ������:</span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		<tr>
		  <td class="tr" width="187">�������,<br />
           ���,��������<span class="text_red">*</span></td>
		  <td>
		  <input class="input" style="width:200px" type="text" name="surname"  placeholder="�������" value="<? echo htmlspecialchars($row['surname']);?>"/><br />
		  <input class="input" style="width:200px"  type="text" name="name"  placeholder="���" value="<? echo htmlspecialchars($row['name']);?>"/>
		  <input class="input" style="width:200px; margin-top:5px"  type="text" name="otch"  placeholder="��������"  value="<? echo htmlspecialchars($row['name']);?>"/>
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">���</td>
		  <td>
		  <input type="radio" name="sex" value="1" checked /> <div class="div_left" style="margin:3px 10px 0 5px">�������</div>
		  
		  <input type="radio" name="sex" value="2"/> <div class="div_left" style="margin:3px 0 0 5px">�������</div>
		  <br style="clear:both" />
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">���� ��������<span class="text_red">*</span></td>
		  <td>
		  <select name="bday" class="select" style="width:100px">
					<option value="0" >����</option>
			<?
			for($i = 1; $i <= 31; $i++){
							echo '<option value="'.$i.'"';
							if($i == $row['bday']) echo ' selected';
							echo '>'.$i.'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="bmon" class="select" style="width:100px">
					<option value="0" >�����</option>
			<?
			for($i = 1; $i <= 12; $i++){
							echo '<option value="'.$i.'"';
							if($i == $row['bmon']) echo ' selected';
							echo '>'.$mon[$i].'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="byear" class="select" style="width:100px">
					<option value="0" >���</option>
			<?
			for($i = 1930; $i <= date('Y')-14; $i++){
							echo '<option value="'.$i.'"';
							if($i == $row['byear']) echo ' selected';
							echo '>'.$i.'</option>';
							
					}
					?>
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">����</td>
		  <td><input type="file" name="foto" value=""/><br />
          <div class="text_grey_sm">
          JPG, PNG, TIFF (�� ����� 2Mb).<br />
�� ���������� ������ ���� ����� ����� ���� ���������, ������ �� ������ ��������� ������� (��������, �������� �������) � ��������� ����������.

          </div>
          
          </td>
		</tr>
		<tr>
		  <td class="tr" width="187">����� ����������<span class="text_red">*</span></td>
		  <td>
		  <select  class="select" name="city">
		  <?
		  $city = $q->select("select * from ".$prefix."city where status=1 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'"';
				if($c['id'] == $row['city']) echo ' selected';
				echo '>'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">�����<span class="text_red">*</span></td>
		  <td>
		  <select class="select" name="metro">
		  <?
		  $city = $q->select("select * from ".$prefix."metro where status=1 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'"';
				if($c['id'] == $row['metro']) echo ' selected';
				echo '>'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  </td>
		</tr>
	  </table>
      
      <div style="padding-top:15px;">
	  <a href="#" onclick="
			choose_vk(2);return false;" class="a_big" >��������� ���</a> <img src="/img/point_right.gif" border="0" alt="��������� ���"/> 
      </div>
	</div>
	
	   
	   
	   
	   <div class="step" id="step2" style="display:none;"> <span class="text_grey_sm">2 ��� -</span> <span class="h3"> ���������� ���������:</span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		<tr>
		  <td class="tr" width="187">Email<span class="text_red">*</span></td>
		  <td style="padding-left:11px"><input class="input" style="width:215px" type="text" name="mail" value="<? echo htmlspecialchars($row['mail']);?>"/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">��������� �������</td>
		  <td><span class="phonePlus">+</span>
											<input class="input" style="width:50px"  type="text" maxlength="3" size="3" id="phoneCountryCode" name="phoneCountryCode" value="<? 
											if(!empty($phoneCountryCode))echo htmlspecialchars($row['phoneCountryCode']);
											else echo '7';
											?>"/><b>(</b>
											<input  style="width:50px" type="text" class="input" style="width:100px"  maxlength="6" size="5" id="phoneCode" name="phoneCode" placeholder="���"     value="<? echo htmlspecialchars($row['phoneCode']);?>" /> <b>)</b>
									 <input  class="input" style="width:247px" type="text" maxlength="7" name="phoneNumber" id="phoneNumber" placeholder="�����" value="<? echo htmlspecialchars($row['phoneNumber']);?>"/></td>
	</td>
		</tr>
		<tr>
		  <td class="tr" width="187">�������� �������</td>
	   <td><span class="phonePlus">+</span>
											<input class="input" style="width:50px"  type="text" maxlength="3" size="3" id="phoneCountryCode" name="phoneCountryCode" value="<? 
											if(!empty($phoneCountryCode))echo htmlspecialchars($row['phoneCountryCode']);
											else echo '7';
											?>"/><b>(</b>
											<input class="input" style="width:50px" type="text" class="input" style="width:100px"  maxlength="6" size="5" id="phoneCode" name="phoneCode" placeholder="���"     value="<? echo htmlspecialchars($row['phoneCode']);?>" /> <b>)</b>
									 <input  class="input" style="width:247px" type="text" maxlength="7" name="phoneNumber" id="phoneNumber" placeholder="�����" value="<? echo htmlspecialchars($row['phoneNumber']);?>"/></td>
	</td>
		</tr>
		<tr>
		  <td class="tr" width="187">���������������� ������ ����� </td>
		  <td style="padding-left:12px"> 
		  <input type="checkbox" name="s_email" value="1" /> <div class="div_left" style="margin:3px 10px 0 5px">Email</div>
		  <input type="checkbox" name="s_mob" value="1" /> <div class="div_left" style="margin:3px 10px 0 5px">���������</div>
		  <input type="checkbox" name="s_home" value="1" /> <div class="div_left" style="margin:3px 0 0 5px">��������</div>
		  <br style="clear:both" />
			</td>
		</tr>
	  </table>
      
      <div style="padding-top:15px;">
	  <img src="/img/point_left.gif" border="0" alt="���������� ���"/> <a href="#" onclick="choose_vk(1);return false;" class="a_big">���������� ���</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	  <a href="#" onclick="choose_vk(3);return false;" class="a_big" >��������� ���</a> <img src="/img/point_right.gif" border="0" alt="��������� ���"/>
      </div>
	  
	</div>
	
	   
	   
	   
	   <div class="step" id="step3" style="display:none;">  <span class="text_grey_sm">3 ��� -</span> <span class="h3"> ��������� � ������:</span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		<? /*<tr>
		  <td class="tr" width="187">���������<span class="text_red">*</span> </td>
		  <td><input class="input" style="width:247px"  type="text" name="dolj" value="<? echo htmlspecialchars($row['dolj']);?>"/></td>
		</tr> */ ?>
		<tr>
		  <td class="tr" width="187">���������������� �����<span class="text_red">*</span></td>
		  <td>
		  
		  
		  <select class="select"  name="profil">
		  <?
		  $city = $q->select("select * from ".$prefix."profil where status=1 and parent=0 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'"';
				if($c['id'] == $row['profil']) echo ' selected';
				echo '>'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  
		  
		  
		  <!--a href="" class="a_in">�������</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /--></td>
		</tr>
        <? /*
		<tr>
		  <td class="tr" width="187">����������� ��������<span class="text_red">*</span></td>
		  <td><input  class="input" style="width:247px" type="text" name="zp" value="<? echo htmlspecialchars($row['zp']);?>"/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">������ ������</td>
		  <td>
         
			<input type="checkbox" name="gr_full" value="1" /><div class="div_left" style="margin:3px 0 0 5px">������ ������� ����</div><div style="clear:both; margin-bottom:5px"></div>
			<input type="checkbox" name="gr_smen" value="1" /><div class="div_left" style="margin:5px 0 0 5px">�������</div><div style="clear:both; margin-bottom:5px"></div>
			<input type="checkbox" name="gr_free" value="1" /><div class="div_left" style="margin:5px 0 0 5px">���������</div><div style="clear:both; margin-bottom:5px"></div>
			<input type="checkbox" name="gr_chast" value="1" /><div class="div_left" style="margin:5px 0 0 5px">��������� ���������</div><div style="clear:both; margin-bottom:5px"></div>
			<input type="checkbox" name="gr_far" value="1" /><div class="div_left" style="margin:5px 0 0 5px">��������� ������</div><br style="clear:both" />
			
			</td>
		</tr>
		<tr>
		  <td class="tr" width="187">����� ������<span class="text_red">*</span></td>
		  <td class="tr" width="187">
		  <select class="select"  name="rabota_city">
		  <?
		  $city = $q->select("select * from ".$prefix."city where status=1 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'"';
				if($c['id'] == $row['rabota_city']) echo ' selected';
				echo '>'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  
		  <!--a href="" class="a_in">�������</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /--></td>
		</tr>
		<tr>
		  <td class="tr" width="187">������������</td>
		  <td> 
			<input type="radio" name="komand" value="1" checked /><div class="div_left" style="margin:3px 10px 0 5px">����� � �������������</div><div style="clear:both; margin-bottom:5px"></div>
			<input type="radio" name="komand" value="2"/><div class="div_left" style="margin:3px 10px 0 5px">�� ����� � �������������</div>
			<br style="clear:both" /> </td>
		</tr> */ ?>
	  </table>
      <div style="padding-top:15px;">
		<img src="/img/point_left.gif" border="0" alt="���������� ���"/> <a href="#" onclick="choose_vk(2);return false;" class="a_big">���������� ���</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	  <a href="#" onclick="choose_vk(4);return false;" class="a_big" >��������� ���</a> <img src="/img/point_right.gif" border="0" alt="��������� ���"/>
      </div>
	</div>
	
	   
	   
	   
	   <div class="step" id="step4" style="display:none;"> <span class="text_grey_sm">4 ��� -</span> <span class="h3"> ���� ������:</span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		<tr>
		  <td class="tr" width="187">�������� ��������<span class="text_red">*</span></td>
		  <td><input class="input" style="width:247px" type="text" name="opit_company" value="<? echo htmlspecialchars($row['opit_company']);?>"/></td>
		</tr>
		<!--tr>
		  <td class="tr" width="187">������� �������</td>
		  <td><a href="" class="a_in">�������</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /></td>
		</tr-->
		<tr>
		  <td class="tr" width="187">���������<span class="text_red">*</span></td>
		  <td><input  class="input" style="width:247px" type="text" name="opit_dolj" value="<? echo htmlspecialchars($row['opit_dolj']);?>"/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">������ ������<span class="text_red">*</span></td>
		  <td>
		  �
		   <select name="opit_mon" class="select" style="width:100px">
					<option value="0" >�����</option>
			<?
			for($i = 1; $i <= 12; $i++){
							echo '<option value="'.$i.'"';
							if($i == $row['opit_mon']) echo ' selected';
							echo '>'.$mon[$i].'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="opit_year" class="select" style="width:100px">
					<option value="0" >���</option>
			<?
			for($i = date('Y'); $i >= 1930; $i--){
							echo '<option value="'.$i.'"';
							if($i == $row['opit_year']) echo ' selected';
							echo '>'.$i.'</option>';
							
					}
					?>
		  </select>
		  
		  ��
		  
		   <select name="opit_mon_to" class="select" style="width:100px">
					<option value="0" >�����</option>
			<?
			for($i = 1; $i <= 12; $i++){
							echo '<option value="'.$i.'"';
							if($i == $row['opit_mon_to']) echo ' selected';
							echo '>'.$mon[$i].'</option>';
							
					}
					?>
		  </select>
		  
		  <select name="opit_year_to" class="select" style="width:100px">
					<option value="0" >���</option>
			<?
			for($i = date('Y'); $i >= 1930; $i--){
							echo '<option value="'.$i.'"';
							if($i == $row['opit_year_to']) echo ' selected';
							echo '>'.$i.'</option>';
							
					}
					?>
		  </select>
		  
		  </td>
		</tr>
		<tr>
		  <td class="tr" width="187">�������� ������</td>
		  <td><textarea class="textarea"  style="width:247px" type="text" name="opit_descr"><? echo $row['opit_descr'];?></textarea></td>
		</tr>
        <?
        /*
		TODO
		�������� ��� ����� ������
		
		���� �������� �������� ������ ���� ������, ��� ������������� ������������� �� ����: � ������ ������������ ������ ������ �������� ���������� ����� ������.
		*/
		?>
	  <!--  <tr>
		  <td>������������ (���, ���������, �������, �����������)</td>
		  <td><input type="text" name="" value="<? echo htmlspecialchars($row['']);?>"/></td>
		</tr>
			-->
	  </table>
      <div style="padding-top:15px;">
		<img src="/img/point_left.gif" border="0" alt="���������� ���"/> <a href="#" onclick="choose_vk(3);return false;" class="a_big">���������� ���</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	  <a href="#" onclick="choose_vk(5);return false;" class="a_big" >��������� ���</a> <img src="/img/point_right.gif" border="0" alt="��������� ���"/>
      </div>
	</div>
	
	   
	   
	   
	   <div class="step" id="step5" style="display:none;"> <span class="text_grey_sm">5 ��� -</span> <span class="h3"> �����������: </span>
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
		<tr>
		  <td class="tr" width="187">��������</td>
		  <td>
	<input type="radio" name="ed_main" value="������" checked/><div class="div_left" style="margin:3px 0 0 5px">������</div><div style="clear:both; margin-bottom:5px"></div>
	<input type="radio" name="ed_main" value="�������� ������"/><div class="div_left" style="margin:3px 0 0 5px">�������� ������</div><div style="clear:both; margin-bottom:5px"></div>
	<input type="radio" name="ed_main" value="������� �����������"/><div class="div_left" style="margin:3px 0 0 5px">������� �����������</div><div style="clear:both; margin-bottom:5px"></div>
	<input type="radio" name="ed_main" value="�������"/><div class="div_left" style="margin:3px 0 0 5px">�������</div><div style="clear:both; margin-bottom:5px"></div>
    
    <div class="text_grey_sm">
    �������� ����������� � �� �����������, �� ������� �� ������� ���� � ����� ���������������� ������������.
    </div>
	</td>
		</tr>
		<tr>
		  <td class="tr" width="187">�������� �������� ���������<span class="text_red">*</span></td>
		  <td><input class="input" style="width:247px" type="text" name="ed_name" value="<? echo htmlspecialchars($row['ed_name']);?>"/></td>
		</tr>
		<!--tr>
		  <td class="tr" width="187">���������� �����������</td>
		  <td><select name="ed_get" class="select" style="width:250px" >
		  
		  </select></td>
		</tr-->
		<tr>
		  <td class="tr" width="187">���� ��������<span class="text_red">*</span></td>
		  <td>� <input class="input" style="width:50px;text-align:center;" type="text" name="ed_y_from" value="<? echo htmlspecialchars($row['ed_y_from']);?>" /> ��  <input class="input" style="width:50px;text-align:center;"  type="text" name="ed_y_to" value="<? echo htmlspecialchars($row['ed_y_to']);?>" /> </td>
		</tr>
		<tr>
		  <td class="tr" width="187">�������������<span class="text_red">*</span></td>
		  <td><input  class="input" style="width:247px"type="text" name="ed_spec" value="<? echo htmlspecialchars($row['ed_spec']);?>"/></td>
		</tr>
		<tr>
		  <td class="tr" width="187">�����������</td>
		  <td><textarea class="textarea" style="width:247px" type="text" name="ed_comm"><? echo $row['ed_comm'];?></textarea></td>
		</tr>
	  </table>
      
      <?
	  /*
	  ���������� �����������
	  
	  TODO
      ���� �������� �������� ������ ���� �����, ��� ������������� ������������� �� ����: � ������ ������������ ������ ������ �������� ���������� ����� �����.
*/
	  ?>
      
      <div style="padding-top:15px;">
		<img src="/img/point_left.gif" border="0" alt="���������� ���"/> <a href="#" onclick="choose_vk(4);return false;" class="a_big">���������� ���</a> <img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> 
	  <a href="#" onclick="choose_vk(6);return false;" class="a_big" >��������� ���</a> <img src="/img/point_right.gif" border="0" alt="��������� ���"/>
      </div>
	</div>
	
	   
	   
	   
	   <div class="step" id="step6" style="display:none;"><span class="text_grey_sm">6 ��� -</span> <span class="h3">�������������� ����������:</span>
	
	  <table cellpadding="0" cellspacing="0" style="padding:10px 13px 0 10px">
	
	   <!-- <tr>
		  <td>������ ���� (���������� ������)</td>
		  <td><input type="text" name="" value="<? echo htmlspecialchars($row['']);?>"/></td>
		</tr>
		<tr>
		  <td>������ ����� (���������� ������)</td>
		  <td><input type="text" name="" value="<? echo htmlspecialchars($row['']);?>"/></td>
		</tr>-->
		<tr>
		  <td class="tr" width="187">���������������� ������ </td>
		  <td><textarea class="textarea" style="width:247px;	margin-bottom:10px;" type="text" name="dop_prof"><? echo $row['dop_prof'];?></textarea></td>
		</tr>
		<tr>
		  <td class="tr" width="187">� ���� </td>
		  <td><textarea class="textarea" style="width:247px" type="text" name="dop_about"><? echo $row['dop_about'];?></textarea></td>
		</tr>
		<? /*<tr>
		  <td class="tr" width="187">�����������</td>
		  <td>
		  <select class="select"  name="grajd">
		  <?
		  $city = $q->select("select * from ".$prefix."grajd where status=1 order by name");
		  foreach($city as $c){
			  echo '<option value="'.$c['id'].'">'.$c['name'].'</option>';
			  
		  }
		  ?>
		  </select>
		  <!--a href="" class="a_in">�������</a> <img src="/img/point_bottom.gif" width="6" height="8" alt="" /--></td>
		</tr>*/ ?>
		<tr>
		<td></td>
		<td style="padding-top:15px">
		<input type="image" src="/img/button_renew.png" />
		</td>
		</tr>
	   <!-- <tr>
		  <td>�������� ���������</td>
		  <td><input type="text" name="" value="<? echo htmlspecialchars($row['']);?>"/></td>
		</tr>
		<tr>
		  <td>������������ �����</td>
		  <td><input type="text" name="" value="<? echo htmlspecialchars($row['']);?>"/></td>
		</tr>
		<tr>
		  <td>������������</td>
		  <td><input type="text" name="" value="<? echo htmlspecialchars($row['']);?>"/></td>
		</tr>
			-->
	  </table>
	  <div style="padding-top:15px;">
		<img src="/img/point_left.gif" border="0" alt="���������� ���"/> <a href="#" onclick="choose_vk(5);return false;" class="a_big">���������� ���</a> 
	  <!--<img src="/img/menu_line.gif" width="1" height="8" alt="" style="margin:0 10px" /> <a href="#" onclick="$('#step6').hide();$('#step7').show();return false;" class="a_big" >��������� ���</a>-->
      </div>
	</div>
	
	<!--
	   
	   
	   
	   <div class="step" id="step7" style="display:none;"> 7) <strong>��������� ����������</strong>
	  <table>
		<tr>
		  <td>��� ������ ���� ������</td>
		  <td><input type="text" name="" value="<? echo htmlspecialchars($row['']);?>"/></td>
		</tr>
		<tr>
		  <td>�������� ������� � ���������� ������</td>
		  <td><input type="text" name="" value="<? echo htmlspecialchars($row['']);?>"/></td>
		</tr>
	  </table>
		<a href="#" onclick="choose_vk(6);return false;" class="a_big">���������� ���</a>
	
	</div> -->
	</form>
	
	<?
	}//else if($added == 'true'){
}//if(empty($_SESSION['user_info']['id'])){

?>