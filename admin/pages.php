<?
$inc_path = "";
$_menu_type = 'pages';
include($inc_path."class/header_adm.php");
?>
<div class="box box-primary">
<div class="box-header with-border">
              <h1 class="box-title"><i class="fa fa-files-o"></i> Страницы</h1>
            </div>

            <div class="box-body">
<?
$pages = new pages($prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');
$pages->draw();
?>
<br>
</div></div>
<?
include($inc_path."class/bottom_adm.php");
?>