<?
$_SESSION['back_url'] = $_SERVER['REQUEST_URI'];
$action = trim(get_param('action'));
$word = get_param('word');

?>

<h1>Поиск</h1>
<form method="post" action="" id="linkForm" name="curform" >
<div class="row search">
<div class="col-md-6 col-sm-6 col-xs-12 ">
<input type="text" class="input form-control"   name="word" value="<? echo  htmlspecialchars($word);?>">
</div><div class="col-md-2 col-sm-2 col-xs-12">
<input type="submit" value="найти" class="btn"></td>
   </div>
   </div>
</form>

<?

if(!empty($word)){

        $tmpl_goods = 'goods_main.php';
	$where = " (name like ".to_sql('%'.$word.'%')." or G.id like ".to_sql('%'.$word.'%')." or articul like ".to_sql('%'.$word.'%')." )";
	$page_size = 20;
	$page_name = 'page';
	$sql = "select G.* from ".$prefix."goods as G
		where status=1 and ".$where."
		 order by G.rank desc LIMIT ".(get_param($page_name,0)*$page_size).", ".$page_size;

	 $goods = mysql_query($sql,$db);

	$numb = $q->select1("select count(id) as number  from ".$prefix."goods as G where status=1 and
	".$where." ");

	$total_number = $numb['number'];

	if($total_number > 0){
		echo '<div class="narrow">';
			echo '<div style="padding:8px 0">Найдено по фразе "<span style="color:#00c0f7">'.$word.'</span>".</div>';

			echo '<div align="right">';
			draw_pages($page_size, $total_number, $page_name, "/search/",'word='.$word );
			echo '</div>';
		echo '</div>';
		include($inc_path.'templates/goods.php');

		echo '<div class="narrow"><div align="right">';
		draw_pages($page_size, $total_number, $page_name, "/search/",'word='.$word );
		echo '</div></div>';
	}else{
		echo '<div class="narrow"><div style="padding:8px 0">По фразе "<span style="color:#00c0f7">'.$word.'</span>" ничего не найдено.</div></div>';
	}
}


?>
