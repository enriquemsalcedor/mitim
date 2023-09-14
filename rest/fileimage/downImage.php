 <?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('content-type: application/json; charset=utf-8');
  //header('Content-Type: application/JSON');
  header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");
  $method = $_SERVER['REQUEST_METHOD'];        
	if ($method =='GET') 
	{
    $action = $_REQUEST['action'];   
    switch($action)
    {
      case "dowImage":
            dowImage();
            break;
      case "deletImage":
            deletImage();
            break;
      case "download":
            download();
            break;
      case "downactivo":
            dowImageActivo();
            break;
      default:
          echo "{failure:true}";
          break;
    }
  }elseif ($method =='POST') {
        $action = '';
        
    	if (isset($_REQUEST['action'])) {
    		$action = $_REQUEST['action'];
    	}
    	
    	$action = $_REQUEST['action'];   
        switch($action)
        { 
    		case "upImgBs64":
    			  upImgBs64();
    			  break;
    	   default:
                  echo "{failure:true}";
                  break;
        } 
	}
     
function dowImage() 
{
///soporte/incidentes/20932/reporte 19072.jpg
  if(isset($_REQUEST['idDir']))
	{
      //ID_DEL_DIRECTORIO
      $Directorio = $_REQUEST['idDir'];
      //DIRECTORIO DONDE DEBO BUSCAR EL ARCHIVO
      $folder_path = '../../incidentes/'.$Directorio; 
      
      $num_files = glob($folder_path . "*.{JPG,jpg,gif,png,bmp,pdf,PDF,doc,DOC,docx,jpeg,JPEG}", GLOB_BRACE);
      
    if (file_exists($folder_path)) {
     
      $folder = opendir($folder_path);  
      if($num_files > 0)
      {
        $json=[];
        //$handle = opendir(dirname(realpath(__FILE__)).'/images/'); 
            while(false !== ($file = readdir($folder))) 
            {
              $file_path = $folder_path.'/'.$file;
              $extension = strtolower(pathinfo($file ,PATHINFO_EXTENSION));
              if($extension=='jpg' || $extension =='png' || $extension == 'gif' || $extension == 'bmp' || $extension == 'pdf' || $extension == 'PDF' || $extension == 'doc' || $extension == 'docx' || $extension == 'jpeg' || $extension == 'JPEG') 
                  {
                    $json[]=array(
                    'archivo'=>$file,    
                    'carpeta'=>$Directorio, 
                    'tipo'=>$extension,
                    'url'=> $Directorio.'/'.$file,
                    'urlDelet'=> $file_path);	
                  }
            }
            echo json_encode($json);
      }else{
        // $json[]=array(msg'=>'Error de tipo Imagen');
          echo json_encode([]);
      }
    }else{
         //$json[]=array('msg'=>'No Existe Directorio');
          echo json_encode([]);
    }
  }else{
    // $json[]=array( 'msg'=>'Error enpoint');
    echo json_encode([]);
  }
}	


function dowImageActivo() 
{
///soporte/incidentes/20932/reporte 19072.jpg
  $idfolder=$_REQUEST['idactivo'];

  if(isset($idfolder))
	{
      //ID_DEL_DIRECTORIO
      $Directorio = $_REQUEST['idactivo'];
      //DIRECTORIO DONDE DEBO BUSCAR EL ARCHIVO
      $folder_path = '../../activos/'.$Directorio; 
      
      $num_files = glob($folder_path . "*.{JPG,jpg,gif,png,bmp,jpeg,JPEG}", GLOB_BRACE);
     
    if (file_exists($folder_path)) {
     
      $folder = opendir($folder_path);  
      
      if($num_files > 0)
      {
        $json=[];
            
            while(false !== ($file = readdir($folder))) 
            {
              $file_path = $folder_path.'/'.$file;
              $extension = strtolower(pathinfo($file ,PATHINFO_EXTENSION));
              if($extension=='jpg' || $extension =='png' || $extension == 'gif' || $extension == 'bmp' ||  $extension == 'jpeg' || $extension == 'JPEG') 
                  {
                    $json[]=array(
                    'archivo'=>$file,    
                    'carpeta'=>$Directorio, 
                    'tipo'=>$extension,
                    'url'=> $Directorio.'/'.$file);	
                  }
            }
            echo json_encode($json);
      }else{
          //$json[]=array('msg'=>'Error de tipo Imagen');
          echo json_encode([]);
      }
    
    }else{
         //$json[]=array('msg'=>'No Existe Directorio');
          echo json_encode([]);
    }
    
  }else{
    //$json[]=array( 'msg'=>'Error enpoint');
    echo json_encode([]);
  }

}


function deletImage() 
{
  if(isset($_REQUEST['imgDelet']))
	{
     $Directorio=$_REQUEST['imgDelet'];
     
      unlink('../../incidentes/'.$Directorio);
      
      $json[]=array('msg'=>'Imagen Eliminada de forma Exitossa');
       echo json_encode($json);
  }else{
      $json[]=array('msg'=>'La imagen no pudo ser eliminada');
       echo json_encode($json);
  }
}


