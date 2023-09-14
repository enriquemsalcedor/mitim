<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nivel = $_SESSION['nivel'];
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Correctivos', 'Solicitud de interfaz de correctivo', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?>  | Correctivo</title>
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
	<!-- Quill -->
	<link href="./vendor/quill/quill.snow.css" rel="stylesheet">
	<link href="./vendor/quill/quill-ajustes.css" rel="stylesheet">
	<!-- Ajustes -->
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
											<a class="nav-link active" data-toggle="tab" href="#correctivo">Correctivo</a>
										</li>
										<li class="nav-item comentarios" style="display:none;" id="tabcom">
											<a class="nav-link" data-toggle="tab" href="#comentarios">Comentarios</a>
										</li>
										<li class="nav-item estados" style="display:none;" id="tabest">
											<a class="nav-link" data-toggle="tab" href="#estados">Estados</a>
										</li>
										<?php if($_SESSION['nivel'] != 4 && $_SESSION['nivel'] != 3 ):	?>
										<li class="nav-item historial" style="display:none;" id="tabhis">
											<a class="nav-link" data-toggle="tab" href="#historial">Historial</a>
										</li>
										<?php endif; ?>
										<li class="nav-item fusionado" style="display:none;" id="tabfus">
											<a class="nav-link" data-toggle="tab" href="#fusionados">Fusionados</a>
										</li>
										<?php if($_SESSION['nivel'] != 4 ):	?>
										<li class="nav-item encuesta" style="display:none;" id="tabenc">
											<a class="nav-link" data-toggle="tab" href="#encuesta">Encuesta</a>
										</li>
										<?php endif; ?>
										<!--Pestaña Costos habilitada solo para usuarios nivel Administrador / Soporte / Técnicos / Directores / Cliente SyM-->
										<?php if($_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 2 || $_SESSION['nivel'] == 3 || $_SESSION['nivel'] == 5 || $_SESSION['nivel'] == 7): ?>
										<li class="nav-item costos" style="display:none;" id="tabcost">
											<a class="nav-link" data-toggle="tab" href="#costos">Costos</a>
										</li>
										<?php endif; ?>
										<li class="nav-item facturacion" style="display:none;" id="tabfact">
											<a class="nav-link" data-toggle="tab" href="#facturacion">Facturación</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade show active" id="correctivo" role="tabpanel">
											<form id="form_incidentes" autocomplete="off">
												<input type="hidden" id="idpreventivos" name="idpreventivos" class="form-control inc-edit text">
												<div class="pt-4">												
													<div class="form-row">
														<div class="col-xs-12 col-sm-4 col-md-4 nonivelcliente">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">Operador</label>
																<select name="solicitante" id="solicitante" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-cc select2-multiple">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">C.C.</label>
																<select name="notificar" id="notificar" class="form-control inc-edit text" multiple></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-cc row pr-0 nonivelcliente">
															<div class="col-xs-12 col-sm-6 col-md-6 content-fechacreacion pr-0">
																<div class="form-group label-floating">
																	<label class="text-label">Fecha Cita  </label>
																	<input type="text" name="fechacreacion" id="fechacreacion" class="form-control inc-edit text">
																</div>
															</div>
															<div class="col-xs-12 col-sm-6 col-md-6 content-horacreacion pr-0 nonivelcliente">
																<div class="form-group label-floating">
																	<label class="control-label">Hora Cita</label>
																	<input type="text" name="horacreacion" id="horacreacion" class="form-control inc-edit text">
																</div>
															</div>
														</div>
														<div class="col-xs-12 col-sm-12 col-md-12">
															<div class="form-group label-floating">
																<label class="control-label">Titulo <span class="text-red">*</span></label>
																<input type="text" id="titulo" name="titulo" class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-12 col-md-12">
															<div class="form-group label-floating">
																<label class="control-label">Descripción <span class="text-red">*</span></label>
																<!-- <textarea name="descripcion" id="descripcion" rows="7" class="form-control inc-edit text"></textarea>  -->
																<div  name="descripcion" id="descripcion"></div> 
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4" style="display:none; nonivelcliente">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">Empresas <span class="text-red">*</span></label>
																<select name="idempresas" id="idempresas" class="form-control inc-edit text"></select>
															</div>
														</div>								
														<div class="col-xs-12 col-sm-4 col-md-4 box-clientes nonivelcliente"> 
															<div class="input-group align-items-center w-100">
                                                                <div class="form-group label-floating is-empty div_cliente">
                                                                    <label class="control-label">Clientes <span class="text-red">*</span></label>
                                                                    <select name="idclientes" id="idclientes" class="form-control inc-edit text"></select>
                                                                </div>
                                                                <div class="input-group-append ml-2 pt-2" id="agregar_nuevo_cliente" data-toggle="tooltip" title="Creación de nuevo cliente" style="display:none;">
                                                                    <button class="btn btn-primary rounded-circle" type="button" style="zoom:60%">
                                                                        <i class="fa fa-plus"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-proyectos nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Proyectos </label>
																<select name="idproyectos" id="idproyectos" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-categorias nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Categoría <span class="text-red">*</span></label>
																<select name="categoria" id="categoria" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-subcategorias nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Subcategoría</label>											
																<select name="subcategoria" id="subcategoria" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-sitio nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Ubicación</label>
																<select name="unidadejecutora" id="unidadejecutora" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-sitio nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Área</label>
																<select name="area" id="area" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-serie nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Activo</label>
																<select name="serie" id="serie" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-marca nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Marca</label>
																<input type="text" name="marca" id="marca" disabled class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-modelo nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Modelo</label>
																<input type="text" id="modelo" name="modelo" disabled class="form-control inc-edit text">
															</div>
														</div> 
														<div class="col-xs-12 col-sm-4 col-md-4 box-prioridad nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Prioridad <span class="text-red">*</span></label>											
																<select name="prioridad" id="prioridad" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 box-estado nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Estado <span class="text-red">*</span></label>
																<select name="estado" id="estado" class="form-control inc-edit text"></select>
															</div>
														</div>
														<?php if($nivel != 7): ?>
														<div class="col-xs-12 col-sm-4 col-md-4 box-departamentos nonivelcliente">
															<div class="form-group label-floating">
																<label class="control-label">Departamentos / Grupos <span class="text-red">*</span></label>
																<select name="iddepartamentos" id="iddepartamentos" class="form-control inc-edit text"></select>
															</div>
														</div>
														<?php endif; ?>
														<div class="col-xs-12 col-sm-4 col-md-4 box-asignado nonivelcliente">
															<div class="form-group label-floating selectsr selectcr2">
																<label class="control-label">Asignado a</label>
																<select name="asignadoa" id="asignadoa" class="form-control inc-edit text"></select>
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4">
															<div class="form-group label-floating">
																<label class="control-label">Contacto</label>
																<input type="text" name="contacto" id="contacto" class="form-control inc-edit text">
															</div>
														</div>	
														<div class="col-xs-12 col-sm-4 col-md-4 content-fechacierre box-fechahoracierre nonivelcliente" id="divfechresol" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Fecha y Hora de Resolución <span class="text-red">*</span></label>
																<input type="text" id="fecharesolucion" name="fecharesolucion" class="form-control inc-edit text">
																<input type="hidden" id="fechacierre" name="fechacierre" class="form-control inc-edit text">
																<input type="hidden" id="horacierre" name="horacierre" class="form-control inc-edit text">
																<input type="hidden" id="creadopor" name="creadopor" class="form-control inc-edit text">
																<input type="hidden" id="estadoant" name="estadoant" class="form-control inc-edit text">
																<input type="hidden" id="fecharesolucionant" name="fecharesolucionant" class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inprepse box-reporteservicio nonivelcliente" id="divrepserv" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Reporte de servicio</label>
																<input type="text" id="reporteservicio" name="reporteservicio" class="form-control inc-edit text">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inphorast box-horastrabajadas nonivelcliente" id="divhorastrab" style="display:none;">
															<div class="form-group label-floating">
																<div class="col-xs-12 col-sm-12 col-md-12">
																	<label class="control-label">Horas Trabajadas</label>
																</div>
																<div class="col-xs-12 col-sm-12 col-md-12 row p-0 m-0">
																	<div class="col-xs-6 col-sm-6 col-md-6 pl-0">
																		<input type="number" min="0" max="60" placeholder="00" id="horast" name="horast" class="form-control inc-edit text">
																	</div>
																	<div class="col-xs-6 col-sm-6 col-md-6 pr-0">
																		<input type="number" min="0" max="59" placeholder="00" id="minutost" name="minutost" class="form-control inc-edit text">
																	</div>
																</div>
																<!--<input type="text" name="horastrabajadas" id="horastrabajadas" class="form-control inc-edit text">-->
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 inpatencion box-atencion nonivelcliente" id="divaten" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label">Atención <span class="text-red">*</span></label>
																<select name="atencion" id="atencion" class="form-control inc-edit text">
																	<option value="remoto">Remoto</option>
																	<option value="ensitio">En Sitio</option>
																</select>
															</div>
														</div> 	
														<!--<div class="col-xs-12 col-sm-12 col-md-12"></div>-->							
														<div class="checkbox col-xs-12 mt-2 mb-2 col-sm-4 col-md-3 row nonivelcliente" style="clear: both;">
															<label class="col-sm-8 col-form-label text-red">Fuera de Servicio</label>
															<div class="col-sm-4">
																<label>
																	<input type="checkbox" id="fueraservicio" class="mt-2" name="fueraservicio" value="1"><span class="checkbox-material"></span>
																</label> 
															</div>
														</div>  
														<div class="col-xs-12 col-sm-4 content-fechacreacion nonivelcliente" style="display:none">
															<div class="form-group label-floating">
																<label class="control-label">Fuera de servicio desde</label>
																<input type="text" id="relleno" name="relleno" class="form-control inc-edit" style="width: 0px;height: 0px;margin: 0;padding: 0;" disabled="disabled">
																<input type="text" name="fechadesdefueraservicio" id="fechadesdefueraservicio" class="form-control inc-edit" disabled="disabled">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 content-fechacreacion nonivelcliente" style="display:none">
															<div class="form-group label-floating">
																<label class="control-label">Fuera de servicio hasta</label>
																<input type="text" id="relleno" name="relleno" class="form-control inc-edit" style="width: 0px;height: 0px;margin: 0;padding: 0;" disabled="disabled">
																<input type="text" name="fechafinfueraservicio" id="fechafinfueraservicio" class="form-control inc-edit" disabled="disabled">
															</div>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-3 inphorast nonivelcliente" style="display:none">
															<div class="form-group label-floating">
																<label class="control-label">Dias fuera de servicio</label>
																<input type="text" name="diasfueraservicio" id="diasfueraservicio" class="form-control inc-edit text" disabled="disabled">
															</div>
														</div>
														<br>
														<div class="clearfix"></div>
														<div class="col-xs-12 col-sm-12 col-md-12 inpresol nonivelcliente" id="divresol" style="display:none;">
															<div class="form-group label-floating">
																<label class="control-label"> Resolución <span class="text-red">*</span></label>
																<textarea rows="7" cols="50" id="resolucion" name="resolucion" class="form-control inc-edit text"></textarea>
															</div>
														</div>
														<?php if( $_SESSION['nivel'] == 1 || $_SESSION['nivel'] == 2 || $_SESSION['nivel'] == 3 ||
																$_SESSION['nivel'] == 5 || $_SESSION['nivel'] == 8 ): ?>
														<div class="col-md-12 mt-4">
															<div class="form-group label-floating">
																<label class="control-label"><i class="fa fa-tags"></i> Etiquetas</label>
															</div>
															<div class="etiquetas-lista">
															</div> 
															<input type="hidden" id="idetiquetas" name="idetiquetas" >															
														</div>
														<?php endif; ?>
														<?php if($_SESSION['nivel'] == 4): ?>
															<!-- SOLICITUDES DE SERVICIO -->
															<div class="col-xs-12 col-sm-12 col-md-12" id="adjuntonuevocliente">
																<label class="control-label">Adjuntos</label>
																<br>
																<!--<div id="elfinder-nuevo"></div>-->
																<input type="hidden" id="idincidentestempevidencias"/>
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
														<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
															<div class="table-responsive">
																<table id="tablaestados" class="display min-w850 ">
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
												</form>
											</div>
										</div>
										<div class="tab-pane fade" id="historial">
										    <div class="card" > 
												<form id="formhistorial">
													<div class="pt-4"> 
														<div class="col-xs-12 col-sm-12 col-md-12 mt-4 gridBit">
															<div class="table-responsive">
																<table id="tablabitacora" class="display min-w850 ">
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
												</form>
											</div>
										</div>
										<div class="tab-pane fade" id="fusionados">
										    <div class="card" > 
												<form id="formfusionados">
													<div class="pt-4">  
														<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
															<div class="table-responsive">
																<table id="tablafusionados" class="display min-w850 ">
																	<thead>
																		<tr>
																			<th>Id</th>
																			<th>Título</th>
																			<th>Descripción</th>
																			<th>Fecha Creación</th> 
																		</tr>
																	</thead>
																</table>
															</div> 
														</div>  
													</div>
												</form>
											</div>
										</div>
										<div class="tab-pane fade" id="encuesta">
										    <div class="card" > 
												<form id="formencuesta">
													<div class="pt-4">
														<div class="tab-content">
															<div class="form-row">
																<div class="col-xs-12">
																	<div id="resultadosEncuesta" style="margin-top: 15px; margin-bottom: 30px;"></div> 
																</div>
															</div>
														</div><!-- fin pt-4-->
													</div>
												</form>
											</div>
										</div> 
										<div class="tab-pane fade" id="costos">
										    <div class="card" > 
												<form id="formcostos">
													<div class="pt-4">
														<div class="tab-content">
															<div class="form-row">
																<div class="col-xs-6 col-sm-6 col-md-6"> 
																	<div class="form-group label-floating">
																	<label class="control-label">Descripción <span class="text-red">*	</span></label>
																	<input type="text" id="desccosto" name="desccosto" class="form-control inc-edit text">
																	</div>
																</div>
																<div class="col-xs-6 col-sm-6 col-md-6"> 
																	<div class="form-group label-floating">
																	<label class="control-label">Monto <span class="text-red">*	</span></label>
																	<input type="text" id="monto" name="monto" class="form-control inc-edit text">
																	</div>
																</div> 
															</div>
															<div class="text-right col-xs-12 col-sm-12 col-md-12 mt-3">
																<button type="button" class="btn btn-warning  text-white btn-xs" style="float:right" onclick="limpiarCosto();"><i class="fas fa-eraser"></i> Limpiar</button>
																<button type="button" class="btn btn-primary btn-xs" style="float:right; margin-right:10px" onclick="agregarCosto();"><i class="fas fa-check-circle mr-2"></i>Agregar</button> 
																
															</div>
															<div class="pt-4">
																<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
																	<div class="table-responsive">
																		<table id="tablacostos" class="display min-w850 ">
																			<thead>
																				<tr>
																					<th>Id</th>
																					<th>Acción</th>
																					<th>Detalle</th>
																					<th>Monto</th>
																					<th>Usuario</th>
																					<th>Fecha</th>
																					<th>Adjunto</th>
																				</tr>
																			</thead>
																			<tbody>
																				<tfoot>
																					<tr>
																						<th colspan="6" style="text-align:right">Total:</th>
																						<th></th>
																					</tr>
																				</tfoot>
																			</tbody>
																		</table>
																	</div>  
																</div>
															</div>
														</div><!-- fin pt-4-->
													</div>
												</form>
											</div>
										</div>
										<div class="tab-pane fade" id="facturacion">
										    <div class="card" > 
												<form id="formfacturacion">
													<div class="pt-4">
														<div class="tab-content">
															<div class="form-row">
																<div class="col-xs-6 col-sm-6 col-md-6"> 
																	<div class="form-group label-floating">
																	<label class="control-label">Descripción <span class="text-red">*	</span></label>
																	<input type="text" id="descripcion_facturacion" name="descripcion_facturacion" class="form-control inc-edit text">
																	</div>
																</div>
																<div class="col-xs-6 col-sm-6 col-md-6"> 
																	<div class="form-group label-floating">
																	<label class="control-label">Monto <span class="text-red">*	</span></label>
																	<input type="text" id="monto_facturacion" name="monto_facturacion" class="form-control inc-edit text">
																	</div>
																</div> 
															</div>
															<div class="text-right col-xs-12 col-sm-12 col-md-12 mt-3">
																<button type="button" class="btn btn-warning  text-white btn-xs" style="float:right" onclick="limpiar_item_facturacion();"><i class="fas fa-eraser"></i> Limpiar</button>
																<button type="button" class="btn btn-primary btn-xs" style="float:right; margin-right:10px" onclick="agregar_item_facturacion();"><i class="fas fa-check-circle mr-2"></i>Agregar</button> 
																
															</div>
															<div class="pt-4">
																<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
																	<div class="table-responsive">
																		<table id="tablafacturacion" class="display min-w850 ">
																			<thead>
																				<tr>
																					<th>Id</th>
																					<th>Acción</th>
																					<th>Detalle</th>
																					<th>Monto</th>
																					<th>Usuario</th>
																					<th>Fecha</th> 
																				</tr>
																			</thead>
																			<tbody>
																				<tfoot>
																					<tr>
																						<th colspan="5" style="text-align:right">Total:</th>
																						<th></th>
																					</tr>
																				</tfoot>
																			</tbody>
																		</table>
																	</div>  
																</div>
															</div>
														</div><!-- fin pt-4-->
													</div>
												</form>
											</div>
										</div> 
										<?php if($_SESSION['nivel'] != 4): ?>		  
											<button type="button" class="btn btn-warning  text-white btn-xs" style="display:none;float:right;" id="revertir-fusion-incidente">Revertir Fusión</button>
										<?php endif; ?>
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
        ***********************************-->

		<?php include_once "correctivos-adjuntoscom.php"; ?>
		<?php include_once "correctivos-adjuntoscost.php"; ?>
		<?php include_once "cliente-creacionrapida.php"; ?>
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
	   <script src="./vendor/quill/quill.js"></script>
    <!-- registro -->
    <script src="./js/funciones1.js<?php autoVersiones(); ?>"></script>
    <script src="./js/correctivo.js<?php autoVersiones(); ?>"></script>
	<script src="./js/cliente-creacionrapida.js<?php autoVersiones(); ?>"></script>
	
</body>

</html>