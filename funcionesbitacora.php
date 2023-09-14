<?php

function nuevoRegistro($modulo,$tiporegistro,$id,$campos,$query){
    $acciones = "Fue creado un registro en $tiporegistro con el id #$id. <br/>";
	$acciones .= "<ul>";
	foreach($campos as $campo => $valor){
		if($valor != ''){
			$acciones .= "<li><b>".ucfirst($campo)."</b>: $valor.</li>";
		}
	}
	$acciones .= "</ul>";
	
	bitacora($_SESSION['usuario'], $modulo, $acciones, $id, $query);
}

function actualizarRegistro($modulo,$tiporegistro,$id,$valoresold,$valoresnew,$query){
	global $mysqli;  
    $acciones = "Fue actualizado un registro en $tiporegistro con el id #$id. <br/>";
	$acciones .= "<ul>";
	foreach($valoresold as $campo => $valor){
		
		if($valor != $valoresnew[$campo] || !array_key_exists ($campo, $valoresnew )){
			if( !array_key_exists ($campo, $valoresnew ) ){
				if($valor != ''){
					//debug("PASÓ foreac valor !=");
					$acciones .= "<li><b>".ucfirst($campo)."</b>: $valor.</li>";
				}
			} else {
			//	debug("PASÓ acciones".$acciones);
				$acciones .= "<li>El campo <b>$campo</b> fue modificado. Valor anterior: ".$valor." / Valor nuevo: ".$valoresnew[$campo].".</li>";
			}
		}
	}
	$acciones .= "</ul>";
	
	bitacora($_SESSION['usuario'], $modulo, $acciones, $id, $query);
}

function eliminarRegistro($modulo,$tiporegistro,$nombre,$id,$query){
	$acciones = "Fue eliminado un registro en $tiporegistro de nombre \"$nombre\", con el id #$id";
	bitacora($_SESSION['usuario'], $modulo, $acciones, $id, $query);
}

function guardarRegistroG($modulo, $accion){	
	bitacora($_SESSION['usuario'], $modulo, $accion, 0, '');
}

function getId($campo,$tabla,$valor,$regla){
	global $mysqli;
	
	if($valor != ''){
		$q = "SELECT $campo FROM $tabla WHERE $regla = '$valor' LIMIT 1"; 
		$r = $mysqli->query($q);
		$val = $r->fetch_assoc(); 
		$valor = isset($val[$campo]) ? $val[$campo] : 0;
		//echo 'cons:'.$q;
	}else{ 
		$valor = ''; 
	}	
	return $valor;
}

function getCount($tabla){
	global $mysqli;
	
	if($tabla != ''){
		$q = "SELECT COUNT(id) AS total FROM $tabla"; 
		$r = $mysqli->query($q);
		$val = $r->fetch_assoc();
		$valor = $val['total'];
	}else{ 
		$valor = '';
	}
	return $valor;
}
function getValor($campo,$tabla,$id){
	global $mysqli;
	debugL("IDBIT ES:".$id);
	if($id != ''){
		$q = " SELECT $campo FROM $tabla WHERE id = $id LIMIT 1 ";
		debugL("GETVALOR ES:".$q);
		$r = $mysqli->query($q);
		$val = $r->fetch_assoc();
		$valor = $val[$campo];
	}else{
		debugL("PASÓ 2 BIT");
		$valor = '';
	}	
	return $valor;
}

function getValorEx($campo,$tabla,$id,$regla){
	global $mysqli;
	if( $tabla != 'tipoplan' ){
		$q = "	SELECT $campo FROM $tabla WHERE $regla = '$id' LIMIT 1";	
		//debugL($q);
		if($r = $mysqli->query($q)){
			$val = $r->fetch_assoc();
		} else {
			die($mysqli->error);
		}
		
		$valor = isset($val[$campo]) ? $val[$campo] : 0;
	}else{
		switch($id ){
			case 'A':
				$valor = 'Automático';
			break;
			case 'M':
				$valor = 'Manual';
			break;
			case 'D':
				$valor = 'Desactivar';
			break;
			default:
				$valor = '';
			break;
		}
	}
	
	return $valor;
}

function getValorJoin($campo,$tabla,$tablajoin,$on,$id){
	global $mysqli;
	
	$q = "SELECT b.$campo FROM $tabla a LEFT JOIN $tablajoin b ON b.id = a.$on WHERE a.id = $id LIMIT 1";
	
	if($r = $mysqli->query($q)){
		$val = $r->fetch_assoc();
	} else {
		die($mysqli->error);
	}
	
	$valor = $val[$campo];
	
	return $valor;
}

function getValores($campo,$tabla,$ids){
	global $mysqli;
	
	$q = "SELECT GROUP_CONCAT($campo) as $campo FROM $tabla WHERE FIND_IN_SET(id,'$ids') ";
	
	if($r = $mysqli->query($q)){
		$val = $r->fetch_assoc();
	} else {
		die($mysqli->error);
	}
	
	$valor = $val[$campo];
	
	return $valor;
}



function getRegistro($tabla,$id){
	global $mysqli;
	
	$q = "SELECT * FROM $tabla WHERE id = $id LIMIT 1";
	$r = $mysqli->query($q);
	$val = $r->fetch_assoc();
	$valor[] = $val;
	
	return $valor;
}

function getRegistroSQL($query){ 
	global $mysqli;
	
	if($r = $mysqli->query($query)){
		$val = $r->fetch_assoc();
	} else {
		die($mysqli->error);
	}
	
	return $val;
}

function getRegistroJoin($tabla,$id,$joins){
	global $mysqli;
	
	$select = '';
	$rjoins = '';
	$i = 2;
	
	foreach($joins as $join){
		$letra = toAlpha($i);
		$select .= $join['campos'];
		$rjoins .= ' LEFT JOIN '.$join['tabla'].' '.$letra.' ON '.$letra.'.id = a.'.$join['on'].' ';
		
		
		$i++;
	}
	
	$q = "	SELECT a.*,$select FROM $tabla a 
			$rjoins
			WHERE a.id = $id 
			LIMIT 1";
	$r = $mysqli->query($q);
	$val = $r->fetch_assoc();
	$valor[] = $val;
	
	return $valor;
}

function toAlpha($data){
    $alphabet =   array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    $alpha_flip = array_flip($alphabet);
	
	if($data <= 25){
	  return $alphabet[$data];
	}
	elseif($data > 25){
	  $dividend = ($data + 1);
	  $alpha = '';
	  $modulo;
	  while ($dividend > 0){
		$modulo = ($dividend - 1) % 26;
		$alpha = $alphabet[$modulo] . $alpha;
		$dividend = floor((($dividend - $modulo) / 26));
	  } 
	  return $alpha;
	}
}

?>