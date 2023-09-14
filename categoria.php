<?php
    include_once("conexion.php");
    include_once("funciones.php");
    
    verificarLogin();
    $nombre = $_SESSION['nombreUsuario'];
    $arrnombre = explode(' ', $nombre);
    $inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
    //bitacora($_SESSION['usuario'], 'Categorías', 'Solicitud de interfaz de categoría', 0, '');
	permisosUrl();
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $sistemaactual ?> | Categoría </title>
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

    <!--link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
    <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet"> 

    <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
    <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="./css/style6.css" rel="stylesheet">
    <link href="./css/ajustes1.css" rel="stylesheet"-->

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
                               Categoría
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
                
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="form_categoria" autocomplete="none">
                                    <div class="pt-4">
                                        <div class="form-row">

                                        <input type="hidden" id="id"><!--CAMPOS-OCULTOS-->
 
										<div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group label-floating">
                                                    <label class="control-label" for="nombre">Nombre <span class="text-red">*</span> <!--<span class="fa fa-question-circle" data-toggle="tooltip" data-original-title="Busque el nombre de la categoría, si no existe se creará una nueva" data-placement="right" aria-hidden="true"></span>--></label>
													<!--<input type="hidden" id="idcategorias" name="idcategorias">-->
                                                    <input type="text" class="form-control text" name="nombre" id="nombre" autocomplete="off">
                                            </div>
                                        </div>
										
                                        <!--<div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Tipo <span class="text-red">*</span></label>
                                                <select name="tipo" id="tipo" class="form-control text" multiple>
													<option value="correctivos">Correctivos</option>
													<option value="preventivos">Preventivos</option>
													<option value="postventas">Postventas</option>
												</select>
                                            </div>
                                        </div>
 
										<div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Clientes <span class="text-red">*</span></label>
                                                <select name="cliente" id="idcliente" class="form-control text" multiple></select>
                                            </div>
                                        </div>


                                        <div class="col-xs-12 col-sm-6 col-md-6">
                                            <div class="form-group label-floating">
                                                <label class="control-label">Proyectos <span class="text-red">*</span></label>
                                                <select name="proyecto" id="idproyecto" class="form-control text" multiple></select>
                                            </div>
                                        </div>-->
										
                                    </div><!--ROW-->
									<div class="col-sm-12 col-md-12">
										<button type="button" class="btn btn-primary btn-xs" id="guardar" style="float:right;"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
									</div>									
                                        
                                    <!--<div class="form-row mb-5 mt-2"> -->
                                     <!--<div class="form-row"> 

                                        <div class="col-xs-12 col-sm-4 col-md-4" id="box_anadir_campo">
                                            <div class="form-group" style="height:100%;">
                                                <button type="button" class="btn btn-primary btn-xs float-left" 
                                                        style="margin-top:28px;height: 40px;" id="anadir">Añadir</button>

                                            </div>
                                        </div>
                                    </div>--><!--ROW-->
										 
                                    </div><!--pt-4-->
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
        <script src="./js/categoria.js?<?php autoVersiones(); ?>"></script>
        <script src="./js/helpers.js?<?php autoVersiones(); ?>"></script>
        <!--sweetalert2-->
        <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>

    </body>
</html>