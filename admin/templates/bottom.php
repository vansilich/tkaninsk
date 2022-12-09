<div id="bottom" <? if($this_page_id ==1) echo 'class="main-bottom"';?>>
  <div class="container">
    <div class="col-md-3 col-sm-3 col-xs-12"> @2011 - <?=date('Y');?> - TkaniNsk<br>
      <a href="/contacts/">Контактная информация</a>
      <a href="" class="btn btn-call vakas-dialog" data-id="20" data-type="Заказать звонок">Заказать звонок</a>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-12 menu-bottom mb1">
- <a href="/about/">О компании</a><br>
- <a href="/delivery/">Доставка и оплата</a><br>
- <a href="/opt/">Ткани оптом</a>

      </div>
    <div class="col-md-3 col-sm-3 col-xs-12  menu-bottom mb2">
    <?
  $catalog = $q->select("select id,name,cpu,ico from ".$prefix."catalog where status=1 and parent=0 order by name");
  foreach($catalog as $c){
		echo '- <a href="/catalog/'.$c['cpu'].'/">'.$c['name'].'</a> <br>';	
  }
  ?>

</div>
    <div class="col-md-3 col-sm-3 col-xs-12">
      <div>Мы принимаем к оплате</div>
    <img src="/img/card.png" width="123" height="54" alt=""/> 
    <div class="vk"><a href="<? echo $_settings['vk'];?>" target="_blank"><img src="/img/vk.png" width="40" height="41" alt=""/></a> </div>
    </div>
  </div>
</div>

<?
/************menu **********/
?>
<div id="top_menu_container" >
<div class="container">
<div id="top_menu">
<div class="menu_header">Каталог</div>
    <?
$cats = $q->select("SELECT id,name,cpu FROM ".$prefix."catalog where status=1 and parent=0 order by name");
foreach($cats as $row){
		$cats2 = $q->select("SELECT id,name,cpu FROM ".$prefix."catalog where status=1 and parent=".to_sql($row['id'])."  order by name");
		echo '<a href="/catalog/'.$row['cpu'].'/"  class="one';
		if(sizeof($cats2) > 0) echo ' drop_down';
		echo '">'.$row['name'].'</a>';	
		/*******второй уровень*************/	
		echo '<div class="second">';
		foreach($cats2 as $row2){
				$cats3 = $q->select("SELECT id,name,cpu FROM ".$prefix."catalog where status=1 and parent=".to_sql($row2['id'])." order by name");
				echo '<div class="second_in"><a href="/catalog/'.$row2['cpu'].'/" class="second_a';
				
				if(sizeof($cats3) > 0) echo ' drop_down
				';
				echo '">'.$row2['name'].'</a>';
				/*******третий уровень*************/	
				echo '<div class="third">';
				foreach($cats3 as $row3){
						echo '<a href="/catalog/'.$row3['cpu'].'/" class="third_a">'.$row3['name'].'</a>';		
				
				}
				echo '</div></div>';		
				/*******конец третий уровень*************/	
		
		}
		echo '</div>';	
		/*******конец второй уровень*************/	
}					
						
					
				  ?>
<div class="menu_header">Меню</div>                  
 <a href="/about/" class="one">О компании</a>
 <a href="/delivery/" class="one">Доставка и оплата</a>
 <a href="/opt/" class="one">Ткани оптом</a>
              
                  
                  

   </div>
 </div> </div> </div></div>
<?
/**********end menu************/
$refresh = rand(1,9999);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery.colorbox.js"></script>
<link rel="stylesheet" href="/css/colorbox.css">
<link rel="stylesheet" href="/css/new.css">
<link rel="stylesheet" href="/js/owl-carousel/owl.transitions.css">
<link rel="stylesheet" href="/js/owl2/owl.carousel.css">
<link rel="stylesheet" href="/js/owl2/owl.theme.css?v=2">
<script src="/js/owl2/owl.carousel.js"></script>
<script src="/js/script.js?v=<?=$refresh;?>"></script>
<script src="https://use.fontawesome.com/940454ca85.js"></script>


  <div id="shadow"></div>

<div id="res_dialog" class="wdialog">
  <div class="div_input">
    <div class="close"><a href="#" onClick="$('#res_dialog').hide();$('#shadow').hide();return false;"><img src="/img/close.png" width="21" height="21" alt=""></a></div>
    <div align="center" class="">Заявка принята, мы с вами свяжемся! <br>
    </div>
  </div>
</div>

<div id="res_basket" class="wdialog">
  <div class="div_input">
    <div class="close"><a href="#" onClick="$('#res_basket').hide();$('#shadow').hide();return false;"><img src="/img/close.png" width="21" height="21" alt=""></a></div>
   
<div align="center">    
<a href="#0" onClick="$('#res_basket').hide();$('#shadow').hide();return false;" class="btn btn-click">Продолжить покупки</a>
</div><br>
<div align="center">    
<a href="/basket/" class="btn">Перейти в корзину</a>
</div>
    
   
  </div>
</div>

<div>
  <div  class="wdialog" id="w20">
    <div class="div_input">
      <div class="close"><a href="#" onClick="$('#w20').hide();$('#shadow').hide();return false;"><img src="/img/close.png" width="21" height="21" alt=""></a></div>
      <form id="form-20" action="javascript:void(0);" onSubmit="ajax_f(20)">
        <div class="name_form">Оставьте заявку  и наши <br>
специалисты свяжуться с Вами <br>
для дальнейшего сотрудничества!</div>
        <input type="hidden" name="type" id="type-20" value="">
        <div class="input_form">
          <input placeholder="Введите имя.." name="name" id="name-20" class="input-lg" />
        </div>
        <div class="input_form">
          <input placeholder="Введите телефон.." name="tel"  id="tel-20"  class="input-lg phone2" />
        </div>
        <div class="input_form">
          <input placeholder="Введите e-mail.."  name="email" id="email-20" class="input-lg" />
        </div>
        <div><a href="#" onclick="ajax_f(20);return false;" class="btn">Отправить заявку</a></div>
      </form>
    </div>
  </div>
  
  

  <div  class="wdialog" id="w120">
    <div class="div_input">
      <div class="close"><a href="#" onClick="$('#w120').hide();$('#shadow').hide();return false;"><img src="/img/close.png" width="21" height="21" alt=""></a></div>
      <form id="form-120" action="javascript:void(0);" onSubmit="ajax_one(120)">
        <div class="name_form">Заказать "<span id="one_click_name">{NEWS_TITLE}</span>" в один клик
</div>
        <input type="hidden" name="types" id="type-120" value="">
	<input type="hidden" name="gid" id="gid-120" value="">
        <div class="input_form">
          <input placeholder="Введите имя.." name="fio" id="name-120" class="input" />
        </div>
        <div class="input_form">
          <input placeholder="Введите телефон.." name="phone"  id="tel-120"  class="input phone2" />
        </div>
        <div class="input_form">
          <input placeholder="Введите e-mail.."  name="email" id="email-120" class="input" />
        </div>
        <div><a href="#" onclick="ajax_one(120);return false;" class="btn">Заказать</a></div>
      </form>
    </div>
  </div>
  
  
</div>
<?
echo $_settings['block'];
?>
</body>
</html>