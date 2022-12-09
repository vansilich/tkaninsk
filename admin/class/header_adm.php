<?
include($inc_path."class/header_adm_inc.php");

if(!isset($_menu_active)) $_menu_active = '';
if(!isset($_menu_type)) $_menu_type = '';
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Vakas CMS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/bootstrap/css/bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<? echo $inc_path;?>includes/dist/css/AdminLTE.min.css">
<!-- jQuery 2.2.0 -->
<script src="<? echo $inc_path;?>includes/plugins/jQuery/jQuery-2.2.0.min.js"></script>

<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="/admin/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">CMS</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">Vakas <b>CMS</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <? /*
          <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <img src="<? echo $inc_path; ?>includes/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning">10</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 10 notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li>
                    <a href="#">
                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="#">View all</a></li>
            </ul>
          </li>
          <!-- Tasks: style can be found in dropdown.less -->
          <li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger">9</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 9 tasks</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <li><!-- Task item -->
                    <a href="#">
                      <h3>
                        Design some buttons
                        <small class="pull-right">20%</small>
                      </h3>
                      <div class="progress xs">
                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                          <span class="sr-only">20% Complete</span>
                        </div>
                      </div>
                    </a>
                  </li>
                  <!-- end task item -->
                </ul>
              </li>
              <li class="footer">
                <a href="#">View all tasks</a>
              </li>
            </ul>
          </li>
          */ ?>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<? echo $inc_path; ?>includes/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><? echo $_SESSION["admin_info"]['login'];?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<? echo $inc_path; ?>includes/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <? echo $_SESSION["admin_info"]['login'];?>
                </p>
              </li>
              <!-- Menu Body -->
             
              <!-- Menu Footer-->
              <li class="user-footer">
                
                <div class="pull-right">
                  <a href="/admin/login.php?cancel=true" class="btn btn-default btn-flat">Выход</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

           
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">Панель</li>

        <li class="treeview linked <? if($_menu_type == 'pages') echo ' active';?>">
          <a href="<? echo $inc_path; ?>pages.php">
            <i class="fa fa-files-o"></i>
            <span>Страницы</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>    
          
          <ul class="treeview-menu">
              <li><a href="<? echo $inc_path; ?>pages.php?pmode=badd_page"><i class="fa fa-plus"></i> Добавить страницу</a></li>
              <li><a href="<? echo $inc_path; ?>pages.php?pmode=generate"><i class="fa fa-recycle"></i> Перегенерация страниц</a></li>
              
          </ul>
          
               
        </li>
        <li class="treeview active">
          <a href="<? echo $inc_path; ?>settings/blocks.php">
            <i class="fa fa-th"></i> <span>Модули</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
          <?

$q = new query();
$blocks = $q->select("select * from ".$prefix."blocks where status=1");
foreach($blocks as $row){
	echo '
	<li><a href="'.$inc_path.'protoblocks/'.$row['folder'].'/admin/"><i class="fa '.(empty($row['icon'])?'fa-circle-o':$row['icon']).'"></i> '.$row['name'].'</a></li>
	';
}
?>
          </ul>
        </li>
        <li class="treeview <? if($_menu_type == 'settings') echo ' active';?>">
          <a href="<? echo $inc_path; ?>settings/settings.php">
            <i class="fa fa-gear"></i>
            <span>Настройки</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
              <li<? if($_menu_active == 'users') echo ' class="active"';?>>
              <a href="<? echo $inc_path; ?>settings/users.php"><i class="fa fa-users"></i> Пароли и доступ</a></li>
              <li<? if($_menu_active == 'contacts') echo ' class="active"';?>>
              <a href="<? echo $inc_path; ?>settings/settings.php"><i class="fa fa-envelope"></i> Контакты</a></li>
              <li<? if($_menu_active == 'counter') echo ' class="active"';?>>
              <a href="<? echo $inc_path; ?>settings/counters.php"><i class="fa fa-bar-chart"></i> Код счетчиков</a></li>
          </ul>
        </li>
        
        
        <li class="treeview <? if($_menu_type == 'admin') echo ' active';?>">
          <a href="<? echo $inc_path; ?>settings/settings.php">
            <i class="fa fa-cogs"></i>
            <span>Администрирование</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">  
          
          <li<? if($_menu_active == 'robots') echo ' class="active"';?>><a href="<? echo $inc_path; ?>settings/robots.php"><i class="fa  fa-file-code-o"></i> robots.txt</a></li>
                     
              <li<? if($_menu_active == 'templates') echo ' class="active"';?>><a href="<? echo $inc_path; ?>settings/templates.php"><i class="fa fa-object-ungroup"></i> Шаблоны</a></li>
              <li<? if($_menu_active == 'blocks') echo ' class="active"';?>><a href="<? echo $inc_path; ?>settings/blocks.php"><i class="fa fa-object-group"></i> Модули</a></li>
			  
			   <li<? if($_menu_active == 'icons') echo ' class="active"';?>><a href="<? echo $inc_path; ?>settings/icons.php"><i class="fa fa-fonticons"></i> Иконки</a></li>
          </ul>
        </li>
        <li><a href="/" target="_blank"><i class="fa fa-external-link"></i> <span>На сайт</span></a></li>
      <? /*
        <li><a href="../../documentation/index.html"><i class="fa fa-book"></i> <span>Документация</span></a></li>
        <li class="header">Заголовок</li>
        */?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
<? /*    <section class="content-header">
      <h1>
        Blank page
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section>
*/ ?>
    <!-- Main content -->
    <section class="content">
<?
if($_SESSION["admin_info"]["group_id"] == 1){
	$this_block_edit = 1;
	$this_block_del = 1;
}else{
	$this_page = basename($_SERVER['REQUEST_URI']);
	if (strpos($this_page, "?") !== false) $this_page = reset(explode("?", $this_page));
	
	$dir = dirname($_SERVER['REQUEST_URI']);
	$mas = explode('/',$dir );
	
	if(empty($mas[2])){
		switch($this_page){
			case 'index.php': break;	
			case '': break;
			case 'admin': break;	
			case 'pages.php': echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;
			
			default: echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();				
			
		}
	}elseif($mas[2] == 'settings'){
		echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;
		/*case 'users.php': echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;
			case 'settings.php': echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;
			case 'counters.php': echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;
			case 'templates.php': echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;
			case 'blocks.php': echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;
			case 'protoblocks.php': echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;
			case 'templates.php': echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();break;*/
	}elseif($mas[2] == 'protoblocks'){
		if(empty($_SESSION["user_rights"][$mas[3]]['show'])){
			echo 'У вас нет прав для просмотра данной страницы';include($inc_path."class/bottom_adm.php");die();	
		}else{
			$this_block_edit = $_SESSION["user_rights"][$mas[3]]['edit'];
			$this_block_del = $_SESSION["user_rights"][$mas[3]]['del'];
			
			
		}
		
		
	}

}

?>