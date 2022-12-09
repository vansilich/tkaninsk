<? $inc_path = ""; $_menu_type = 'none'; include($inc_path."class/header_adm.php");
?>

<div class="box box-success">
<div class="box-header with-border">
  <h3 class="box-title">Старт</h3>
</div>
<div class="box-body">


<? /*
 <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>150</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        </div>
*/ ?>
  <div class="row start">
    <div class="col-md-3 col-sm-6 col-sx-12">
      <h4><i class="fa fa-files-o"></i> Страницы</h4>
      <div style="padding-left:30px;">
        <div><a href="pages.php?pmode=badd_page"><i class="fa fa-plus"></i> Добавить страницу</a></div>
        <?
              $q = new query();
			  $blocks = $q->select("select * from ".$prefix."blocks where status=1");
			  $col = sizeof($blocks)-1;
			  if($col < 3) $col = 3;
			  $pages = new pages($prefix.'pages',$main_page='Главная', $main_page_title = 'index.php');
			  $child = $q->select("select id,name from ".$prefix."pages where parent=".$pages->root()." order by rank desc limit ".$col);
			  foreach($child as $row){
			  		echo '<div><a href="edit.php?page_id='.$row['id'].'"><i class="fa fa-file-o"></i> '.$row['name'].'</a></div>';			  
			  }
			  ?>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-sx-12">
      <h4><i class="fa fa-files-o"></i> Модули</h4>
      <div style="padding-left:30px;">
        <?

			
			foreach($blocks as $row){
				echo '<div><a href="'.$inc_path.'protoblocks/'.$row['folder'].'/admin/"><i class="fa '.(empty($row['icon'])?'fa-circle-o':$row['icon']).'"></i> '.$row['name'].'</a></div>';
			}
			?>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-sx-12">
      <h4><i class="fa fa-gear"></i> Настройки сайта</h4>
      <div style="padding-left:30px;">
        <div><a href="<? echo $inc_path; ?>settings/users.php"><i class="fa fa-users"></i> Пароли и доступ</a></div>
        <div><a href="<? echo $inc_path; ?>settings/settings.php"><i class="fa fa-envelope"></i> Контакты</a></div>
        <div><a href="<? echo $inc_path; ?>settings/counters.php"><i class="fa fa-bar-chart"></i> Код счетчиков</a></div>
      </div>
    </div>
    <div class="col-md-3 col-sm-6 col-sx-12">
      <h4><i class="fa fa-cogs"></i> Администрирование</h4>
      <div style="padding-left:30px;">
        <div><a href="<? echo $inc_path; ?>settings/templates.php"><i class="fa fa-object-ungroup"></i> Шаблоны</a></div>
        <div><a href="<? echo $inc_path; ?>settings/blocks.php"><i class="fa fa-object-group"></i> Модули</a></div>
      </div>
    </div>
  </div>
</div>
<?
include($inc_path."class/bottom_adm.php");
?>
