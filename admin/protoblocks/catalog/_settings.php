<?
  $cpu = get_param('cpu');
  $cat_cpu = get_param('cat_cpu');
  if(!empty($cat_cpu)){
    $this_cat = $this_catalog = $q->select1("SELECT * FROM ".$prefix."catalog WHERE cpu=".to_sql($cat_cpu)."");
    if($this_catalog == 0){
        header("HTTP/1.0 404 Not Found");
    }else{    
      $title = $this_catalog['title'];
      $descr = $this_catalog['description'];
      $keys = $this_catalog['keywords'];
    }
  }
   if(!empty($cpu)){
      $this_good = $row = $q->select1("SELECT * FROM ".$prefix."goods WHERE cpu=".to_sql($cpu)."");
      $this_cat = $this_catalog = $q->select1("SELECT * FROM ".$prefix."catalog WHERE id=".to_sql($row['catalog'])."");
      if($row == 0){
        header("HTTP/1.0 404 Not Found");
      }else{
        $title = $row['seo_title'];
        $descr = $row['seo_description'];
        $keys = $row['seo_keywords'];
      }
  }
  if(!empty($this_catalog)){

    if(!empty($this_catalog['all_parent'])){
      $all_ids =   $this_catalog['id'].','.$this_catalog['all_parent'];
    }else{
      $all_ids = $this_catalog['id'];
    }
    $_all_ids = $all_ids;
    $_allcats_id = explode(',',$all_ids);
	
			/* Выбираем все родителей */
		$cats = $this_cat['id'];
		if(!empty($this_cat['all_parent'])){
				$cats .= ','.$this_cat['all_parent'];
		}
		$temp = $q->select("select id,level from ".$prefix."catalog where id in (".$cats.")");
		$_all_cats = array();
		foreach($temp as $t){
			$_all_cats[$t['level']] = $t['id'];
		}
		/* end Выбираем все родителей */
		
		/* Выбираем все подразделы */
			$child = $q->select("select id from ".$prefix."catalog where parent=".to_sql($this_cat['id'])." and status=1");
			$_all_child = $this_cat['id'];
			foreach($child as $c){
				$_all_child .= ','.$c['id'];
				$_all_child .= get_cat_child($c['id']);
				/*$child2 = $q->select("select id from ".$prefix."catalog where parent=".to_sql($c['id'])." and status=1");
				foreach($child2 as $c2){
					$_all_child .= ','.$c2['id'];
				}*/
			}
		/* end Выбираем все подразделы */

	
	
  }
function get_cat_child($id){
	global $prefix;
	$_all_child = '';
	$q= new query();
	$child2 = $q->select("select id from ".$prefix."catalog where parent=".to_sql($id)." and status=1");
	foreach($child2 as $c2){
		$_all_child .= ','.$c2['id'];
		$_all_child .= get_cat_child($c2['id']);
		
	}
	return $_all_child;
}
?>