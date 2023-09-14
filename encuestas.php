<?php 
	include("conexion.php");
	global $mysqli;
	
    $idencuestas = $_GET['idencuestas'];
	$idpreguntas = $_GET['idpreguntas'];
	$idincidentes = $_GET['idincidentes'];
	$idusuarios = $_GET['idusuarios'];
	$evaluacion = $_GET['evaluacion'];
	
	//echo $idencuestas.'-'.$idpreguntas.'-'.$idincidentes.'-'.$idusuarios.'-'.$evaluacion;
	
	if($idencuestas != '' && $idpreguntas != '' && $idincidentes != '' && $idusuarios != '' && $evaluacion != ''){
		$queryE = " UPDATE encuestasresultados SET evaluacion = ".$evaluacion.", realizada = now()
					WHERE idencuestas = ".$idencuestas." AND idpreguntas =  ".$idpreguntas." AND idincidentes = ".$idincidentes."
					AND idusuarios = ".$idusuarios." ";
		$mysqli->query($queryE);
	}	
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" /> 
    <title>Soporte | Encuesta</title>   
</head>
<body>
    <div id="page-transitions">
        <div id="page-content" class="page-content">    
            <div id="page-content-scroll" class="">
                <div class="content" style="text-align: center;">
					<img src="https://toolkit.maxialatam.com/soporte/images/encabezado-maxia-c.png" alt="" style="margin-top:15px; margin-bottom:10px">
                    <h3>Gracias por completar la encuesta, su retroalimentación es muy valiosa para nosotros</h3>
                </div>
            </div> 
        </div>
    </div> 
</body>
</html>