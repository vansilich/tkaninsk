<?
$inc_path = '../../../';
include($inc_path."class/header_adm.php");

echo '<div class="sub_menu"><a href="./">Назад</a></div>';

$q->exec("CREATE TABLE IF NOT EXISTS `".$prefix."news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rank` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `anons` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `created` datetime NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `img` varchar(5) NOT NULL,
  `seo_title` varchar(255) NOT NULL,
  `seo_description` text NOT NULL,
  `seo_keywords` text NOT NULL,
  `cpu` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251");



include($inc_path."class/bottom_adm.php");

?>