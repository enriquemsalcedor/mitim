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
	<title>Maxia Toolkit | Soporte | Actas Mantenimiento Correctivo</title>
	<?php linksheader(); ?>
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.css">
	<!-- DataTables -->
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/datatables/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/material/css/dataTables.material.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/material.min.css"> -->
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/select/css/select.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/colreorder/css/colReorder.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../repositorio-tema/assets/datatables/fixedheader/css/fixedHeader.dataTables.min.css">
	<style>
 
		.footer { padding: 0px;	clear: both; width: 100%; margin: 0 auto; position: fixed !important; bottom: 0 !important;	z-index: 100; background-color: #1e3d7a; }
		.clearfix {  position: static !important;}
		.acta_tit{ padding: 2% 0; }
		.iconcalfdesde, .iconcalfhasta{ cursor: pointer; }
		#fecha-desde, #fecha-hasta{ width: 90%; float: left; }
		
	</style>
</head>
<body>	
    <div id="page-transitions">
		<?php menusup(); ?>		
		<?php menu(); ?>
		<div id="page-content" class="page-content">	
			<div id="page-content-scroll" class=""><!--Enables this element to be scrolled --> 	
			    <div class="tabs"> 
					<div class="tab-titles">
						<a href="actas.php" class="uppercase" data-tab="tab-1"> Cuatrimestrales </a>
						<a href="actasmpc.php" class="uppercase" data-tab="tab-1"> <strong>Mensuales</strong> </a>
						<a href="mttopendientes.php" class="uppercase" data-tab="tab-1"> Mtto. Pendientes </a>
						<a href="actasperif.php" class="uppercase" data-tab="tab-1"> Periféricos</a>
					</div>
        		</div>
				<div class="content">					
                    <div class="container-fluid">
                        <div class="row">
                        	<div class="col-xs-12">
								<div class="col-xs-12 col-md-12 acta_tit"><h3> Actas de Mantenimiento Preventivo y Correctivo </h3></div>
    							<div class="col-xs-12 col-md-4 col-lg-2 form-group label-floating is-empty">					
    								<label class="col-xs-12 control-label" for="textinput">Desde</label>
    								<div class="input-group box-input date">
    								    <input type="hidden" name="calendarhidendesde" id="calendarhidendesde">
										<input type="text" name="fecha-desde" id="fecha-desde" class="form-control" placeholder="YYYY-MM-DD">
										<i class="fa fa-calendar iconcalfdesde" aria-hidden="true"></i>
    								</div>
    							</div>
    							<div class="col-xs-12 col-md-4 col-lg-2 form-group label-floating is-empty">					
    								<label class="col-xs-12 control-label" for="textinput">Hasta</label>
    								<div class="input-group box-input date">
    								    <input type="hidden" name="calendarhidenhasta" id="calendarhidenhasta">
										<input type="text" id="fecha-hasta" name="fecha-hasta" class="form-control" placeholder="YYYY-MM-DD">
										<i class="fa fa-calendar iconcalfhasta" aria-hidden="true"></i>
    								</div>
    							</div>
    							
    							<div class="clearfix"></div>
    							<br>
    							<div class="text-center">
    								<button type="button" class="btn btn-danger btn-sm btn-round" id="reporteMPC">
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
<script src="js/actasmpc.js" ></script>
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