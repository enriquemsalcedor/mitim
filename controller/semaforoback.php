<?php
    include("../conexion.php");

	$oper = '';
	if (isset($_REQUEST['oper'])) {
		$oper = $_REQUEST['oper'];   
	}
	
	switch($oper){
		case "cargarCorrectivosVerdes": 
			  cargarCorrectivosVerdes();
			  break;
		case "cargarCorrectivosAmarillos": 
			  cargarCorrectivosAmarillos();
			  break;
		case "cargarCorrectivosRojos": 
			  cargarCorrectivosRojos();
			  break; 
		default:
			  echo "{failure:true}";
			  break;
	}	
	
	
	//Correctivos con comentarios ya realizados, midiendo que este se ha realizado menor o dentro de 15 minutos.
	function cargarCorrectivosVerdes() 
	{ 
		global $mysqli;  
		//HAVING totalcomentario > 0 AND tiempotranscurrido < 15
        $query = "  SELECT a.id,a.titulo,a.fechacreacion, a.horacreacion, COUNT(b.id) AS totalcomentario,
					TIMESTAMPDIFF(MINUTE,CONCAT(a.fechacreacion,' ',a.horacreacion),NOW()) AS tiempotranscurrido
					FROM incidentes a 
					LEFT JOIN comentarios b ON b.idmodulo = a.id 
					WHERE a.tipo = 'incidentes' AND a.idestados != 16 
					GROUP BY a.id 
					HAVING totalcomentario > 0 AND tiempotranscurrido < 15
					ORDER BY a.id DESC";
		
		if(!$result = $mysqli->query($query)){
		  die();  
		} 
		$data=array(); 
		$mark=0;
		$total = $result->num_rows;
		
		while($row = $result->fetch_assoc()){
			
			$data[$mark][]=array(
				'id' =>	$row['id'], 				
				'titulo' =>	$row['titulo']  				
			);  
		}   
        $response = array(
			"total" => $total, 
			"data" => $data
		);
        echo json_encode($response);
	}
	
	//Correctivos sin comentarios, midiendo que está próximo a cumplir los 15 minutos.
	function cargarCorrectivosAmarillos() 
	{ 
		global $mysqli;  
		//HAVING totalcomentario = 0 AND tiempotranscurrido < 15
        $query = "  SELECT a.id,a.titulo,a.fechacreacion, a.horacreacion, COUNT(b.id) AS totalcomentario,
					TIMESTAMPDIFF(MINUTE,CONCAT(a.fechacreacion,' ',a.horacreacion),NOW()) AS tiempotranscurrido
					FROM incidentes a 
					LEFT JOIN comentarios b ON b.idmodulo = a.id 
					WHERE a.tipo = 'incidentes' AND a.idestados != 16
					GROUP BY a.id 
					HAVING totalcomentario = 0 AND tiempotranscurrido < 15 
					ORDER BY a.id DESC ";
		//echo $query;
		if(!$result = $mysqli->query($query)){
		  die();  
		} 
		$data=array(); 
		$mark=0; 
		$total = $result->num_rows;
		
		while($row = $result->fetch_assoc()){
			
			$data[$mark][]=array(
				'id' =>	$row['id'], 				
				'titulo' =>	$row['titulo'] 
			); 
		}   
		$response = array(
			"total" => $total, 
			"data" => $data
		 );
        echo json_encode($response); 
	}
	
	//Correctivos con comentarios registrados mayor a los 15 minutos o Correctivos sin comentarios ya cumplidos los 15 minutos.
	function cargarCorrectivosRojos() 
	{ 
		global $mysqli;  
		
        $query = "  SELECT a.id,a.titulo,a.fechacreacion, a.horacreacion, b.fecha, COUNT(b.id) AS totalcomentario,
					TIMESTAMPDIFF(MINUTE,CONCAT(a.fechacreacion,' ',a.horacreacion),NOW()) AS tiempotranscurrido,
					TIMESTAMPDIFF(MINUTE,CONCAT(a.fechacreacion,' ',a.horacreacion),CONCAT(DATE(b.fecha),' ',TIME(b.fecha))) AS tiempotranscurridocomentario
					FROM incidentes a 
					LEFT JOIN comentarios b ON b.idmodulo = a.id 
					WHERE a.tipo = 'incidentes' AND a.idestados != 16
					GROUP BY a.id 
					HAVING totalcomentario = 0 AND tiempotranscurrido >= 15
					ORDER BY a.id DESC ";
		
		if(!$result = $mysqli->query($query)){
		  die();  
		} 
		$data=array(); 
		$mark=0;  
		$total = $result->num_rows;
		
		while($row = $result->fetch_assoc()){
			
			$data[$mark][]=array(
				'id' =>	$row['id'], 				
				'titulo' =>	$row['titulo'],  				
			); 
		}  
		$response = array(
			"total" => $total, 
			"data" => $data
		 );
        echo json_encode($response);
	}	 	
	
?>