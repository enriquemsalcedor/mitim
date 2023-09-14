<?php
    include_once("conexion.php");
    include_once("funciones.php");
    
    verificarLogin();
    $nombre = $_SESSION['nombreUsuario'];
    $arrnombre = explode(' ', $nombre);
    $inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
    //bitacora($_SESSION['usuario'], 'Maestros', 'Solicitud de interfaz de subambiente', 0, '');
	permisosUrl();
		
	//Pertenece a Maxia
	$esmaxia = 0;
	$email   = $_SESSION['correousuario'];
	$dato    = explode('@', $email);
	$dominio = trim($dato[1]);
	if($dominio == 'maxialatam.com') $esmaxia = 1;
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $sistemaactual ?> | Calendario</title>
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
        <!-- Ajustes -->
        <link rel="stylesheet" href="./css/ajustes1.css<?php autoVersiones(); ?>">
         <!--FULLCALENDAR-->
        <link href="./vendor/fullcalendar/css-v5/fullcalendar.min.css" rel="stylesheet">
        <!--<link rel="stylesheet" href="./conf-calendar.css<?php autoVersiones(); ?>">-->
         <!-- Material color picker -->
        <link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    </head>
	<style>
		/* Altura exacta 
		@media (height: 360px) {
		#DZ_W_Filtros_Body{
		   height:300px !important
		   overflow-y: scroll;
		 }
		}*/

		/* Altura mínima */
		@media (min-height: 500px) {
		#DZ_W_Filtros_Body{
		   height:560px !important;
		   overflow-y: scroll;
		 }
		}

		/* Altura máxima */
		@media (max-height: 600px) {
		 #DZ_W_Filtros_Body{
		   height:480px !important;
		   overflow-y: scroll;
		 }
		}

		@media (max-height: 500px) {
		 #DZ_W_Filtros_Body{
		   height:400px !important;
		   overflow-y: scroll;
		 }
		}

		@media (max-height: 400px) {
		 #DZ_W_Filtros_Body{
		   height:330px !important;
		   overflow-y: scroll;
		 }
		}
	</style>
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
            <div class="chatbox" >
                <div class="chatbox-close"></div>
                <div class="custom-tab-1">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#chat">Filtros</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabpane-filtros fade active show" id="filtrosconfig" role="tabpanel">
                            <div class="card mb-sm-3 mb-md-0">
                                <div class="card-header d-none">
                                    <div>
                                        <h6 class="mb-1">Filtros</h6>
                                    </div>
                                </div>
                                <div class="card-body p-0" id="DZ_W_Filtros_Body" style="overflow-y: scroll;">
                                    <div class="form-config">
                                    <form id="form_filtrosmasivos" method="POST" autocomplete="off">
                                            <div class="d-block my-3">
                                                <div class="form-group row"> 
                                                    <div class="col-sm-4">
														<label class="col-form-label mr-2">Correctivos</label> 
                                                        <input type="checkbox" id="tipoinc" class="mt-2" name="tipoinc" value="1"><span class="checkbox-material"></span> 
                                                    </div>
                                                    <div class="col-sm-4">
														<label class="col-form-label mr-2">Preventivos</label>
                                                        <input type="checkbox" id="tipoprev" class="mt-2" name="tipoprev" value="1"><span class="checkbox-material"></span>
                                                    </div> 
													<?php if($esmaxia == 1):?>
                                                    <div class="col-sm-4">
														<label class="col-mr-2 col-form-label">Solicitudes de Flotas</label>
                                                        <label class="check">
                                                            <input type="checkbox" id="tiposol" class="mt-2" name="tiposol" value="1"><span class="checkbox-material"></span>
                                                        </label> 
                                                    </div>
			 										<?php endif;?>
                                                </div> 
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Desde</label> 
                                                    <div class="col-sm-9 ">
                                                        <input type="text" name="desdef" id="desdef" class="form-control text" placeholder="yyyy-mm-dd">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">Hasta</label> 
                                                    <div class="col-sm-9 ">
                                                        <input type="text" id="hastaf" name="hastaf" class="form-control text" placeholder="yyyy-mm-dd">
                                                    </div>
                                                </div>
                                                <div class="form-group row selectsr selectcr2" style="display:none;">
                                                    <label class="col-sm-3 col-form-label">Empresas</label>
                                                    <select name="idempresasf" id="idempresasf" class="form-control"></select>
                                                </div>
                                                <div class="form-group row selectsr selectcr2 box-cc">
                                                    <label class="col-sm-3 col-form-label">Clientes</label>
                                                    <div class="col-sm-9">
                                                        <select name="idclientesf" id="idclientesf" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Proyectos</label>
                                                    <div class="col-sm-9">
                                                        <select name="idproyectosf" id="idproyectosf" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Categor&iacute;a</label>
                                                    <div class="col-sm-9">
                                                        <select name="categoriaf" id="categoriaf" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Subcategor&iacute;a</label>
                                                    <div class="col-sm-9">
                                                        <select name="subcategoriaf" id="subcategoriaf" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Ubicación</label>
                                                    <div class="col-sm-9">
                                                        <select name="idambientesf" id="idambientesf" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Prioridad</label>											
                                                    <div class="col-sm-9">
                                                        <select name="prioridadf" id="prioridadf" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Estado</label>
                                                    <div class="col-sm-9">
                                                        <select name="estadof" id="estadof" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <?php if($_SESSION['nivel'] != 7): ?>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Departamentos / Grupos</label>
                                                    <div class="col-sm-9">
                                                        <select name="iddepartamentosf" id="iddepartamentosf" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Asignado a</label>
                                                    <div class="col-sm-9">
                                                        <select name="asignadoaf" id="asignadoaf" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group row selectsr selectcr2">
                                                    <label class="col-sm-3 col-form-label">Solicitante</label>
                                                    <div class="col-sm-9">
                                                        <select name="solicitantef" id="solicitantef" multiple class="form-control text"></select>
                                                    </div>
                                                </div>
                                                <div class="checkbox col-xs-12 col-sm-12 col-md-12">
                                                </div> 
                                                <div class="text-right">
                                                    <button type="button" class="col-xs-12 btn btn-primary btn-xs" id="filtrarmasivo"
                                                        title="Filtrar">
                                                        <i class="fas fa-filter"></i> Filtrar
                                                    </button>
                                                    <button type="button" class="col-xs-12 btn btn-warning text-white btn-xs"
                                                        id="limpiarfiltros" title="Limpiar">
                                                        <i class="fas fa-eraser"></i> Limpiar
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
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
                                   Calendario
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
                    <div class="row"><!--
                        <div class="col-md-12 mb-4 text-right">
                            
                            <button type="button" class="btn btn-primary btn-xs bell bell-link" id="filtrosmasivos"  href="javascript:;">
                                <i class="fa fa-filter mr-2"></i> Filtros
                            </button>
                        </div>-->
                    </div>
                    <!-- Large modal -->
                    <!-- Large modal -->
                    <!-- CALENDARIO -->
                    <div class="row">
                    	<div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="calendar-container">
										<div id="calendar" class="app-fullcalendar"></div>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div id='loading'>loading...</div>  -->
                    <!-- Fin CALENDARIO -->
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
        <!-- CALENDARIO-->
        <script src="./vendor/moment/moment.min.js"></script>
        <script src="./vendor/fullcalendar/js-v5/main.js"></script>
        <script src="./vendor/fullcalendar/js-v5/locale-all.js"></script>
        <!-- Material color picker -->
        <script src="./vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
        <script src="../repositorio-tema/assets/js/datepicker-es.js"></script>
        <!--custom-script-->
        <script src="./js/calendarios.js?<?php autoVersiones(); ?>"></script> 
        <script src="./js/helpers.js" type="module"></script>
    </body>
</html>