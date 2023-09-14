<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Dashboard', 'Solicitud de interfaz de Dashboard', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?> | Semáforo de atención</title>
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

    <!-- <link href="./css/ajustes1.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="./css/ajustes.css?version=1.0.0.12">
	<style>  
		#icono-limpiar, #icono-filtrosmasivos{ display: none; }
	</style>
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
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                Semáforo de atención
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
                <hr class="mt-2 mb-2" style="display:none"></hr>
                <div class="col-xs-12 col-sm-12 col-md-12 d-none">
					<h5 class="col-form-label text-success datos"> </h5>
				</div>
				 
                <div class="row"> 
                    <div class="col-xl-12 col-xxl-12 col-lg-12">
						
						<div class="card"> 
							<div class="card-header border-0 p-3 d-none">
                                <!--<h3 class="fs-16 mb-0 text-black">Semáforo de atención</h3>-->
                            </div>
							
                            <div class="card-body pt-2 pr-3 pl-3 pb-2">
                                <div class="default-tab">
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show"> 
											<div class="col-md-12 mb-0 text-right">  
												<p class="mb-0"><span class="mb-2 mt-0 font-w500"> Última actualización: </span><span class="ultima-actualizacion font-w500"></span></p>
												<p class="mb-0"><span class="mb-2 text-success mt-0 font-w500"> Próxima actualización: </span><span class="proxima-actualizacion font-w600"></span> 
												</p>
											</div>
											<div class="row" >
												
												<div class="col-md-4 px-2">
													<div class="basic-list-group dv-semaforo-verde">
														<p class="bg-success text-white fs-14 pt-2 font-w500 mb-0 mt-3 col-md-12 text-center">A TIEMPO <span class="cantidadverdes"></span></p>
														<div class="list-group bg-success pb-3 e-uls fondo-logo-maxia" id="itemsverdes"> 
														</div>
													</div>  
												</div>
												
												<div class="col-md-4 px-2">
													<div class="basic-list-group dv-semaforo-amarillo">
														<p class="bg-warning text-white fs-14 pt-2 font-w500 mb-0 mt-3 col-md-12 text-center">POR VENCER <span class="cantidadamarillos"></span></p>
														<div class="list-group bg-warning pb-3 e-uls fondo-logo-maxia" id="itemsamarillos">  
														</div>
													</div>  
												</div>
												
												<div class="col-md-4 px-2">
													<div class="basic-list-group dv-semaforo-rojo">
														<p class="bg-danger text-white fs-14 pt-2 font-w500 mb-0 mt-3 col-md-12 text-center">RETRASO <span class="cantidadrojos"></span></p>
														<div class="list-group bg-danger pb-3 e-uls fondo-logo-maxia" id="itemsrojos"> 	
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
                </div><!--ROW NEW -->
				
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
    <!-- Usuarios -->
	<script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script> 
    <!-- Especificos -->
    <script src="js/semaforo.js<?php autoVersiones(); ?>"></script>
    <!--sweetalert2-->
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
</body>

</html>