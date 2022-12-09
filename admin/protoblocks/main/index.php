
<div class="container">
<div align="center">
<div class="main_tabs_tabs">
<a href="" class="tabs-a tabs-a-ac" data-tab='tab_hit'>Хиты продаж</a> 
<a href="" class="tabs-a" data-tab='tab_new'>Новинки</a> 
<a href="" class="tabs-a" data-tab='tab_sale'>Скидки</a></div>
</div>
<div class="main_tabs">
    <div id="tab_hit" class="active">
    <?
	$tmpl_goods = 'goods_main.php';
	$where = " and lider=1 ";
    $sql = "select G.*, price from ".$prefix."goods as G where  G.status=1 ".$where." ".$order." order by rand() LIMIT 12";
    $goods = mysql_query($sql,$db);
    include($inc_path.'templates/goods.php');
    ?>
    <div align="center"><a href="/hits/" class="btn">Смотреть все хиты</a></div>
    </div>
    <div id="tab_new">
    <?
	$where = " and new=1 ";
    $sql = "select G.*, price from ".$prefix."goods as G where  G.status=1 ".$where." ".$order." order by rand()  LIMIT 12";
    $goods = mysql_query($sql,$db);
    include($inc_path.'templates/goods.php');
    ?>
    <div align="center"><a href="/novinki/" class="btn">Смотреть все новинки</a></div>
    </div>
    <div id="tab_sale">
    <?
	$where = " and action=1 ";
    $sql = "select G.*, price from ".$prefix."goods as G where  G.status=1 ".$where." ".$order." order by rand()  LIMIT 12";
    $goods = mysql_query($sql,$db);
    include($inc_path.'templates/goods.php');
    ?>
    <div align="center"><a href="/sale/" class="btn">Смотреть все скидки</a></div>
    </div>    
</div>

</div>

<div class="grey why">
  <div class="container">
    <div class="title-big"> почему мы?
      <div class="h1"> почему мы? </div>
    </div>
    <div class="row">
      <div class="col-md-4 col-sm-4" align="center"> <img src="img/w1.png" width="65" height="59" alt=""/>
        <div class="name">все ткани в наличии</div>
        Наши ткани не под заказ, они находятся у нас на складе </div>
      <div class="col-md-4 col-sm-4" align="center"> <img src="img/w2.png" width="85" height="56" alt=""/>
        <div class="name">быстрая доставка</div>
        Отправляем заказы на следующий день после оплаты </div>
      <div class="col-md-4 col-sm-4" align="center"> <img src="img/w3.png" width="60" height="60" alt=""/>
        <div class="name">обновление ассортимента</div>
        В нашем интернет магазине постоянно появляются новинки </div>
    </div>
  </div>
</div>

<div id="about">
 <div class="container">
    <div class="row">
    <div class="col-md-9 col-sm-12">
     <h2>Новости</h2>
     <div class="news">
     <?
     $row = $q->select1("SELECT * FROM ".$prefix."news WHERE status=1 order by created desc");
	 ?>
     <a href="/news/<?=$row['cpu']?>/"><?=$row['name']?></a>
<?=$row['anons']?>
     </div>
     <a href="/news/">Все новости <img src="img/point.png" width="6" height="8" alt=""/></a>
    </div>
    <div class="col-md-3 col-sm-12 text">
<?
echo $this_page['text'];
?>
    <script type="text/javascript" src="https://vk.com/js/api/openapi.js?156"></script>

<!-- VK Widget -->
<div id="vk_groups"></div>
<script type="text/javascript">
VK.Widgets.Group("vk_groups", {mode: 3, width: "auto"}, 61221112);
</script>
    
    </div>
     
    </div>
    </div>
</div> 
<img src="img/about.jpg" width="100%"  alt=""/>
<? /*
<div class="grey command">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-sm-12">
        <div class="title-big"> Наша команда
          <div class="h1">Наша команда </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-12">
        <div class="look-div"><img src="img/look.png" width="22" height="15" alt="" class="look-icon"/> <a href="/staff/">Посмотреть весь коллектив</a></div>
      </div>
    </div>
<?
	$link = '/staff/';
	$img_folder = 'files/staff/';
	$data = $q->select("SELECT * FROM ".$prefix."staff WHERE status=1 order by rank desc  LIMIT 4");
	$tmpl = file_get_contents($inc_path.'protoblocks/staff/templates/main.php');
	preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
	preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
	preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
	preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);

	echo $tmp_begin[1];
	foreach($data as $row){
		$img = $link_img = '';
		if(is_file($root_path.$img_folder.'pre'.$row['id'].$row['img'])){
			$img = '<img src="/'.$img_folder.'pre1_'.$row['id'].$row['img'].'" alt="'.my_htmlspecialchars($row['name']).'" border="0">';
		}
		$tmpl_row = $tmp_row[1];
		$tmpl_row = str_replace('{NEWS_LINK}',$link.$row['cpu'].'/',$tmpl_row);
		$tmpl_row = str_replace('{NEWS_NAME}',$row['name'],$tmpl_row);
		$tmpl_row = str_replace('{NEWS_DOLJ}',$row['dolj'],$tmpl_row);
		$tmpl_row = str_replace('{NEWS_IMG}',$img,$tmpl_row);
		$tmpl_row = str_replace('{NEWS_ANONS}',$row['anons'],$tmpl_row);
		echo $tmpl_row;
		echo $tmp_delim[1];
	}
	echo $tmp_end[1];
?>    
    
  </div>
</div>
*/ ?>