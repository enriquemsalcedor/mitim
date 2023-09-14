<?php
	include("conexion.php");
	$method = $_SERVER['REQUEST_METHOD'];        
    $action =isset($_REQUEST['op'])?$_REQUEST['op']:"";
    
    if ($method =='GET'){
        $action = $_REQUEST['op'];   
        switch($action){ 
            case 'preventivos':
                preventivos();
                break;
            case 'preventivo':
                preventivo();
                break;
            default:
                echo "{failure-GET:true}";
                break;
        }
    }elseif ($method =='POST') {
        switch($action){ 
    	    case 'test':
                test();
                break;
    	    default:
                echo "{failure-POST:true}";
                break;
        } 
	} 
    
	function formulario(){
        
        $data['id']         =   (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
        /*-DATOS-DEL-PACIENTE-------------------------------------------------*/
        $data['numero']	= (!empty($_REQUEST['numero']) ? $_REQUEST['numero'] : '');
		$data['desde']  = (!empty($_REQUEST['desde']) ? $_REQUEST['desde'] : '');
		$data['hasta'] 	= (!empty($_REQUEST['hasta']) ? $_REQUEST['hasta'] : '');
		$data['idambientes'] = (!empty($_REQUEST['idambientes']) ? $_REQUEST['idambientes'] : '');
		$data['idproyectos'] = (!empty($_REQUEST['idproyectos']) ? $_REQUEST['idproyectos'] : '');
		$data['idcategorias'] = (!empty($_REQUEST['idcategorias']) ? $_REQUEST['idcategorias'] : '');
		$data['idestados'] = (!empty($_REQUEST['idestados']) ? $_REQUEST['idestados'] : '');
		$data['asignadoa'] = (!empty($_REQUEST['asignadoa']) ? $_REQUEST['asignadoa'] : '');
		$data['modalidad'] = (!empty($_REQUEST['modalidad']) ? $_REQUEST['modalidad'] : '');
		$data['idactivos'] = (!empty($_REQUEST['idactivos']) ? $_REQUEST['idactivos'] : '');
	    $data['solicitante'] = (!empty($_REQUEST['solicitante']) ? $_REQUEST['solicitante'] : '');
		$data['idclientes'] = (!empty($_REQUEST['idclientes']) ? $_REQUEST['idclientes'] : '');
		$data['page']		= (!empty($_REQUEST['page']) ? $_REQUEST['page'] : 1);
	    
	    return $data;
    }
	
  
    function preventivos(){
		global $mysqli;
		$data=formulario();
        
		$where = '';
		
		if($numero != ''){
			$where .= " AND a.id = '".$data['numero']."' ";
		}
		
		if($desde != ''){
			$where .= " AND a.fechacreacion >= '".$data['desde']."' ";
		}
		
		if($hasta != ''){
			$where .= " AND a.fechacreacion <= '".$data['hasta']."' ";
		}
		
		if($idambientes != ''){
			$where .= " AND a.idambientes = '".$data['idambientes']."' ";
		}
		
		if($idproyectos != ''){
			$where .= " AND a.idproyectos = '".$data['idproyectos']."'";
		}
		
		if($idcategorias!=''){
			$where .= " AND a.idcategorias = '".$data['idcategorias']."'";
		}
		
		if($idestados != ''){

		    if($idestados == 'not'){
			    $where .= " AND a.idestados IN (12,13,14,15,18,26,28,31,33,42,43,44,45,46,47,48,49,50,51)";
			}else{
			    $where .= " AND a.idestados IN ('".$data['idestados']."') ";
			}
			
		}
		
		if($asignadoa != ''){
			$where .= " AND a.asignadoa = '".$data['asignadoa']."' ";
		}
		
		if($modalidad != ''){

			$where .= " AND ti.id IN ('".$data['modalidad']."')";
		}
		
		if($idactivos != ''){
			$where .= " AND a.idactivos = '".$data['idactivos']."' ";
		}
		
		if($solicitante != ''){
			$where .= " AND a.solicitante = '".$data['solicitante']."' ";
		}
		
		if($idclientes != ''){
			$where .= " AND a.idclientes = '".$data['idclientes'].""; 
		}           
	
		
		$query  = " SELECT a.id, e.nombre AS estado, LEFT(a.titulo,45) as titulo, a.titulo as titulott,
					IFNULL(j.nombre, a.solicitante) AS solicitante, a.fechacreacion, a.horacreacion, a.fechacierre,
					f.nombre AS categoria, g.nombre AS subcategoria, a.asignadoa, l.nombre AS nomusuario, 
					c.nombre AS ambiente, m.serie, mar.nombre as marca, r.nombre as modelo, m.modalidad, h.prioridad, a.fecharesolucion, 
					case when a.fechacierre IS NULL OR LENGTH(ltrim(rTrim(a.fechacierre))) > 0
					then a.fechacreacion else a.fechacierre end as fechaorden,
					n.descripcion as idempresas, o.nombre as iddepartamentos, p.nombre as idclientes, a.estadoant
					FROM incidentes a
					LEFT JOIN proyectos b ON a.idproyectos = b.id
					LEFT JOIN ambientes c ON a.idambientes = c.id
					LEFT JOIN estados e ON a.idestados = e.id
					LEFT JOIN categorias f ON a.idcategorias = f.id
					LEFT JOIN subcategorias g ON a.idsubcategorias = g.id
					LEFT JOIN sla h ON a.idprioridades = h.id
					LEFT JOIN usuarios j ON a.solicitante = j.correo
					LEFT JOIN usuarios l ON a.asignadoa = l.correo
					LEFT JOIN activos m ON a.idactivos = m.id AND a.idambientes = m.idambientes
					LEFT JOIN empresas n ON a.idempresas = n.id
					LEFT JOIN departamentos o ON a.iddepartamentos = o.id
					LEFT JOIN clientes p ON a.idclientes = p.id
					LEFT JOIN marcas mar ON m.idmarcas = mar.id
					LEFT JOIN modelos r ON m.idmodelos = r.id
					LEFT JOIN activostipos ti ON ti.id = m.idtipo";
		
		$query  .= " WHERE a.tipo = 'preventivos' ";

		//$query  .= permisos('correctivos', '', $data['id']);
		$query  .= " $where ";
		$query  .= " AND (a.fechacreacion > DATE_SUB(CURDATE(),INTERVAL 1 YEAR) OR a.idestados <> 17 OR a.idestados <> 16) ";
		$query  .= " GROUP BY a.id";
		
		if(!$result = $mysqli->query($query)){
		    die($mysqli->error);  
    	}
    	/*-TOTAL-REGISTROS----------------------------------------------------*/	
    	$recordsTotal = $result->num_rows;
    	$inicio = $data['page'] * 10 - 10;
    	$query .= " ORDER BY a.id DESC LIMIT $inicio, 10 ";
    	debugL($query,"consultas");
    	/*-REGISTRO-FILTRADO--------------------------------------------------*/
    	$result = $mysqli->query($query);
    	$records = $result->num_rows;
		//varble infiniti scroll numero de registros
		$response = array();
		if($result->num_rows > 0 ){
		 	
		 	while($row = $result->fetch_assoc()){
				$siglasestado = strtoupper(substr(str_replace(' ','',$row['estado']), 0, 2));
				
				$response['data'][]=array(
					'id'        => $row['id'],
					'titulo'    => $row['titulo'],
					'fechacreacion' => $row['fechacreacion'],
					'Siglas'    => $siglasestado,
					'tipo'      =>'correctivo');	
		 	}
    		
    		$response['estatus'] = "ok";
    		$response['totalResults'] = intval($recordsTotal);
    		$response['recordsFiltered'] = intval($records);
    		echo json_encode($response);  
    		
		}else{
		    
		    $response['estatus'] = "ok";
		    $response['data'] = array();
            echo json_encode($response);
		}
	}
?>