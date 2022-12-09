<?
$inc_path = "./";
$_menu_type = 'pages';
include($inc_path."class/header_adm.php");
include($inc_path."class/pages.php");

$pages = new pages($prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');
$generate = get_param('generate');
$generate_tree = get_param('generate_tree');
if($generate==1){	
	$pages->generate_page(get_param('page_id'));
}
if($generate_tree==1){
	$pages->generate_site_branch(get_param('page_id'));
}

$q = new query();
if(isset($_POST['page_text'])){
	$q->exec("update ".$prefix."pages set 
	text='".addslashes(get_param('page_text'))."', 
	template='".get_param('template')."', 
	name='".get_param('name')."', 
	dir_name='".get_param('dir_name')."', 
	
	title='".my_htmlspecialchars(get_param('title'))."', 
	description='".my_htmlspecialchars(get_param('description'))."', 
	keywords='".my_htmlspecialchars(get_param('keywords'))."', 
	
	block='".get_param('block')."', 
	block_place='".get_param('block_place')."'
	where id=".get_param('page_id'));
	$pages->generate_page(get_param('page_id'));

	
	$url = $pages->get_page_link(get_param('page_id'));
	$q->exec("update ".$prefix."pages set 
	uri='".$url."'
	where id=".get_param('page_id'));
/*	
$pagesss = $q->select("select id from ".$prefix."pages");	
foreach($pagesss as $p){
	$url = $pages->get_page_link($p['id']);
	$q->exec("update ".$prefix."pages set 
	uri='".$url."'
	where id=".$p['id']);
}
*/	


}
$page = $q->select1("select * from ".$prefix."pages where id=".$_GET['page_id']);?>
<section class="content-header" style="margin-bottom:15px;">
 <h1><i class="fa fa-file-text-o"></i> <? echo $page['name'];?></h1>
<?
echo $pages->path_to(get_param('page_id'));
?>
</section>

<form method="POST" action="edit.php?page_id=<? echo get_param('page_id'); ?>">
  <div class="nav-tabs-custom"> 
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs ">
    <li class="pull-right header">
        <span>
        <div class="btn btn-primary" 
		onclick="document.location.href='edit.php?generate=1&page_id=<? echo get_param('page_id'); ?>'; return false;">
		<i class="fa fa-recycle"></i> Сгенирировать страницу</div>
        </span>
      </li>
    
      <li class="active"><a href="#tab_text" data-toggle="tab" ><i class="fa fa-file-text-o"></i> Текст</a></li>
      <li><a href="#tab_seo" data-toggle="tab" ><i class="fa fa-search"></i> SEO-параметры</a></li>
      
    </ul>
    <div class="tab-content no-padding">
      <div class="tab-pane active" id="tab_text" style="position: relative; padding:20px; ">
        <div class="row">
          <div class="form-group col-md-4  col-xs-12">
            <label for="">Название</label>
            <input type="text" class="form-control" name="name" value="<? echo $page['name']; ?>">
          </div>
          <div class="form-group col-md-4  col-xs-12">
            <label for="">Название лат</label>
            <input type="text" class="form-control" name="dir_name" value="<? echo $page['dir_name']; ?>">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-4  col-xs-12">
            <label for="">Шаблон</label>
            <select class="form-control" name="template">
              <option value="0">без шаблона</option>
              <?
$templ = $q->select("select * from ".$prefix."templates");
foreach($templ as $row){
	echo '<option value="'.$row['id'].'"';
	if($row['id'] == $page['template']) echo ' selected ';
	echo ' >'.$row['name'];
}
?>
            </select>
          </div>
          <div class="form-group col-md-4  col-xs-12">
            <label for="">Модуль</label>
            <select name="block" class="form-control" >
              <option value="0">без модуля</option>
              <?
$protos = $q->select("select id,name from ".$prefix."blocks order by name");
foreach($protos as $row){
	echo '<option value="'.$row['id'].'"';
	if($row['id'] == $page['block']) echo ' selected ';
	echo ' >'.$row['name'];
}
?>
            </select>
          </div>
          <div class="form-group col-md-4  col-xs-12">
            <label for="">Выводить на странице</label>
            <select name="block_place" class="form-control">
              <option value = "0"
<? if($page['block_place'] == 0) echo ' selected ';?>
>Вместо текста</option>
              <option value = "1"
<? if($page['block_place'] == 1) echo ' selected ';?>
>После текста</option>
              <option value = "2"
<? if($page['block_place'] == 2) echo ' selected ';?>
>До текста</option>
            </select>
          </div>
        </div>
        <textarea class="ckeditor form-control" id="editor1" name="page_text" style="width:98%;height:600px;"><? echo $page['text']; ?></textarea>
      </div>
      <div class="tab-pane" id="tab_seo" style="position: relative;  padding:20px;">
        <div class="form-group">
          <label for="">Title</label>
          <input class="form-control" type="text" name="title" value="<? echo $page['title']; ?>" size="50">
        </div>
        <div class="form-group">
          <label for="">Description</label>
          <textarea class="form-control" cols="50" rows="4"  name="description" ><? echo $page['description']; ?></textarea>
        </div>
        <div class="form-group">
          <label for="">Keywords</label>
          <textarea class="form-control" cols="50" rows="4" name="keywords" ><? echo $page['keywords']; ?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div style="padding:0 20px 20px 20px;" align="right">
    <input type="submit" value="Сохранить"  class="btn btn-success">
    <input type="reset" value="Отмена"  class="btn" onClick="document.location.href='pages.php'">
  </div>
</form>
<?
include($inc_path."class/bottom_adm.php");
?>
