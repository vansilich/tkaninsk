<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$this_block_id = $_GET['this_block_id'];
$cat_id = (int)get_param('cat_id');
$zid = (int)get_param('zid');
//echo 'zid='.$zid.'<br>cid='.$cat_id.'<br>';
echo 'копия сделана<br>';
echo '<a href="index.php?cur_cat='.$cat_id.'">назад</a>';
$m = $q->select1("select max(rank) as r from ".$prefix."goods ");
$r = $m['r']+1;
//,full 
$good = $q->select1("select * from ".$prefix."goods where id=".to_sql($zid));
 
$sql = "insert into ".$prefix."goods set status=0, rank=".$r." ";
foreach($good as $k=>$v){
	$z = (int)$k;
	if($z == 0 && $k != '0' ){
		if($k == 'id') continue;
		if($k == 'rank') continue;
		if($k == 'status') continue;
		if($k == 'img1' || $k == 'img2' || $k == 'img3' || $k == 'img4' || $k == 'img5') continue;
		
		if($k == 'name') $sql .= ", ".$k."=".to_sql($v.'копия');
		
		elseif($k == 'cpu') $sql .= ", ".$k."=".to_sql($v.rand(999,9999	));
		
		else $sql .= ", ".$k."=".to_sql($v);
	}


}
//echo $sql;
$newid = $q->insert($sql);

header("index.php?cur_cat=".$cat_id);
include($inc_path."class/bottom_adm.php");
?>
