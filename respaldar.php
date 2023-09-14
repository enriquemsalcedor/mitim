<?php






//Introduzca aquí la información de su base de datos y el nombre del archivo de copia de seguridad.
$mysqlDatabaseName ='senadis';//Nombre de la base de datos
$mysqlUserName ='root';//Nombre de usuario
$mysqlPassword ='M4X14W3B';//contraseña
$mysqlHostName ='127.0.0.1';
$mysqlExportPath ='respaldos/senadis'.date('Y-m-d').'.sql';//Su-nombre-de-archivo-deseado.sql

//Por favor, no haga ningún cambio en los siguientes puntos
//Exportación de la base de datos y salida del status
$command='C:/wamp64/bin/mysql/mysql8.0.20/bin/mysqldump -u ' .$mysqlUserName .' -p'.$mysqlPassword.' -h' .$mysqlHostName .'   '.$mysqlDatabaseName .' > ' .$mysqlExportPath;
exec($command,$output,$worked);
echo $command .'<br>';
echo json_encode($output) .'<br>';
echo $worked .'<br>';
switch($worked){
	case 0:
		echo 'La base de datos <b>' .$mysqlDatabaseName .'</b> se ha almacenado correctamente en la siguiente ruta '.getcwd().'/' .$mysqlExportPath .'</b>';
		break;
	case 1:
		echo 'Se ha producido un error al exportar <b>' .$mysqlDatabaseName .'</b> a '.getcwd().'/' .$mysqlExportPath .'</b>';
		break;
	case 2:
		echo 'Se ha producido un error de exportación, compruebe la siguiente información: <br/><br/><table><tr><td>Nombre de la base de datos:</td><td><b>' .$mysqlDatabaseName .'</b></td></tr><tr><td>Nombre de usuario MySQL:</td><td><b>' .$mysqlUserName .'</b></td></tr><tr><td>Contraseña MySQL:</td><td><b>NOTSHOWN</b></td></tr><tr><td>Nombre de host MySQL:</td><td><b>' .$mysqlHostName .'</b></td></tr></table>';
		break;
} 
?>