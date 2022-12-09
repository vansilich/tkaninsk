<?
session_start();
ob_start();
include($inc_path."class/config.php");
include($inc_path."class/query.php");
include($inc_path."class/utils.php");
include($inc_path."class/pages.php");
include($inc_path."class/catalog.php");
include($inc_path."class/ctrl.php");
include($inc_path."class/table.php");
//include($inc_path."class/mail.php");
//include($inc_path."class/comments.php");

$q = new query();
?>