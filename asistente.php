<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Asistente de configuración de proyectos', 'Solicitud de interfaz de asistente de configuración de proyectos', 0, '');
	$nivel = $_SESSION["nivel"]; 
	permisosUrl();
	$tipo="";
	if(!isset($_GET['type'])){//NUEWVO

		if(($nivel == 1) || ($nivel == 2)){
			$tipo="new";
		}else{////NO Authorized
			header("Location: clientes.php");
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
        <title><?php echo $sistemaactual ?> | Asistente de configuración de proyectos</title>
        <!-- JQUERY UI -->
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.theme.css">
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
            <!-- Toastr -->
        <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
        <!--sweetalert2-->
        <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet"> 
        <link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">	

        <!--<link href="./css/style1.css" rel="stylesheet">-->
        <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
        <!--  Fonts and icons -->
        <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
        <!-- Ajustes -->
        <link href="./css/style6.css" rel="stylesheet">
        <link rel="stylesheet" href="./css/ajustes1.css<?php autoVersiones(); ?>">
        <!-- Form step -->
        <link href="./vendor/jquery-steps/css/jquery.steps.css" rel="stylesheet">
        <style>
            
            .wizard > .content{ background: #fff; min-height: max-content;  }
            .wizard > .content > .body { position: initial; }
            .wizard > .actions a, .wizard > .actions a:hover, .wizard > .actions a:active {
                 padding: 0.55em 2em; 
            } 
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
    			line-height: 2.3;  
    		} 
    		.ui-widget-content.ui-autocomplete li div{
    			padding-left: 3%;
    		}  
    		ui-widget-content.ui-autocomplete li:hover, .ui-state-active{
    		    border: 0px solid #36c95f !important;
    			background: #36c95f !important;
    			color: #ffffff !important;
    		}
    		.tit_items { margin-left: 31px; }
    		.nueva_ubicacion, .nuevo_departamento{ margin-left: 32%; }
    		.scrollaccordion { height: 350px; /*overflow-y: scroll;*/ }
    		.scrollaccordion::-webkit-scrollbar {
              width: 8px;
            }
            
              
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
            
             
            .scrollaccordion::-webkit-scrollbar-track {
              background: #e1e1e1;
              border-radius: 4px;
            }
            
           .scrollaccordion::-webkit-scrollbar-track:hover, 
            .scrollaccordion::-webkit-scrollbar-track:active {
              background: #d4d4d4;
            }
            .wizard > .content > .body{ width: 100%; }
            
            .wizard > .steps .current a, .wizard > .steps .current a:hover, .wizard > .steps .current a:active{
                color: #36C95F;
                font-weight: bold;
            } 
            .wizard .actions ul li.disabled a{
                background: #cccccc !important;
                border: 1px solid #cccccc;
            }
            .wizard > .steps > ul > li {
                width: 16%;
            }
            .btnconct{ position: absolute; float: right; } 
            .select2asist .select2-container{ width: 83% !important; }
            .select2asist2 .select2-container{ display: inline-block; }
			.tipocategoria{ display: inherit !important; }
            .selectmodulos .select2-container{ width: 88% !important; }
            .selectdepartamento .select2-container{ width: 92% !important;}
			.etiquetas .badge{ cursor: pointer; }
			.color_etiqueta i{ color: #FFFFFF;}						   
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
                                Asistente de configuración de proyectos
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
                            <!--
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link bell config-link" href="javascript:;">
                                    <i class="fas fa-cogs text-success"></i>
                                </a>
                            </li>
							-->
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
				<!-- row -->
                <div class="row">
                    <div class="col-xl-12 col-xxl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title"></h4>
                            </div>
                            <div class="card-body">
                                <form id="step-form-horizontal" class="step-form-horizontal">
                                    <div>
                                        <h4>Cliente</h4>
                                        <section class="capa_cliente">
                                            <div class="form-row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Información del cliente</h5>
                                                    </div>
                                                </div>
    											<div class="col-xs-12 col-sm-12 col-md-6">
    												<div class="form-group label-floating">
    													<input type="hidden" name="id" id="id" >
    													<label class="control-label" for="nombre" >Nombre <span class="text-red">*</span></label>
    														<input type="text" class="form-control text" name="nombre" id="nombre" autocomplete="off">
    												</div>
    											</div>
    											<div class="col-xs-12 col-sm-12 col-md-6">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="siglas" >Siglas </label>
    														<input type="text" class="form-control text" name="siglas" id="siglas" autocomplete="off">
    												</div>
    											</div>
    											<div class="col-xs-12 col-sm-12 col-md-6">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="direccion" >Dirección </label>
    														<input type="text" class="form-control text" name="direccion" id="direccion" autocomplete="off">
    												</div>
    											</div>
    											<div class="col-xs-12 col-sm-12 col-md-6">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="telefono" >Teléfono </label>
    														<input type="text" class="form-control text" name="telefono" id="telefono" autocomplete="off">
    												</div>
    											</div>
    											<!--<div class="col-xs-12 col-sm-12 col-md-6">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="contacto" >Contacto </label>
    														<input type="text" class="form-control text" name="contacto" id="contacto" autocomplete="off">
    												</div>
    											</div>
    											<div class="col-xs-12 col-sm-12 col-md-6">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="movil" >Movil </label>
    														<input type="text" class="form-control text" name="movil" id="movil" autocomplete="off">
    												</div>
    											</div> -->
    
    
    										</div><!--form-row-->
                                        </section>
                                        <h4>Proyecto</h4>
                                        <section class="capa_proyecto">
                                            <!--<div class="form-row">  -->
                                            <div class="form-row pb-3">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Información del proyecto</h5>
                                                    </div>
                                                </div>
    											<div class="col-xs-12 col-sm-12 col-md-12">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="nombre" >Nombre <span class="text-red">*</span></label>
    													<input type="hidden" name="idclientes" id="idclientes" >
    													<input type="hidden" name="idproyectos" id="idproyectos" >
    													<input type="text" class="form-control text" name="nombre_proyecto" id="nombre_proyecto" autocomplete="off">
    												</div>
    											</div> 
    											<div class="col-xs-12 col-sm-12 col-md-12">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="correlativo" >Descripción <span class="text-red">*</span></label>
														<input type="text" class="form-control text" name="descripcion_proyecto" id="descripcion_proyecto" autocomplete="off"> 
    												</div>
    											</div> 
    											<div class="col-xs-12 col-sm-4 col-md-4">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="nombre" >Estado <span class="text-red">*</span></label>
    													<select name="estado_proyecto" id="estado_proyecto">
    													    <option value="Activo">Activo</option>
    													    <option value="Inactivo">Inactivo</option>
    													</select>
    												</div>
    											</div>
                                                <div class="col-xs-12 col-sm-4 col-md-4">
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
    											</div>
    										</div>
    										<hr class="mt-2 mb-2">
    									    <div class="form-row">
    											<div class="col-xs-12 col-sm-12 col-md-12 mt-4">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Contactos</h5>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-4 col-md-3">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="nombre" >Nombre <span class="text-red">*</span></label>
    													<input type="text" class="form-control text" name="nombre_contacto" id="nombre_contacto" autocomplete="off">
    												</div>
    											</div>
    											<div class="col-xs-12 col-sm-4 col-md-3">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="tlfofic_contacto" >Teléfono de oficina <span class="text-red">*</span></label>
    													<input type="text" class="form-control text" name="tlfofic_contacto" id="tlfofic_contacto" autocomplete="off">
    												</div>
    											</div>
    											<div class="col-xs-12 col-sm-4 col-md-3">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="movil_contacto" >Movil <span class="text-red">*</span></label>
    													<input type="text" class="form-control text" name="movil_contacto" id="movil_contacto" autocomplete="off">
    												</div>
    											</div>
    											<div class="col-xs-12 col-sm-4 col-md-3" style="position: relative;">
    												<div class="form-group label-floating"> 
    													<label class="control-label" for="email_contacto" >Email <span class="text-red">*</span></label>
    													<input type="text" class="form-control text" name="email_contacto" id="email_contacto" autocomplete="off" style="display: inline-block; width: 96%;">
    													<button type="button" class="btn btn-primary mt-1 ml-2 px-2 py-1 btnconct guardarcontacto"><span class="fas fa-plus" data-toggle="tooltip" data-original-title="Agregar" data-placement="top" aria-hidden="true"></span></button>
    												</div>
    											</div> 
												<div class="row">
													<div class="col-xl-6">
														<div class="card-body">
															<div id="accordion_contactos" class="accordion accordion-left-indicator"> 
															</div>
														</div>
													</div>
												</div>
												
												<table class="table table-bordered verticle-middle table-responsive-sm">
													<thead class="bg-success-light">
														<tr>
															<th class="py-2 px-2">Contacto</th>
															<th class="py-2 px-2">Teléfono de oficina</th>
															<th class="py-2 px-2">Móvil</th>
															<th class="py-2 px-2">Email</th>
															<th class="py-2 px-2">Acción</th>
														</tr>
													</thead>
													<tbody id="libro-list"></tbody>            
												</table>
											</div>
    									<!--	</div>-->
                                        </section> 
                                        <h4>Categorías / Ubicaciones</h4>
                                        <section>   
                                            <div class="form-row pb-3">
                                                <div class="form-group col-xs-12 col-sm-12 m-0">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Categoría</h5>
                                                    </div>
													                                                </div> 
												<div class="form-group col-xs-6 col-sm-6">
    												<div class="form-group"> 
														<label class="control-label">Nombre <span class="text-red">*</span></label>
                                                        <input name="idcategorias" id="idcategorias" type="hidden">
														<input name="nombre_categoria" id="nombre_categoria" class="form-control text">
                                                    </div>
                                                </div>                                                
                                                <div class="form-group col-xs-6 col-sm-6 select2asist">
                                                    <div class="form-group">
														<label class="control-label tipocategoria">Módulos <span class="text-red">*</span></label>	
																												   
                                                        <select name="tipo_categoria" id="tipo_categoria" class="form-control text" multiple>
															<option value="Correctivo">Correctivo</option>
															<option value="Preventivo">Preventivo</option>
															<option value="Postventa">Postventa</option>
														</select>
                                                        <button type="button" class="btn btn-primary ml-2 px-2 py-1 guardarcategoria"><span class="fas fa-plus" data-toggle="tooltip" data-original-title="Agregar" data-placement="top" aria-hidden="true"></span></button> 
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="caja_categorias pl-0" style="display:none"> 
                                                <div class="card-body pl-0 pt-0">
                                                    <div id="accordion_categorias" class="accordion accordion-left-indicator form-row col-xl-12"> 
                                                    </div>
                                                </div>
                                            </div> 
                                            <hr class="mt-2 mb-2">
                                            <div class="form-row pt-3">
                                                <div class="form-group col-xs-12 col-sm-12 m-0">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Ubicación</h5>
                                                    </div>
                                                </div>
                                                <div class="form-group col-xs-6 col-sm-6">
                                					<div class="form-group">
                                						<label class="control-label">Nombre <span class="text-red">*</span></label>
                                						<input name="idambientes" id="idambientes" type="hidden">
                                						<input name="nombre_ambiente" id="nombre_ambiente" class="form-control text">
                                					</div>
                                				</div>
                                				<div class="form-group col-xs-6 col-sm-6 select2asist">
                                					<div class="form-group">
                                						<label class="control-label">Responsables </label>
                                						<select name="responsables" id="responsables" multiple class="form-control text">
                                						</select>
                                						<button type="button" class="btn btn-primary mt-1 ml-2 px-2 py-1 guardarubicacion" style="position: absolute;"><span class="fas fa-plus" data-toggle="tooltip" data-original-title="Agregar" data-placement="top" aria-hidden="true"></span></button>
                                					</div> 
                                				</div>  
                                			</div>
                                			<div class="caja_ambientes pl-0" style="display:none">
                                                <div class="card-body pl-0 pt-0">
                                                    <div id="accordion_ambientes" class="accordion accordion-left-indicator form-row col-xl-12"> 
                                                    </div>
                                                </div>
                                            </div>  
                                        </section>
                                        <h4>Estados / Departamentos</h4>
                                        <section>  
                                            <div class="form-row pb-3">
                                                <div class="form-group col-xs-12 col-sm-12 m-0">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Estado</h5>
                                                    </div>
                                                </div>
                                                <div class="form-group col-xs-4 col-sm-4 mb-0">
                                					<div class="form-group"> 
                                						<label class="control-label">Nombre <span class="text-red">*</span></label>
                                						<input name="idestados" id="idestados" type="hidden">
                                						<input name="nombre_estado" id="nombre_estado" class="form-control text" >
                                					</div>
                                				</div>
                                				<div class="form-group col-xs-4 col-sm-4 mb-0">
                                					<div class="form-group">
                                						<label class="control-label">Descripción <span class="text-red">*</span></label>
                                						<input name="descripcion_estado" id="descripcion_estado" class="form-control text">
                                						</select>
                                					</div>
                                				</div>
                                				<div class="form-group col-xs-4 col-sm-4 mb-0 select2asist2 selectmodulos" style="position: relative;">
                                					<div class="form-group">
                                						<label class="control-label">Módulos <span class="text-red">*</span></label>
                                						<select name="tipo_estados" id="tipo_estados" class="form-control" multiple>
                                							<option value="Correctivo">Correctivo</option>
                                							<option value="Preventivo">Preventivo</option>
                                							<option value="Postventa">Postventa</option>
                                							<option value="Laboratorio">Laboratorio</option>
                                							<option value="Flota">Flotas</option>
                                						</select>
                                						<button type="button" class="btn btn-primary mt-1 ml-2 px-2 py-1 guardarestado" style="position:absolute"><span class="fas fa-plus" data-toggle="tooltip" data-original-title="Agregar" data-placement="top" aria-hidden="true"></span></button>
                                					</div>
                                				</div>     
                                            </div> 
                                            <div class="caja_estados pl-0"> 
                                                <div class="card-body pl-0 pt-0">
                                                    <div id="accordion_estados" class="accordion accordion-left-indicator form-row col-xl-12"> 
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mt-2 mb-2">
                                            <div class="form-row pt-3">
                                                <div class="form-group col-xs-12 col-sm-12 m-0">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Departamento</h5>
                                                    </div>
                                                </div>
                                                <div class="form-group col-xs-6 col-sm-6 mb-0">
                                					<div class="form-group"> 
                                						<label class="control-label">Nombre <span class="text-red">*</span></label>
                                						<input name="iddepartamentos" id="iddepartamentos" type="hidden">
                                						<input name="nombre_departamento" id="nombre_departamento" class="form-control text">
                                					</div>
                                				</div>
                                				<div class="form-group col-xs-6 col-sm-6 mb-0 select2asist2 selectdepartamento" style="position: relative;">
                                					<div class="form-group">
                                						<label class="control-label">Tipo <span class="text-red">*</span></label>
                                						<select name="tipo_departamento" id="tipo_departamento" class="form-control text">
                                							<option value="departamento">Departamento</option>
                                							<option value="grupo">Grupo</option>
                                						</select>
                                						<button type="button" class="btn btn-primary ml-2 px-2 py-1 guardardepartamento"><span class="fas fa-plus" data-toggle="tooltip" data-original-title="Agregar" data-placement="top" aria-hidden="true"></span></button>
                                					</div>
                                				</div> 
                                            </div> 
                                            <div class="caja_departamentos pl-0">
                                                <div class="card-body pl-0 pt-0">
                                                    <div id="accordion_departamentos" class="accordion accordion-left-indicator form-row col-xl-12"> 
                                                    </div>
                                                </div>
                                            </div> 
                                        </section>
                                        <h4>Prioridades</h4>
                                        <section>
                                            <div class="form-row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Prioridad</h5>
                                                    </div>
                                                </div>
                                                <div class="form-group col-xs-4 col-sm-4">
                                                    <label class="text-label">Nombre <span class="text-red">*</span></label>
                                                    <input name="idprioridades" id="idprioridades" type="hidden">
                                                    <input name="nombre_prioridad" id="nombre_prioridad" class="form-control text">
                                                </div>
                                                <div class="form-group col-xs-4 col-sm-4">
                                                    <label class="text-label">Descripción </label> 
                                                    <input name="descripcion_prioridad" id="descripcion_prioridad" class="form-control text">
                                                </div>
                                                <div class="form-group col-xs-4 col-sm-4" style="position: relative;">
                                                    <label class="text-label">Tiempo de respuesta (horas) <span class="text-red">*</span></label> 
                                                    <input type="number" name="tiempo_respuesta" id="tiempo_respuesta" class="form-control text" style="    display: inline-block; width: 82%;">
                                                    <button type="button" class="btn btn-primary ml-2 px-2 py-1 guardarprioridad"><span class="fas fa-plus" data-toggle="tooltip" data-original-title="Agregar" data-placement="top" aria-hidden="true"></span></button>
                                                </div> 
                                            </div> 
                                            <div class="caja_prioridades pl-0"> 
                                                <div class="card-body pl-0 pt-0">
                                                    <div id="accordion_prioridades" class="accordion accordion-left-indicator form-row col-xl-12"> 
                                                    </div>
                                                </div>
                                            </div></section>
										<h4>Etiquetas</h4>
										<section>
												
												<div class="col-xs-12 col-sm-12 col-md-12">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Crear nueva etiqueta</h5>
                                                    </div>
                                                </div>
												
												<div class="form-row">
												
													<div class="form-group col-xs-6 col-sm-6">
														<label class="text-label">Nombre <span class="text-red">*</span></label>
														<input name="nombre_etiqueta" id="nombre_etiqueta" class="form-control text">
													</div>
													
													<div class="form-group col-xs-6 col-sm-6 text-center"> 
														<label class="text-label" style="width:100%">Seleccionar un color <span class="text-red">*</span></label>
														<div class="colores-lista">
														</div>  
														<input type="hidden" id="idcolores" data-color="">
													</div>
													
													<div class="col-lg-12 text-left"> 
														<button type="button" class="btn btn-primary crearetiqueta">Crear</button>
													</div>
													
												</div>
												
												<hr class="mt-4 mb-4">
												
                                                <div class="col-xs-12 col-sm-12 col-md-12">
    												<div class="form-group label-floating">
                                                        <h5 class="text-success">Etiquetas</h5>
                                                    </div>
                                                </div>
												<div class="form-group col-md-12 etiquetas"> 
													<label class="text-label" style="width:100%">Seleccionar etiquetas <span class="text-red">*</span></label>
													<div class="etiquetas-lista">
													</div> 
													<!--<input type="hidden" id="idcolores">-->
                                                </div> 
                                                 
												 
												 
										</section>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
		<!--MODAL SUBAMBIENTES-->
         <div class="modal fade" id="modal_subambientes">
        	<div class="modal-dialog" role="document">
        		<div class="modal-content">
        			<div class="modal-header bg-success-light px-3">
        				<h5 class="modal-title">Área</h5>
        				<button type="button" class="close" data-dismiss="modal">&times;
        				</button>
        			</div>
        			<div class="modal-body">
        				<div class="col-xs-12 col-sm-12">
        					<div class="form-group">
        						<label class="control-label">Nombre <span class="text-red">*</span></label>
        						<input name="idamb" id="idamb" type="hidden">
        						<input name="idsubambientes" id="idsubambientes" type="hidden">
        						<input name="nombre_subambiente" id="nombre_subambiente" class="form-control text">
        					</div>
        				</div>
        			</div>
        			<div class="modal-footer"> 
        				<button type="button" class="btn btn-primary btn-xs guardarsubambiente py-2">Guardar</button>
        			</div>
        		</div>
        	</div>
        </div>
        <!--MODAL SUBCATEGORÍAS-->
         <div class="modal fade" id="modal_subcategorias">
        	<div class="modal-dialog" role="document">
        		<div class="modal-content">
        			<div class="modal-header bg-success-light px-3">
        				<h5 class="modal-title">Subcategoría</h5>
        				<button type="button" class="close" data-dismiss="modal">&times;
        				</button>
        			</div>
        			<div class="modal-body">
        				<div class="col-xs-12 col-sm-12">
        					<div class="form-group">
        						<label class="control-label">Nombre <span class="text-red">*</span></label>
        						<input name="idcat" id="idcat" type="hidden">
        						<input name="idsubcategorias" id="idsubcategorias" type="hidden">
        						<input name="nombre_subcategoria" id="nombre_subcategoria" class="form-control text">
        					</div>
        				</div>
        			</div>
        			<div class="modal-footer"> 
        				<button type="button" class="btn btn-primary btn-xs guardarsubcategoria py-2">Guardar</button>
        			</div>
        		</div>
        	</div>
        </div>
		<!--**********************************
            Content body end
        ***********************************-->
         <?php // include_once('proyectomodales.php'); ?>
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
	    <!-- momment js is must -->
        <script src="./vendor/moment/moment.min.js"></script>
	    <script src="./vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	    <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
		
	    <!-- Datatable -->
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script> 
	    
	    <script src="./vendor/jquery-steps/build/jquery.steps.min.js"></script>
	    <!-- Form step init -->
        <script src="./js/jquery-steps-init.js"></script> 
		<!--Jquery UI-->
		
		<script>var _tooltip = jQuery.fn.tooltip;</script>
        <script src="../repositorio-tema/assets/js/jquery-ui.min.js" type="text/javascript"></script>
		<script>jQuery.fn.tooltip = _tooltip;</script>
		<script>
			$(function () {
				$('[data-toggle="tooltip"]').tooltip()
			})
		</script>
	    <!-- Usuarios -->
	    <script src="./js/select2/select2.min.js"></script>
	    <script src="./js/select2/select2-es.min.js"></script>
	    
	    <script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
	    <script src="./js/asistente.js?<?php autoVersiones(); ?>"></script>
	    <!--sweetalert2-->

	</body>
</html>