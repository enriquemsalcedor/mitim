<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nivel = $_SESSION['nivel'];
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Activos', 'Solicitud de interfaz de activo', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?>  | Activo</title>
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
		<!-- Toastr -->
	<link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
	<!--sweetalert2-->
	<link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">	

	<link href="./css/style1.css" rel="stylesheet">
	<link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
	<!--  Fonts and icons -->
	<link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
	<!-- Datatable -->
	<link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
	<!-- Ajustes -->
	<!--<link href="./css/style6.css" rel="stylesheet">-->
	<!--<link href="./css/ajustes1.css" rel="stylesheet">-->
	<link rel="stylesheet" href="./css/ajustes.css<?php autoVersiones(); ?>">
	<script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<style type="text/css">
		.btn-light:not(:disabled):not(.disabled){
			cursor: pointer;
			display: none;
		}
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
                    <div class="col-md-12 mb-12 text-right barraOpc">
                        <button type="button" class="btn btn-primary btn-xs" id="listado">
							<i class="fas fa-th-list" aria-hidden="true"></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				<div class="row mt-4">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<div class="default-tab">
									<ul class="nav nav-pills review-tab" role="tablist">
										<li class="nav-item active nav-general navact">
											<a class="nav-link active" data-toggle="tab" href="#boxact" role="tablist" aria-expanded="true">
												Activo
											</a>
										</li>
										<li class="nav-item navcom" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxcom" role="tablist">
												Comentarios
											</a>
										</li>
										<li class="nav-item navser" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxser" role="tablist">
												Seriales
											</a>
										</li>
										<li class="nav-item navtra" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxtra" role="tablist">
												Trasladar
											</a>
										</li>
										<li class="nav-item navfs" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxfs" role="tablist">
												Fuera de Servicio
											</a>
										</li>
										<li class="nav-item navcorr" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxcorr" role="tablist">
												Correctivos
											</a>
										</li>
										<li class="nav-item navprev" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxprev" role="tablist">
												Preventivos
											</a>
										</li> 
										<li class="nav-item navadj" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxadj" role="tablist">
												Adjuntos
											</a>
										</li>
									</ul>
									<div class="tab-content tab-activos-modal">										
										<div class="tab-pane fade show active tab-pane-general" id="boxact" role="tabpanel">
											<form id="form_activos" autocomplete="off">
												<div class="pt-4">												
													<div class="form-row">
														<?php if($_SESSION['nivel'] != 7): ?>
															<div class="col-xs-12 col-sm-6 col-md-4" style="display:none;">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idempresas">Empresas <span class="text-red">*</span></label>
																	<select class="form-control" name="idempresas" id="idempresas" style="width:93%"></select>
																	<span class="material-input"></span>
																</div>
															</div>
															<?php endif; ?>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idclientes">Clientes <span class="text-red">*</span></label>
																	<select class="form-control" name="idclientes" id="idclientes" ></select>
																	<span class="material-input"></span>
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idproyectos">Proyectos</label>
																	<select class="form-control" name="idproyectos" id="idproyectos" style="width:93%"></select>
																	<span class="material-input"></span>
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="text-label" for="nombreactivos">Nombre <span class="text-red">*</span></label>
																	<input type="text" class="form-control text" name="nombreactivos" id="nombreactivos" autocomplete="off">
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<input type="hidden" name="idactivos" id="idactivos" >
																	<label class="text-label" for="seractivos">Serial 1 <span class="text-red">*</span></label>
																	<input type="text" class="form-control text" name="seractivos" id="seractivos" autocomplete="off"> 
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="text-label" for="actactivos">Serial 2</label>
																	<input type="text" class="form-control text" name="actactivos" id="actactivos" autocomplete="off">
																	<span class="material-input"></span>
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idmarcasactivo">Marca <span class="text-red">*</span></label>
																	<select class="form-control" name="idmarcasactivo" id="idmarcasactivo" style="width:100%"></select>
																	<span class="material-input"></span>
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idmodelosactivo">Modelo <span class="text-red">*</span></label>
																	<select class="form-control" name="idmodelosactivo" id="idmodelosactivo" style="width:100%"></select>
																	<span class="material-input"></span>
																</div>
															</div>  
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idresponsablesactivo">Responsable</label> 
																	<select class="form-control" name="idresponsablesactivo" id="idresponsablesactivo" style="width:93%"></select>		 
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idambientesactivo">Ubicación</label> 
																	<select class="form-control" name="idambientesactivo" id="idambientesactivo" style="width:93%"></select>
																</div>
															</div>					
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idsubambientesactivo">Área</label>
																	<select class="form-control" name="idsubambientesactivo" id="idsubambientesactivo" style="width:93%"></select>
																	<span class="material-input"></span>
																</div>
															</div> 
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="text-label" for="faseactivos">Fase</label>
																	<input type="text" class="form-control text" name="faseactivos" id="faseactivos" autocomplete="off">
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="text-label" for="fectopactivos">Fecha tope mantenimiento</label>
																	<input type="text" class="form-control text" name="fectopactivos" id="fectopactivos" autocomplete="off">
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="text-label" for="fecinstactivos">Fecha instalación</label>
																	<input type="text" class="form-control text" name="fecinstactivos" id="fecinstactivos" autocomplete="off">
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="text-label" for="vidautil">Vida útil (Meses)</label>
																	<input type="number" class="form-control text" name="vidautil" id="vidautil" autocomplete="off"> 
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="text-label" for="vidautil">Vida útil restante (Meses)</label>
																	<input type="number" class="form-control text" name="vidautilreal" id="vidautilreal" disabled autocomplete="off">
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="text-label" for="ingresos">Ingresos que genera $(diario)</label>
																	<input type="text" class="form-control text" name="ingresos" id="ingresos" autocomplete="off"> 
																</div>
															</div>					
															<!--<div class="col-xs-12">	
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="comactivos">Comentarios</label>
																	<textsubambiente name="comactivos" id="comactivos" rows="4" class="form-control"></textsubambiente>
																	<span class="material-input"></span>
																</div>
															</div>-->
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idestadosactivo">Estado <span class="text-red">*</span></label>
																	<select class="form-control" name="idestadosactivo" id="idestadosactivo" style="width:93%">
																		<option value="ACTIVO">Activo</option>
																		<option value="INACTIVO">Inactivo</option>
																	</select>
																	<span class="material-input"></span>
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idtipo">Tipo</label>
																	<select class="form-control" name="idtipo" id="idtipo" style="width:93%"> 
																	</select>
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-4">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="idsubtipo">Subtipo</label>
																	<select class="form-control" name="idsubtipo" id="idsubtipo" style="width:93%"> 
																	</select>
																</div>
															</div>
															<div class="clearfix"></div> 
													</div>
													<hr class="mt-2 mb-2">
													<div class="form-row mb-3">
														<div class="col-xs-12 col-sm-12 col-md-12 subtipos campossubtipos">
														</div>
													</div>													
													<div class="clearfix"></div>
													<?php if($_SESSION['nivel']!=3 && $_SESSION['nivel']!=4): ?>
														<button type="button" class="btn btn-primary btn-xs" style="float:right;" id="guardar-activo"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
													<?php endif; ?>
												</div>
											</form>
										</div>
										<div class="tab-pane fade" id="boxcom"> 
												<form id="formcomentarios">
													<div class="pt-4">
														<div class="form-row mb-5 mt-2"> 
															<div class="col-xs-12 col-sm-12 col-md-12"> 
																<h5 class="col-form-label text-success">Comentarios</h5> 
																<?php if($_SESSION['nivel'] != 4): ?>
																	<input type="radio" name="visibilidad" id="visibilidad1" value="Público" checked>
																	<label class="text-label" for="visibilidad1">Público</label> 
																	<input type="radio" name="visibilidad" id="visibilidad2" value="Privado">
																	<label class="text-label" for="visibilidad2">Privado</label> 
																<?php endif; ?> 
															</div>
															<div class="col-xs-12 col-sm-12 col-md-12">
																<label class="text-label" for="comentario">Nuevo Comentario</label>
																<textarea rows="4" class="form-control" name="comentario" id="comentario"></textarea>
															</div>  
														</div>
														<div class="text-right col-xs-12 col-sm-12 col-md-12 mt-3">
															<button type="button" class="btn btn-warning  text-white btn-xs" style="float:right" onclick="limpiarComentario();"><i class="fas fa-eraser"></i> Limpiar</button>
															<button type="button" class="btn btn-primary btn-xs" style="float:right; margin-right:10px" onclick="agregarComentario();"><i class="fas fa-check-circle mr-2"></i>Agregar</button> 
															
														</div> 
													</div>
													<div class="pt-4">
														<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
															<div class="table-responsive">
																<table id="tablacomentario" class="display min-w850">
																	<thead>
																		<tr>
																			<th>Id</th>
																			<th>Acción</th>
																			<th>Comentario</th>
																			<th>Usuario</th>
																			<th>Visibilidad</th>
																			<th>Fecha</th>
																			<th>Adjunto</th>
																		</tr>
																	</thead>
																	<tbody></tbody>
																</table>
															</div>  
														</div>
													</div>
												</form> 
										</div>
										<div class="tab-pane fade" id="boxser"> 
											<div class="pt-4">
												<div class="form-row"> 
													<div class="col-xs-12 col-sm-12 col-md-12">
														<div class="table-responsive">
															<table id="tablaseriales" class="mdl-data-table display nowrap table-striped" style="width:100%">
																<thead>
																	<tr>
																		<th>Serial anterior</th>
																		<th>Serial actual</th>
																		<th>Fecha de cambio</th>
																		<th>Días transcurridos</th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table>
														</div> 
													</div>
												</div>
											</div> 
										</div>
										<div class="tab-pane fade" id="boxtra">
										    <!--<div class="card" >-->  
												<div class="pt-4">
													<div class="tab-content">
														<div class="form-row"> 
															<div class="col-sm-12">
    															<h5 class="col-form-label text-success">Actual</h5>
    														</div>
    														<div class="col-md-6 col-xs-6 col-sm-6">
																<div class="form-group label-floating is-empty">
																	<input type="hidden" name="idactivotraslado" id="idactivotraslado" >
																	<label class="control-label" for="ambienteactual">Ubicación</label> 
																	<select class="form-control" name="ambienteactual" id="ambienteactual" disabled="disabled" style="width:93%"></select>
																</div>
															</div>
															<div class="col-md-6 col-xs-6 col-sm-6">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="subambienteactual">Area</label> 
																	<select class="form-control" name="subambienteactual" id="subambienteactual" disabled="disabled" style="width:93%"></select>
																</div>
															</div>
															<hr class="mt-2 mb-2">
															<div class="col-sm-12">
    															<h5 class="col-form-label text-success">Traslado</h5>
    														</div>
															<div class="col-md-6 col-xs-6 col-sm-6">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="ambientenuevo">Ubicación</label> 
																	<select class="form-control" name="ambientenuevo" id="ambientenuevo" style="width:93%"></select>	 
																</div>
															</div>
															<div class="col-md-6 col-xs-6 col-sm-6">
																<div class="form-group label-floating is-empty">
																	<label class="control-label" for="subambientenuevo">Area</label> 
																	<select class="form-control" name="subambientenuevo" id="subambientenuevo" style="width:93%"></select>	 
																</div>
															</div>
															<div class="col-md-12 col-sm-12 col-md-12 text-right">
															<?php if($_SESSION['nivel']!=3 && $_SESSION['nivel']!=4 && $_SESSION['nivel']!=5): ?>
        														<button type="button" class="btn btn-primary btn-xs" id="trasladar-activo">Trasladar</button>
        													<?php endif; ?>
        													</div>
															<div class="col-md-12 col-sm-12 col-md-12">
																<h4>Historial</h4>
																<div class="cardtable" style="margin-top:0px">
																	<div class="table-responsive">
																		<table id="tbtraslados" class="mdl-data-table display nowrap table-striped" style="width:100%">
																			<thead>
																				<tr> 
																					<th></th>
																					<th></th>
																					<th>Ubicación Anterior</th>
																					<th>Area Anterior</th>
																					<th>Ubicación Nueva</th>
																					<th>Area Nueva</th>
																					<th>Usuario</th>
																					<th>Fecha</th>
																				</tr>
																			</thead>
																		</table>
																	</div> 
																</div> 
															</div>  					    
														</div>
													</div><!-- fin pt-4-->
												</div>
											<!--</div>-->
										</div>
										<div class="tab-pane fade" id="boxfs">
										    <!--<div class="card" >  -->
												<div class="pt-4">
													<div class="tab-content">
														<div class="form-row"> 
															<div class="col-xs-12 col-sm-12 col-md-12">
																<div class="cardtable" style="margin-top:0px">
																	<div class="table-responsive">
																		<table id="tablafueraservicio" class="mdl-data-table display nowrap table-striped" style="width:100%">
																			<thead>
																				<tr>
																					<th>Serial</th>
																					<th>Desde</th>
																					<th>Hasta</th>
																					<th>Incidente</th>
																				</tr>
																			</thead>
																		</table>
																	</div> 
																</div> 
															</div>			
														</div>
													</div><!-- fin pt-4-->
												</div>
											<!--</div>-->
										</div>
										<div class="tab-pane fade" id="boxcorr">
										    <!--<div class="card" >  -->
												<div class="pt-4">
													<div class="tab-content">
														<div class="form-row"> 
															<div class="col-xs-12 col-sm-12 col-md-12">
																<div class="cardtable" style="margin-top:0px">
																	<div class="table-responsive">
																		<table id="tablacorrectivos" class="mdl-data-table display nowrap table-striped" style="width:100%">
																			<thead>
																				<tr>
																					<th>Id</th>
																					<th>Título</th> 
																					<th>Estado</th> 
																					<th>Fecha Creación</th>
																					<th>Solicitante</th>
																					<th>Asignado a</th>
																					<th></th>
																				</tr>
																			</thead>
																		</table>
																	</div> 
																</div> 
															</div>			
														</div>
													</div><!-- fin pt-4-->
												</div>
											<!--</div>-->
										</div>
										<div class="tab-pane fade" id="boxprev">
										    <!--<div class="card" > --> 
												<div class="pt-4">
													<div class="tab-content">
														<div class="form-row"> 
															<div class="col-xs-12 col-sm-12 col-md-12">
																<div class="cardtable" style="margin-top:0px">
																	<div class="table-responsive">
																		<table id="tablapreventivos" class="mdl-data-table display nowrap table-striped" style="width:100%">
																			<thead>
																				<tr>
																					<th>Id</th>
																					<th>Título</th> 
																					<th>Estado</th> 
																					<th>Fecha Creación</th>
																					<th>Solicitante</th>
																					<th>Asignado a</th>
																					<th></th>
																				</tr>
																			</thead>
																		</table>
																	</div> 
																</div> 
															</div>	
														</div>
													</div><!-- fin pt-4-->
												</div>
											<!--</div>-->
										</div>
										<div class="tab-pane fade" id="boxadj">
										    <!--<div class="card" > --> 
												<div class="pt-4">
													<div class="tab-content">
														<div class="form-row"> 
															<iframe width="100%" height="368" src="" frameborder="0" allowfullscreen id="fevidenciasmodal"></iframe>	
														</div>
													</div><!-- fin pt-4-->
												</div>
											<!--</div>-->
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

		<?php include_once "activos-adjuntoscom.php"; ?>
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
    <script src="./js/activo.js<?php autoVersiones(); ?>"></script>
	
</body>

</html>