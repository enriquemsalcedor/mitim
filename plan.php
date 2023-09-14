    <?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Plan de Mantenimiento', 'Solicitud de interfaz de Plan de Mantenimiento', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?> | Plan de Mantenimiento </title>
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
	<link rel="stylesheet" href="./css/ajustes.css<?php autoVersiones(); ?>">
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
                    <div class="tab-pane fade active show" id="filtrosconfig" role="tabpanel">
                        <div class="card mb-sm-3 mb-md-0">
                            <div class="card-header d-none">
                                <div>
                                    <h6 class="mb-1">Filtros</h6>
                                </div>
                            </div>
                            <div class="card-body p-0" id="DZ_W_Filtros_Body">
                                <div class="form-config">
                                <form id="form_filtrosmasivos" method="POST" autocomplete="off">
                                        <div class="d-block my-3">
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
                                                <label class="col-sm-4 col-form-label">Empresas</label>
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
                                                    <select name="unidadejecutoraf" id="unidadejecutoraf" multiple class="form-control text"></select>
                                                </div>
                                            </div>
                                            <div class="form-group row selectsr selectcr2">
                                                <label class="col-sm-3 col-form-label">Tipo</label>
                                                <div class="col-sm-9">
                                                    <select name="modalidadf" id="modalidadf" multiple class="form-control text"></select>
                                                </div>
                                            </div>
                                            <div class="form-group row selectsr selectcr2 ">
                                                <label class="col-sm-3 col-form-label">Marca</label>
                                                <div class="col-sm-9">
                                                    <select name="marcaf" id="marcaf" multiple class="form-control text"></select>
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
                                            <?php if($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 2 || $_SESSION['nivel'] == 7): ?>
                                            <div class="form-group row selectsr selectcr2">
                                                <label class="col-sm-3 col-form-label">Proveedores</label>
                                                <div class="col-sm-9">
                                                    <select name="idproveedoresf" id="idproveedoresf" multiple class="form-control text"></select>
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
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label text-red">Fuera de Servicio</label>
                                                <div class="col-sm-9">
                                                    <label>
                                                        <input type="checkbox" id="fueraserviciof" class="mt-2" name="fueraserviciof" value="1"><span class="checkbox-material"></span>
                                                    </label> 
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
                                Plan de Mantenimiento
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
                        <button type="button" class="btn btn-primary btn-xs" id="nuevo">
							<i class="fa fa-plus-circle"></i> <span class="ml-2">Nuevo</span>
						</button>
						<button type="button" class="btn btn-primary btn-xs" id="generarpreventivos">
							<i class="fa fa-tasks"></i> <span class="ml-2">Generar Preventivos</span>
						</button>
                    </div>
                </div>
				
				<!--tabla-->
				<div class="row">
                    <div class="col-xl-12">
						<div class="table-responsive">
							<table id="tablactividades" class="display min-w850" style="width:100%">
								<thead>
									<tr>
										<th>-</th>
										<th>Acción</th>
										<th id="cid">Id</th>
										<th id="ctitulo">título</th>
										<th id="cdescripcion">descripción</th>
										<th id="cdiainiciofrecuencia">día inicio</th>
										<th id="ctipo">Tipo</th>
										<th id="cresponsable">responsable</th>
										<th id="cfrecuencia">frecuencia</th>
										<th id="ccliente">cliente</th> 
										<th id="cproyecto">Proyecto</th> 
									</tr>
								</thead>									
							</table>
							<?php include_once('plan-generar.php'); ?>
						</div>
                    </div>
                </div> 
                <!--fin tabla-->

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
	<script src="./js/funciones1.js<?php autoVersiones(); ?>"></script>
    <script src="./js/plan.js<?php autoVersiones(); ?>"></script>
    <!--sweetalert2-->
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
</body>

</html>