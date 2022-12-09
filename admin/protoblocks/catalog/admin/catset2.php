<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");
$q = new query();
$cur_ctgr = (int)get_param('cur_ctgr');

echo '<a href="index.php?cur_cat='.$cur_ctgr.'"><b>Назад</b></a><br><br>';
$cat = $q->select1("select name from ".$prefix."catalog where id=".to_sql($cur_ctgr));
$settings = $q->select1("select count(id) as col from ".$prefix."catalog_search_set where types=1 and catalog=".to_sql($cur_ctgr));
//вставка 20 доп полей
if($settings['col'] < 20){

	for($i=$settings['col']+1;$i <= 20; $i++){
		$q->insert("insert into ".$prefix."catalog_search_set set
		parametr =".to_sql('доп.поле '.$i).",
		p =".to_sql($i).",
		catalog =".to_sql($cur_ctgr).",
		types = 1,
		rank = ".to_sql(11-$i)."");
	}

	
}

	$table = new cTable('Настройки категории "'.$cat['name'].'" для поиска',$prefix.'catalog_search_set','id',$is_rank=1, $is_status=1, $is_add=0,$is_edit=1,$is_del=0);
	$table->set_page_size(20);
	$table->settings(' catalog ='.$cur_ctgr);
	$table->draw_params('cur_ctgr='.$cur_ctgr);
	$table->insertparam('catalog', $cur_ctgr);	
	$table->insertcol(new c_text('поле','parametr',1,0,$size='100',$max=''));
	$table->insertcol(new c_text('название','title',1,1,$size='100',$max=''));
	$table->insertcol(new c_text('ед. изм.','ext',1,1,$size='100',$max=''));
//	$table->insertcol(new c_checkbox('для поиска','for_search',1,1,0));
	$table->draw();

?>
