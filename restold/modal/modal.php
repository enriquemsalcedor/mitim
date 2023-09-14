<?php
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
	header('content-type: application/json; charset=utf-8');
	header('Content-Type: application/JSON');
	//capurando el tipo de metodo
	include("../../conexion.php");
	
	$method = $_SERVER['REQUEST_METHOD'];        
  
	if ($method =='GET') {
		/*--datos que debo enviar del storage--*/  
		$id = $_REQUEST['id'];
		
		$query  = " SELECT a.id, a.titulo, a.descripcion, a.idestados, a.idambientes, a.idsubambientes, a.idclientes, a.idproyectos, 
					a.iddepartamentos, a.fechacreacion, a.horacreacion, a.asignadoa, a.idcategorias, a.idsubcategorias, a.idprioridades, 
					a.resolucion, a.fecharesolucion, a.horaresolucion, a.reporteservicio
					FROM incidentes a
					WHERE a.id IN ($id)";
		//debug($query);
		$result = $mysqli->query($query);
		if($result->num_rows > 0 ){
			$json = array();
			while($row = $result->fetch_assoc()){
				$json[]= array(
							'id' => $row['id'],
							'titulo' => $row['titulo'],
							'descripcion' => $row['descripcion'],
							'idestados' => $row['idestados'],
							'idambientes' => $row['idambientes'],
							'idsubambientes' => $row['idsubambientes'],
							'idclientes' => $row['idclientes'],
							'idproyectos' => $row['idproyectos'],
							'iddepartamentos' => $row['iddepartamentos'],
							'fechacreacion' => $row['fechacreacion'],
							'horacreacion' => $row['horacreacion'],
							'asignadoa' => $row['asignadoa'],
							'idcategorias' => $row['idcategorias'],
							'idsubcategorias' => $row['idsubcategorias'],
							'idprioridades' => $row['idprioridades'],
							'resolucion' => $row['resolucion'],
							'fecharesolucion' => $row['fecharesolucion'],
							'horaresolucion' => $row['horaresolucion'],
							'reporteservicio' => $row['reporteservicio']
						);
			}
			echo json_encode($json);
		}else{
			echo json_encode("no hay registros");
		}
	}else{
		$response['status'] = "fail-Incident";
		$response['cod'] = '004';
		echo json_encode($result);
	}
?>