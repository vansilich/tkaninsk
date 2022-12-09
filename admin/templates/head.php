<?
$page = $q->select1("select title,description,keywords from ".$prefix."pages where id=".to_sql($this_page_id));
$title  = $page['title'];
$descr = $page['description'];
$keys = $page['keywords'];
//$cat_id= get_param('cat_id',0);
if($this_block_id == 28){
	$n_name = get_param('n_name');

	if(!empty($n_name)){
		$this_house = $q->select1("select * from ".$prefix."house where cpu=".to_sql($n_name));		
		$title = $this_house['gtitle']; 
		$descr = $this_house['gdescription']; 
		$keys = $this_house['gkeywords']; 
	}
	if(!empty($good_name)){
		$row =$q->select1("select gtitle,gdescription,gkeywords,catalog,id from ".$prefix."goods as G where G.cpu=".to_sql($good_name));
		
		$good_cat = $q->select1("select cat_id from ".$prefix."cat_goods where good_id =".to_sql($row['id'])." and cat_id=".to_sql($_SESSION['cur_good_cat']));
		if($good_cat == 0){
			$good_cat = $q->select1("select cat_id from ".$prefix."cat_goods where good_id =".to_sql($row['id']));
		}
		$gid = $row['id'];
		$title = $row['gtitle']; 
		$descr = $row['gdescription']; 
		$keys = $row['gkeywords']; 
		$this_cat_id = $row['catalog'];
		$cat_id = $row['catalog'];
		if(!empty($good_cat['cat_id'])){			
			$this_cat = $q->select1("select * from ".$prefix."catalog where id=".to_sql($good_cat['cat_id']));
		}
		//$this_cat = $q->select1("select * from ".$prefix."catalog where id=".to_sql($row['catalog']));
	}

	
}

if($this_page_id == 4){
	$aname = get_param('aname');
	if(!empty($aname)){	
		$row = $q->select1("SELECT gtitle,gdescription,gkeywords,id FROM ".$prefix."actions WHERE status=1 AND cpu=".to_sql($aname));	
		$title = $row['gtitle']; 
		$descr = $row['gdescription']; 
		$keys = $row['gkeywords']; 
	}	
	
}
$_settings = $q->select1("select * from ".$prefix."settings where id='phone'");
$title = $title;
$descr = my_htmlspecialchars($descr);
$keys = my_htmlspecialchars($keys);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<title><? echo  $title;?></title>
<meta name="description" content="<? echo  $descr;?>">
<meta name="Keywords" content="<? echo  $keys;?>">

    <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,500&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="/c/s.css">
 	

</head>
