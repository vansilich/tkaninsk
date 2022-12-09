<div class="wrapper">
<div class="page-content">
<div class="line"> </div>
<div class="right-menu-sm clearfix">
<a href="/search/"><img src="/img/search-sm.png" alt=""/></a> 
<a href="/basket/"><img src="/img/cart-sm.png"  alt=""/></a> 
&nbsp;<!--a href="/reg/"><img src="/img/login-sm.png" alt=""/></a--></div>
<div class="container">
  <div id="top" class="row">
    <div class="right-menu">
      <div>
        <a href="/search/"><img src="/img/search.png" width="32" height="34" alt=""/> <br>Поиск</a> </div>
      <div class="cart" onClick="document.location.href='/basket/';"><span id="basket_col"><? 
	  if(isset($_SESSION['basket_catalog'])){
		  echo sizeof($_SESSION['basket_catalog']); 
	  }else{
		  echo '0';  
	  }
	  
	  ?></span><br>
        <a href="/basket/">Заказ</a> </div>
      <!--div> <a href="/reg/"><img src="/img/login.png" width="32" height="41" alt=""/><br>
        Войти</a> </div-->
    </div>
    <div class="col-md-5 col-sm-12 menu"><a href="/">ГЛАВНАЯ</a>  <a href="/about/">О КОМПАНИИ</a> <a href="/delivery/">ДОСТАВКИ И ОПЛАТА</a> <a href="/opt/">ТКАНИ ОПТОМ</a> <a href="/contacts/">КОНТАКТЫ</a> </div>
    <div class="col-md-2 col-sm-12" align="center"><a href="/"><img src="/img/logo.png" width="191" height="50" alt=""/></a></div>
    <div class="col-md-2 col-sm-12 mail"><a href="mailto:<? echo $_settings['site_email'];?>" class="mail"><img src="/img/mail.png" width="10" height="7" alt=""/> <? echo $_settings['site_email'];?></a></div>
    <div class="col-lg-3 col-md-5 col-sm-12 phone">
      <div><? echo $_settings['phone'];?></div>
      <div><a href="/contacts/" onclick="$('#dop_contacts').toggle(); return false;" class="contact">Еще контакты</a>
      <div id="dop_contacts">
      <a href="mailto:<? echo $_settings['site_email'];?>" class="mail"><img src="/img/mail.png" width="10" height="7" alt=""/> <? echo $_settings['site_email'];?></a>
      <div><? echo $_settings['phone'];?></div>
      
      </div>
      </div>
    </div>
  </div>
</div>

<div id="menu-catalog">
  <div class="container"> 
  <ul class="nav nav-justified">
  <li class="dropdown">
  <a href="/catalog/" class="catalog_button dropdown-toggle22" data-toggle="dropdown"><img src="/img/sm.png" width="22" height="32" alt=""/> <b>Все товары</b></a>
  <?/*
  <div class="dropdown-menu">
           
         <div class="container container2">
         <div class="green">
          <?
  $catalog = $q->select("select id,name,cpu,ico from ".$prefix."catalog where status=1 and parent=0 order by name");
  foreach($catalog as $c){
	$img = get_image($c,'files/ico/','ico',0);  
	if(!empty($img)){
		echo '<div><a href="/catalog/'.$c['cpu'].'/" data-id="'.$c['id'].'" class="_js_cat_menu"><img src="'.$img.'" alt=""/> '.$c['name'].'</a> </div>';	
	}
  }
  ?>
          </div>
          <div class="col-md-3 col-sm-3 col-xs-3 ">
          
         
           </div>
          <div class="col-md-9 col-sm-9 col-xs-9 white">
<?
	foreach($catalog as $c){
		echo '<div id="sub_cat'.$c['id'].'" data-parent="'.$c['id'].'" class="sub_cat">';	
		$catalog2 = $q->select("select id,name,cpu,ico from ".$prefix."catalog where status=1 and parent=".to_sql($c['id'])." order by name");
		foreach($catalog2 as $c2){
			echo '<a href="/catalog/'.$c2['cpu'].'/">'.$c2['name'].'</a> <br>';	
		}
		echo '</div>';
	}
?>
          </div>
         </div> 
           
           
          </div>
          */ ?>
  </li>
  <?
  $catalog = $q->select("select id,name,cpu,ico from ".$prefix."catalog where status=1 and parent=0 order by name");
  foreach($catalog as $c){
	$img = get_image($c,'files/ico/','ico',0);  
	if(!empty($img)){
		echo '<li class="menu-catalog-big"><a href="/catalog/'.$c['cpu'].'/"><img src="'.$img.'" alt=""/> '.$c['name'].'</a> </li>';	
	}else{
	        echo '<li class="menu-catalog-big"><a href="/catalog/'.$c['cpu'].'/">'.$c['name'].'</a> </li>';	
	}
  }
  ?>
  </ul>
    
  </div>
  
  <div class="sub_menu" id="sub_menu">
  <?
  $catalog = $q->select("select id,name,cpu,ico from ".$prefix."catalog where status=1 and parent=0 order by name");
  foreach($catalog as $c){
		echo '<a href="/catalog/'.$c['cpu'].'/">'.$c['name'].'</a> <br>';	
		
		  $catalog2 = $q->select("select id,name,cpu,ico from ".$prefix."catalog where status=1 and parent=".to_sql($c['id'])." order by name");
		  foreach($catalog2 as $c2){
				echo '<a href="/catalog/'.$c2['cpu'].'/">'.$c2['name'].'</a> <br>';	
		  }
  }
  ?>
  </div>
  
  
</div>