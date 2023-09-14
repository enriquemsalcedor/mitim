<?php

include("conexion.php");

function copiarCarpeta($origen, $destino) {
    // Crea la carpeta de destino si no existe
    if (!is_dir($destino)) {
        mkdir($destino, 0777, true);
    }

    // Abre el directorio de origen
    $dir = opendir($origen);

    // Recorre todos los archivos y subdirectorios del directorio de origen
    while (($archivo = readdir($dir)) !== false) {
        if ($archivo == '.' || $archivo == '..') {
            continue;
        }
		
		 if ($archivo == '.' || $archivo == '..') {
			continue;
		}

		// Si es un subdirectorio, omite la copia y continúa al siguiente archivo
		if (is_dir($origen . '/' . $archivo)) {
			continue;
		}

		// Copia el archivo
		copy($origen . '/' . $archivo, $destino . '/' . $archivo);

        // Si es un subdirectorio, copia recursivamente
        if (is_dir($origen . '/' . $archivo)) {
			copiarCarpeta($origen . '/' . $archivo, $destino . '/' . $archivo);
        } else {
            // Copia el archivo
            copy($origen . '/' . $archivo, $destino . '/' . $archivo);
        } 
    }

    closedir($dir);
}

function nuevoNombre($carpeta, $nuevoNombre) {
    // Obtiene la ruta del directorio padre
    $directorioPadre = dirname($carpeta);

    // Renombra la carpeta
    rename($carpeta, $directorioPadre . '/' . $nuevoNombre);
}

