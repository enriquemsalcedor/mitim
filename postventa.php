<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nivel = $_SESSION['nivel'];
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Postventas', 'Solicitud de interfaz de postventa', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?>  | Postventa</title>
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
	<!--<link href="./css/style6.css" rel="stylesheet">
	<link href="./css/ajustes1.css" rel="stylesheet">--> 
	<link rel="stylesheet" href="./css/ajustes.css<?php autoVersiones(); ?>">
	<script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                    <div class="col-xl-12 onlymed" id="divnombreregistro" style="display:none;">
						<div class="bg-info" style="border-radius: 0.2rem;">
							<h4 class="p-2 m-0 text-right text-white" id="nombreregistro"> </h4>
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<div class="default-tab">
									<ul class="nav nav-pills review-tab" role="tablist">
										<li class="nav-item active nav-general navpost">
											<a class="nav-link active" data-toggle="tab" href="#boxact" role="tablist" aria-expanded="true">
												Visita
											</a>
										</li>
										<li class="nav-item navcom" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxcom" role="tablist">
												Compromisos
											</a>
										</li>
										<li class="nav-item navhist" style="display:none;">
											<a class="nav-link" data-toggle="tab" href="#boxhist" role="tablist">
												Historial
											</a>
										</li>  
									</ul>
									<div class="tab-content tab-activos-modal">										
										<div class="tab-pane fade show active tab-pane-general" id="boxact" role="tabpanel">
											<form id="form_postventa" autocomplete="off">
												<div class="pt-4">												
													<div class="form-row">
														<div class="col-xs-12 col-sm-2 col-md-2 eseditar content-incidente" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label" style="font-weight: bold">Visita</label>
																<input type="text" id="incidente" name="incidente" disabled="disabled" class="form-control text" style="font-size: 18px;font-weight: bold;color: #000000;">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating select2">
																<label class="control-label">Solicitante</label>
																<select name="solicitante" id="solicitante" class="form-control text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-cc">
															<div class="form-group label-floating select2">
																<label class="control-label">C.C.</label>
																<select name="notificar" id="notificar" class="form-control" multiple></select>
															</div>
														</div> 
														<div class="col-xs-12 col-sm-4 col-md-4 box-cc row pr-0">
															<div class="col-xs-12 col-sm-6 col-md-6 content-fechacreacion pr-0">
																<div class="form-group label-floating">
																	<label class="text-label">Fecha Creación</label> 
																	<input type="text" name="fechacreacion" id="fechacreacion" class="form-control text">
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-6 content-horacreacion pr-0">
																<div class="form-group label-floating">
																	<label class="text-label">Hora Creación</label>
																	<input type="text" name="horacreacion" id="horacreacion" class="form-control text">
																</div> 
															</div> 
														</div> 
														<div class="col-xs-12" id="fusion" style="display:none;" >
															<div class="form-group label-floating">
																<input type="hidden" id="idincidente" value="">
																<label class="control-label">Fusionado con </label>
																<input type="text" name="fusionado" id="fusionado" class="form-control" disabled>
															</div>
														</div>
														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="form-group label-floating">
																<label class="control-label">Título <span class="text-red">*</span></label>
																<input type="text" id="titulo" name="titulo" class="form-control">
															</div>
														</div>
														<div class="col-md-12 col-sm-12 col-xs-12">
															<div class="form-group label-floating">
																<label class="control-label">Objetivo de la visita</label>
																<textarea name="descripcion" id="descripcion" class="form-control"></textarea>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-3" style="display:none;">
															<div class="form-group label-floating select2">
																<label class="control-label">Empresas <span class="text-red">*</span></label>
																<select name="idempresas" id="idempresas" class="form-control"></select>
															</div>
														</div> 
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Clientes <span class="text-red">*</span></label>
																<select name="idclientes" id="idclientes" class="form-control"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Proyectos <span class="text-red">*</span></label>
																<select name="idproyectos" id="idproyectos" class="form-control"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Categoría <span class="text-red">*</span></label>
																<select name="idcategorias" id="idcategorias" class="form-control"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Subcategoría</label>											
																<select name="idsubcategorias" id="idsubcategorias" class="form-control"></select>
															</div>
														</div> 
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Ubicación <span class="text-red">*</span></label>
																<select name="idambientes" id="idambientes" class="form-control"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4"> 
															<div class="form-group label-floating">
																<label class="control-label">Prioridad</label>											
																<select name="idprioridades" id="idprioridades" class="form-control"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Estado</label>
																<select name="idestados" id="idestados" class="form-control"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Departamentos / Grupos <span class="text-red">*</span></label>
																<select name="iddepartamentos" id="iddepartamentos" class="form-control"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating select2">
																<label class="control-label">Asignado a</label>
																<select name="asignadoa" id="asignadoa" class="form-control"></select>
															</div>
														</div> 
														<div class="col-xs-12 col-sm-4 col-md-4 content-fechacierre eseditar" style="display:none;">
															<div class="form-group label-floating">
																<label class="text-label">Fecha y Hora de Resolución <span class="text-red">*</span></label>
																<input type="text" id="fecharesolucion" name="fecharesolucion" class="form-control text">
																<input type="hidden" id="fechacierre" name="fechacierre" class="form-control">
																<input type="hidden" id="horacierre" name="horacierre" class="form-control">
																<input type="hidden" id="creadopor" name="creadopor" class="form-control">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inprepse eseditar" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Reporte de servicio</label>
																<input type="text" id="reporteservicio" name="reporteservicio" class="form-control">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inphorast eseditar" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Horas Trabajadas</label>
																<input type="text" name="horastrabajadas" id="horastrabajadas" class="form-control">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="text-label">Fecha y hora real</label> 
																<input type="text" id="fechareal" name="fechareal" class="form-control text">
															</div>
														</div>
														<div class="col-md-12 col-sm-12 col-xs-12 inpresol eseditar" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label"> Resolución <span class="text-red">*</span></label>
																<textarea id="resolucion" name="resolucion" class="form-control"></textarea>
															</div>
														</div>
													</div> 												
													<div class="clearfix"></div> 
														<button type="button" class="btn btn-primary btn-xs" style="float:right;" id="guardar-activo" onClick="guardar();"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
												</div>
											</form>
										</div>
										<div class="tab-pane fade" id="boxcom">
											<div class="pt-4">
												<div class="form-row mb-5 mt-2"> 
													<div class="col-xs-12 col-sm-12 col-md-12"> 
														<h5 class="col-form-label text-success">Compromisos</h5> 
														<?php if($_SESSION['nivel'] != 4): ?>
															<input type="radio" name="visibilidad" id="visibilidad1" value="Público" checked>
															<label class="text-label" for="visibilidad1">Público</label> 
															<input type="radio" name="visibilidad" id="visibilidad2" value="Privado">
															<label class="text-label" for="visibilidad2">Privado</label> 
														<?php endif; ?> 
													</div>
													<div class="col-xs-12 col-sm-12 col-md-12">
														<label class="text-label" for="comentario">Nuevo compromiso</label>
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
														<table id="tablacomentario" class="display min-w850 ">
															<thead>
																<tr>
																	<th>Id</th>
																	<th>Acción</th>
																	<th>Compromiso</th>
																	<th>Usuario</th>
																	<th>Visibilidad</th>
																	<th>Fecha</th>
																	<th>Adjunto</th>
																	<th>Estado</th>
																	<th>Resolución</th>
																</tr>
															</thead>
															<tbody></tbody>
														</table>
													</div>  
												</div>
											</div>
											<!--<div class="pt-4"> 
												<div class="tab-content">
													<div class="form-row">
														<div class="col-xs-12 col-sm-12 col-md-12 inpcoment">
															<h4>Compromisos</h4>
															<div class="row">
																<div class="form-group label-floating col-xs-9 col-sm-9 col-md-9">
																	<label class="control-label" for="comentario">Nuevo Compromiso</label>
																	<textarea rows="3" class="form-control" name="comentario" id="comentario"></textarea>
																</div>
																<div class="form-group label-floating col-xs-3 col-sm-3 col-md-3  text-center">
																	<?php
																		if($_SESSION['nivel'] != 4):
																	?>
																	<label class="control-label" for="visibilidad">Visibilidad</label>
																	<div class="row">
																		<div class="col-md-6 tex-center"> 
																			<input type="radio" name="visibilidad" id="visibilidad1" value="Público" checked>
																			<label for="visibilidad1">Publico</label>
																		</div>
																		<div class="col-md-6 tex-center"> 
																			<input type="radio" name="visibilidad" id="visibilidad2" value="Privado">
																			<label for="visibilidad2">Privado</label>
																		</div>
																	</div> 
																	<?php
																		endif;
																	?>
																	<div class="row">
																		<div class="col-md-6 tex-center">
																			<a href="#" id="btnAgregarComentario" class="btn btn-primary btn-xs" onclick="agregarComentario();"><i class="fas fa-check-circle mr-2"></i>Agregar</a>
																		</div>
																		<div class="col-md-6 tex-center">
																			<a href="#" id="btnLimpiarComentario" class="btn btn-warning  text-white btn-xs" onclick="limpiarComentario();"><i class="fas fa-eraser"></i>Limpiar</a>
																		</div>
																	</div> 
																</div>
															</div> 
														</div>
														<div class="col-xs-12 col-sm-12 col-md-12 gridComent">
															<div class="cardtable" style="margin-top:0px">
																<table id="tablacomentario" class="table table-striped table-bordered" style="width:100%">
																	<thead>
																		<tr>
																			<th>Id</th>
																			<th>Acciones</th>
																			<th>Compromiso</th>
																			<th>Usuario</th>
																			<th>Visibilidad</th>
																			<th>Fecha</th>
																			<th>Adjunto</th>
																			<th>Estado</th>
																			<th>Resolución</th>
																		</tr>
																	</thead>									
																</table>
															</div>
														</div>
													</div>
												</div>
											</div> -->
										</div>
										<div class="tab-pane fade" id="boxhist"> 
											<div class="pt-4"> 
												<div class="col-xs-12 col-sm-12 col-md-12 mt-4 gridBit">
													<!--<h4>Historial</h4>-->
													<div class="table-responsive">
														<table id="tablabitacora" class="display min-w850 ">
															<thead>
																<tr>
																	<th>Id</th>
																	<th>Usuario</th>
																	<th>Fecha</th>
																	<th>Acción</th>
																</tr>
															</thead>									
														</table>
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
			</div>
		</div>
		<!--**********************************
            Content body end
        ***********************************-->

		<?php include_once "postventas-adjuntoscom.php"; ?>
		<?php include_once "postventas-compromisos.php"; ?>
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
    <script src="./js/postventa.js<?php autoVersiones(); ?>"></script>
	
</body>

</html>