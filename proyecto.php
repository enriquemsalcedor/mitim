<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Proyectos', 'Solicitud de interfaz de proyecto', 0, '');
	$nivel = $_SESSION["nivel"]; 
	permisosUrl();
	$tipo="";
	if(!isset($_GET['type'])){//NUEWVO

		if(($nivel == 1) || ($nivel == 2)){
			$tipo="new";
		}else{////NO Authorized
			header("Location: proyectos.php");
			exit;
		} 
	}else{
		$tipo = $_GET['type']; 
	}

?>

<!DOCTYPE html>
	<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $sistemaactual ?> | Proyecto</title>
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.css">
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
        <!--<link href="./css/style6.css" rel="stylesheet">-->
        <link rel="stylesheet" href="./css/ajustes.css<?php autoVersiones(); ?>">
		<style>
		/*PRUEBAS*/
		.accordion__body--text { padding: 0 }
		.accordion__item {margin-bottom: 0 }
		.rounded-lg{ border-radius: 0 !important; padding: 0.75rem 1.0625rem; } 
		.accordion__header--text{ color: #6a707e } 
		.accordion-primary .accordion__header.collapsed{ border-color: #d7dae3; border-top-width: 0; }
		#divcategorias>div{ border-top-width: thin !important; } 
		.accordion-primary .accordion__header{ background: #ebfaef; border-color: #d7dae3; color: #6a707e; box-shadow: none;} 
		.accordion-primary .accordion__header .fa-plus{ color: #6a707e; }
		.child{ background: #ffffff !important; padding-right: 8px; }
		.ui-widget-content.ui-autocomplete{
			background: #ffffff;
			border: 1px solid #d7dae3;
			max-width: 22.7%;
			padding: 10px 5px 10px 15px;
			border-radius: 0 0 0.2rem 0.2rem;
			padding: 0px;
			z-index: 1050;
		}
		.ui-widget-content.ui-autocomplete li{
			cursor: pointer;
			padding-bottom: 5px;
			padding: 5px;
		}
		.ui-widget-content.ui-autocomplete li:hover{
			background: #36c95f;
			color: #ffffff;
		}
		.accordion-primary .accordion__header .fa-plus {
            color: #6a707e;
            margin-top: 4px;
        }
        .modulos{ padding: 0.75rem 1.0625rem; }
        .custom-dropdown{ margin-bottom: 0; }
                /* Tamaño del scroll */
        .scrollaccordion{ height: 350px; overflow-y: scroll }
        .scrollaccordion::-webkit-scrollbar {
          width: 8px;
        }
        
         /* Estilos barra (thumb) de scroll */
        .scrollaccordion::-webkit-scrollbar-thumb {
          background: #ccc;
          border-radius: 4px;
        }
        
       .scrollaccordion::-webkit-scrollbar-thumb:active {
          background-color: #999999;
        }
        
       .scrollaccordion::-webkit-scrollbar-thumb:hover {
          background: #b3b3b3;
          box-shadow: 0 0 2px 1px rgba(0, 0, 0, 0.2);
        }
        
         /* Estilos track de scroll */
        .scrollaccordion::-webkit-scrollbar-track {
          background: #e1e1e1;
          border-radius: 4px;
        }
        
       .scrollaccordion::-webkit-scrollbar-track:hover, 
        .scrollaccordion::-webkit-scrollbar-track:active {
          background: #d4d4d4;
        }
        .headergreen{ background-color: #36C95F; } 
        .headergreen h5{ color: #ffffff; }
        .accordion__header{ cursor: pointer !important;}
		.color_etiqueta i{ color: #FFFFFF; }							  
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
                                Proyecto
                            </div>
                        </div>

                        <ul class="navbar-nav header-right"> 
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:;" role="button" data-toggle="dropdown">
                                    <div class="round-header"><?php echo $inombre; ?></div>
                                    <div class="header-info">
                                        <span><?php echo $nombre; ?></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="cerrar.php" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                            width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                            <polyline points="16 17 21 12 16 7"></polyline>
                                            <line x1="21" y1="12" x2="9" y2="12"></line>
                                        </svg>
                                        <span class="ml-2">Salir </span>
                                    </a>
                                </div>
                            </li>
                        </ul>
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
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<div class="email-left-box generic-width px-0 mb-5 font-w600 d-none">
									<!--<a class="list-group-item active modulos"><i class="fa fa-inbox align-middle mr-2"></i>Módulos</a>-->
									<div id="accordion_principal" class="accordion accordion-primary">
										<!--Categorías--> 									
										<div id="divcategorias" class="accordion accordion-primary">
											<div class="titulocategorias accordion__header rounded-lg">
												<span class="accordion__header--text font-w600 collapsed" data-toggle="collapse" data-target="#colcategorias" >Categorías</span>
												<span data-toggle='tooltip' data-placement='right' data-original-title='' class="badge badge-circle badge-danger text-white badge-xs cantidad_categorias"></span>
												<i class="fa fa-plus nueva_categoria float-right" aria-hidden="true"></i> 
												
											</div>
											<div id="colcategorias" class="collapse accordion__body" data-parent="#accordion_principal">
												<div class="accordion__body--text"> 
													<div id="accordion_categorias" class="scrollaccordion accordion accordion-primary"> 
													</div> 
												</div>
											</div> 
										</div> 
										<!--Ubicaciones--> 
										<div id="divubicaciones" class="accordion accordion-primary">
											<div class="tituloubicaciones accordion__header rounded-lg">
												<span class="accordion__header--text font-w600 collapsed" data-toggle="collapse" data-target="#colubicaciones">Ubicaciones</span> 
												<span data-toggle='tooltip' data-placement='right' data-original-title='' class="badge badge-circle badge-danger text-white badge-xs cantidad_ambientes"></span>
												<span class="fa fa-plus nueva_ubicacion float-right"></span>
											</div>
											<div id="colubicaciones" class="collapse accordion__body" data-parent="#accordion_principal">
												<div class="accordion__body--text"> 
													<div id="accordion_ambientes" class="scrollaccordion accordion accordion-primary" style="height: 350px; overflow-y: scroll"> 
													</div>
												</div>
											</div> 								
										</div>
										<!--Departamentos--> 
										<div id="divdepartamentos" class="accordion accordion-primary">
											<div class="titulodepartamentos accordion__header rounded-lg">
												<span class="accordion__header--text font-w600 collapsed" data-toggle="collapse" data-target="#coldepartamentos">Departamentos</span> 
												<span data-toggle='tooltip' data-placement='right' data-original-title='' class="badge badge-circle badge-danger text-white badge-xs cantidad_departamentos"></span>
												<span class="fa fa-plus nuevo_departamento float-right"></span>
											</div>
											<div id="coldepartamentos" class="collapse accordion__body" data-parent="#coldepartamentos">
												<div class="accordion__body--text"> 
													<div id="accordion_departamentos" class="scrollaccordion accordion accordion-primary" style="height: 350px; overflow-y: scroll"> 
													</div>
												</div>
											</div> 								
										</div>
										<!--Estados--> 
										<div id="divestados" class="accordion accordion-primary">
											<div class="tituloestados accordion__header rounded-lg">
												<span class="accordion__header--text font-w600 collapsed" data-toggle="collapse" data-target="#colestados">Estados</span> 
												<span data-toggle='tooltip' data-placement='right' data-original-title='' class="badge badge-circle badge-danger text-white badge-xs cantidad_estados"></span>
												<span class="fa fa-plus nuevo_estado float-right"></span>  
											</div>
											<div id="colestados" class="collapse accordion__body" data-parent="#colestados">
												<div class="accordion__body--text"> 
													<div id="accordion_estados" class="scrollaccordion accordion accordion-primary" style="height: 350px; overflow-y: scroll"> 
													</div>
												</div>
											</div> 								
										</div>
										<!--Prioridades--> 
										<div id="divprioridades" class="accordion accordion-primary">
											<div class="tituloprioridades accordion__header rounded-lg">
												<span class="accordion__header--text font-w600 collapsed" data-toggle="collapse" data-target="#colprioridades">Prioridades</span> 
												<span data-toggle='tooltip' data-placement='right' data-original-title='' class="badge badge-circle badge-danger text-white badge-xs cantidad_prioridades"></span>
												<span class="fa fa-plus nueva_prioridad float-right"></span>  
											</div>
											<div id="colprioridades" class="collapse accordion__body" data-parent="#colprioridades">
												<div class="accordion__body--text"> 
													<div id="accordion_prioridades" class="scrollaccordion accordion accordion-primary" style="height: 350px; overflow-y: scroll"> 
													</div>
												</div>
											</div> 								
										</div>
										<!--Etiquetas--> 
										<div id="divprioridades" class="accordion accordion-primary">
											<div class="tituloetiquetas accordion__header rounded-lg">
												<span class="accordion__header--text font-w600 collapsed" data-toggle="collapse" data-target="#coletiquetas">Etiquetas</span> 
												<span data-toggle='tooltip' data-placement='right' data-original-title='' class="badge badge-circle badge-danger text-white badge-xs cantidad_etiquetas"></span>
												<span class="fa fa-plus nueva_etiqueta float-right"></span>  
											</div>
											<div id="coletiquetas" class="collapse accordion__body" data-parent="#coletiquetas">
												<div class="accordion__body--text"> 
													<div id="accordion_etiquetas" class="scrollaccordion accordion accordion-primary" style="height: 350px; overflow-y: scroll"> 
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="ml-0 ml-sm-4 ml-sm-0"> 
									<div class="row">
										<div class="col-12">
										    <div class="card">
                                                <div class="card-body">
                    								<div class="default-tab">
                    									<ul class="nav nav-pills review-tab" role="tablist">
                    										<li class="nav-item active">
                    											<a class="nav-link active" data-toggle="tab" href="#proyecto">Datos del proyecto</a>
                    										</li>
                    										<li class="nav-item d-none">
                    											<a class="nav-link" data-toggle="tab" href="#contratos">Contratos</a>
                    										</li>
                    										<li class="nav-item d-none">
                    											<a class="nav-link" data-toggle="tab" href="#contactos">Contactos</a>
                    										</li>
                    									</ul>
                    									<div class="tab-content">										
										                    <div class="tab-pane fade show active" id="proyecto" role="tabpanel">
										                        <!--PANEL-->
										                        <div class="right-box-padding d-none capa_informacionproyectos">
                    												<div class="toolbar mb-4" role="toolbar"> 
                    													<div class="btn-group mb-1">
                    														<button type="button" class="btn btn-primary light fa fa-pen editar_proyecto" data-toggle="dropdown"><span class="caret m-l-5"></span>
                    														</button> 
                    													</div>
                    												</div>
                    												<div class="form-row">
                            											<div class="col-xs-12 col-sm-12 col-md-6 mt-4 d-none">
                            													<p class="txt_idclientes mb-0" style="display:none;"></p>
                     															<h5 class="text-success mb-0 mt-1">Cliente</h5>
                    															<p class="txt_nombrecliente mb-0"></p>
                            											</div> 
                            											<div class="col-xs-12 col-sm-12 col-md-6 pt-3">
                            											    <p class="txt_idclientes mb-0" style="display:none;"></p>
                     														<h5 class="text-success mb-0 mt-1">Nombre</h5>
                    														<p class="txt_nombreproyecto mb-0"></p>
                            											</div> 
                            											<hr>
                            											<div class="col-xs-12 col-sm-12 col-md-12 pt-3">
                            											    <h5 class="my-1 text-success">Descripción</h5>
                            											    <p class="txt_descripcionproyecto mb-2"> 
                    														</p>  
                    														<!--<hr>-->
                            											</div>  
                            											<div class="col-xs-12 col-sm-12 col-md-4 mt-4"> 
                     														<h5 class="text-success mb-0 mt-1">Estado</h5>
                    														<p class="txt_estado mb-0"></p>
                            											</div> 
                            										<!--	<div class="col-xs-12 col-sm-12 col-md-4 mt-4"> 
                     														<h5 class="text-success mb-0 mt-1">Tipo de servicio</h5>
                    														<p class="txt_tiposervicio mb-0"></p>
                            											</div>
                            											<div class="col-xs-12 col-sm-12 col-md-4 mt-4"> 
                     														<h5 class="text-success mb-0 mt-1">Horas contratadas</h5>
                    														<p class="txt_horascontratadas mb-0"></p>
                            											</div>
                            											<div class="col-xs-12 col-sm-12 col-md-4 mt-4"> 
                     														<h5 class="text-success mb-0 mt-1">Fecha de inicio</h5>
                    														<p class="txt_fechainicio mb-0"></p>
                            											</div>
                            											<div class="col-xs-12 col-sm-12 col-md-4 mt-4"> 
                     														<h5 class="text-success mb-0 mt-1">Fecha de finalización</h5>
                    														<p class="txt_fechafin mb-0"></p>
                            											</div>-->
                                									</div> 
                    											</div>  
                                								<div class="pt-4 capa_editarproyectos">
                                									<form id="form_proyectos" autocomplete="none">
                                										<div class="form-row">
                                											<div class="col-xs-12 col-sm-12 col-md-6 d-none">
                                												<div class="form-group label-floating"> 
                                													<label class="control-label" for="idclientes" >Cliente <span class="text-red">*</span></label>
                                														<select class="form-control text" name="idclientes" id="idclientes" ><select>
                                												</div>
                                											</div>  
                                											<div class="col-xs-12 col-sm-12 col-md-6">
                                												<div class="form-group label-floating"> 
                                												    <input type="hidden" id="id">
                                													<label class="control-label" for="nombre" >Nombre <span class="text-red">*</span></label>
                                														<input type="text" class="form-control text" name="nombre" id="nombre" autocomplete="off">
                                												</div>
                                											</div> 
                                											<div class="col-xs-12 col-sm-12 col-md-12">
                                												<div class="form-group label-floating"> 
                                													<label class="control-label" for="correlativo" >Descripción </label>
                                														<input type="text" class="form-control text" name="descripcion_proyecto" id="descripcion_proyecto" autocomplete="off">
                                												</div>
                                											</div>  
                                                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                												<div class="form-group label-floating"> 
                                													<label class="control-label" for="nombre" >Estado <span class="text-red">*</span></label>
                                													<select name="estado_proyecto" id="estado_proyecto">
                                													    <option value="Activo">Activo</option>
                                													    <option value="Inactivo">Inactivo</option>
                                													</select>
                                												</div>
                                											</div>
                                                                          <!--  <div class="col-xs-12 col-sm-4 col-md-4">
                                												<div class="form-group label-floating"> 
                                													<label class="control-label" for="nombre" >Tipo de servicio <span class="text-red">*</span></label>
                                													<select name="tipo_servicio" id="tipo_servicio" multiple>
                                													    <option value="correctivos">Correctivos</option>
                                													    <option value="preventivos">Preventivos</option>
                                													</select>
                                												</div>
                                											</div>
                                											<div class="col-xs-12 col-sm-4 col-md-4">
                                												<div class="form-group label-floating"> 
                                													<label class="control-label" for="nombre" >Horas contratadas <span class="text-red">*</span></label>
                                													<input type="number" class="form-control text" name="horas_contratadas" id="horas_contratadas" autocomplete="off">
                                												</div>
                                											</div> 
                                											<div class="col-xs-12 col-sm-4 col-md-4">
                                												<div class="form-group label-floating"> 
                                													<label class="control-label" for="nombre" >Fecha de inicio <span class="text-red">*</span></label>
                                													<input type="text" class="form-control text" name="fecha_inicio" id="fecha_inicio" autocomplete="off">
                                												</div>
                                											</div>
                                											<div class="col-xs-12 col-sm-4 col-md-4">
                                												<div class="form-group label-floating"> 
                                													<label class="control-label" for="nombre" >Fecha de finalización <span class="text-red">*</span></label>
                                													<input type="text" class="form-control text" name="fecha_fin" id="fecha_fin" autocomplete="off">
                                												</div>
                                											</div>-->    
                                										</div><!--form-row-->
                                									</form> 
                                		                          <button type="button" class="btn btn-danger btn-xs ml-2" id="cancelar" style="float:right;"><i class="fas fa-check-circle mr-2"></i>Cancelar</button>
                                		                          <button type="button" class="btn btn-primary btn-xs" id="guardar" style="float:right;"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
                                		                          
                                								</div><!--pt-4-->
										                    </div> 
										                    <div class="tab-pane fade" id="contratos" role="tabpanel">
										                        <div class="form-row mt-4">
																	<div class="col-xs-12 col-sm-4 col-md-4">
																		<div class="form-group label-floating"> 
																		    <input type="hidden" id="idcontratos">
																			<label class="control-label" for="nombre" >Tipo de servicio <span class="text-red">*</span></label>
																			<select name="tipo_servicio" id="tipo_servicio" multiple>
																				<option value="correctivos">Correctivos</option>
																				<option value="preventivos">Preventivos</option>
																			</select>
																		</div>
																	</div>
																	<div class="col-xs-12 col-sm-4 col-md-4">
																		<div class="form-group label-floating"> 
																			<label class="control-label" for="nombre" >Horas contratadas <span class="text-red">*</span></label>
																			<input type="number" class="form-control text" name="horas_contratadas" id="horas_contratadas" autocomplete="off">
																		</div>
																	</div>
										                            <div class="col-xs-12 col-sm-4 col-md-4">
                        												<div class="form-group label-floating"> 
                        													<label class="control-label" for="nombre" >Fecha de inicio <span class="text-red">*</span></label>
                        													<input type="text" class="form-control text" name="fecha_inicio" id="fecha_inicio" autocomplete="off">
                        												</div>
                        											</div>
                        											<div class="col-xs-12 col-sm-4 col-md-4">
                        												<div class="form-group label-floating"> 
                        													<label class="control-label" for="nombre" >Fecha de finalización <span class="text-red">*</span></label>
                        													<input type="text" class="form-control text" name="fecha_fin" id="fecha_fin" autocomplete="off">
                        												</div>
                        											</div>
		                											<div class="col-xs-12 col-sm-4 col-md-4">
																		<div class="form-group label-floating"> 
																		    <input type="hidden" id="idcontratos">
																			<label class="control-label" for="nombre" >Estado <span class="text-red">*</span></label>
																			<select name="estado_contrato" id="estado_contrato">
																				<option value="Inactivo">Inactivo</option>
																				<option value="Activo">Activo</option>
																			</select>
																		</div>
																	</div>
                        											<div class="form-group col-xs-12 col-sm-12 text-right"> 
                                                                        <button type="button" class="btn btn-warning  text-white btn-xs" style="float:right" onclick="limpiarContratos();"><i class="fas fa-eraser"></i> Limpiar</button>
                                                                        <button type="button" class="btn btn-primary btn-xs agregarcontrato" style="float:right; margin-right:10px" onclick="agregarContratos();"><i class="fas fa-check-circle mr-2"></i>Agregar</button>
                                                                    </div>
																	<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
            															<div class="table-responsive">
            																<table id="tablacontratos" class="display min-w850 ">
            																	<thead>
            																		<tr>
            																			<th>Id</th>
            																			<th>Acción</th>
            																			<th>Tipo de servicio</th>
																						<th>Hrs. contratadas</th>
            																			<th>Hrs. trabajadas</th>
            																			<th>Hrs. restantes</th>
            																			<th>Fecha inicio</th>
            																			<th>Fecha fin</th> 
																						<th>Estado</th>
            																		</tr>
            																	</thead>
            																	<tbody></tbody>
            																</table>
            															</div>  
            														</div>
										                        </div>      
										                    </div> 
										                    <div class="tab-pane fade" id="contactos" role="tabpanel">
										                        <div class="form-row mt-4">
										                            <div class="col-xs-12 col-sm-4 col-md-4">
                        												<div class="form-group label-floating"> 
                        												    <input type="hidden" id="idcontactos">
                        													<label class="control-label" for="nombre" >Nombre <span class="text-red">*</span></label>
                        													<input type="text" class="form-control text" name="nombre_contacto" id="nombre_contacto" autocomplete="off">
                        												</div>
                        											</div>
                        											<div class="col-xs-12 col-sm-4 col-md-4">
                        												<div class="form-group label-floating"> 
                        													<label class="control-label" for="tlfofic_contacto" >Teléfono de oficina <span class="text-red">*</span></label>
                        													<input type="text" class="form-control text" name="tlfofic_contacto" id="tlfofic_contacto" autocomplete="off">
                        												</div>
                        											</div>
                        											<div class="col-xs-12 col-sm-4 col-md-4">
                        												<div class="form-group label-floating"> 
                        													<label class="control-label" for="movil_contacto" >Movil <span class="text-red">*</span></label>
                        													<input type="text" class="form-control text" name="movil_contacto" id="movil_contacto" autocomplete="off">
                        												</div>
                        											</div>
                        											<div class="col-xs-12 col-sm-4 col-md-4">
                        												<div class="form-group label-floating"> 
                        													<label class="control-label" for="email_contacto" >Email <span class="text-red">*</span></label>
                        													<input type="email" class="form-control text" name="email_contacto" id="email_contacto" autocomplete="off">
                        												</div>
                        											</div>
                        											<div class="col-xs-12 col-sm-8 col-md-8"></div>
                        											<div class="form-group col-xs-12 col-sm-12 text-right"> 
                                                                        <button type="button" class="btn btn-warning  text-white btn-xs" style="float:right" onclick="limpiarContactos();"><i class="fas fa-eraser"></i> Limpiar</button>
                                                                        <button type="button" class="btn btn-primary btn-xs agregarcontacto" style="float:right; margin-right:10px" onclick="agregarContactos();"><i class="fas fa-check-circle mr-2"></i>Agregar</button>
                                                                    </div> 
                                                                    <!--<div class="text-right col-xs-12 col-sm-12 col-md-12 mt-3">
            															<button type="button" class="btn btn-warning  text-white btn-xs" style="float:right" onclick="limpiarComentario();"><i class="fas fa-eraser"></i> Limpiar</button>
            															<button type="button" class="btn btn-primary btn-xs" style="float:right; margin-right:10px" onclick="agregarComentario();"><i class="fas fa-check-circle mr-2"></i>Agregar</button> 
            															
            														</div>-->
            														<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
            															<div class="table-responsive">
            																<table id="tablacontactos" class="display min-w850 ">
            																	<thead>
            																		<tr>
            																			<th>Id</th>
            																			<th>Acción</th>
            																			<th>Nombre</th>
            																			<th>Tlf. Oficina</th>
            																			<th>Móvil</th>
            																			<th>Email</th> 
            																		</tr>
            																	</thead>
            																	<tbody></tbody>
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
						</div> 
					</div>
				</div>
			</div>
		</div>
		<?php include 'proyectomodales.php'; ?>
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
	    <script>var _tooltip = jQuery.fn.tooltip;</script>
		<script src="../repositorio-tema/assets/js/jquery-ui.min.js" type="text/javascript"></script>
	    <script>jQuery.fn.tooltip = _tooltip;</script>
	    <!-- Toastr -->
	    <script src="./vendor/toastr/js/toastr.min.js"></script>
	    <!-- momment js is must -->
	    <script src="./vendor/moment/moment.min.js"></script>
	    <script src="./vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	    
	    <!-- Datatable -->
	    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
	    
	    <!-- Select - Font -->
	    <script src="./js/select2/select2.min.js"></script>
	    <script src="./js/select2/select2-es.min.js"></script>
	    <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
	    
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
	    
	    <!-- Usuarios -->
	    <script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
	    <script src="./js/proyecto.js?<?php autoVersiones(); ?>"></script>
	    <!--sweetalert2-->

	</body>
</html>