<?
session_start();

// Выключение протоколирования ошибок
error_reporting(0);

// Включать в отчет простые описания ошибок
error_reporting(E_ALL | E_STRICT);
	
ob_start();

if (!isset($_SESSION["admin_info"]) || empty($_SESSION["admin_info"]['id'])){
	header("location:".$inc_path."login.php?back=".$_SERVER['REQUEST_URI']);
}

include($inc_path."class/config.php");
include($inc_path."class/query.php");
include($inc_path."class/utils.php");
include($inc_path."class/pages.php");
include($inc_path."class/catalog.php");
include($inc_path."class/ctrl.php");
include($inc_path."class/table.php");
include($inc_path."class/table2.php");
include($inc_path."class/mail.php");
$q = new query();
?>