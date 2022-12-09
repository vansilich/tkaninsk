<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$this_block_id = $_GET['this_block_id'];
$cat_id = (int)get_param('cat_id');
$zid = (int)get_param('zid');
//echo 'zid='.$zid.'<br>cid='.$cat_id.'<br>';
echo 'копия сделана<br>';
echo '<a href="index.php?cur_cat='.$cat_id.'">назад</a>';
$m = $q->select1("select max(rank) as r from ".$prefix."port ");
$r = $m['r']+1;
$good = $q->select1("select * from ".$prefix."port where id=".to_sql($zid));
 
$sql = "insert into ".$prefix."port set status=0, rank=".$r." ";
foreach($good as $k=>$v){
	$z = (int)$k;
	if($z == 0 && $k != '0' ){
		if($k == 'rank') continue;
		if($k == 'status') continue;
		if($k == 'id') continue;
		if($k == 'name') $sql .= ", ".$k."=".to_sql($v.'копия');
		else $sql .= ", ".$k."=".to_sql($v);
	}


}
//echo $sql;
$newid = $q->insert($sql);

//$q->exec("insert into ".$prefix."cat_goods set good_id=".$newid.", cat_id=".to_sql($cat_id));


?>
