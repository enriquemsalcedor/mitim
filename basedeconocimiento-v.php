<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nivel = $_SESSION['nivel'];
	$nombre = $_SESSION['nombreUsu'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], '', 'Base de conocimiento', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?>  | Base de conocimiento</title>
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
                                Base de conocimiento
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

						<?php if(isset($_GET['id'])):	?>
							<a class="btn btn-primary btn-xs" href="incidente?id=<?php echo $_GET['id'];?>">
							    <i class="fas fa-edit" aria-hidden="true"></i> 
							    <span class="ml-2">Editar</span>
							</a>
						<?php endif; ?>

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
										<li class="nav-item active">
											<a class="nav-link active" data-toggle="tab" href="#correctivo">Corretivo</a>
										</li>
										<li class="nav-item" style="display:none;" id="tabcom">
											<a class="nav-link" data-toggle="tab" href="#comentarios">Comentarios</a>
										</li>
										<?php if($_SESSION['nivel'] != 4):	?>
										<li class="nav-item" style="display:none;" id="tabhis">
											<a class="nav-link" data-toggle="tab" href="#historial">Historial</a>
										</li>
										<?php endif; ?>
									</ul>
									<div class="tab-content">										
										<div class="tab-pane fade show active" id="correctivo" role="tabpanel">
											<form id="form_incidentes" autocomplete="off">
												<div class="pt-4">												
													<div class="form-row">
														<div class="col-xs-12 col-sm-4 col-md-4 nonivelcliente">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">Solicitante</label>
																<select name="solicitante" id="solicitante" class="form-control inc-edit text" disabled></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-cc nonivelcliente">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">C.C.</label>
																<select name="notificar" id="notificar" class="form-control inc-edit text" multiple disabled></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-cc row pr-0 nonivelcliente">
															<div class="col-xs-12 col-sm-6 col-md-6 content-fechacreacion pr-0">
																<div class="form-group label-floating">
																	<label class="text-label">Fecha Creación  </label>
																	<input disabled type="text" name="fechacreacion" id="fechacreacion" class="form-control inc-edit text">
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-6 content-horacreacion pr-0 nonivelcliente">
																<div class="form-group label-floating">
																	<label class="control-label">Hora Creación</label>
																	<input disabled type="text" name="horacreacion" id="horacreacion" class="form-control inc-edit text">
																</div>
															</div>
														</div>
														<div class="col-xs-12 col-sm-12 col-md-12">
															<div class="form-group label-floating">
																<label class="control-label">Título <span class="text-red">*</span></label>
																<input disabled type="text" id="titulo" name="titulo" class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-12 col-md-12">
															<div class="form-group label-floating">
																<label class="control-label">Descripción</label>
																<textarea disabled name="descripcion" id="descripcion" rows="7" class="form-control inc-edit text"></textarea>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4" style="display:none; nonivelcliente">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">Empresas <span class="text-red">*</span></label>
																<select disabled name="idempresas" id="idempresas" class="form-control inc-edit text"></select>
															</div>
														</div>								
														<div class="col-xs-12 col-sm-4 col-md-4 box-clientes nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Clientes <span class="text-red">*</span></label>
																<select disabled name="idclientes" id="idclientes" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-proyectos nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Proyectos <span class="text-red">*</span></label>
																<select disabled name="idproyectos" id="idproyectos" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-categorias nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Categoría <span class="text-red">*</span></label>
																<select disabled name="categoria" id="categoria" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-subcategorias nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Subcategoría</label>											
																<select disabled name="subcategoria" id="subcategoria" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-sitio nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Ubicación</label>
																<select  disabled name="unidadejecutora" id="unidadejecutora" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-serie nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Activo</label>
																<select disabled name="serie" id="serie" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-marca nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Marca</label>
																<input  disabled type="text" name="marca" id="marca" disabled class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-modelo nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Modelo</label>
																<input disabled type="text" id="modelo" name="modelo" disabled class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-prioridad nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Prioridad <span class="text-red">*</span></label>											
																<select disabled name="prioridad" id="prioridad" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-estado nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Estado</label>
																<select  disabled name="estado" id="estado" class="form-control inc-edit text"></select>
															</div>
														</div>
														<?php if($nivel != 7): ?>
														<div class="col-xs-12 col-sm-4 col-md-4 box-departamentos nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Departamentos / Grupos <span class="text-red">*</span></label>
																<select disabled name="iddepartamentos" id="iddepartamentos" class="form-control inc-edit text"></select>
															</div>
														</div>
														<?php endif; ?>
														<div class="col-xs-12 col-sm-4 col-md-4 box-asignado nonivelcliente">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">Asignado a</label>
																<select disabled name="asignadoa" id="asignadoa" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Contacto</label>
																<input disabled type="text" name="contacto" id="contacto" class="form-control inc-edit text">
															</div>
														</div>	
														<div class="col-xs-12 col-sm-4 col-md-4 content-fechacierre box-fechahoracierre nonivelcliente" id="divfechresol" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Fecha y Hora de Resolución <span class="text-red">*</span></label>
																<input disabled type="text" id="fecharesolucion" name="fecharesolucion" class="form-control inc-edit text">
																<input disabled type="hidden" id="fechacierre" name="fechacierre" class="form-control inc-edit text">
																<input disabled type="hidden" id="horacierre" name="horacierre" class="form-control inc-edit text">
																<input disabled type="hidden" id="creadopor" name="creadopor" class="form-control inc-edit text">
																<input disabled type="hidden" id="estadoant" name="estadoant" class="form-control inc-edit text">
																<input disabled type="hidden" id="fecharesolucionant" name="fecharesolucionant" class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inprepse box-reporteservicio nonivelcliente" id="divrepserv" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Reporte de servicio</label>
																<input disabled type="text" id="reporteservicio" name="reporteservicio" class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inphorast box-horastrabajadas nonivelcliente" id="divhorastrab" style="display:none;">
															<div class="form-group label-floating">
																<div class="col-xs-12 col-sm-12 col-md-12">
																	<label class="control-label">Horas Trabajadas</label>
																</div>
																<div class="col-xs-12 col-sm-12 col-md-12 row p-0 m-0">
																	<div class="col-xs-6 col-sm-6 col-md-6 pl-0">
																		<input disabled type="number" min="0" max="60" placeholder="00" id="horast" name="horast" class="form-control inc-edit text">
																	</div>
																	<div class="col-xs-6 col-sm-6 col-md-6 pr-0">
																		<input disabled type="number" min="0" max="59" placeholder="00" id="minutost" name="minutost" class="form-control inc-edit text">
																	</div>
																</div>
																<!--<input type="text" name="horastrabajadas" id="horastrabajadas" class="form-control inc-edit text">-->
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inpatencion box-atencion nonivelcliente" id="divaten" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Atención <span class="text-red">*</span></label>
																<select disabled name="atencion" id="atencion" class="form-control inc-edit text">
																	<option value="remoto">Remoto</option>
																	<option value="ensitio">En Sitio</option>
																</select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 nonivelcliente">
															<div class="form-group label-floating is-empty">
																<label class="text-label" for="ingresos">Costo</label>
																<input disabled type="text" class="form-control inc-edit text" name="ingresos" id="ingresos" autocomplete="off"> 
															</div>
														</div>	
														<div class="col-xs-12 col-sm-12 col-md-12"></div>							
														<div class="checkbox col-xs-12 mt-2 mb-2 col-sm-4 col-md-3 row nonivelcliente" style="clear: both;display:none;">
															<label class="col-sm-8 col-form-label text-red">Fuera de Servicio</label>
															<div class="col-sm-4">
																<label>
																	<input disabled type="checkbox" id="fueraservicio" name="fueraservicio" value="1"><span class="checkbox-material"></span>
																</label> 
															</div>
														</div>  
														<div class="col-xs-12 col-sm-4 content-fechacreacion nonivelcliente" style="display:none">
															<div class="form-group label-floating">
																<label class="control-label">Fuera de servicio desde</label>
																<input disabled type="text" id="relleno" name="relleno" class="form-control inc-edit" style="width: 0px;height: 0px;margin: 0;padding: 0;" disabled="disabled">
																<input disabled type="text" name="fechadesdefueraservicio" id="fechadesdefueraservicio" class="form-control inc-edit" disabled="disabled">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 content-fechacreacion nonivelcliente" style="display:none">
															<div class="form-group label-floating">
																<label class="control-label">Fuera de servicio hasta</label>
																<input disabled type="text" id="relleno" name="relleno" class="form-control inc-edit" style="width: 0px;height: 0px;margin: 0;padding: 0;" disabled="disabled">
																<input disabled type="text" name="fechafinfueraservicio" id="fechafinfueraservicio" class="form-control inc-edit" disabled="disabled">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-3 inphorast nonivelcliente" style="display:none">
															<div class="form-group label-floating">
																<label class="control-label">Dias fuera de servicio</label>
																<input disabled type="text" name="diasfueraservicio" id="diasfueraservicio" class="form-control inc-edit text" disabled="disabled">
															</div>
														</div>
														<br>
														<div class="clearfix"></div>
														<div class="col-xs-12 col-sm-12 col-md-12 inpresol nonivelcliente" id="divresol" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label"> Resolución <span class="text-red">*</span></label>
																<textarea disabled rows="7" cols="50" id="resolucion" name="resolucion" class="form-control inc-edit text"></textarea>
															</div>
														</div>
														<?php if($_SESSION['nivel'] == 4): ?>
															<!-- SOLICIUDES DE SERVICIO -->
															<div class="col-xs-12 col-sm-12 col-md-12" id="adjuntonuevocliente">
																<label class="control-label">Adjuntos</label>
																<br>
																<!--<div id="elfinder-nuevo"></div>-->
																<input type="hidden" disabled id="idincidentestempevidencias"/>
																<div class="card-content">
																	<iframe width="100%" height="368" src="filegator/incidentestemp.php" frameborder="0" allowfullscreen id="fevidenciastemp"></iframe>
																</div>
																<br>
															</div>
														<?php endif; ?>
													</div>
												</div>
											</form>
										</div>
										<div class="tab-pane fade" id="comentarios">
										    <div class="card" > 
											<form id="formcomentarios">
													<div class="pt-4">
														<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
															<div class="table-responsive">
																<table id="tablacomentario" class="mdl-data-table display nowrap table-striped" style="width:100%">
																	<thead>
																		<tr>
                                                                            <th>Acción</th>		
																			<th>Id</th>
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

		<?php include_once "correctivos-adjuntoscom.php"; ?>
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
    <script src="./js/basedeconocimiento-v.js<?php autoVersiones(); ?>"></script>
	
</body>

</html>