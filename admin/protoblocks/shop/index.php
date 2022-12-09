  <table cellpadding="0" cellspacing="0" width="946" border="0" align="center" style="margin-top:25px">
    <tr valign="top">
      <td width="700" style="padding-right:60px;">
<?
$_SESSION['back_url'] = $_SERVER['REQUEST_URI'];
$cpu_good = get_param('cpu_good');
if(empty($cpu_good)){



/*********goods maker**********/
			
			
			
			
			echo '<h1>'.$this_cat['name'].'</h1>';
			$img =  '';
			$goodimg = $root_path.'files/catimg/'.$this_cat['id'].$this_cat['img'];
			if(is_file($goodimg)){
				$img =  '<img src="/files/catimg/'.$this_cat['id'].$this_cat['img'].'" border="0" alt="'.my_htmlspecialchars($this_cat['name']).'" align="left" style="margin:0 10px 10px 0">';
			}			 
			echo $img;
			echo $this_cat['text'].'<br style="clear:both;">';
			$child = $q->select("select id,cpu,name,img,anons from ".$prefix."catalog where status=1 and parent=".to_sql($this_cat['id'])." order by rank desc");
			if(sizeof($child) > 0){
				foreach($child as $row){
					echo '<div style="margin-bottom:7px;">';
					$img =  '';
					$goodimg = $root_path.'files/catimg/'.$row['id'].$row['img'];
					if(is_file($goodimg)){
						$img =  '<img src="/files/catimg/'.$row['id'].$row['img'].'" border="0" alt="'.my_htmlspecialchars($row['name']).'" align="left" width="80" style="margin:0 10px 10px 0">';
					}			 
					echo $img;
					echo '<a href="/shop/'.$row['cpu'].'/">'.$row['name'].'</a><br>';
					echo $row['anons'];
					echo '<br style="clear:both;"></div>';
					
				}
			}
			
			$draw_params = '';
			$where = '';
			
			
			
			
			/*
			
			$goods_git = $q->select("select * from ".$prefix."goods where catalog = ".to_sql($this_cat['id'])."  and status=1 and hit=1 LIMIT 3");
			if(sizeof($goods_git) > 0){
				$tmpl = file_get_contents($inc_path.'parts/hit.php');
				
				foreach($goods_git as $row){
					$goods_row = $tmpl;	
					$goodimg = $root_path.'files/catalog/pre1_'.$row['id'].$row['img'];
					if(is_file($goodimg)){
						
						$goodimg =  '<img src="'.$goodimg.'" border="0" alt="'.$row['name'].'">';
					 
					}
					else $goodimg =  '';
					$price = $row['last_price'];	
					
					
					$goods_row = str_replace('{GOOD_LINK}','/'.(int)$row['id'].'-'.rutranslit($row['name']).'.html',$goods_row );
					$goods_row = str_replace('{GOOD_TITLE}',$row['name'],$goods_row );
					$goods_row = str_replace('{GOOD_IMG}',$goodimg,$goods_row );	
					$goods_row = str_replace('{GOOD_PRICE}',$price,$goods_row );
					$goods_row = str_replace('{GOOD_ID}',(int)$row['id'],$goods_row );
					echo $goods_row;
				}
				
				
				echo '<div class="clear"></div>';
			
			}
			*/
			
			
			
			
			$ord = get_param('ord');
			switch($ord){
				case 'name':
					$draw_ord_params .= '&ord=name';
					$order = ' order by name';
					$order_name = 'названию&darr;';
					break;
				case 'dname':
					$draw_ord_params .= '&ord=dname';
					$order = ' order by name desc';
					$order_name = 'названию&uarr;';
					break;
				case 'nedorogie':
					$draw_ord_params .= '&ord=nedorogie';
					$order = ' order by last_price';
					$order_name = 'цене&darr;';
					break;
				case 'dorogie':
					$draw_ord_params .= '&ord=dorogie';
					$order = ' order by last_price desc';
					$order_name = 'цене&uarr;';
					break;
				default:
					/*$draw_ord_params .= '';
					$order = ' order by last_price';
					$order_name = 'цене&darr;';*/
					$draw_ord_params .= '';
					$order = ' order by rank desc';
					$order_name = '';
					break;
			}
			$page_size = 10;			
			
			
			
			/***************************************************/
			/********************    Поиск    *******************************/
			/***************************************************/
			$cat_set = $q->select("select C.*,P.name,P.types, P.id as pid from ".$prefix."catalog_params as C
			join ".$prefix."adv_params as P on P.id = C.param_id
			where C.status=1 and C.cat_id=".to_sql($this_cat['id'])." and C.for_search=1 order by C.rank desc");
			$f = 0;
			$search = '';
			$par_where = '';
			$par_col = 0;
			if(sizeof($cat_set)>0){
				   $search .= '<div class="clear"></div> 
				   <form method="post" id="filtform" name="filtform">                  
				   <div class="select_warm_container">
						<div class="header_container">
							<img src="/include/img/warm_element_select/ico.jpg" alt="" />
							<div style="padding:0px 0 0 0;">
								Подобрать 
								<a onclick="select_war_elem_hidden()">Свернуть</a>
							</div>
							<div class="clear"></div>
						</div><!--header_container-->';
					$search .= '
						<div class="body"  id="select_warm_element_body">';
					foreach($cat_set as $row){
						
						if($row['types'] == 'c_int'){
							$fp[$row['pid']] = get_param('f'.$row['pid']);
							$t[$row['pid']] = get_param('t'.$row['pid']);
							$search .= '<div class="cell_1" align="right">'.$row['name'].':</div>
							<div class="cell_2" align="center">от</div>
							<input name="f'.$row['pid'].'" value="'.$fp[$row['pid']].'" />
							<div class="cell_2" align="center">до</div>
							<input name="t'.$row['pid'].'" value="'.$t[$row['pid']].'" />
							<div class="clear"></div>
							';
							
							if(!empty($fp[$row['pid']]) || !empty($t[$row['pid']]) ){
								$par_col++;
								$par_where .= empty($par_where) ? '': ' or ' ;
								$par_where .= " ( P.param_id=".to_sql($row['pid'])." ";	
							}
							if(!empty($fp[$row['pid']])){
								$par_where .= " and P.ival >= ".to_sql($fp[$row['pid']])." ";
								$draw_params .= '&f'.$row['pid'].'='.$fp[$row['pid']];
							}
							if(!empty($t[$row['pid']])){
								$par_where .= " and P.ival <= ".to_sql($t[$row['pid']])." ";
								$draw_params .= '&t'.$row['pid'].'='.$t[$row['pid']];
							}
							if(!empty($fp[$row['pid']]) || !empty($t[$row['pid']]) ){
								$par_where .= ' ) ';	
							}
						}else{
							$fp[$row['pid']] = get_param('f'.$row['pid']);
							$search .= '<div class="cell_1" align="right">'.$row['name'].':</div>
							<input name="f'.$row['pid'].'" value="'.$fp[$row['pid']].'" />
							<div class="clear"></div>
							';
							
							if(!empty($fp[$row['pid']])){
								$par_col++;
								$par_where .= empty($par_where) ? '': ' or ' ;
								$par_where .= " ( P.param_id=".to_sql($row['pid'])." and P.cval like ".to_sql('%'.$fp[$row['pid']].'%').") ";
								$draw_params .= '&f'.$row['pid'].'='.$fp[$row['pid']];
							}
							
							
							
						}
					}
					$search .= '<div class="clear"></div>';
					
					
					$search .= '<div class="cell_1" align="right">&nbsp; </div>
							<div class="cell_2" align="center">&nbsp;</div>
							<img src="/include/img/warm_element_select/pick_up_button.jpg" alt="Подобрать" 
							style="margin-top:20px;cursor:pointer;" onclick="filtform.submit();" />
							<div class="clear"></div>';
							
					$search .= '</div><!--body-->
				   </div><!--select_warm_container--></form>';		
					
			
			
			}			
			echo $search;
			
			/***************************************************/
			/********************   END Поиск    *******************************/
			/***************************************************/
			
			$draw_params_for_page = $draw_params.$draw_ord_params;
			$draw_params_for_ord = $draw_params.$draw_ps_params;
			$draw_params .= $draw_ord_params.$draw_ps_params;
			
			
			$page_name = 'page';	
			
/****************SQL Запрос**********************************/
			if(!empty($par_where)){
		
				if($par_col >1){
					$sql_d = " group by P.good_id having count(*)=".$par_col;
				}
				$sql = "select G.* from ".$prefix."goods as G 
				join 			
				(
					select good_id
					from ".$prefix."goods_param as P
					where  ".$par_where."  ".$sql_d."
				) as PP on G.id = PP.good_id
				where  G.status=1 ".$where." ".$order." LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size;
				
				$goods = mysql_query($sql,$db);
				
				$numb = $q->select1("select count(*) as number from ".$prefix."goods 
				as G 
				join 
				(
					select  good_id
					from ".$prefix."goods_param as P
					where  (".$par_where.")  ".$sql_d."
				) as PP on G.id = PP.good_id			
				where status=1 ".$where."  ");
				$total_number = $numb['number'];
			}else{			
				$sql = "select * from ".$prefix."goods where catalog = ".to_sql($this_cat['id'])."  and status=1 ".$where." ".$order." LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size;
				$goods = mysql_query($sql,$db);
				
				$numb = $q->select1("select count(id) as number from ".$prefix."goods where catalog = ".to_sql($this_cat['id'])."  and status=1 ".$where."");
				$total_number = $numb['number'];
			}
			
			
			
			
			
			
			
			
			if($total_number > 0){//если есть товары			
											
						$f = 0;
						$m = '';
						$cat_set2 = Array();
						
						$tmpl = file_get_contents($inc_path.'parts/goods.php'); 
						preg_match("/<!--begin-->(.+)<!--end_begin-->/isU", $tmpl,$tmp_begin);
						preg_match("/<!--end-->(.+)<!--end_end-->/isU", $tmpl,$tmp_end);
						preg_match("/<!--row-->(.+)<!--end_row-->/isU", $tmpl,$tmp_row);
						preg_match("/<!--delim-->(.+)<!--end_delim-->/isU", $tmpl,$tmp_delim);
						
						
						
						$totalpages = (int)(($total_number-1)/$page_size)+1;
						$cur = get_param($page_name,0)+1;	
						
						//$dp = draw_pages($page_size, $total_number, $page_name, $this_cat['alias'].".html" ,$draw_params,'page_tek');
draw_pages($page_size, $total_number, $page_name, '' ,$draw_params,'page_tek');
						$goods_begin = $tmp_begin[1];
						$f = get_param($page_name,0)*$page_size;						

						echo $goods_begin;
						
						$params ='';
						$z = 0;						

						while ($row = mysql_fetch_assoc($goods)) {
							if($z == 2){
								$z = 0;
								echo $tmp_delim[1];
							}
							$goodimg = $root_path.'files/catalog/1/pre1_'.$row['id'].$row['img1'];
							if(is_file($goodimg)){
								list($w, $h) = getimagesize($goodimg);
								if($w > 140) $goodimg =  '<img src="'.$goodimg.'" border="0" width="140" alt="'.$row['name'].'">';
								else  $goodimg =  '<img src="'.$goodimg.'" border="0" alt="'.$row['name'].'" class="pic2">';
							}
							else $goodimg =  '';
						
								
							$goods_row = $tmp_row[1];			
	
							//$price= $row['last_price'];
							$price= $row['price'];

							$goods_row = str_replace('{GOOD_LINK}','/goods/'.$row['cpu'].'/',$goods_row );
							$goods_row = str_replace('{GOOD_TITLE}',$row['name'],$goods_row );
							$goods_row = str_replace('{GOOD_ARTICUL}',$row['articul'],$goods_row );
							$goods_row = str_replace('{GOOD_IMG}',$goodimg,$goods_row );	
							$goods_row = str_replace('{GOOD_PRICE}',$price,$goods_row );
							$goods_row = str_replace('{GOOD_ANONS}',$row['anons'],$goods_row );			
							$goods_row = str_replace('{GOOD_ID}',(int)$row['id'],$goods_row );
							
							if($row['nal'] == 0){
								$goods_row = str_replace('{GOODS_STATUS}','',$goods_row );
				
							}elseif($row['isnew'] == 1){
								$goods_row = str_replace('{GOODS_STATUS}','<img src="/include/img/katalog/new.jpg" alt="" /><span class="red">Новинка!</span><a href="234">Заказать</a>',$goods_row );
							
							}elseif($row['hit'] == 1){
								$goods_row = str_replace('{GOODS_STATUS}','<img src="/include/img/katalog/hit.jpg" alt="" /><span class="yellow">Хит продаж</span><a href="234">Заказать</a>',$goods_row );
							
							}else{
								$goods_row = str_replace('{GOODS_STATUS}','',$goods_row );
							
							}
							

				
							$goods_row = str_replace('{GOOD_PARAMS}',$params,$goods_row );
							echo $goods_row;
							if($z == 0) echo '<td width="50">&nbsp;</td>';
							$z++;
						}
						
						
						
						echo $tmp_end[1];
						/*********end goods maker**********/
			}else{//if($total_number > 0){			
				echo 'Товаров не найдено.';
			
			}

}else{
	include('goods.php');	
	
}


?></td>

<td><div class="h">КАТАЛОГ</div>
<div id="basket_info">
<?
echo $_SESSION['basket_mini'];
?>
</div>

<?
$cats = $this_cat['id'];
if(!empty($this_cat['all_parent'])){
	$cats .= ','.$this_cat['all_parent'];
}
$all_cats = explode(',',$cats);

$catalog = $q->select("select id,cpu,name from ".$prefix."catalog where parent=0 and status=1 order by rank desc");
foreach($catalog as $c){
	echo '<a href="/shop/'.$c['cpu'].'/" class="menu_left">';
	if(in_array($c['id'],$all_cats)){
		echo '<b>'.$c['name'].'</b>';
	}else{
		echo $c['name'];
	}
	echo '</a><br />';
	$catalog2 = $q->select("select id,cpu,name from ".$prefix."catalog where parent=".to_sql($c['id'])." and status=1 order by rank desc");
	if(in_array($c['id'],$all_cats)){
		if(sizeof($catalog2)){
			echo '<div style="padding-left:10px;">';
			foreach($catalog2 as $c2){
				echo '-<a href="/shop/'.$c2['cpu'].'/" class="menu_left">';
				if(in_array($c2['id'],$all_cats)){
					echo '<b>'.$c2['name'].'</b>';
				}else{
					echo $c2['name'];
				}
				echo '</a><br />';	
				if(in_array($c2['id'],$all_cats)){
				
				
					$catalog3 = $q->select("select id,cpu,name from ".$prefix."catalog where parent=".to_sql($c2['id'])." and status=1 order by rank desc");
					if(sizeof($catalog3)){
						echo '<div style="padding-left:10px;">';
						foreach($catalog3 as $c3){
							echo '-<a href="/shop/'.$c3['cpu'].'/" class="menu_left">';
							if(in_array($c3['id'],$all_cats)){
								echo '<b>'.$c3['name'].'</b>';
							}else{
								echo $c3['name'];
							}
							echo '</a><br />';	
							
						}
						echo '</div>';
					}
				
					
				}
			}
			echo '</div>';
		}
	}
}
?>
       
        <!--hr class="hr2" style="margin-top:20px;" />
        <div class="h2">Стоимость (руб)</div>
        <span class="italic_text">от</span>
        <input class="input" style="width:60px; text-align:center" />
        <span class="italic_text">до</span>
        <input class="input" style="width:60px; text-align:center" />
        <div class="h2">Производитель</div>
        <input type="checkbox" /><span class="italic_text">AquaSkim</span><br style="clear:both" />
        <input type="checkbox" /><span class="italic_text" style="color:#000">SwimSkim</span><br style="clear:both" />
        <input type="checkbox" /><span class="italic_text">ProfiSkim</span><br style="clear:both" />
        <div class="h2">Объем корзины фильтра</div>
        <input type="checkbox" /><span class="italic_text">0,9 л</span><br style="clear:both" />
        <input type="checkbox" /><span class="italic_text" style="color:#000">1,4 л</span><br style="clear:both" />
        <input type="checkbox" /><span class="italic_text">7 л</span><br style="clear:both" />
        <input type="submit" value="подобрать" class="btn_green" style="width:121px; margin-top:25px;" /-->
        </td>
    </tr>
  </table>