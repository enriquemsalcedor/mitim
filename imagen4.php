<?php
if(!isset($_GET['size'])) $_GET['size'] = 44;
    if(!isset($_GET['text'])) $_GET['text'] = "Hello, world!";

    $size = imagettfbbox($_GET['size'], 0, "fonts/open/OpenSans-Regular.ttf", $_GET['text']);
    $xsize = abs($size[0]) + abs($size[2]);
    $ysize = abs($size[5]) + abs($size[1]);

    $image = imagecreate($xsize, $ysize);
    $blue = imagecolorallocate($image, 0, 0, 255);
    $white = ImageColorAllocate($image, 255,255,255);
    imagettftext($image, $_GET['size'], 0, abs($size[0]), abs($size[5]), $white, "fonts/open/OpenSans-Regular.ttf", $_GET['text']);

    header("content-type: image/png");
    imagepng($image);
    imagedestroy($image);
?>