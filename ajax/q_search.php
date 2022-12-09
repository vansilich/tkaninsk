<? 
$inc_path = "../admin/";	$root_path = "../"; 
include($inc_path."class/header.php");
$q = new query();

$this_cat['id'] = get_param('cat_id');
$cat_idm = get_param('cat_idm');
$param_id = get_param('param_id');
$nal = get_param('nal');
		

/* Выбираем все подразделы */
			$child = $q->select("select id from ".$prefix."catalog where parent=".to_sql($this_cat['id'])." and status=1");
			$_all_child = $this_cat['id'];
			foreach($child as $c){
				$_all_child .= ','.$c['id'];
				$child2 = $q->select("select id from ".$prefix."catalog where parent=".to_sql($c['id'])." and status=1");
				foreach($child2 as $c2){
					$_all_child .= ','.$c2['id'];
				}
			}
		/* end Выбираем все подразделы */
			//$where = " and catalog=".to_sql($this_cat['id'])." ";
			
			$where = " and catalog in (".$_all_child.") ";
			$for_search_where = " and catalog in (".$_all_child.") ";
			
			
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



			$cat_set = $q->select("select C.*,P.name,P.types, P.id as pid from ".$prefix."catalog_params as C
			join ".$prefix."adv_params as P on P.id = C.param_id
			where C.status=1 and C.cat_id=".to_sql(1)." and C.for_search=1 order by C.rank desc");   


          $price = $q->select1("select min(price) as min, max(price) as max
		  from ".$prefix."goods as G where status=1 and catalog in (".to_sql($this_cat['id']).")");

		  $min = (int)$price['min'];
		  $max = (int)$price['max'];
		  $step = ($max-$min)/100;
		  if($step > 1000) $step = 1000;
		  elseif($step > 100) $step = 100;
		  elseif($step > 10) $step = 10;
		  else $step = 1;


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
							}
							
							
							/**/
						
						////////////////////////////////////////////
							/***********************************
							****НАЗНАЧЕНИЕ***
							************************************/

			
			
			
			if(sizeof($cat_set)>0){
					foreach($cat_set as $row){
						if($row['types'] == 'c_int'){

							$fp[$row['pid']] = get_param('f'.$row['pid']);
							$t[$row['pid']] = get_param('t'.$row['pid']);


							$temp = $q->select1("select min(ival) as min, max(ival) as max
							  from ".$prefix."goods_param
							  where param_id=".to_sql($row['pid'])." and 
							   good_id in (select id from ".$prefix."goods where  catalog in (".to_sql($this_cat['id'])."))");



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
							/**/
						}else{

							/***********************************
							****$select['c_char']='Строка';***
							************************************/

							$fp[$row['pid']] = get_param('f'.$row['pid']);
							

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
				
				$numb = $q->select1("select count(DISTINCT id) as number from ".$prefix."goods 
				as G 
				join 
				(
					select  good_id
					from ".$prefix."goods_param as P
					where  (".$par_where.")  ".$sql_d."
				) as PP on G.id = PP.good_id			
				where status=1 ".$where."  ");
				$total_number = $numb['number'];
				/*
				echo "select count(*) as number from ".$prefix."goods 
				as G 
				join 
				(
					select  good_id
					from ".$prefix."goods_param as P
					where  (".$par_where.")  ".$sql_d."
				) as PP on G.id = PP.good_id			
				where status=1 ".$where."  ";*/
			}else{			

				
				$numb = $q->select1("select count(DISTINCT id) as number from ".$prefix."goods as G
				where G.status=1 ".$where."");
				/*
				echo "select count(DISTINCT id) as number from ".$prefix."goods as G
				where G.status=1 ".$where."";
				*/
				$total_number = $numb['number'];
			}
echo $total_number;
?>