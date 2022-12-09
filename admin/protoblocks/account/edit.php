<?
$logout = get_param('logout');
if($logout == 1){
	$_SESSION['user_info'] = '';
	header('location: /');
}
if(empty($_SESSION['user_info']['id'])){
	echo 'Вы не авторизованы!';
	header('location: /auth/');
}else{



		echo '<div class="tasks">
		<a href="edit.php">Личные данные</a> | 	<a href="orders.php">Заказы</a> | <a href="?logout=1">Выход</a>
		</div><hr>';
$done = get_param('done');
if($done == 'true'){
	$temp = $q->select1("select * from ".$prefix."tmpl where id=2");
	echo '<div class="msg">'.$temp['text'].'</div>';	
	
}else{


	$action=get_param('action');
	if($action == 'edit'){
		$phone=get_param('phone');
		$adres=get_param('adres');
		$name=get_param('name');
		$surname=get_param('surname');
		
		$pass=get_param('pass');
		$pass2=get_param('pass2');
		$code=get_param('code');
		$is_agree=get_param('is_agree');
		
		$postindex=get_param('postindex');
		$order_email=get_param('order_email');
		$otch=get_param('otch');
	
		if($err == 0){
			$q->exec("update ".$prefix."members set
			name=".to_sql($name).",
			phone=".to_sql($phone).",
			adres=".to_sql($adres).",
			
			postindex=".to_sql($postindex).",
			order_email=".to_sql($order_email).",
			otch=".to_sql($otch).",
			
			
			surname=".to_sql($surname)."
			where id=".to_sql($_SESSION['user_info']['id']));	
			
			$_SESSION['secret_code'] = '';
			
			//header('location: /account/');
	
		}else{
			echo '<div class="error">'.$err_msg.'</div>';	
		}	
			
	}
	
	if($err != 2){
		$row = $q->select1("select * from ".$prefix."members where id=".to_sql($_SESSION['user_info']['id']));
		$_SESSION['user_info'] = $row;
	?>
	<script>
	function check_reg(form){	
		return true;
	}
	</script>
   

 <style>
 .h3_name{ padding-right:10px; text-align:right}
 </style>  
  
 <form action="" method="POST" name="registerForm" id="registerForm"  enctype="multipart/form-data" onsubmit="return check_reg(this);">
 <input type="hidden" name="action" value="edit"/>
	   <div class="h2" style="margin:0px 0 10px 0">Личные данные:</div>
      

      
      
      <div>
      
       
		<table cellpadding="0" cellspacing="8" border="0" style="padding-left:13px">
		  <tr>
			<td class="h3_name right tr"><label for="name" >Имя </label></td>
			<td><input class="input" style="width:215px" id="name" name="name" type="text" value="<? echo htmlspecialchars($row['name']);?>"/></td>
			<td class="err"></td>
		  </tr>
          <tr>
			<td class="h3_name right tr"><label for="name" >Отчество </label></td>
			<td><input class="input" style="width:215px" id="otch" name="otch" type="text" value="<? echo htmlspecialchars($row['otch']);?>"/></td>
			<td class="err"></td>
		  </tr>
		  <tr>
			<td class="h3_name right tr"><label for="surname"> Фамилия </label></td>
			<td><input class="input" style="width:215px"  id="surname" name="surname" type="text" value="<? echo htmlspecialchars($row['surname']);?>"/></td>
			<td class="err"></td>
		  </tr>
          
          
          <tr>
			<td class="h3_name right tr"><label for="surname"> Телефон </label></td>
			<td><input class="input" style="width:215px"  id="phone" name="phone" type="text" value="<? echo htmlspecialchars($row['phone']);?>"/></td>
			<td class="err"></td>
		  </tr>
           <tr>
			<td class="h3_name right tr"><label for="surname"> Email для заказов </label></td>
			<td><input class="input" style="width:215px"  id="order_email" name="order_email" type="text" value="<? echo htmlspecialchars($row['order_email']);?>"/></td>
			<td class="err"></td>
		  </tr>
          
          
          
           <tr>
			<td class="h3_name right tr"><label for="surname"> Почтовый индекс </label></td>
			<td><input class="input" style="width:215px"  id="postindex" name="postindex" value="<? echo htmlspecialchars($row['postindex']);?>" />
            </td>
			<td class="err"></td>
		  </tr>
          
          <tr>
			<td class="h3_name right tr"><label for="surname"> Адрес </label></td>
			<td><textarea class="textarea" style="width:215px"  id="adres" name="adres"><? echo htmlspecialchars($row['adres']);?></textarea>
            </td>
			<td class="err"></td>
		  </tr>
          
		 
		  

		  <tr>
			<td>&nbsp;</td>
			<td style="padding-top:30px">
            		<!--input type="image" src="/img/button_renew.png" /-->
            <input type="submit" name="submit" value="Обновить" class="btn" /></td>
			<td>&nbsp;</td>
		  </tr>
		</table>
	  </div>
	</form>
  
    
    
    
    
    
    
    
    
    
    
    
	<?
	}//if($err == 2){
}

}
?>
