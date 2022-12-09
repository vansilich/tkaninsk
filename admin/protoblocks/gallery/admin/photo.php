<?
$inc_path = '../../../';
include($inc_path."class/header_adm_pop.php");
?>
<?
$gid = get_param('gid');
$q = new query();
$table = new cTable('Фото',$prefix.'gallery_foto' ,'id',$is_rank=1, $is_status=1, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);
$table->settings(' gid='.to_sql($gid));
$table->draw_params('gid='.$gid);
$table->insertparam('gid',$gid);
//$table->insertcol(new c_text('Заголовок','title',1,1,$size='100',$max=''));
$table->insertcol(new c_image('Картинка','img',1,1,$inc_path.'../files/gallery_foto/', '120;500'));

$table->draw();

include($inc_path."class/bottom_adm_pop.php");
?>