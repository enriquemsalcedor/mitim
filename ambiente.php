<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Maestros', 'Solicitud de interfaz de ubicacion', 0, '');
	permisosUrl();
	$nivel = $_SESSION["nivel"]; 
    $var_nivel = '    <script type="text/javascript"> var nivel = "'.$_SESSION['nivel'].'"; var idproyectos = "'.$_SESSION['idproyectos'].'"; var temp = "'.$_SESSION['user_id'].'"; </script>';

	$tipo="";
	$tipo		= (!empty($_GET['nombre']) ? $_GET['nombre'] : '');


	if(!isset($_GET['type'])){//NUEWVO
		$tipo="new";
	}else{
		$tipo = $_GET['type'];
		if($tipo=="edit" || $tipo=="view" || $tipo=="new"){//EDITAR O VER

		}else{//NO VALID
			header("Location: ambientes.php");
			exit;
		}
	}
?>



 
<!DOCTYPE html>
	<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $sistemaactual ?> | Ubicación</title>
        <!-- Favicon icon -->

        <?php echo $var_nivel ?>

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
	<link rel="stylesheet" href="./css/ajustes.css<?php autoVersiones(); ?>">
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
					<!--
                    <div class="col-md-10">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Reges </a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0)">Usuarios</a></li>
                            </ol>
                        </div>
                    </div>
					-->
                    <div class="col-md-12 mb-4 text-right barraOpc">
                        <button type="button" class="btn btn-primary btn-xs" id="salir-ambientes">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<form id="form_ambiente_ce" autocomplete="none">
									<div class="pt-4">
										<div class="form-row">

											<input type="hidden" name="idsitios" id="idsitios">
											<div class="col-xs-12 col-sm-6 col-md-6">
												<div class="form-group label-floating is-empty">
													<label class="control-label" for="unidad">Nombre <span class="text-red">*</span></label>
													<input type="text" class="form-control text" name="unidad" id="unidad" autocomplete="off">
												</div>
											</div>
											<!--<?php if($_SESSION["nivel"] != 7): ?>
												<div class="col-xs-12 col-sm-6 col-md-6">
													<div class="form-group label-floating" >
														<label class="control-label" for="codigo">Código</label>
														<input type="text" class="form-control text" name="codigo" id="codigo" autocomplete="off">
													</div>
												</div>
											<?php endif; ?>



										<?php if($_SESSION["nivel"] != 7): ?>
										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
													<label class="control-label" for="latitud">Latitud</label>
													<input type="text" class="form-control text" name="latitud" id="latitud" autocomplete="off">
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
													<label class="control-label" for="longitud">Longitud</label>
													<input type="text" class="form-control text" name="longitud" id="longitud" autocomplete="off">
											</div>
										</div>
										<?php endif; ?>





										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
													<label class="control-label" for="provincia">Provincia</label>

													<input type="text" class="form-control text" name="provincia" id="provincia" autocomplete="off">
												</div>
											</div>


										<?php if($_SESSION["nivel"] != 7): ?>
											<div class="col-xs-12 col-sm-6 col-md-6">
												<div class="form-group label-floating">
														<label class="control-label" for="codaceptacion">Código Aceptación</label>
														<input type="text" class="form-control text" name="codaceptacion" id="codaceptacion" autocomplete="off">
												</div>
											</div>
										<?php endif; ?>-->



										<div class="col-xs-12 col-sm-6 col-md-6 select2-multiple">
											<div class="form-group label-floating selectsr selectcr2">
												<label class="control-label">Responsables</label>
												<select name="responsables" id="responsables" class="form-control text" multiple>
												</select>
											</div>
										</div>







										</div><!--form-row-->
									</div><!--pt-4-->




        						</form>
									<?php if($tipo=="new" || $tipo=="edit"): ?>

		                                    <button type="button" style="float: right;" class="btn btn-primary btn-xs" id="guardar-ambiente"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
									<?php endif; ?>
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
	    <!-- Select - Font -->
	    <script src="./js/select2/select2.min.js"></script>
	    <script src="./js/select2/select2-es.min.js"></script>
	    <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
	    <!-- Datatable -->
	    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
	    <!-- Usuarios -->
	    <script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
	    <script src="./js/ambientes-ne.js?<?php autoVersiones(); ?>"></script>
	    <!--sweetalert2-->
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>

	</body>
</html>