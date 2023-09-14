<?php
	include_once('conexion.php');
	
	$query = 'SELECT idincidentes, encuesta, pregunta, evaluacion, incidente, descripcion, categoria, usuario, asignadoa, creadopor, enviada, realizada
				FROM resultadosencuesta2';
			//	echo $query;
	echo '
	<table>
		<thead>
			<tr>
			    <td>idincidentes</td>
				<td>Encuesta</td>
				<td>Pregunta</td>
				<td>Evaluación</td>
				<td>Incidente</td>
				<td>Descripción</td>
				<td>Categoría</td>
				<td>Usuario</td>
				<td>Asignado A</td>
				<td>Creado Por</td>
				<td>Enviada el</td>
				<td>Realizada el</td>
			</tr>
		</thead>
		<tbody>
	';
	$result = $mysqli->query($query);
	while($row = $result->fetch_assoc()){
		echo '
			<tr>
			    <td>'.$row['idincidentes'].'</td>
				<td>'.$row['encuesta'].'</td>
				<td>'.$row['pregunta'].'</td>
				<td>'.$row['evaluacion'].'</td>
				<td>'.$row['incidente'].'</td>
				<td>'.$row['descripcion'].'</td>
				<td>'.$row['categoria'].'</td>
				<td>'.$row['usuario'].'</td>
				<td>'.$row['asignadoa'].'</td>
				<td>'.$row['creador'].'</td>
				<td>'.$row['enviada'].'</td>
				<td>'.$row['realizada'].'</td>
			</tr>';
	}
	echo '
		</tbody>
	</table>
	';
	
?>