<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Maestros', 'Solicitud de interfaz de marca', 0, '');
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


		/* if($tipo=="edit" || $tipo=="view" ){//EDITAR O VER
			if($nivel==7 &&  $tipo=="edit"){//NO Authorized
				header("Location: marcas.php");
				exit;
			}
		}else{//NO VALID
			header("Location: marcas.php");
			exit;
		} */


	}

?>

<!DOCTYPE html>
	<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $sistemaactual ?> | Cliente</title>
        <!-- Favicon icon -->
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
        <!-- Datatable -->
        <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
        <!-- Ajustes -->
        <link href="./css/style6.css" rel="stylesheet">
        <link href="./css/ajustes1.css" rel="stylesheet">
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
                                Cliente
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
				<div class="row">
					<!--
                    <div class="col-md-10">
                        <div class="page-titles">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0)">Reges </a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0)">Usuarios</a></li>
                            </ol>
                        </div>
                    </div>
					-->
                    <div class="col-md-12 mb-4 text-right barraOpc">
                        <button type="button" class="btn btn-primary btn-xs" id="listado">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>

				<li class="nav-item facturacion" style="display:none;" id="tabfact">
											<a class="nav-link" data-toggle="tab" href="#facturacion">Facturación</a>
										</li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane fade show active" id="correctivo" role="tabpanel">
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<ul class="nav nav-pills review-tab" role="tablist">
									<li class="nav-item cliente active" style="display:none;" id="tabdatos">
										<a class="nav-link active" data-toggle="tab" href="#cliente">Datos del Cliente</a>
									</li>
									<li class="nav-item propiedades" style="display:none;" id="tabpropiedades">
										<a class="nav-link" data-toggle="tab" href="#propiedades">Propiedades</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade show active" id="cliente" role="tabpanel">
										<form id="form_marca_ce" autocomplete="none">
											<div class="pt-4">
												<div class="form-row">
													<div class="col-xs-12 col-sm-12 col-md-6">
														<div class="form-group label-floating">
															<input type="hidden" name="id" id="id" >
															<label class="control-label" for="nombre" >Nombre <span class="text-red">*</span></label>
																<input type="text" class="form-control text" name="nombre" id="nombre" autocomplete="off">
														</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6">
														<div class="form-group label-floating"> 
															<label class="control-label" for="apellidos" >Apellidos </label>
																<input type="text" class="form-control text" name="apellidos" id="apellidos" autocomplete="off">
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
															<label class="control-label" for="telefono" >Teléfono <span class="text-red">*</span></label>
																<input type="text" class="form-control text" name="telefono" id="telefono" autocomplete="off">
														</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-4">
														<div class="form-group label-floating"> 
															<label class="control-label" for="provincia" >Provincia </label>
															<select class="form-control" name="id_provincia" id="id_provincia" style="width:100%"></select>
														</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-4">
														<div class="form-group label-floating"> 
															<label class="control-label" for="provincia" >Distrito </label>
															<select class="form-control" name="id_distrito" id="id_distrito" style="width:100%"></select>
														</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-4">
														<div class="form-group label-floating"> 
															<label class="control-label" for="provincia" >Corregimiento </label>
															<select class="form-control" name="id_corregimiento" id="id_corregimiento" style="width:100%"></select>
														</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6">
														<div class="form-group label-floating"> 
															<label class="control-label" for="correo" >Correo <span class="text-red">*</span></label>
																<input type="text" class="form-control text" name="correo" id="correo" autocomplete="off">
														</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6">
														<div class="form-group label-floating"> 
															<label class="control-label" for="movil" >Movil </label>
																<input type="text" class="form-control text" name="movil" id="movil" autocomplete="off">
														</div>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6">
														<div class="form-group label-floating"> 
															<label class="control-label" for="provincia" >¿Cómo supo de nosotros? </label>
															<select class="form-control" name="id_referido" id="id_referido" style="width:100%"></select>
														</div>
													</div> 
													<div class="col-xs-12 col-sm-12 col-md-6">
														<div class="form-group label-floating"> 
															<label class="control-label" for="provincia" >Especifique </label>
															<select class="form-control" name="id_subreferido" id="id_subreferido" style="width:100%"></select>
														</div>
													</div> 


												</div><!--form-row-->
											</div><!--pt-4-->


											<?php //if($tipo=="new" || $tipo=="edit"): ?>
													<button type="button" class="btn btn-primary btn-xs" id="guardar" style="float:right;"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
											<?php// endif; ?>




										</form>
									</div>

									<div class="tab-pane fade show mt-3" id="propiedades" role="tabpanel">
										<div class="row">
											<div class="col-md-12 mb-4 text-right">
												<button type="button" class="btn btn-primary btn-xs" id="nueva_propiedad">
													<i class="fa fa-plus-circle mr-2"></i> Nueva Propiedad
												</button>
											</div>

											<div class="col-xl-12">
												<div class="table-responsive">
													<table id="tablapropiedades" class="mdl-data-table display table-striped" style="width:100%">
														<thead>
															<tr>
																<th></th>  
																<th id="<?php echo $nivel; ?>" >Acción</th>
																<th id="cnombre">Nombre</th>
																<th id="cprovincia">Provincia</th>
																<th id="cdistrito">Distrito</th>
																<th id="ccorregimiento">Corregimiento</th>
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
		<!--**********************************
            Content body end
        ***********************************-->
		<?php include_once('cliente-propiedad.php'); ?>
	    	









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
	    <!-- Datatable -->
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
	    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
	    <!-- Usuarios -->
	    <script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
	    <script src="./js/cliente.js?<?php autoVersiones(); ?>"></script>
		<script src="./js/cliente-propiedad.js?<?php autoVersiones(); ?>"></script>

	    <!--sweetalert2-->

	</body>
</html>