<div class="order"><?
$done = get_param('done'); 
$action = get_param('action');
$gid = get_param('gid');
$cart_id = get_param('cart_id');

include($inc_path.'protoblocks/basket/update.php');


$done = get_param('done');
if($done == 'yes'){
	$oid = get_param('oid');
	$send = 1;
	echo '<div class="complete"><span>
	
	<h1>Спасибо за то, что выбрали наш интернет-магазин.</h1>
Ваш заказ<b> №'.$oid.'</b> поступил в отдел продаж.<br>
В самое ближайшее время, с вами свяжется наш менеджер , для подтверждения вашего заказа . </span><br>
<p>Вы можете посмотреть наш <a href="/catalog/">каталог товаров</a>, пока ожидаете обработки Вашего заказа.
</p>
	</div>';
	echo '<script>document.addEventListener(\'DOMContentLoaded\', function () {refreshbasket();});</script>';
}else{//if($done == 'yes'){

	$n_basket = sizeof($_SESSION['basket_catalog']);
	if($n_basket>0){
	
	/**********send**********/
	$send = 0;
	$cmd = get_param('cmd');
	
	
	if($cmd == 'send'){
		include($inc_path.'protoblocks/basket/send.php');
	}
	/**********end send**********/	
	
	if($send != 1){
?>
<div id="table">

<h2>ваш заказ</h2>

<form method="post" id="cbform" name="cbform">
			<input type="hidden" name="action" value="update">
<?
$cart_b = $_SESSION['basket_catalog'];
			$sum = 0;
			$col = 0;
			$ves = 0;
			$tmpl = file_get_contents($inc_path.'parts/basket.php'); 
			preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
			preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
			preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
			preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
			
			
		
			echo $tmp_begin[1];	
			
			
			$f = 1;
			foreach($cart_b as $k=>$v){
				$good = $q->select1("select G.*,C.edizm from ".$prefix."goods as G
				join ".$prefix."catalog as C on C.id=G.catalog
				where G.id=".to_sql($v['good']));
				
				$img = get_image_cpu($good,'files/goods/1/','img1',1);
				if(!empty($img)){
					$img =  '<img src="'.$img.'" border="0" alt="'.my_htmlspecialchars($good['title']).'" class="img">';
				}
				$link = '/goods/'.$good['cpu'].'/';
				if($v['type']=='color' && $v['type_id'] > 0){
					/*$color = $q->select1("select G.*,C.code,C.name as cname from ".$prefix."goods_price as G 
					left join ".$prefix."color as C on C.id = G.color
					where G.id=".to_sql($v['type_id']));*/
					$color = $q->select1("select G.*,C.name as cname from ".$prefix."goods_price as G 
					left join ".$prefix."adv_params_value as C on C.id = G.color
					where G.id=".to_sql($v['type_id']));
					
					$good['articul'] = $color['articul'];
					$good['kol'] = $color['kol'];
					if(!empty($color['cname']))$good['name'] = $good['name'].' ['.$color['cname'].']';
					$img = get_image_folder($color,'files/color/1/','img1',1);
					if(!empty($img)){
						$img = '<img src="'.$img.'" border="0" alt="'.my_htmlspecialchars($color['name']).'" class="img">';
					}
					$link = '/goods/'.$good['cpu'].'/?color='.$color['id'];
				}else{
					//$main_color = $q->select1("select C.code,C.name as cname from ".$prefix."color as C where id=".to_sql($good['color']));
					$main_color = $q->select1("select C.name as cname from ".$prefix."adv_params_value as C where id=".to_sql($good['color']));
					if(!empty($main_color['cname']))$good['name'] = $good['name'].' ['.$main_color['cname'].']'; 	
				}

				
				
				$price_tit = '';	
				if($v['q'] >=5 && $good['price_opt']>0){
					$price = $good['price_opt'];
					$price_tit = 'опт. ';		
				}else{
					$price = $good['price'];	
				}
				
				$sum += $v['q']*$price;
				$ves += $v['q']*$good['ves'];
				$col += $v['q'];					
		
				$news_row = $tmp_row[1];
				
				 $news_row = str_replace('{NEWS_MAXKOL}',$good['kol'],$news_row);
				if($f == 1){
					$news_row = str_replace('{ROW_CLASS}','td1',$news_row);
					$f=2;
				}else{
					$news_row = str_replace('{ROW_CLASS}','td2',$news_row);
					$f=1;
				}
			
				
				$news_row = str_replace('{GOOD_IMG}',$img,$news_row);
				$news_row = str_replace('{GOOD_TITLE}',$good['name'],$news_row);
				$news_row = str_replace('{articul}',$good['articul'],$news_row);
				$news_row = str_replace('{edizm}',$good['edizm'],$news_row);
				
				  if($good['edizm'] == 'шт'){
					$news_row = str_replace('{b_type_col}','_cel',$news_row);		  
				  }else{
					$news_row = str_replace('{b_type_col}','',$news_row);	  
				  }
				
				$news_row = str_replace('{GOOD_TITLE2}',$good['title2'],$news_row);
				$news_row = str_replace('{GOOD_PRICE}',$price_tit.$price,$news_row);
				$news_row = str_replace('{GOOD_COL}',$v['q'],$news_row);
				$news_row = str_replace('{VAR}',$v['var'],$news_row);
				$news_row = str_replace('{GOOD_PRICE_ALL}',$price*$v['q'],$news_row);
				$news_row = str_replace('{GOOD_ID}',$v['good'],$news_row);
				$news_row = str_replace('{CART_ID}',$k,$news_row);
				$news_row = str_replace('{GOOD_LINK}',$link,$news_row);
				echo $news_row;
			}
			
			$end = $tmp_end[1];		
			if($f == 1){
				$end = str_replace('{ROW_CLASS}','td1',$end);
			}else{
				$end = str_replace('{ROW_CLASS}','td2',$end);
			}
				
			$end = str_replace('{GOOD_PRICE_SUM}',$sum,$end);
			$end = str_replace('{GOOD_COL}',$col,$end);
			echo $end;
			
			$all_sum = $sum;
		

?>
</form>






<div class="row">
  
  
<a name="oform"></a> 
	 <form action="/basket/#oform" name="send_form" id="send_form" method="post" onSubmit="return check_basket();" style="margin:0;">  
     <input type="hidden" name="cmd" value="send">
     <input type="hidden" id="basket_ves" name="ves" value="<?=$ves;?>">
     <input type="hidden" id="basket_sum" name="sum" value="<?=$sum;?>">
     <div class="col-md-3">
       <div class="h3">оформить заказ</div>
        <ul class="ul ul2">
    <li><label><input type="radio" class="" name="ftype" value="1" checked> Физ. лицо</label></li>
     <li><label><input type="radio" class="" name="ftype" value="2"> Юр. лицо</label> </li>
      </ul> 
       <input placeholder="Имя" name="fio"  class="input" />
       <input placeholder="Телефон" name="phone" id="basket_phone" class="input" required/>
       <input placeholder="Email" name="email" class="input" required/>
       <input placeholder="Почтовый индекс"  name="postindex" id="postindex" class="input"/>
       <textarea class="textarea" name="adres" placeholder="Адрес доставки"></textarea>
       <textarea class="textarea" name="comm" placeholder="Комментарий"></textarea>
      
     </div>
   
    <div class="col-md-3 top">
    <div class="name">Способ доставки:</div>
    <ul class="ul ul2">
    <li><label><input type="radio" class="delivery_check" name="delivery" value="sam" checked> РЦР по Новосибирску</label></li>
     <li><label><input type="radio" class="delivery_check" name="delivery" value="post" id="delivery_post"> Почтой России</label>
     
     <div id="delivery_res" class="filters-res none"></div>
     </li>
     <li><label><input type="radio" class="delivery_check" name="delivery" value="transport"> Транспортной компанией</label>
     <div id="delivery_res2" class="filters-res none"><?
     if($sum > 7000){
		echo 'Доставка до ТК <span class="s_res">бесплатно</span>';
	 }else{	 
	 	echo 'Доставка до ТК - <span class="s_res">100руб</span>';
	 }
	 ?></div>
     </li>
    </ul>
    
    
     <div class="name">Способ оплаты:</div>
    <ul class="ul ul2">
    <li><label><input type="radio" name="pay" value="nal" checked> Наличными</label></li>
     <li><label><input type="radio" name="pay" value="schet"> По счету</label></li>
     <li><label><input type="radio" name="pay" value="card"> Картой Visa, Mastercard, Maestro</label></li>
    </ul>
     <a href="javascript:" onclick="$('#send_form').submit();" class="btn btn-click">отправить заказ</a>
    </div>
  </form> 

   <div class="col-md-3">
   
    <form action="/reg/" method="POST" name="registerForm" id="registerForm"  enctype="multipart/form-data" onsubmit="return check_reg(this);">
 		<input type="hidden" name="action" value="add" />
	   <div class="h3">Регистрация </div>
      
      
   <input placeholder="Имя" class="input input2" id="name" name="name" />
   <input placeholder="Телефон" class="input input2"  name="phone" id="phone"/>
   <input placeholder="Email"  name="mail" id="mail" class="input input2" />
   <input placeholder="Пароль"  type="password" name="pass" class="input input2" />
   <input placeholder="Повторите пароль" type="password" id="pass2" name="pass2" class="input input2" />
       
      <table cellpadding="0" cellspacing="0"> 
       <tr>
			<td><label class="label2 label3" for="code">Введите цифры <input  class="input input2" style="width:107px"  name="code" maxlength="8" id="code" autocomplete="off" type="text"></label></td>
            
			<td class="captcha">
			  <img id="captcha"	src="/img.php" alt="Цифры с картинки" title="Цифры с картинки"></td><td> <a class="dot_a a_in" id="captcha_refresh" href="#" 
			  onclick="$('#captcha').attr('src', '/img.php?z='+(new Date()).valueOf()); return false;">Обновить цифры </a></td>
		
		  </tr>
          
       </table>
       
       
       
          <div class="label2"><a href="javascript:" onclick="$('#registerForm').submit();return false;" class="btn btn-click">Зарегистрироваться</a></div>

	</form>
  
   
   
   

<form id="login-form" action="/reg/" method="post" class="none">
   <div class="h3">Авторизация </div>
  <input type="hidden" name="cmd" value="login" />
 
  <input class="input input2"   name="login" placeholder="Логин"/>
 
 <input class="input input2" type="password" name="pass"  placeholder="Пароль"/>
 
   
    
    <div class="left">
    <a href="javascript:" onclick="$('#login-form').submit();return false;" class="btn btn-click">Войти</a>
    </div>
    <div class="left"><a href="/reg/forget.php" >Забыли пароль?</a></div>
    <br class="clear" />
  

</form>
   
   
 
   </div>
   
   
   <div class="col-md-3 text-small">
  <div>
  <a href="/auth/" class="already" onclick="$('#login-form').show();$('#registerForm').hide();return false;">Авторизация</a>
  <a href="/reg/" class="already" onclick="$('#login-form').hide();$('#registerForm').show();return false;">Регистрация</a>
</div>
  
   Доступ в личный кабинет дает вам доступ к оптовым ценам, сохраняет историю ваших заказов. Информацию об акциях вы получаете первым.
   </div>
   
  </div>
 </div> 

<?	
	}//if($send !=1)	  
		  
	}else{
		echo '<h1 style="margin-top:30px">Ваша корзина</h1>
		<p class="alertM">В корзине пока нет товаров.</p>
		
		<br class="clearfix"/>';
	}
}//esle{//if($done == 'yes'){
?>
</div>