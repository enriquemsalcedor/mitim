<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	bitacora($_SESSION['usuario'], 'Datos de agua', 'Solicitud de interfaz de datos de agua', 0, '');
?>
<!DOCTYPE html>

<html lang="en">
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width,initial-scale=1">
	    <title><?php echo $sistemaactual ?>  | Datos de agua</title>
	    <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
            <!-- Toastr -->
        <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
        <!--sweetalert2-->
        <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
        <link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet"> 

        <!--<link href="./css/style1.css" rel="stylesheet">-->
        <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
        <!--  Fonts and icons -->
        <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
        <!-- Datatable -->
        <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
        <!-- Ajustes -->
        <link href="./css/style6.css" rel="stylesheet">
        <link href="./css/ajustes.css" rel="stylesheet">
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
                                <span class="tipo"></span>
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
                    <div class="col-md-12 mb-4 text-right barraOpc">
                        <button type="button" class="btn btn-primary btn-xs" id="listado">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<form id="form_permiso_ce" autocomplete="none">
                                    <div class="pt-4">
                                        <div class="form-row">
													<input type="hidden" name="idaguas" id="idaguas" >
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="fecha">Fecha / Hora <span class="ast_red">*</span></label>
								<input type="text" class="form-control text" name="fecha" id="fecha">
								
							</div>
						</div>
					 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="Consumo">Consumo<span class="ast_red"></span></label>
								<input type="text" class="form-control text" name="consumo" id="consumo">
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="tanque1m1">Tanque 1 M.1<span class="ast_red"></span></label>
								<input type="text" class="form-control text" name="tanque1m1" id="tanque1m1">
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="tanque1m2">Tanque 1 M.2<span class="ast_red"></span></label>
								<input type="text" class="form-control text" name="tanque1m2" id="tanque1m2">
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="tanque2m1">Tanque 2 M.1<span class="ast_red"></span></label>
								<input type="text" class="form-control text" name="tanque2m1" id="tanque2m1">
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="tanque2m2">Tanque 2 M.2<span class="ast_red"></span></label>
								<input type="text" class="form-control text" name="tanque2m2" id="tanque2m2">
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="horasdisponible">Horas Disponible<span class="ast_red"></span></label>
								<input type="text" class="form-control text" name="horasdisponible" id="horasdisponible">
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="potabilizado">Potabilizado<span class="ast_red"></span></label>
								<input type="text" class="form-control text" name="potabilizado" id="potabilizado">
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="edotiempo">Edo. Tiempo <span class="ast_red"></span></label> 
								<select class="form-control text" name="edotiempo" id="edotiempo" style="width:100%">
								    <option value="">Seleccione</option>
									<option value="Nublado">NUBLADO</option>
									<option value="Soleado">SOLEADO</option>
									<option value="Lluvia">LLUVIA</option>
								</select>
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-6 col-md-2">
							<div class="form-group label-floating">
								<label class="control-label" for="estadoplanta">Estado Planta<span class="ast_red"></span></label> 
								<select class="form-control text" name="estadoplanta" id="estadoplanta" style="width:100%">
								    <option value="">Seleccione</option>
									<option value="Operativa">OPERATIVA</option>
									<option value="Mantenimiento">MANTENIMIENTO</option>
								</select>
								
							</div>
						</div>
						 <div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group label-floating">
								<label class="control-label" for="notas">Nota</label>
								<textarea name="notas" id="notas" rows="7" class="form-control text"></textarea>
							</div>
						</div>
                                    </div><!--pt-4--> 
        						</form>
	                                <div class="text-right col-xs-12 mt-3">
	                                    <button type="button" class="btn btn-primary btn-xs" id="guardar" style="float:right;"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
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
    <script src="../repositorio-tema/assets/js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="./js/custom.min.js"></script>
    <script src="./js/deznav-init.js"></script>
    <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
    
    <!-- Daterangepicker -->
    <!-- momment js is must -->
    <script src="./vendor/moment/moment.min.js"></script>
    <!-- Material color picker -->
    <script src="./vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="../repositorio-tema/assets/js/datepicker-es.js"></script>
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <!--<script src="./vendor/datatables/js/dataTables.rowGroup.js"></script>-->
    <!-- Select 2 -->
    <script src="./js/select2/select2.min.js"></script>
    <script src="./js/select2/select2-es.min.js"></script>
   <!-- <script src="./js/plugins-init/select2-init.js"></script>-->
       <!-- Toastr -->
    <script src="./vendor/toastr/js/toastr.min.js"></script>
    <!--sweetalert2-->
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    <!-- registro -->
    <script src="./js/funciones1.js<?php autoVersiones(); ?>"></script>

    <script src="./js/datosagua.js?<?php autoVersiones(); ?>"></script>
    <!--sweetalert2-->

</body>

</html>