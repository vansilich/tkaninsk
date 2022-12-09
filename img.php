<?php  
session_start();
header('Content-Type: image/png'); // даем знать браузеру, что это картинка png  
$string = mt_rand(10000,99999);
$_SESSION['secret_code']=$string;
$img = imagecreate(100,40);  
$black = imagecolorallocate($img,0,0,0);  
$white = imagecolorallocate($img,255,255,255);  
//$white = imagecolorallocate($img,194,199,201);  
imagefill($img,0,0,$white);  
imagettftext($img, 20, 10, 15, 35, $black, realpath('.').'/fonts/arial.ttf', $string);
$p = 0;  
while ($p<400)  
{  
   $x = mt_rand(1,99); // случайная координата пикселя шума по оси X  
   $y = mt_rand(1,39); // ось Y  
   $pixel = imagecolorat($img,$x,$y); // узнаем какой цвет используется на месте будущего пикселя шума  
   $point = ($pixel == $black) ? $white : $black; // если был черный, красим пиксель белым, если белый - красим черным  
   imagesetpixel($img,$x,$y,$point); // рисуем сам пиксель  
   $p++;  
}  
imagepng($img); 

?>
