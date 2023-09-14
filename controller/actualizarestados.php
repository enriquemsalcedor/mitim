<?php








//SCRIPT PARA ACTUALIZAR ESTADOS
include("conexion.php");


//Busca estados de los proyectos en Incidentes

$hoy = date("Y-m-d H:i:s");

$sql = " SELECT id,tipo,descripcion,idproyectos FROM estados WHERE id <> '' AND id <> 0 ORDER BY id ASC ";
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
$r = $mysqli->query($sql); 
while($row = $r->fetch_assoc()){
	
	$id 	 		 = $row['id']; 
	$tipo 	 		 = $row['tipo']; 
	$descripcion 	 = $row['descripcion']; 
	$idproyectos 	 = $row['idproyectos']; 
	
	echo "TIPOS ES:" . $tipo;
	echo "<br>";
	echo "IDPROYECTOS ES:" . $idproyectos;
	echo "<br>";
	echo "IDESTADOS ES:" . $id;
	echo "<br>"; 
	 
	 
	$arrp = strpos($idproyectos, ',');
	
	//Arreglo de proyectos
	
	if ($arrp !== false) {
		
		echo "ARREGLO DE PROYECTOS";
		echo "<br>";
		
		$arrpro = explode(',',$idproyectos);
		
		foreach($arrpro as $pro){
				echo "<br>";
				
					if($pro != ""){
							$cl = " SELECT idclientes FROM proyectos WHERE id = ".$pro."";
							
							$rc = $mysqli->query($cl);
							if($rowc = $rc->fetch_assoc()){
								$idclientes = $rowc['idclientes'];
							}
							
							echo "EL CLIENTE ES:" . $idclientes;
							echo "<br>";
							echo "EL PROYECTO ES:" . $pro;
							echo "<br>";
							echo "EL ESTADO ES:" . $id;
							echo "<br>";
							
							$verduplicado = " SELECT COUNT(id) AS total FROM estadospuente WHERE idclientes = ".$idclientes." AND idproyectos = ".$pro." AND idestados = ".$id." AND tipo LIKE '%".$tipo."%' ";
							echo $verduplicado;
							$rv = $mysqli->query($verduplicado);
							if($rowv = $rv->fetch_assoc()){
								$total = $rowv['total'];
							}
							if($total == 0){
								$sqlD = " INSERT INTO estadospuente (idclientes,idproyectos,idestados,tipo,descripcion,fechacreacion,idusuarios)
										  VALUES (".$idclientes.",".$pro.",".$id.",'".$tipo."','".$descripcion."','".$hoy."',null)";
								$rD = $mysqli->query($sqlD);
								if($rD == true){
									echo "SUCCESS - INSERTÓ EN LA TABLA ESTADOS: " . $sqlD;
									echo "<br>";
								}else{
									echo "ERROR - NO INSERTÓ EN LA TABLA ESTADOS: " . $sqlD;
									echo "<br>";
								}
							}else{
								echo "YA EXISTE";
								echo "<br>";
							}
					} 
			}
	}else{
		echo "PROYECTO INDIVIDUAL";
		echo "<br>";
		//Proyecto individual
				$cl = " SELECT idclientes FROM proyectos WHERE id = ".$idproyectos."";
				$rc = $mysqli->query($cl);
				if($rowc = $rc->fetch_assoc()){
					$idclientes = $rowc['idclientes'];
				}
				
				echo "EL PROYECTO ES:" . $idproyectos;
				echo "<br>";
				echo "EL ESTADO ES:" . $id;
				echo "<br>"; 
			
			echo "ESTADO INDIVIDUAL";
			echo "<br>";
			
			if($id != ""){
				
				$cl = " SELECT idclientes FROM proyectos WHERE id = ".$idproyectos."";
					$rc = $mysqli->query($cl);
					if($rowc = $rc->fetch_assoc()){
						$idclientes = $rowc['idclientes'];
					}
					
				$verduplicado = " SELECT COUNT(id) AS total FROM estadospuente WHERE idclientes = ".$idclientes." AND idproyectos = ".$idproyectos." AND idestados = ".$id." AND tipo LIKE '%".$tipo."%'";
				 
				$rv = $mysqli->query($verduplicado);
				if($rowv = $rv->fetch_assoc()){
					$total = $rowv['total'];
				}
				
				if($total == 0){
					//Estado individual
					$sqlD = " INSERT INTO estadospuente (idclientes,idproyectos,idestados,tipo,descripcion,fechacreacion,idusuarios)
								  VALUES (".$idclientes.",".$idproyectos.",".$id.",'".$tipo."','".$descripcion."','".$hoy."',null)";
					$rD = $mysqli->query($sqlD);
					 
					if($rD == true){
						echo "SUCCESS - INSERTÓ EN LA TABLA ESTADOS: " . $sqlD;
						echo "<br>";
					}else{
						echo "ERROR - NO INSERTÓ EN LA TABLA ESTADOS: " . $sqlD;
						echo "<br>";
					}
				}else{
					echo "YA EXISTE";
					echo "<br>";
				}
			} 
	}  
}