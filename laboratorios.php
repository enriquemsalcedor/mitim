<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Seguridad', 'Solicitud de interfaz de usuarios', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?> | Laboratorio</title>
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
        <div class="chatbox" >
			<div class="chatbox-close"></div>
			<div class="custom-tab-1">
				<ul class="nav nav-tabs tab-filtros">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#chat" id="filtrosmasivos">Filtros</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#chatnot" id="notificaciones_a">Notificaciones</a>
					</li>
				</ul>
                <div class="tab-content">
                    <div class="tab-pane tabpane-filtros fade active show" id="chat" role="tabpanel">
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
                                                <input type="hidden" name="calendarhidendesde" id="calendarhidendesde">
                                                <input type="hidden" name="calendarhidenhasta" id="calendarhidenhasta">
                                                <label class="col-sm-3 col-form-label">Desde</label> 
                                                <div class="col-sm-9">
                                                        <input type="text" name="desdef" id="desdef" class="form-control text" placeholder="yyyy-mm-dd">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Hasta</label> 
                                                <div class="col-sm-9">
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
                                                    <select name="idproyectosf" id="idproyectosf" multiple class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group row selectsr selectcr2" style="display:none;">
                                                <label class="col-sm-3 col-form-label">Categor&iacute;a</label>
                                                <div class="col-sm-9">
                                                    <select name="idcategoriasf" id="idcategoriasf" multiple class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group row selectsr selectcr2" style="display:none;">
                                                <label class="col-sm-3 col-form-label">Subcategor&iacute;a</label>
                                                <div class="col-sm-9">
                                                    <select name="idsubcategoriasf" id="idsubcategoriasf" multiple class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group row selectsr selectcr2" style="display:none;">
                                                <label class="col-sm-3 col-form-label">Ubicación</label>
                                                <div class="col-sm-9">
                                                    <select name="idambientesf" id="idambientesf" multiple class="form-control"></select>
                                                </div>
                                            </div>  
                                            <div class="form-group row selectsr selectcr2">
                                                <label class="col-sm-3 col-form-label">Prioridad</label>											
                                                <div class="col-sm-9">
                                                    <select name="idprioridadesf" id="idprioridadesf" multiple class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group row selectsr selectcr2">
                                                <label class="col-sm-3 col-form-label">Estado</label>
                                                <div class="col-sm-9">
                                                    <select name="idestadosf" id="idestadosf" multiple class="form-control"></select>
                                                </div>
                                            </div> 
                                            <div class="form-group row selectsr selectcr2">
                                                <label class="col-sm-3 col-form-label">Departamentos / Grupos</label>
                                                <div class="col-sm-9">
                                                    <select name="iddepartamentosf" id="iddepartamentosf" multiple class="form-control"></select>
                                                </div>
                                            </div> 
                                            <div class="form-group row selectsr selectcr2">
                                                <label class="col-sm-3 col-form-label">Asignado a</label>
                                                <div class="col-sm-9">
                                                    <select name="asignadoaf" id="asignadoaf" multiple class="form-control"></select>
                                                </div>
                                            </div>
                                            <div class="form-group row selectsr selectcr2">
                                                <label class="col-sm-3 col-form-label">Solicitante</label>
                                                <div class="col-sm-9">
                                                    <select name="solicitantef" id="solicitantef" multiple class="form-control"></select>
                                                </div>
                                            </div>  
											<div class="form-group row selectsr selectcr2">
                                                <label class="col-sm-3 col-form-label">Estado de equipo</label>
                                                <div class="col-sm-9">
                                                    <select name="diagnosticof" id="diagnosticof" multiple class="form-control">
														<option value="sinasignar">Sin asignar</option>
														<option value="funcional">Funcional</option>
														<option value="irreparable">Irreparable</option>
													</select>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <button type="button" class="col-xs-12 btn btn-primary btn-xs" onClick="filtrosMasivos();" id="filtrarmasivo"
                                                    title="Filtrar">
                                                    <i class="fas fa-filter"></i> Filtrar
                                                </button>
                                                <button type="button" class="col-xs-12 btn btn-warning text-white btn-xs"
                                                    onClick="limpiarFiltrosMasivos();" id="limpiarfiltros" title="Limpiar">
                                                    <i class="fas fa-eraser"></i> Limpiar
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="tab-pane tabpane-notificaciones fade active show" id="chatnot" role="tabpanel">
						<div class="card mb-sm-3 mb-md-0">
							<div class="card-header d-none">
								<div>
									<h6 class="mb-1">Notificaciones</h6>
								</div>
							</div>
							<div class="card-body p-0" id="DZ_W_Filtros_Body">
								<ul class="ul-notificaciones"> 
								</ul>
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
                                Laboratorio
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
                    <div class="col-md-12 mb-4 text-right"> 
						<button type="button" class="btn btn-primary btn-xs" id="nuevo">
							<i class="fa fa-plus-circle mr-2"></i> Nuevo
						</button>						
						<?php if($_SESSION['nivel'] == 1 || $_SESSION['usuario'] == 'umague' || $_SESSION['usuario'] == 'mbatista' || ($_SESSION['nivel'] == 2 && $pos !== false)): ?>
						<button type="button" class="btn btn-primary btn-xs" onClick="editarMasivo();" id="masivo">
							<i class="fa fa-edit mr-2"></i> Editar
						</button>
						<?php endif; ?>
						<?php if($_SESSION['nivel'] == 1 || $_SESSION['usuario'] == 'umague' || $_SESSION['usuario'] == 'mbatista' || ($_SESSION['nivel'] == 2 && $pos !== false)): ?>
						<button type="button" class="btn btn-primary btn-xs" onclick="generarSalidas();" id="salidas">
							<i class="fa fa-file-o mr-2"></i> Generar Salidas
						</button>
						<button type="button" class="btn btn-primary btn-xs" onclick="verCierres();" id="versalidas">
							<i class="fa fa-file-o mr-2"></i> Ver Salidas
						</button>
						<?php endif; ?>
						<?php if($_SESSION['nivel'] == 1 || $_SESSION['usuario'] == 'umague' || $_SESSION['usuario'] == 'mbatista' || ($_SESSION['nivel'] == 2 && $pos !== false)): ?>
						<button type="button" class="btn btn-primary btn-xs" onClick="mergeIncidente();" id="importar">
							<i class="fa fa-file-zip-o mr-2"></i> Fusionar
						</button>
						<?php endif; ?> 
						<button type="button" class="btn btn-primary text-white btn-xs" id="botonocultarcolumnas" data-toggle="modal" data-target=".modal-columnas-modal-lg">
                            <i class="fa fa-columns mr-2"></i> Columnas
						</button>
                        <button type="button" class="btn btn-primary btn-xs" onClick="exportar()" id="reportes">
							<i class="fa fa-file-o mr-2"></i> <span class="ml-2">Reportes</span>
						</button>						
                    </div>
                </div> 
                <div class="modal fade modal-columnas-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
                     <div class="modal-dialog modal-lg">
                         <div class="modal-content">
                            <div class="modal-header bg-success-light">
                                <h5 class="modal-title">Seleccione las columnas</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body"> 
								<div class="row">  
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c5" data-column="5">
										Solicitante
									</button>
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c6"  data-column="6">
										Creación
									</button> 
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c8"  data-column="8">
										Departamento
									</button>
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c9" data-column="9">
										Cliente
									</button>
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c10" data-column="10">
										Proyecto
									</button>  
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c11" data-column="11">
										Asignado
									</button> 
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c12" data-column="12">
										Serie
									</button>
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c13" data-column="13">
										Marca
									</button>
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c14" data-column="14">
										Modelo
									</button>
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c15" data-column="15">
										Prioridad
									</button>
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c16" data-column="16">
										Cierre
									</button>
									<button type="button" class="btn btn-primary btn-xxs toggle-vis mr-1 mt-1" style="width:24%;" id="c18" data-column="18">
										Estado equipo
									</button> 
								</div> 
             	        	</div>
                            <div class="modal-footer">
                            </div>
                         </div>
                     </div>
                </div> 
                <!--tabla-->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="table-responsive">
                            <table id="tablaincidentes" class="display min-w850 ">
                                <thead>
                                    <tr>
                                        <th>-</th>									<!--0-->
										<th>Acción</th>								<!--1-->
										<th id="cid">Id</th>						<!--2-->
										<th id="cestado">Estado</th>				<!--3-->
										<th id="ctitulo">Nombre de Activo</th>		<!--4-->
										<th id="csolicitante">Solicitante</th>		<!--5-->
										<th id="ccreacion">Creación</th> 			<!--6-->
										<th id="cempresa">Empresa</th>				<!--7-->
										<th id="cdepartamento">Dep. / Grupo</th>	<!--8-->
										<th id="ccliente">Cliente</th>				<!--9-->
										<th id="cproyecto">Proyecto</th> 			<!--10-->
										<th id="casignadoa">Asignado a</th> 		<!--11-->
										<th id="cserie">Serie</th>					<!--12-->
										<th id="cmarca">Marca</th>					<!--13-->
										<th id="cmodelo">Modelo</th>				<!--14-->
										<th id="cprioridad">Prioridad</th>			<!--15-->
										<th id="ccierre">Cierre</th>				<!--16-->
										<th id="cestadoant">Estado Ant.</th>		<!--17-->
										<th id="cestadoequipo">Estado Equipo</th>	<!--18-->
                                    </tr>
                                </thead>									
                                <tbody></tbody>
                            </table>    
                        </div>
                    </div>
                </div>
                <!--fin tabla-->
            </div>
        </div>
    </div>
    <!--**********************************
            Content body end
        ***********************************-->

    <!-- Adjuntos -->
	<?php include_once "laboratorio-adjuntos.php"; ?> 
	<!-- Adjuntos com -->
	<?php include_once "laboratorio-adjuntoscom.php"; ?> 
	<!-- Editar masivo -->
	<?php include_once "laboratorio-masivo.php"; ?> 
	<!-- Fusión -->
	<?php include_once "laboratorio-fusion.php"; ?> 
	<!-- Generar salidas -->
	<?php include_once "laboratorio-generarsalidas.php"; ?>  
	<!-- Ver cierres -->
	<?php include_once "laboratorio-versalidas.php"; ?>
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
    <script src="js/laboratorios.js<?php autoVersiones(); ?>" ></script>
	<script src="js/laboratorio-filtrosmasivos.js?" ></script>
	<script src="js/laboratorio-fusion.js?" ></script>
	<script src="js/laboratorio-masivo.js?" ></script>
	<script src="js/laboratorio-generarsalidas.js?" ></script>
	<script src="js/laboratorio-versalidas.js?" ></script>

    <!--sweetalert2-->
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
</body>
</html>