<?php 
    include_once "funciones.php"; 
    verificarLogin(); 
?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" /> 
    <title><?php echo $sistemaactual ?> | Permisos</title>
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
</head>
<body>
    <div id="page-transitions">
        <?php menusup(); ?>        
        <?php menu(); ?> 
        <div id="page-content" class="page-content">    
            <div id="page-content-scroll" class="">
                <div class="tabs">
                    <div class="tab-titles">
                        <a href="niveles.php" class="uppercase" data-tab="tab-1"><i class="fa fa-refresh"></i> Actualizar </a>
                        <a data-toggle='modal' data-target='#modalpermisos' class="uppercase"><i class="fa fa-plus-circle"></i> Nuevo</a>
						<a href="#" id="limpiarFiltros" class="uppercase"><i class="fa fa-file-excel-o"></i> Limpiar Columnas</a>
                    </div>
                </div>
                <div class="content">
                    <table id="tbpermisos" class="mdl-data-table display nowrap table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th id="cnotificacion">Notificación</th>  
                                <th id="cniveles">Niveles</th>
                                <th id="cexcepciones">Excepciones</th>
                            </tr>
                        </thead> 
                    </table>
                </div> 
                <?php include_once "permisos-modal.php"; ?> 
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
<script src="js/permisos.js?"+rand()+"" ></script>
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