/*-DECARGA-ARCHIVOS---------------------------------------------------------------------------*/
function download(){
    //incidente
    $incidente=$_GET['idincidente'];
    //archivo
    $archivo=$_GET['file'];
    
    if(ValidarDir($incidente))
    {
     /*-----------------------------------*/
     if(!empty($archivo)){
        //devuelvo el último componente de nombre de una ruta
        $fileName = basename($archivo);
        //DIRECTORIO DONDE DEBO BUSCAR EL ARCHIVO
        $folder_path = "../../incidentes/".$incidente."/".$fileName;
        /*--------------------------------------------------------*/
        $size = filesize($file);
        $name = rawurldecode($name);
        
        $known_mime_types=array(
           "docx" => "application/msword",
           "zip" => "application/zip",
           "doc" => "application/msword",
           "jpg" => "image/jpg",
           "php" => "text/plain",
           "xls" => "application/vnd.ms-excel",
           "ppt" => "application/vnd.ms-powerpoint",
           "gif" => "image/gif",
           "pdf" => "application/pdf",
           "txt" => "text/plain",
           "png" => "image/png",
           "jpeg"=> "image/jpg"
       );
       
       $mime_type="jpg";
       
       if($mime_type==''){
           $file_extension = strtolower(substr(strrchr($archivo,"."),1));
           if(array_key_exists($file_extension, $known_mime_types)){
               $mime_type=$known_mime_types[$file_extension];
           } else {
               $mime_type="application/force-download";
           };
       };

        /*--------------------------------------------------------*/
        if(!empty($fileName) && file_exists($folder_path)){
            // Define headers
            header('Content-Description: File Transfer');
            header('Content-Type: '. $mime_type);
            header('Content-Disposition: attachment; filename='.basename($fileName));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileName));
            ob_clean();
            flush();
            readfile($folder_path);
            exit;                
        }else{
            echo 'El archivo no existe';
        }
    }
     /*-----------------------------------*/
    }else{
      $json[]=array('msg'=>'Directorio no existe');
    }
}

/*-VALIDAR-DIRECTORIO----------------------------------------------------------*/
function ValidarDir($incidente)
{  
   //ruta de la carpeta
    $folder_path = "../../incidentes/".$incidente."/";
    if(file_exists($folder_path)){
        return true;
    }else{
        return false;
    }
}

function upImgbs64()
{   
    $nameImg= (!empty($_REQUEST['nameImg']) ? $_REQUEST['nameImg'] : '');
    $idIncidente= (!empty($_REQUEST['idincidente']) ? $_REQUEST['idincidente'] : '');
    $imagenCodificada= (!empty($_REQUEST['img']) ? $_REQUEST['img'] : '');
    
    if(ValidarDir($idIncidente)){
        //$imagenCodificadaLimpia = str_replace("data:image/jpeg;base64,", "", urldecode($imagenCodificada));
        //$data = base64_decode($imagenCodificada);
        /*-EXTENSION------------------------image/png;base-*/
        $ext = substr(urldecode($imagenCodificada),5,9) ;
    
    	if($ext ==='image/png')
    	{
            //header('content-type: image/png');
            $imagenCodificadaLimpia = str_replace("data:image/png;base64,", "", urldecode($imagenCodificada));
            $data = str_replace(' ', '+', $imagenCodificadaLimpia);
            $data = base64_decode($data);
            
            /*-RUTA-DIRECTORIO------------------------------------------------------*/
            //date('H:m:s-j-n-Y');
            $file = '../../incidentes/'.$idIncidente.'/'.rand().'.png';
            /*Esta función devuelve el número de bytes que fueron escritos en el fichero, 
            o FALSE en caso de error. Advertencia. Esta función puede devolver el valor booleano*/ 
            $success = file_put_contents($file, $data);
            if($success !=false)
            {
                $json[]=array('respomse'=>1,
                              'msg'=>'Evidencia agregada de forma Exitosa!!!');
                echo json_encode($json);
            }else{
                $json[]=array('respomse'=>0,
                              'msg'=>'La evidencia no se pudo agregar');
                echo json_encode($json);
            }
    	    
    	}else{
            //header('Content-Type: image/jpeg');
            $imagenCodificadaLimpia = str_replace("data:image/jpeg;base64,", "", urldecode($imagenCodificada));
            $data = str_replace(' ', '+', $imagenCodificadaLimpia);
            $data = base64_decode($data);
            /*-RUTA-DIRECTORIO------------------------------------------------------*/
            //date('H:m:s-j-n-Y');
            $file = '../../incidentes/'.$idIncidente.'/'.rand().'.jpeg';
            /*Esta función devuelve el número de bytes que fueron escritos en el fichero, 
            o FALSE en caso de error. Advertencia. Esta función puede devolver el valor booleano*/ 
            $success = file_put_contents($file, $data);
            if($success !=false)
            {
                $json[]=array('respomse'=>1,
                              'msg'=>'Evidencia agregada de forma Exitosa!!!');
                echo json_encode($json);
    
            }else{
                $json[]=array('respomse'=>0,
                              'msg'=>'La evidencia no se pudo agregar');
                echo json_encode($json);
            }
    	}
    }else{
        $json[]=array('respomse'=>0,
                      'msg'=>'El directorio no existe');
                echo json_encode($json);
    }
} 


?>