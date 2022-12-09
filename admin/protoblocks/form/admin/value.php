<?
$inc_path = '../../../';
include($inc_path."class/header_adm_pop.php");
?>
<div class="sub_menu">
<a href="./form.php">Назад</a><br />
</div>
<?

$fid = get_param('fid');

$table = new cTable('Значения',$prefix.'form_field_value' ,'id',$is_rank=1, $is_status=0, $is_add=1,$is_edit=1,$is_del=1);
$table->set_page_size(20);

$table->settings(' fid='.to_sql($fid));
$table->draw_params('fid='.$fid);
$table->insertparam('fid',$fid);



$table->insertcol(new c_text('Название','name',1,1,$size='100',$max=''));


$table->draw();
include($inc_path."class/bottom_adm_pop.php");
?>