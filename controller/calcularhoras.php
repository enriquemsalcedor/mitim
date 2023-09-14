














<?php
	date_default_timezone_set("America/Panama");
	function diferencia($fechadesde,$horadesde,$fechahasta,$horahasta){
		
		$fecha1 = strtotime($fechadesde."".$horadesde);
		$fecha2 = strtotime($fechahasta."".$horahasta);  
		$dif = $fecha2 - $fecha1; 

		$horas = floor($dif/3600);
		$minutos = floor(($dif-($horas*3600))/60);
		$segundos = $dif-($horas*3600)-($minutos*60);
		 
		return $horas;
	}
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	echo "<br>";
	$fechadesde = "2021-05-06";
	$horadesde  = "13:45:00";
	$fechahoy = date("Y-m-d");
	$horahoy  = date("H:i:s");
	
	echo "FECHA HOY:".$fechahoy;
	echo "<br>";
	echo "HORA HOY:".$horahoy;
	echo "<br>";
	echo "HORA SERVIDOR:". date("G:H:s");;
	echo "<br>";
	echo "DIFERENCIA ES:".diferencia($fechadesde,$horadesde,$fechahoy,$horahoy);