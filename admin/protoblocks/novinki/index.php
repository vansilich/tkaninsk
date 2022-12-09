<?
			//$where = "and catalog=".to_sql($this_cat['id'])."";
			$where = " and catalog in (".$_all_child.") ";
			$for_search_where = " and catalog in (".$_all_child.") ";
			$draw_params = array();
			$pf = get_param('pf');	
			$pt = get_param('pt');	
			if(!empty($pf)){
				$where .= " and price >=".to_sql($pf);
				$draw_params['pf']= $pf;
			}
			
			if(!empty($pt)){
				$where .= " and price <=".to_sql($pt);
				$draw_params['pt']= $pt;
			}
?>
  <div class="row">
    <div class="col-md-3 col-sm-12 col-xs-12">
      <div class="menu-left">
      <div class="menu-left-sm">
      <div class="select-div"><a href="#0" onclick="$('#filtr').toggle();return false;">Фильтры <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      </div>
      <div class="menu-left-big">
<?
if(empty($cpu)) include($inc_path.'protoblocks/catalog/search.php');
?>      
<? /*      
      <div class="select-div"><a href="">Основной состав <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Плотность, гр/м. пог. <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Ширина, см <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Производитель <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Цвет <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      <div class="select-div"><a href="">Назначение <img src="/img/select-btn.jpg" width="38" height="40" alt="" class="select-icon"/></a></div>
      
*/ ?>
      <ul>      
<?
$catalog = $q->select("select id,name,cpu from ".$prefix."catalog where parent=0 and status=1 order by name");
foreach($catalog as $c){
	echo '<li> <a href="/catalog/'.$c['cpu'].'/" class="one">'.$c['name'].'</a>';
	$catalog2 = $q->select("select id,name,cpu from ".$prefix."catalog where parent=".to_sql($c['id'])." and status=1 order by name");
	if(sizeof($catalog2) > 0){
		echo '<ul>';
		foreach($catalog2 as $c2){
			echo '<li> <a href="/catalog/'.$c2['cpu'].'/" class="two">'.$c2['name'].'</a>';
			$catalog3 = $q->select("select id,name,cpu from ".$prefix."catalog where parent=".to_sql($c2['id'])." and status=1 order by name");
			if(sizeof($catalog3) > 0){
				echo '<ul>';
				foreach($catalog3 as $c3){
					echo '<li> <a href="/catalog/'.$c3['cpu'].'/" class="three">'.$c3['name'].'</a>';
					echo '</li>';
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
	echo '</li>';
}
?>      
        </ul>
      
      
      
      
    </div>
    </div>
    </div>
    <div class="col-md-9 col-sm-12 col-xs-12">
    

<h1>Новинки</h1>    <?
	$tmpl_goods = 'goods.php';
	$where = " and new=1 ";
    $sql = "select G.*, price from ".$prefix."goods as G where  G.status=1 ".$where." ".$order." ";
    $goods = mysql_query($sql,$db);
    include($inc_path.'templates/goods.php');
    ?>
	
    </div></div>