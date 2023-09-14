<?php 
	include_once "funciones.php"; 
	verificarLogin();
?>
 <!DOCTYPE HTML>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
	<link rel="apple-touch-icon" sizes="76x76" href="images/favicon.png" />
	<link rel="icon" type="image/png" href="images/favicon.png" />
	<title>Maxia Toolkit | Soporte | Actas Periféricos</title>
	<?php linksheader(); ?>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.css">
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
			<div id="page-content-scroll" class=""><!--Enables this element to be scrolled --> 	
			    <div class="tabs">
			        <div class="tab-titles">
						<a href="actas.php" class="uppercase" data-tab="tab-1"> Cuatrimestrales</a>
						<a href="actasmpc.php" class="uppercase" data-tab="tab-1">Mensuales</a>
						<a href="mttopendientes.php" class="uppercase" data-tab="tab-1"> Mtto. Pendientes </a>
						<a href="actasperif.php" class="uppercase" data-tab="tab-1"> <strong>Periféricos</strong> </a>
					</div>
        		</div>
				<div class="content">					
                    <div class="container-fluid">
                        <div class="row">
                        	<div class="col-xs-12">
								<div class="col-xs-12 col-md-12 acta_tit"><h3> Actas </h3></div>
                        		<div class="col-xs-12 col-md-6 col-lg-2 form-group label-floating is-empty">
    								<label class="control-label">Periodo</label>
    								<select required="required" name="periodo" id="periodo" class="form-control"></select>
    								<span class="material-input"></span>
    							</div>						
    							<div class="clearfix"></div>
    							<br>
    							<div class="text-center">
    								<button type=button class="btn btn-danger btn-sm btn-round" id="reporteMP">
    									<span class="icon-col fa fa-file-pdf-o"></span> Generar Acta
    								</button>
    							</div>
                        	</div>
                        </div>
                    </div>
				</div>
				<div class="footer footer-dark">
					<a href="#" class="footer-logo"></a>
					<p class="copyright-text">Copyright © Maxia Latam <span id="copyright-year">2020</span>. All Rights Reserved.</p>
				</div>
			</div>  	
		</div>
	</div>
<?php configuracion(); ?>
<?php linksfooter(); ?>
<!-- page specific plugin scripts -->
<script src="scripts/custom.js"></script>
<script src="scripts/plugins.js"></script>
<script src="js/actasperif.js" ></script>
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
</body>
</html>