<?php
    include_once("conexion.php");
    include_once("funciones.php");
    
    verificarLogin();
    $nombre = $_SESSION['nombreUsuario'];
    $arrnombre = explode(' ', $nombre);
    $inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	permisosUrl();
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $sistemaactual ?> | Subcategoría </title>
        <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
        <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
        <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
        <link href="./css/style1.css" rel="stylesheet">
        <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
        <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
        <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
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
                               Subcategoría
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
                                <form id="form_categoria" autocomplete="none">
                                    <div class="pt-4"> 
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12">
												<h5 class="col-form-label text-success">Datos de la subcategoría</h5>
												<div class="form-group label-floating">
														<label class="control-label" for="nombre">Nombre <span class="text-red">*</span></label> 
														<input type="text" class="form-control text" name="nombre" id="nombre" autocomplete="off">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<button type="button" class="btn btn-primary btn-xs" id="guardar" style="float:right;"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
											</div> 
										</div>  
										<hr class="mt-3 mb-2 seccion_editar_categoria d-none">
										<div class="row seccion_editar_categoria d-none">
											<div class="col-xs-10 col-sm-10 col-md-10">
												<h5 class="col-form-label text-success">Agregar a categoría</h5>
												<label class="control-label" for="nombre">Categoría <span class="text-red">*</span></label>
												<div class="form-group label-floating d-flex align-items-center">
													<select class="form-control flex-grow-1" name="id_categoria" id="id_categoria"></select> 
													<button type="button" class="btn btn-primary btn-xs ml-10 w-10" onClick="agregar_categoria_subcategoria()">
														<i class="fas fa-check-circle mr-2"></i>Agregar
													</button>
												</div>
											</div>  
										</div> 
                                    </div>
									
									 <div class="row seccion_editar_categoria d-none">
										<div class="col-xl-12">
											<div class="table-responsive">
												<table id="tablacategorias" class="mdl-data-table display nowrap table-striped" style="width:100%">
													<thead>
														<tr>
															<th></th>  
															<th>Acción</th> 
															<th>Categoría</th>  
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
                    </div>
                </div>
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
        <script src="../repositorio-tema/assets/js/jquery-ui.min.js" type="text/javascript"></script>
        <!-- Toastr -->
        <script src="./vendor/toastr/js/toastr.min.js"></script>
        <!-- Select - Font -->
        <script src="./js/select2/select2.min.js"></script>
        <script src="./js/select2/select2-es.min.js"></script>
        <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
        <!-- Datatable -->
        <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
		<script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
        <script src="./js/subcategoria.js?<?php autoVersiones(); ?>"></script>
        <!--sweetalert2-->
        <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>

    </body>
</html>