$query ="select * from incidentescopia where idclientes = 55 AND id > 45575 ";
$result = $mysqli->query($query);
while($row = $result->fetch_assoc()){
	
	$id = $row['id'];
	
	$titulo = $row['titulo'];
	$descripcion = $row['descripcion'];
	$idactivos = $row['idactivos'];
	$idestados = $row['idestados'];
	$idcategorias = $row['idcategorias'];
	$idsubcategorias = $row['idsubcategorias'];
	$idprioridades = $row['idprioridades'];
	$origen = $row['origen'];
	$creadopor = $row['creadopor'];
	$solicitante = $row['solicitante'];
	$asignadoa = $row['asignadoa'];
	$resolucion = $row['resolucion'];
	$resueltopor = $row['resueltopor'];
	$fechacreacion = $row['fechacreacion'];
	$fechavencimiento = $row['fechavencimiento'];
	$fecharesolucion = $row['fecharesolucion'];
	$fechacierre = $row['fechacierre'];
	$fechamodif = $row['fechamodif'];
	$horacreacion = $row['horacreacion'];
	$horavencimiento = $row['horavencimiento'];
	$horaresolucion = $row['horaresolucion'];
	$horacierre = $row['horacierre'];
	$fueraservicio = $row['fueraservicio'];
	$fechadesdefueraservicio = $row['fechadesdefueraservicio'];
	$fechafinfueraservicio = $row['fechafinfueraservicio'];
	$horastrabajadas = $row['horastrabajadas'];
	$periodo = $row['periodo'];
	$notificar = $row['notificar'];
	$fusionado = $row['fusionado'];
	$idsfusion = $row['idsfusion'];
	$reporteservicio = $row['reporteservicio'];
	$horario = $row['horario'];
	$tags = $row['tags'];
	$idemail = $row['idemail'];
	$fechareal = $row['fechareal'];
	$horareal = $row['horareal'];
	$idempresas = $row['idempresas'];
	$idclientes = $row['idclientes'];
	$idproyectos = $row['idproyectos'];
	$iddepartamentos = $row['iddepartamentos'];
	$idambientes = $row['idambientes'];
	$idsubambientes = $row['idsubambientes'];
	$tipo = $row['tipo'];
	$atencion = $row['atencion'];
	$estadoant = $row['estadoant'];
	$niveldeservicio = $row['niveldeservicio'];
	$contacto = $row['contacto'];
	$costo = $row['costo'];
	$frecuencia = $row['frecuencia'];
	$fechageneracion = $row['fechageneracion'];
	$idplan = $row['idplan'];
	$idpreventivos = $row['idpreventivos'];
	$idetiquetas = $row['idetiquetas'];
	 
	$insert = "INSERT INTO incidentes (titulo,descripcion,idactivos,idestados,idcategorias,idsubcategorias,idprioridades,origen,creadopor,solicitante,asignadoa,resolucion,resueltopor,fechacreacion,fechavencimiento,
				fecharesolucion,fechacierre,fechamodif,horacreacion,horavencimiento,horaresolucion,horacierre,fueraservicio,fechadesdefueraservicio,fechafinfueraservicio,horastrabajadas,periodo,notificar,fusionado,
				idsfusion,reporteservicio,horario,tags,idemail,fechareal,horareal,idempresas,idclientes,idproyectos,iddepartamentos,idambientes,idsubambientes,tipo,atencion,estadoant,niveldeservicio,contacto,
				costo,frecuencia,fechageneracion,idplan,idpreventivos,idetiquetas) VALUES (
				'".$titulo."','".$descripcion."','".$idactivos."','".$idestados."','".$idcategorias."','".$idsubcategorias."','".$idprioridades."','".$origen."','".$creadopor."','".$solicitante."','".$asignadoa."','".$resolucion."','".$resueltopor."','".$fechacreacion."','".$fechavencimiento."',
				'".$fecharesolucion."','".$fechacierre."','".$fechamodif."','".$horacreacion."','".$horavencimiento."','".$horaresolucion."','".$horacierre."','".$fueraservicio."','".$fechadesdefueraservicio."','".$fechafinfueraservicio."','".$horastrabajadas."','".$periodo."','".$notificar."','".$fusionado."',
				'".$idsfusion."','".$reporteservicio."','".$horario."','".$tags."','".$idemail."','".$fechareal."','".$horareal."','".$idempresas."','".$idclientes."','".$idproyectos."','".$iddepartamentos."','".$idambientes."','".$idsubambientes."','".$tipo."','".$atencion."','".$estadoant."','".$niveldeservicio."','".$contacto."',
				'".$costo."','".$frecuencia."','".$fechageneracion."','".$idplan."','".$idpreventivos."','".$idetiquetas."')";
	if($reg = $mysqli->query($insert)){
		$id_incidente_nuevo = $mysqli->insert_id;
		
		$carpetaOrigen = 'incidentescopia/'.$id;
		$carpetaDestino = 'incidentes/'.$id_incidente_nuevo;
		
		// Verificar si la carpeta de origen existe
		if (!is_dir($carpetaOrigen)) {
			echo 'La carpeta de origen de correctivos no existe.';
		} else {
			// Copiar la carpeta y su contenido
			copiarCarpeta($carpetaOrigen, $carpetaDestino);

			// Renombrar la carpeta
			nuevoNombre($carpetaDestino, $id_incidente_nuevo);
		}  
		
		//Costos
		$select_costos = "SELECT id,modulo,descripcion,monto,usuario,fecha FROM incidentescostoscopia WHERE idmodulo = ".$id."";
		$resultCostos = $mysqli->query($select_costos);
		while($regCost = $resultCostos->fetch_assoc()){
			
			$idcostosold = $regCost['id'];
			$modulo_costos = $regCost['modulo'];
			$descripcion_costos = $regCost['descripcion'];
			$monto_costos = $regCost['monto'];
			$usuario_costos = $regCost['usuario'];
			$fecha_costos = $regCost['fecha'];
			
			$insert_costos = "INSERT INTO incidentescostos (idmodulo,modulo,descripcion,monto,usuario,fecha) VALUES (
							'".$id_incidente_nuevo."','".$modulo_costos."','".$descripcion_costos."','".$monto_costos."','".$usuario_costos."','".$fecha_costos."')";
			if($mysqli->query($insert_costos)){
				
				$idcostosnuevo = $mysqli->insert_id;
				$carpetaOrigenCostos = 'incidentescopia/'.$id.'/costos/'.$idcostosold;
				$carpetaDestinoCostos = 'incidentes/'.$id_incidente_nuevo.'/costos/'.$idcostosnuevo;
				
				if (!is_dir($carpetaOrigenCostos)) {
					echo 'La carpeta de origen de costos no existe.';
				} else {
					// Copiar la carpeta y su contenido
					copiarCarpeta($carpetaOrigenCostos, $carpetaDestinoCostos);

					// Renombrar la carpeta
					nuevoNombre($carpetaDestinoCostos, $idcostosnuevo);	
				} 
			} 
		}
		
		//Cambios de estado
		$select_cambios_estados = "SELECT estadoanterior,estadonuevo,usuario,fechadesde,horadesde,fechahasta,horahasta,dias FROM incidentesestadoscopia WHERE idincidentes = ".$id."";
		$resultCambioEstados = $mysqli->query($select_cambios_estados);
		while($reg_cambioestados = $resultCambioEstados->fetch_assoc()){
			 
			 $estadoanterior_est = $reg_cambioestados['estadoanterior'];
			 $estadonuevo_est = $reg_cambioestados['estadonuevo'];
			 $usuario_est = $reg_cambioestados['usuario'];
			 $fechadesde_est = $reg_cambioestados['fechadesde'];
			 $horadesde_est = $reg_cambioestados['horadesde'];
			 $fechahasta_est = $reg_cambioestados['fechahasta'];
			 $horahasta_est = $reg_cambioestados['horahasta'];
			 $dias_est = $reg_cambioestados['dias'];
			 
			$insert_cambioestados = "INSERT INTO incidentesestados (idincidentes,estadoanterior,estadonuevo,usuario,fechadesde,horadesde,fechahasta,horahasta,dias) VALUES (
									'".$id_incidente_nuevo."','".$estadoanterior_est."','".$estadonuevo_est."','".$usuario_est."','".$fechadesde_est."','".$horadesde_est."','".$fechahasta_est."','".$horahasta_est."','".$dias_est."')";
			 $mysqli->query($insert_cambioestados); 
		}
		
		//Historial 
		$select_bitacora = "SELECT usuario,fecha,modulo,accion,sentencia FROM bitacoracopia WHERE modulo IN ('Correctivos','Incidentes') AND identificador = ".$id."";
		$resultBitacora = $mysqli->query($select_bitacora);
		while($regBit = $resultBitacora->fetch_assoc()){
			
			$usuario_bit = $regBit['usuario'];
			$fecha_bit = $regBit['fecha'];
			$modulo_bit = $regBit['modulo'];
			$accion_bit = $regBit['accion'];
			$sentencia_bit = $regBit['sentencia'];
			
			$insert_bitacora = "INSERT INTO bitacora (identificador,usuario,fecha,modulo,accion,sentencia) VALUES (
								'".$id_incidente_nuevo."','".$usuario_bit."','".$fecha_bit."','".$modulo_bit."','".$accion_bit."','".$sentencia_bit."')";
			$mysqli->query($insert_bitacora); 
		}
		
		//Comentarios
		$select_comentarios = "SELECT id,modulo,idmodulo,comentario,visibilidad,usuario,fecha,visto FROM comentarioscopia WHERE idmodulo = ".$id."";
		$resultComentarios = $mysqli->query($select_comentarios);
		while($regCom = $resultComentarios->fetch_assoc()){
			
			$idcomentariosold = $regCom['id'];
			$modulo = $regCom['modulo'];
			$comentario = trim($regCom['comentario']);
			$visibilidad = $regCom['visibilidad'];
			$usuario = $regCom['usuario'];
			$fecha = $regCom['fecha'];
			$visto = $regCom['visto'];
			
			
			$select_validar_comentarios = "SELECT * FROM comentarios WHERE idmodulo = ".$id_incidente_nuevo." AND fecha = '".$fecha."' AND comentario = '".$comentario."'";
			echo $select_validar_comentarios;
			$existe = $mysqli->query($select_validar_comentarios);
			if($existe->num_rows >0){
				echo "no registrará";
			}else{
				echo "sí registrará";
				$insert_comentarios = "INSERT INTO comentarios (modulo,idmodulo,comentario,visibilidad,usuario,fecha,visto) VALUES (
									'".$modulo."','".$id_incidente_nuevo."','".$comentario."','".$visibilidad."','".$usuario."','".$fecha."','".$visto."')";
				if($mysqli->query($insert_comentarios)){
					
					$idcomentariosnuevo = $mysqli->insert_id;
					$carpetaOrigenComentarios = 'incidentescopia/'.$id.'/comentarios/'.$idcomentariosold;
					$carpetaDestinoComentarios = 'incidentes/'.$id_incidente_nuevo.'/comentarios/'.$idcomentariosnuevo;
					
					if (!is_dir($carpetaOrigenComentarios)) {
						echo 'La carpeta de origen de comentarios no existe.';
					} else {
						// Copiar la carpeta y su contenido
						copiarCarpeta($carpetaOrigenComentarios, $carpetaDestinoComentarios);

						// Renombrar la carpeta
						nuevoNombre($carpetaDestinoComentarios, $idcomentariosnuevo);	
					}  
				} 
			} 
		}
	} 
	
	echo "ID : ".$id."<br>";
	$tipoDato = gettype($id);
	echo "TIPO DATO: ".$tipoDato."<br>";
	if($id == "45378"){
		break;
	}
}

exit;
		
?>		