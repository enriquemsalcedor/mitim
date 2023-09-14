<?php 
	include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nivel = $_SESSION['nivel'];
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Actas', 'Solicitud de interfaz de Reportes', 0, '');
	permisosUrl();
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?> | Reportes</title>
	<!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
    <!--sweetalert2-->
    <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="./css/style1.css" rel="stylesheet">
    <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--  Fonts and icons -->
    <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- Material color picker -->
    <link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Ajustes -->
    <script type="text/javascript"> var nivel = "'.$_SESSION['nivel'].'"; var idproyectos = "'.$_SESSION['idproyectos'].'"; var temp = "'.$_SESSION['user_id'].'"; </script>
	<link rel="stylesheet" href="./css/ajustes.css?version=1.0.0.12">
</head>

<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <?php navheader(); ?>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->
        <!--**********************************
            Configuración start
        ***********************************-->
        <!--**********************************
            Configuración End
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                Reportes
                            </div>
                        </div>
                        <?php navheaderbotones(); ?>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <?php menuplantilla(); ?>
        <!--**********************************
            Sidebar end
        ***********************************-->


        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <div class="container-fluid">
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<div class="default-tab">
									<ul class="nav nav-pills review-tab" role="tablist">
										<li class="nav-item active">
											<a class="nav-link active" data-toggle="tab" href="#reporteclientes">Reporte mensual de clientes</a>
										</li>
										<li class="nav-item" id="tabcom">
											<a class="nav-link" data-toggle="tab" href="#reportecss">Reporte mensual CSS</a>
										</li> 
									</ul>
									<div class="tab-content">										
										<div class="tab-pane fade show active" id="reporteclientes">
										    <div class="card" > 
												<form id="formcuatrimestrales">
													<div class="pt-4">
														<div class="tab-content">
															<div class="form-row">
																<div class="col-xs-12 col-sm-12 col-md-12">
																	<h5 class="col-form-label text-success">Reporte mensual de clientes</h5>
																</div>
																<div class="col-xs-12 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Cliente</label>
																		<select name="idclientes" id="idclientes" class="form-control text"></select>
																	</div>
																</div>

																<div class="col-xs-12 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Proyecto</label>
																		<select name="idproyectos" id="idproyectos" class="form-control text"></select>
																	</div>
																</div>
																
																<div class="col-xs-12 col-sm-12 col-md-12">
																	<h5 class="col-form-label text-success">Fecha de creación</h5>
																</div> 
																<div class="col-xs-6 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Desde</label>
																		<input type="text" name="fecha-desdec" id="fecha-desdec" class="form-control text" autocomplete="off">
																	</div>
																</div>

																<div class="col-xs-6 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Hasta</label>
																		<input type="text" name="fecha-hastac" id="fecha-hastac" class="form-control text" autocomplete="off">
																	</div>
																</div>
																
																<div class="col-xs-12 col-sm-12 col-md-12">
																	<h5 class="col-form-label text-success">Fecha de resolución</h5>
																</div> 
																<div class="col-xs-6 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Desde</label>
																		<input type="text" name="fecha-desder" id="fecha-desder" class="form-control text" autocomplete="off">
																	</div>
																</div>

																<div class="col-xs-6 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Hasta</label>
																		<input type="text" name="fecha-hastar" id="fecha-hastar" class="form-control text" autocomplete="off">
																	</div>
																</div>
																
															</div>
														</div><!-- fin pt-4-->
													</div>
			                                    <button type="button" style="float: right;" class="btn btn-primary btn-xs" id="exportar"><i class="fas fa-file-pdf-o mr-2"></i>Generar reporte</button>
												</form> 
											</div>
										</div>

										<div class="tab-pane fade" id="reportecss">
										    <div class="card" > 
												<form id="formmensuales"> 
													<div class="pt-4">
														<div class="tab-content">
															<div class="form-row">
																<div class="col-xs-12 col-sm-12 col-md-12">
																	<h5 class="col-form-label text-success">Reporte mensual de cliente CSS</h5>
																</div>
																<div class="col-xs-12 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Cliente</label>
																		<select name="idclientescss" id="idclientescss" class="form-control text"></select>
																	</div>
																</div>

																<div class="col-xs-12 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Proyecto</label>
																		<select name="idproyectoscss" id="idproyectoscss" class="form-control text"></select>
																	</div>
																</div>
																
																<div class="col-xs-12 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Tipo</label>
																		<select name="tipo" id="tipo" class="form-control text" multiple>
																			<option value="incidentes">Correctivo</option>
																			<option value="preventivos">Preventivo</option>
																		</select>
																	</div>
																</div>

																<div class="col-xs-12 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Ubicación</label>
																		<select name="idambientes" id="idambientes" class="form-control text"></select>
																	</div>
																</div> 
																<div class="col-xs-6 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Desde</label>
																		<input type="text" name="fecha-desdecss" id="fecha-desdecss" class="form-control text" autocomplete="off">
																	</div>
																</div>

																<div class="col-xs-6 col-sm-6 col-md-6">
																	<div class="form-group label-floating">
																		<label class="control-label">Hasta</label>
																		<input type="text" name="fecha-hastacss" id="fecha-hastacss" class="form-control text" autocomplete="off">
																	</div>
																</div>

															</div>
														</div><!-- fin pt-4-->
													</div>
			                                    <button type="button" style="float: right;" class="btn btn-primary btn-xs" id="exportarCSS"><i class="fas fa-file-pdf-o mr-2"></i>Generar reporte</button>
												</form> 
											</div>
										</div>  
									</div>
                                </div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--**********************************
            Content body end
        ***********************************-->
		<!--**********************************
            Footer start
        ***********************************-->
		<div class="footer">
			<?php include_once('footer.php'); ?>
		</div>
		<!--**********************************
            Footer end
        ***********************************-->

    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="./js/deznav-init.js"></script>
    <!-- Toastr -->
    <script src="./vendor/toastr/js/toastr.min.js"></script>
    <!-- momment js is must -->
    <script src="./vendor/moment/moment.min.js"></script>
    <!-- Material color picker -->
    <script src="./vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="../repositorio-tema/assets/js/datepicker-es.js"></script>
    <!-- Select - Font -->
    <script src="./js/select2/select2.min.js"></script>
    <script src="./js/select2/select2-es.min.js"></script>
    <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <!-- Usuarios -->
	<script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
	<script src="js/reportes.js" ></script>
	
</body>

</html>