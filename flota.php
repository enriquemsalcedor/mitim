<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nivel = $_SESSION['nivel'];
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Flotas', 'Solicitud de interfaz de flota', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?>  | Solicitud de Flotas</title>
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
	<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
		<!-- Toastr -->
	<link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
	<link href="./css/style1.css" rel="stylesheet">
	<!--sweetalert2-->
	<link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">	

	<!--<link href="./css/style1.css" rel="stylesheet">-->
	<link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!--  Fonts and icons -->
	<link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
	<!-- Datatable -->
	<link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
	<!-- Ajustes -->
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
							<i class="fas fa-th-list" aria-hidden="true"></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				<div class="row">
                    <div class="col-xl-12 onlymed" id="divnombrecedula" style="display:none;">
						<div class="bg-info" style="border-radius: 0.2rem;">
							<h4 class="p-2 m-0 text-right text-white" id="nombreusuario"> </h4>
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<div class="default-tab">
									<ul class="nav nav-pills review-tab" role="tablist">
										<li class="nav-item active correctivo">
											<a class="nav-link active" data-toggle="tab" href="#correctivo">Solicitud</a>
										</li>
										<li class="nav-item comentarios" style="display:none;" id="tabcom">
											<a class="nav-link" data-toggle="tab" href="#comentarios">Comentarios</a>
										</li>
										<li class="nav-item estados" style="display:none;" id="tabest">
											<a class="nav-link" data-toggle="tab" href="#estados">Estados</a>
										</li>
										<?php if($_SESSION['nivel'] != 4):	?>
										<li class="nav-item historial" style="display:none;" id="tabhis">
											<a class="nav-link" data-toggle="tab" href="#historial">Historial</a>
										</li>
										<?php endif; ?>
									</ul>
									<div class="tab-content">										
										<div class="tab-pane fade show active" id="correctivo" role="tabpanel">
											<form id="form_incidentes" autocomplete="off">
												<div class="pt-4">												
													<div class="form-row">
														<div class="col-xs-12 col-sm-4 col-md-4 nonivelcliente" style="display:none;" id="divsolic">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">Solicitante</label>
																<select name="solicitante" id="solicitante" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-8 col-md-8 box-cc row pr-0 nonivelcliente">
															<div class="col-xs-12 col-sm-2 col-md-2 content-fechacreacion pr-0" style="display:none;" id="divfechac">
																<div class="form-group label-floating">
																	<label class="text-label">Fecha Creación  </label>
																	<input type="text" name="fechacreacion" id="fechacreacion" class="form-control inc-edit text" disabled>
																</div>
															</div>
															<div class="col-xs-12 col-sm-2 col-md-2 content-horacreacion pr-0 nonivelcliente" style="display:none;" id="divhorac">
																<div class="form-group label-floating">
																	<label class="control-label">Hora Creación</label>
																	<input type="text" name="horacreacion" id="horacreacion" class="form-control inc-edit text" disabled>
																</div>
															</div>
															<div class="col-xs-12 col-sm-4 col-md-4 content-fechasolicituddesde pr-0 nonivelcliente">
																<div class="form-group label-floating">
																	<label class="control-label">Fecha Solicitud Desde <span class="text-red">*</span></label>
																	<input type="text" name="fechasolicituddesde" id="fechasolicituddesde" class="form-control inc-edit text">
																</div>
															</div>
															<div class="col-xs-12 col-sm-4 col-md-4 content-fechasolicitudhasta pr-0 nonivelcliente">
																<div class="form-group label-floating">
																	<label class="control-label">Fecha Solicitud hasta <span class="text-red">*</span></label>
																	<input type="text" name="fechasolicitudhasta" id="fechasolicitudhasta" class="form-control inc-edit text">
																</div>
															</div>
														</div>
														<div class="col-xs-12 col-sm-12 col-md-12">
															<div class="form-group label-floating">
																<label class="control-label">Motivo por la cual requiere el auto  <span class="text-red">*</span></label>
																<textarea name="descripcion" id="descripcion" rows="7" class="form-control inc-edit text"></textarea>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-sitio nonivelcliente" >
															<div class="form-group label-floating">
																<label class="control-label">Destino <span class="text-red">*</span></label>
																<input type="text" name="destino" id="destino" class="form-control inc-edit text" autocomplete="on">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-serie nonivelcliente" style="display:none;" id="divvehiculos">
															<div class="form-group label-floating">
																<label class="control-label">Vehículos<span class="text-red">*</span></label>
																<select name="serie" id="serie" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-marca nonivelcliente" style="display:none;" id="divmarca">
															<div class="form-group label-floating">
																<label class="control-label">Marca</label>
																<input type="text" name="marca" id="marca" disabled class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-modelo nonivelcliente" style="display:none;" id="divmodelo">
															<div class="form-group label-floating">
																<label class="control-label">Modelo</label>
																<input type="text" id="modelo" name="modelo" disabled class="form-control inc-edit text">
															</div>
														</div> 
														<div class="col-xs-12 col-sm-4 col-md-4 box-estado nonivelcliente" id="divestado" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Estado</label>
																<select name="estado" id="estado" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-asignado nonivelcliente">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">Conductor <span class="text-red">*</span></label>
																<select name="asignadoa" id="asignadoa" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 content-fecharetiro box-fechahoracierre nonivelcliente" id="divfecharetiro" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Fecha y hora de retiro <span class="text-red">*</span></label>
																<input type="text" id="fecharetiro" name="fecharetiro" class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 content-fechacierre box-fechahoracierre nonivelcliente" id="divfechresol" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Fecha y Hora de devolución <span class="text-red">*</span></label>
																<input type="text" id="fecharesolucion" name="fecharesolucion" class="form-control inc-edit text">
																<input type="hidden" id="fechacierre" name="fechacierre" class="form-control inc-edit text">
																<input type="hidden" id="horacierre" name="horacierre" class="form-control inc-edit text">
															</div>
														</div>
                                                            <div class="col-xs-12 col-sm-4 col-md-4 inphorast box-kilometraje nonivelcliente" id="divkilometrajes" style="display: none;">
															<div class="form-group label-floating">
                                                                <div class="col-xs-12 col-sm-12 col-md-12 row p-0 m-0">
                                                                <div class="col-xs-12 col-sm-6 col-md-6">
																	<label class="control-label">Kilometraje Inicial <span class="text-red">*</span></label></label>
																</div><div class="col-xs-12 col-sm-6 col-md-6">
																	<label class="control-label">Kilometraje Final <span class="text-red">*</span></label></label>
																</div>   
                                                                    </div>
																<div class="col-xs-12 col-sm-12 col-md-12 row p-0 m-0">
																	<div class="col-xs-6 col-sm-6 col-md-6 pl-0">
																		<input type="number" placeholder="00" id="kilometrajeinicial" name="kilometrajeinicial" class="form-control inc-edit text">
																	</div>
																	<div class="col-xs-6 col-sm-6 col-md-6 pr-0">
																		<input type="number" placeholder="00" id="kilometrajefinal" name="kilometrajefinal" class="form-control inc-edit text">
																	</div>
																</div>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inphorast box-gasolina nonivelcliente" id="divgasolina" style="display: none;">
															<div class="form-group label-floating">
                                                                <div class="col-xs-12 col-sm-12 col-md-12 row p-0 m-0">
                                                                <div class="col-xs-12 col-sm-6 col-md-6">
																	<label class="control-label">Gasolina Inicial <span class="text-red">*</span></label></label>
																</div><div class="col-xs-12 col-sm-6 col-md-6">
																	<label class="control-label">Gasolina Final <span class="text-red">*</span></label></label>
																</div>   
                                                                    </div>
																<div class="col-xs-12 col-sm-12 col-md-12 row p-0 m-0">
																	<div class="col-xs-6 col-sm-6 col-md-6 pl-0">
																<select name="gasolinainicial" id="gasolinainicial" class="form-control inc-edit text">
																    <option value="">Sin asignar</option>
																    <option value="empty">Empty</option>
																	<option value="1/8">1/8</option>
																	<option value="2/8">2/8</option>
																	<option value="3/8">3/8</option>
																	<option value="4/8">4/8</option>
																	<option value="5/8">5/8</option>
																	<option value="6/8">6/8</option>
																	<option value="7/8">7/8</option>
																	<option value="full">Full</option>
																</select>
																	</div>
																	<div class="col-xs-6 col-sm-6 col-md-6 pr-0">
																<select name="gasolinafinal" id="gasolinafinal" class="form-control inc-edit text">
																    <option value="">Sin asignar</option>
																    <option value="empty">Empty</option>
																	<option value="1/8">1/8</option>
																	<option value="2/8">2/8</option>
																	<option value="3/8">3/8</option>
																	<option value="4/8">4/8</option>
																	<option value="5/8">5/8</option>
																	<option value="6/8">6/8</option>
																	<option value="7/8">7/8</option>
																	<option value="full">Full</option>
																</select>
																	</div>
																</div>
																<!--<input type="text" name="horastrabajadas" id="horastrabajadas" class="form-control inc-edit text">-->
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inphorast box-tarjetacontrol nonivelcliente" id="divtarjetacontrol" style="display: none;">
															<div class="form-group label-floating">
                                                                <div class="col-xs-12 col-sm-12 col-md-12 row p-0 m-0">
                                                                <div class="col-xs-12 col-sm-6 col-md-6">
																	<label class="control-label">Tarjeta de Gasolina <span class="text-red">*</span></label></label>
																</div><div class="col-xs-12 col-sm-6 col-md-6">
																	<label class="control-label">Control de Puerta <span class="text-red">*</span></label></label>
																</div>   
                                                                    </div>
																<div class="col-xs-12 col-sm-12 col-md-12 row p-0 m-0">
																	<div class="col-xs-6 col-sm-6 col-md-6 pl-0">
																<select name="tarjetagasolina" id="tarjetagasolina" class="form-control inc-edit text">
																    <option value="">Sin asignar</option>
																    <option value="si">Si</option>
																	<option value="no">No</option>
																</select>
																	</div>
																	<div class="col-xs-6 col-sm-6 col-md-6 pr-0">
																<select name="controlpuerta" id="controlpuerta" class="form-control inc-edit text">
																    <option value="">Sin asignar</option>
																    <option value="si">Si</option>
																	<option value="no">No</option>
																</select>
																	</div>
																</div>
															</div>
														</div>
														<br>
														<div class="clearfix"></div>
														<div class="col-xs-12 col-sm-12 col-md-12 inpresol nonivelcliente" id="divresol" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label"> Estado en el cual entrega el auto <span class="text-red">*</span></label>
																<textarea rows="7" cols="50" id="resolucion" name="resolucion" class="form-control inc-edit text"></textarea>
															</div>
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="tab-pane fade" id="comentarios">
										    <div class="card" > 
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
																<textarea rows="4" class="form-control inc-edit" name="comentario" id="comentario"></textarea>
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
																<table id="tablacomentario" class="display min-w850 ">
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
										</div>
										<div class="tab-pane fade" id="estados">
										    <div class="card" > 
												<form id="formestados">
													<div class="pt-4">
														<div class="tab-content">
															<div class="form-row">
																<div class="col-xs-12 col-sm-12 col-md-12">
																	<div class="cardtable" style="margin-top:0px">
																		<table id="tablaestados" class="table table-striped table-bordered" style="width:100%">
																			<thead>
																				<tr>
																					<th>Estado anterior</th>
																					<th>Estado actual</th>
																					<th>Fecha de cambio</th>
																					<th>Días transcurridos</th>
																				</tr>
																			</thead>
																		</table>
																	</div> 
																</div>
															</div>
														</div><!-- fin pt-4-->
													</div>
												</form>
											</div>
										</div>
										<div class="tab-pane fade" id="historial">
										    <div class="card" > 
												<form id="formhistorial">
													<div class="pt-4">
														<div class="tab-content">
															<div class="form-row">
																<div class="col-xs-12 col-sm-12 col-md-12 gridBit">
																	<div class="cardtable" style="margin-top:0px">
																		<table id="tablabitacora" class="table table-striped table-bordered" style="width:100%">
																			<thead>
																				<tr>
																					<th>Id</th>
																					<th>Usuario</th>
																					<th>Nombre</th>
																					<th>Fecha</th>
																					<th>Acción</th>
																				</tr>
																			</thead>
																		</table>
																	</div> 
																</div>
															</div>
														</div><!-- fin pt-4-->
													</div>
												</form>
											</div>
										</div>
											<button type="button" class="btn btn-primary btn-xs mr-2 " style="float:right" onclick="guardar();">
											<i class="fas fa-check-circle mr-2"></i>Guardar
											</button>
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
        ***********************************

		<?php include_once "flotas-adjuntoscom.php"; ?>-->
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
    <script src="./js/flota.js<?php autoVersiones(); ?>"></script>
	
</body>

</html>