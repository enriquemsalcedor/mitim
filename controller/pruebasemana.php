<?php

header('Content-Type: text/html; charset=UTF-8');
error_reporting(1);
require_once("../conexion.php");

/* $numsem = (date('W')+2);
$numsemana = str_pad($numsem, 2, "0", STR_PAD_LEFT); */
//$fechaActual = date('d-m-Y');	
$fechaActual = date('01-01-2023');	
$fechaSegundos = strtotime($fechaActual);	
$numsemana = date('W', $fechaSegundos);

$asunto = "Mantenimientos Preventivos para la Semana ".$numsemana." del Año ".date('Y');
$semana = "Semana ".$numsemana." del Año ".date('Y');

echo $asunto;




?>