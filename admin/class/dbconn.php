<? if (!defined("_conn_inc")) : ?>
<? define("_conn_inc",1); ?>
<?

$db=@mysql_connect($databasehost, $databaseuser , $databasepassword);
  if (!$db)
    die("<h1 align=center>Попробуйте через пару минут!<br>Unable connect to server!!!</h1>");
  
  if(!(mysql_select_db($database, $db)))
    die("<h1 align=center>Unable connect to database!!!</h1>");
/*  $db=@mysql_connect('localhost', 'root' , '');
  if (!$db)
    die("<h1 align=center>Unable connect to server!!!</h1>");
  
  if(!(mysql_select_db('autokost', $db)))
    die("<h1 align=center>Unable connect to database!!!</h1>");
*/
?>
<? endif; ?>