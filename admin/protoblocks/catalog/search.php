<?
	$pn = get_param($page_name,0);

			/***************************************************/
			/********************    Поиск    *******************************/
			/***************************************************/

//if($this_cat['level'] >=2){


if(!empty($this_cat['id']) ){

			$maker = get_param('maker');
			$pf = get_param('pf');
			$pt = get_param('pt');
			$nal = get_param('nal');

			if(!empty($maker)){
				$stemp = '';
				foreach($maker as $v){
					if(!empty($stemp)) $stemp .= ' or ';
					$stemp .= ' maker='.to_sql($v);
					//$draw_params .= '&maker[]='.$v;
					$dp_ar['maker['.$v.']'] = $v;
				}
				if(!empty($stemp)) $where .= ' and ('.$stemp.') ';
			}
			if(!empty($pf)){
				$where .= " and price >=".to_sql($pf);
				//$draw_params .= '&pf='.$pf;
				$dp_ar['pf'] = $pf;
			}

			if(!empty($pt)){
				$where .= " and price <=".to_sql($pt);
				//$draw_params .= '&pt='.$pt;
				$dp_ar['pt'] = $pt;
			}

			$nal = get_param('nal');
			if($nal == 1){
				$where .= " and nal=1 ";
				$dp_ar['nal'] = 1;
			}



?>

<aside id="filtr" >
<script>
document.addEventListener('DOMContentLoaded', function () {
	onSchange();
});
</script>

<?
$cat_set = $q->select("select C.*,P.name,P.types, P.id as pid,P.dimension from ".$prefix."catalog_params as C
			join ".$prefix."adv_params as P on P.id = C.param_id
			where C.status=1 and C.cat_id=".to_sql(1)." and C.for_search=1 order by C.rank desc");   

?>
<div class="products-params">

<form id="search_Form" action="#" method="get">
   <input type="hidden" name="cat_id" value="<? echo $this_cat['id'];?>"/>
   <input type="hidden" name="cat_idm" value="<? echo $cat_idm;?>"/>
  <div id="search-param">


    <?
          $price = $q->select1("select min(price) as min, max(price) as max
		  from ".$prefix."goods as G where status=1 and kol>0.5 and catalog in (".$_all_child.")");
		  

		  $min = (int)$price['min'];
		  $max = (int)$price['max'];
		  //if(empty($pf)) $pf = $min;
		 // if(empty($pt)) $pt = $max;

		  $step = ($max-$min)/100;
		  if($step > 1000) $step = 1000;
		  elseif($step > 100) $step = 100;
		  elseif($step > 10) $step = 10;
		  else $step = 1;
		  ?>


  		<div class="filtr">
          <div class="select-div">Цена от
            <input class="input"  name="pf" id="minCost" value="<? if(!empty($pf)) echo $pf;?>" placeholder="<? echo $min;?>"/>
            до
            <input class="input" name="pt" id="maxCost" placeholder="<? echo $max;?>" name="pt" id="maxCost" value="<? echo $pt;?>"/>
            р</div>
         

        </div>
<? /*
      <script>
	  document.addEventListener('DOMContentLoaded', function () {
				$("input#minCost").change(function(){
					var value1=$("input#minCost").val();
					var value2=$("input#maxCost").val();
					if (value1 < <? echo $min;?>) { value1 = <? echo $min;?>; $("input#minCost").val(<? echo $min;?>)}
					if(parseInt(value1) > parseInt(value2)){
						value1 = value2;
						$("input#minCost").val(value1);
					}
					onSchange();
				});


				$("input#maxCost").change(function(){
					var value1=$("input#minCost").val();
					var value2=$("input#maxCost").val();

					if (value2 > <? echo $max;?>) { value2 = <? echo $max;?>; $("input#maxCost").val(<? echo $max;?>)}

					if(parseInt(value1) > parseInt(value2)){
						value2 = value1;
						$("input#maxCost").val(value2);
					}
					onSchange();
				});
		});
        </script>
*/ ?>

<?
/*Блок с брендами* /
	  $makers = $q->select("select distinct maker from ".$prefix."goods as G where
	  G.catalog = ".to_sql($this_cat['id'])."
	  and G.status=1");
	if(sizeof($makers) > 1){
?>
 	 <a href="" class="fname">Бренды</a>
  	<div class="text">
        <?
		  $amaker = '0';
		  foreach($makers as $m) if(!empty($m['maker'])) $amaker .= ','.$m['maker'];
		  $temp = $q->select("select id,name from ".$prefix."maker where id in (".$amaker.") order by name");
		  foreach($temp as $row){
			  echo '
			  <label for="id_maker'.$row['id'].'" class="a';
					  if(!empty($maker) && in_array($row['id'], $maker)){
						echo ' ac';
					  }
					  echo '">
                      <input type="checkbox" name="maker['.$row['id'].']" id="id_maker'.$row['id'].'" value="'.$row['id'].'"';
					  if(!empty($maker) && in_array($row['id'], $maker)){
						echo ' checked';
					  }
					  echo '>
                    '.$row['name'].'<span class="filter-checkbox__close">x</span></label>


			  ';
		  }
		  ?>
           <br class="clear">

    </div>
<?
	}//if(sizeof($makers) > 0){
/* end Блок с брендами*/


			$f = 0;
			$par_where = '';
			$par_col = 0;
			
						
							////////////////////////////////////////////
							/***********************************
							****НАЗНАЧЕНИЕ***
							************************************/
							$all_value = $q->select("select distinct param_id from ".$prefix."goods_param where 
							  param_id in (select id from ".$prefix."adv_params where types='nazn') and 
							  good_id in (select id from ".$prefix."goods where status=1 ".$for_search_where.") 
							  
							");
							$pars = array();
							$pars[] = -1;
							foreach($all_value as $v){$pars[]=$v['param_id'];}
							//$temp = $q->select("select * from ".$prefix."adv_params_value where pid in (select id from ".$prefix."adv_params where types='nazn')");
							
							$temp = $q->select("select * from ".$prefix."adv_params_value where pid in (".implode(',',$pars).") order by name");

							
							
							
							
							
							

							$checks = '';
							foreach($temp as $v){
								
								$sval = get_param('s'.$v['pid']);
								if(!empty($sval)){
	
									$par_w = '';
									foreach($sval as $vvv){
										$par_w .= empty($par_w) ? '': ' or ' ;
										//$par_w .= " P.ival=".to_sql($vvv)." ";
										$par_w .= " P.ival=0 ";
										$dp_ar['s'.$v['pid'].'['.$vvv.']'] = $vvv;
									}
									if(!empty($par_w)){
										$par_col++;
										$par_where .= empty($par_where) ? '': ' or ' ;
										$par_where .= " (P.param_id=".to_sql($v['pid'])." and (".$par_w.") )";
	
									}
								}
								
								
								
								$checks .= '
								<label class="a';
								if(!empty($sval) && in_array($v['id'], $sval)){
									$checks .=  ' ac';
								}
								$checks .=  '" for="sv_'.$v['id'].'">
								<input type="checkbox" name="s'.$v['pid'].'['.$v['id'].']" id="sv_'.$v['id'].'" value="'.$v['id'].'"';
								if(!empty($sval) && in_array($v['id'], $sval)) $checks .=  ' checked';
								$checks .=  '>&nbsp;'.$v['name'].'</label><br>
								';

							}
							$tmpl_row = file_get_contents($inc_path.'parts/params_select.php');
							$tmpl_row = str_replace('{name}','Назначение',$tmpl_row);
							$tmpl_row = str_replace('{checks}',$checks,$tmpl_row);
							echo $tmpl_row;

							
							/**/
						
						////////////////////////////////////////////
							/***********************************
							****НАЗНАЧЕНИЕ***
							************************************/
			
			
			if(sizeof($cat_set)>0){
					foreach($cat_set as $row){
						if($row['types'] == 'c_int'){
/*
$select['c_int']='Число';
*/


							
							
							
							$fp[$row['pid']] = get_param('f'.$row['pid']);
							$t[$row['pid']] = get_param('t'.$row['pid']);


							$temp = $q->select1("select min(ival) as min, max(ival) as max
							  from ".$prefix."goods_param
							  where param_id=".to_sql($row['pid'])." and 
							   good_id in (select id from ".$prefix."goods where  catalog in (".to_sql($this_cat['id'])."))");


							  if($_SESSION["admin_info"]['id'] > 0){
							/*		echo "select min(ival) as min, max(ival) as max
							  from ".$prefix."goods_param
							  where param_id=".to_sql($row['pid'])." and good_id in (select good_id from ".$prefix."cat_goods where cat_id in (".$_all_child."))";
							  */
							  }

							  $minv = $temp['min'];
							  $minv = str_replace('.00','',$minv);
							  if(empty($minv)) $minv = 0;
							  //$minv = 0;
							  $maxv = $temp['max'];
							  $maxv = str_replace('.00','',$maxv);
							  if(empty($maxv)) $maxv = 0;
							  $step = ($maxv-$minv)/100;
							  if($step > 1000) $step = 1000;
							  elseif($step > 100) $step = 100;
							  elseif($step > 10) $step = 10;
							  elseif($step > 1) $step = 1;
							  else $step = 0.1;


							$fp[$row['pid']] = get_param('f'.$row['pid']);
							$t[$row['pid']] = get_param('t'.$row['pid']);

							$nf = 'f'.$row['pid'];
							$nt = 't'.$row['pid'];
							$sn = 'pslide'.$row['pid'];
							
							$tmpl_row = file_get_contents($inc_path.'parts/params_int.php');
							$tmpl_row = str_replace('{name}',$row['name'],$tmpl_row);
							$tmpl_row = str_replace('{sn}',$sn,$tmpl_row);
							$tmpl_row = str_replace('{namefrom}',$nf,$tmpl_row);
							$tmpl_row = str_replace('{nameto}',$nt,$tmpl_row);
							$tmpl_row = str_replace('{minvalue}',$minv,$tmpl_row);
							$tmpl_row = str_replace('{maxvalue}',$maxv,$tmpl_row);
							$tmpl_row = str_replace('{curvalfrom}',$fp[$row['pid']],$tmpl_row);
							$tmpl_row = str_replace('{curvalto}',$t[$row['pid']],$tmpl_row);
							$tmpl_row = str_replace('{edizm}',$row['dimension'],$tmpl_row);
							
							echo $tmpl_row;
							
							if(!empty($fp[$row['pid']]) || !empty($t[$row['pid']]) ){
								$par_col++;
								$par_where .= empty($par_where) ? '': ' or ' ;
								$par_where .= " ( P.param_id=".to_sql($row['pid'])." ";
							}
							if(!empty($fp[$row['pid']])){
								$par_where .= " and P.ival >= ".to_sql($fp[$row['pid']])." ";
								//$draw_params .= '&f'.$row['pid'].'='.$fp[$row['pid']];
								$dp_ar['f'.$row['pid']] = $fp[$row['pid']];
							}
							if(!empty($t[$row['pid']])){
								$par_where .= " and P.ival <= ".to_sql($t[$row['pid']])." ";
								//$draw_params .= '&t'.$row['pid'].'='.$t[$row['pid']];
								$dp_ar['t'.$row['pid']] = $t[$row['pid']];
							}
							if(!empty($fp[$row['pid']]) || !empty($t[$row['pid']]) ){
								$par_where .= ' ) ';
							}


						}elseif($row['types'] == 'c_select'){
							////////////////////////////////////////////
							/***********************************
							****$select['c_select']='Список';***
							************************************/
							
							$all_value = $q->select("select distinct cval from ".$prefix."goods_param where 
							  param_id in (".$row['pid'].") and 
							  good_id in (select id from ".$prefix."goods where status=1 ".$for_search_where." ) 
							  order by cval
							");
							$pars = array();
							$pars[] = -1;
							foreach($all_value as $v){$pars[]="'".$v['cval']."'";}
							
							$temp = $q->select("select id,name from ".$prefix."adv_params_value where pid=".$row['pid']." and name in (".implode(',',$pars).") order by name");
							
							$sval = get_param('s'.$row['pid']);
							if(!empty($sval)){

								$par_w = '';
								foreach($sval as $v){
									$t = $q->select1("select name from ".$prefix."adv_params_value  where id=".$v."");
									$par_w .= empty($par_w) ? '': ' or ' ;
									$par_w .= " P.cval=".to_sql($t['name'])." ";
									$dp_ar['s'.$row['pid'].'['.$v.']'] = $v;
								}
								if(!empty($par_w)){
									$par_col++;
									$par_where .= empty($par_where) ? '': ' or ' ;
									$par_where .= " (P.param_id=".to_sql($row['pid'])." and (".$par_w.") )";

								}
							}
							
							
							
							
							
if(sizeof($temp) > 0){
							$checks = '';
							foreach($temp as $v){
								$checks .= '
								<label class="a';
								if(!empty($sval) && in_array($v['id'], $sval)){
									$checks .=  ' ac';
								}
								$checks .=  '" for="sv_'.$v['id'].'">
								<input type="checkbox" name="s'.$row['pid'].'['.$v['id'].']" id="sv_'.$v['id'].'" value="'.$v['id'].'"';
								if(!empty($sval) && in_array($v['id'], $sval)) $checks .=  ' checked';
								$checks .=  '>&nbsp;'.$v['name'].'</label><br>
								';

							}
							$tmpl_row = file_get_contents($inc_path.'parts/params_select.php');
							$tmpl_row = str_replace('{name}',$row['name'],$tmpl_row);
							$tmpl_row = str_replace('{checks}',$checks,$tmpl_row);
							echo $tmpl_row;
}
							
							/**/
						}elseif($row['types'] == 'c_list'){
							////////////////////////////////////////////
							/***********************************
							****$select['c_select']='Список';***
							************************************/
							$temp = $q->select("select id,name from ".$prefix."adv_params_value where pid=".$row['pid']."
							and id in (select distinct ival from ".$prefix."goods_param where good_id in (select id from ".$prefix."goods where  catalog in (".to_sql($this_cat['id']).")))
							");

							$sval = get_param('s'.$row['pid']);
							if(!empty($sval)){

								$par_w = '';
								foreach($sval as $v){
									$par_w .= empty($par_w) ? '': ' or ' ;
									$par_w .= " P.ival=".to_sql($v)." ";
									$dp_ar['s'.$row['pid'].'['.$v.']'] = $v;
								}
								if(!empty($par_w)){
									$par_col++;
									$par_where .= empty($par_where) ? '': ' or ' ;
									$par_where .= " (P.param_id=".to_sql($row['pid'])." and (".$par_w.") )";

								}
							}
							
							
							
							
							

							$checks = '';
							foreach($temp as $v){
								$checks .= '
								<label class="a';
								if(!empty($sval) && in_array($v['id'], $sval)){
									$checks .=  ' ac';
								}
								$checks .=  '" for="sv_'.$v['id'].'">
								<input type="checkbox" name="s'.$row['pid'].'['.$v['id'].']" id="sv_'.$v['id'].'" value="'.$v['id'].'"';
								if(!empty($sval) && in_array($v['id'], $sval)) $checks .=  ' checked';
								$checks .=  '> '.$v['name'].'</label>
								';

							}
							$tmpl_row = file_get_contents($inc_path.'parts/params_select.php');
							$tmpl_row = str_replace('{name}',$row['name'],$tmpl_row);
							$tmpl_row = str_replace('{checks}',$checks,$tmpl_row);
							echo $tmpl_row;

							
							/**/
						}else{

							/***********************************
							****$select['c_char']='Строка';***
							************************************/

							$fp[$row['pid']] = get_param('f'.$row['pid']);
							$search .= '<div class="cell_1" align="right">'.$row['name'].':</div>
							<input name="f'.$row['pid'].'" value="'.$fp[$row['pid']].'" />
							<div class="clear"></div>
							';

							if(!empty($fp[$row['pid']])){
								$par_col++;
								$par_where .= empty($par_where) ? '': ' or ' ;
								$par_where .= " ( P.param_id=".to_sql($row['pid'])." and P.cval like ".to_sql('%'.$fp[$row['pid']].'%').") ";
								//$draw_params .= '&f'.$row['pid'].'='.$fp[$row['pid']];
								$dp_ar['f'.$row['pid']] = $fp[$row['pid']];
							}

						}
					}
			}



	  ?>


    <div class="filters-res">
                
     <div class="filters-result">Найдено товаров: <span id="s_res"><? echo $total_number;?></span></div>
    </div>
	<a href="javascript:" onclick="$('#search_Form').submit();return false;" id="poisk" class="btn">Найти</a>
  
  </div>
</form>
 </div>
  </aside>
<?

}

/***************************************************/
			/********************    end Поиск    *******************************/
			/***************************************************/

?>