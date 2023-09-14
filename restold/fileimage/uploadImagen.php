<?php
  header('Access-Control-Allow-Origin: *');
  header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('content-type: application/json; charset=utf-8');
  header('Content-Type: application/JSON');
  header('Content-Type: text/plain; charset=utf-8');

   $idIncidente=$_POST['id'];
   $directorio = "../../incidentes/".$idIncidente."/";
   $upload_directRouterFile=$directorio.basename($_FILES['file']['name']);
   $img =$_FILES['file']['name'] ;
   //Si el archivo contiene algo y es diferente de vacio
   if (isset($img) && $img != "") 
   {
        if(!file_exists($directorio))
        {
            $myPath = "../../incidentes/".$idIncidente."/";
    		$target_path2 = utf8_decode($myPath);
    		if (!file_exists($target_path2))
    		mkdir($target_path2, 0777);
        }
        //Obtenemos algunos datos necesarios sobre el archivo
        if(file_exists($directorio)){
             $tipo = $_FILES['file']['type'];
             $tamano = $_FILES['file']['size'];
             $temp = $_FILES['file']['tmp_name'];
             //Se comprueba si el archivo a cargar es correcto observando su extensión y tamaño
             /* if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")) && ($tamano < 2000000))) {
                      $response['msg'] = "Laimagen no cumple con los requerimientos";
                      echo json_encode($response);
             }else {*/
            //Si la imagen es correcta en tamaño y tipo Se intenta subir al servidor
            if (move_uploaded_file($temp,$upload_directRouterFile)) {
                   $json[]=array('response'=>1,
                              'msg'=>'Evidencia agregada de forma Exitosa!!!');
                    echo json_encode($json);
            }else {
                    $json[]=array('response'=>0,
                              'msg'=>'Error La evidencia no fue agregada');
                    echo json_encode($json);
            }
             //}
        }else{
            $json[]=array('response'=>2,
                            'msg'=>'no existe el directorio!!!');
            echo json_encode($json);
        }
   }else{
        $json[]=array('response'=>3,
                        'msg'=>'Por favor agregue una imagen!!!');
        echo json_encode($json);
   }
?>