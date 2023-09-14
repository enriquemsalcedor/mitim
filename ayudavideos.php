<?php 
    include_once "funciones.php"; 
    verificarLogin();
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <title><?php echo $sistemaactual ?> | Videos</title>
    <?php linksheader(); ?>
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.css">
    <!-- elFinder -->
    <link rel="stylesheet" href="elFinder/css/elfinder.min.css" />
    <link rel="stylesheet" href="elFinder/themes/Material/theme-gray.css">
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/datatables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/material/css/dataTables.material.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/material.min.css"> -->
    <link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/select/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/colreorder/css/colReorder.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/fixedheader/css/fixedHeader.dataTables.min.css">
    <link rel="stylesheet" href="ayudavideos/css/video-js.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
	<link rel="stylesheet" href="ayudavideos/css/estilos.css">
</head>
<body>
    <div id="page-transitions">
        <?php menusup(); ?>        
        <?php menu(); ?> 
        <div id="page-content" class="page-content">    
                <div class="tabs">
                    <div class="tab-titles">
                        <a href="ayudavideos.php" class="uppercase" data-tab="tab-1"><i class="fa fa-refresh"></i> Actualizar </a>
                        <!--a data-toggle='modal' data-target='#modalvideo' class="uppercase"><i class="fa fa-plus-circle"></i> Vídeos</a-->
                    </div>
                </div>
                
                <div style="text-align:center;">
            		<div style="display:none;margin:0 auto;" class="html5gallery" data-skin="verticallight" data-responsive="true" data-width="800" data-resizemode="fill">
            		    <a href="ayudavideos/Entrada.webm" data-webm="ayudavideos/Entrada.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Introduccion"></a>
            			<a href="ayudavideos/Actas.webm" data-webm="ayudavideos/Actas.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Actas"></a>
            			<a href="ayudavideos/Activos.webm" data-webm="ayudavideos/Activos.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Activos"></a>
            			<a href="ayudavideos/BaseConocimiento.webm" data-webm="ayudavideos/BaseConocimiento.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Base Conoc."></a>
            			<a href="ayudavideos/Bitacora.webm" data-webm="ayudavideos/Bitacora.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Bitacora"></a>
            			<a href="ayudavideos/Calendario.webm" data-webm="ayudavideos/Calendario.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Calendario"></a>
            			<a href="ayudavideos/Clientes.webm" data-webm="ayudavideos/Clientes.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Clientes"></a>
            			<a href="ayudavideos/Dashboard.webm" data-webm="ayudavideos/Dashboard.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Dashboard"></a>
            			<a href="ayudavideos/Departamentos.webm" data-webm="ayudavideos/Departamentos.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Dpto"></a>
            			<a href="ayudavideos/Estados.webm" data-webm="ayudavideos/Estados.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Estado"></a>
            			<!--a href="ayudavideos/Estados.webm" data-webm="ayudavideos/Estados.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Estado 1"></a-->
            			<a href="ayudavideos/GestorDocumental.webm" data-webm="ayudavideos/GestorDocumental.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Gestor"></a>
            			<a href="ayudavideos/Incidentes.webm" data-webm="ayudavideos/Incidentes.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Incidentes"></a>
            			<a href="ayudavideos/Niveles.webm" data-webm="ayudavideos/Niveles.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Niveles"></a>
            			<a href="ayudavideos/Preventivos.webm" data-webm="ayudavideos//Preventivos.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Preventivos"></a>
            			<a href="ayudavideos/Prioridades.webm" data-webm="ayudavideos/Prioridades.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Prioridades"></a>
            			<a href="ayudavideos/Sitios.webm" data-webm="ayudavideos/Sitios.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Sitios"></a>
            			<a href="ayudavideos/Usuario.webm" data-webm="ayudavideos/Usuario.webm"><img src="ayudavideos/imagenes/preview.PNG" alt="Usuarios"></a>
            		</div>
        
        	    </div>
            
        </div>
        <?php include_once "footer.php"; ?>
    </div>  
<?php linksfooter(); ?>
<!-- page specific plugin scripts -->
<script src="scripts/custom.js"></script>
<script src="scripts/plugins.js"></script>
<script src="elFinder/js/elfinder.min.js" ></script>
<script src="elFinder/js/i18n/elfinder.es.js" ></script>
<script src="ayudavideos/js/galeria.js?"+rand()+"" ></script>
<!--script src="ayudavideos/js/video.js"></script-->
<script src="../repositorio-lib/uploader-master/dist/js/jquery.dm-uploader.min.js"></script>
<script src="comentarios/demo-ui.js"></script>
<script src="comentarios/demo-config.js"></script>
<!--  DataTables.net   -->
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/datatables/js/jquery.dataTables19.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/datatables/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/material/js/dataTables.material.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/select/js/dataTables.select.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/colreorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" language="javascript" src="../repositorio-tema/assets/datatables/fixedheader/js/dataTables.fixedHeader.min.js"></script>
<!-- File item template -->
<script type="text/html" id="files-template">
  <li class="media">
    <div class="media-body mb-1">
      <p class="mb-2">
        <strong>%%filename%%</strong> - Estatus: <span class="text-muted">Cargando</span>
      </p>
      <div class="progress mb-2">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
          role="progressbar"
          style="width: 0%" 
          aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
        </div>
      </div>
      <hr class="mt-1 mb-1" />
    </div>
  </li>
</script>

</body>
</html>