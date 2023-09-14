<?php
    include_once("conexion.php");
    include_once("funciones.php");
    
    verificarLogin();
    $nombre = $_SESSION['nombreUsuario'];
    $arrnombre = explode(' ', $nombre);
    $inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
   // bitacora($_SESSION['usuario'], 'Seguridad', 'Solicitud de interfaz de estados', 0, '');
	permisosUrl();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $sistemaactual ?> | Niveles</title>
        <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        <link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
        <!-- Toastr -->
        <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">
        <!--sweetalert2-->
        <link href="./vendor/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">
        <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
        <link href="./css/style1.css" rel="stylesheet">
        <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
        <!--  Fonts and icons -->
        <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
        <!-- Datatable -->
        <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./vendor/toastr/css/toastr.min.css">

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
                                Niveles
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
                    <div class="col-md-12 mb-4 text-right">
                        <button type="button" class="btn btn-primary btn-xs" id="nuevonivel">
                            <i class="fa fa-plus-circle mr-2"></i> Nuevo
                        </button>
                    </div>
                </div>

                <!--tabla-->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="table-responsive">
                            <table id="tbniveles" class="mdl-data-table display table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>  
                                        <th>Acción</th>
                                        <th id="cnombre">Nombre</th>  
                                        <th id="cdescripcion">Descripción</th>
                                    </tr>
                                </thead> 
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!--fin tabla-->
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
    <!-- Toastr -->
    <script src="./vendor/toastr/js/toastr.min.js"></script>
    <!-- Select - Font -->
    <script src="./js/select2/select2.min.js"></script>
    <script src="./js/select2/select2-es.min.js"></script>
    <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./vendor/toastr/js/toastr.min.js"></script>
    <!-- Usuarios -->
    <script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
	<script src="./js/niveles.js?<?php autoVersiones(); ?>"></script>
    <!--sweetalert2-->
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
    </body>
</html>