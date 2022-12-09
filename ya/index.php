<?
//include_once('yandex-php-library_master.phar');
//$phar = new Phar('yandex-php-library_master.phar');
//require_once 'phar://yandex-php-library_master.phar/vendor/autoload.php';

use Yandex\Disk\DiskClient;
require_once 'yaapi/vendor/autoload.php';
require_once 'yaapi/src/Yandex/Disk/DiskClient.php';

//
/*
ID: 66cb3f5c7b7b47aca49f9c8889aa89ff
Пароль: e888e559a73d495a855ef414f4f48514
*/
$diskClient = new DiskClient('AQAAAAAAHrbfAATsUS4yf-j8HUG5sqBRUo-G3o0');
$diskClient->setServiceScheme(DiskClient::HTTPS_SCHEME);

echo 'z'.ini_get("upload_tmp_dir");
echo 'x'.$_ENV['TMP'].'='.$_ENV['TEMP'];

//$diskSpace = $diskClient->diskSpaceInfo();
//$login = $diskClient->getLogin();
?>
Привет, <?=$login?>
<?
//$diskSpace = $diskClient->diskSpaceInfo();
  die('ttt');
//Получаем свободное и занятое место
$diskSpace = $diskClient->diskSpaceInfo();?>
Всего места: <?=$diskSpace['availableBytes']?> байт.
<br />
Использовано: <?=$diskSpace['usedBytes']?> байт.
<br />
Свободно: <?=round(($diskSpace['availableBytes'] - $diskSpace['usedBytes']) / 1024 / 1024 / 1024, 2)?> ГБ 
из <?=round($diskSpace['availableBytes'] / 1024 / 1024 / 1024, 2)?> ГБ.

