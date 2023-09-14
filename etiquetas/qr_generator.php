<?php
require('../librerias/phpqrcode/qrlib.php'); 

QRcode::png($_GET['id']);

?>