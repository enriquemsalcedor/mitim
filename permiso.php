<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//	bitacora($_SESSION['usuario'], 'Seguridad', 'Solicitud de interfaz de usuarios', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $sistemaactual ?> | Notificaciones</title>
    <!-- Favicon icon -->
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
                                Notificaciones
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
                        <button type="button" class="btn btn-primary btn-xs" id="listadousuarios">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				<div class="row">
                    <div class="col-xl-12 onlymed" id="divnombrecedula">
						<div class="bg-info" style="border-radius: 0.2rem;">
							<h4 class="p-2 m-0 text-right text-white" id="nombreusuario"> </h4>
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">								
								<form id="form_usuario_ce" autocomplete="none">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Creación de corretivo: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion1">Si   <input type="radio" id="noti1si" name="noti1" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion1">No   <input type="radio" id="noti1no" name="noti1" value="0"></label>
                                                    </div>													
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Creación de preventivo: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion2">Si   <input type="radio" id="noti2si" name="noti2" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion2">No   <input type="radio" id="noti2no" name="noti2" value="0"></label>
                                                    </div>
                                                </div>
                                            </div>													
                                        </div>
                                        <br>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Cambio de estado Asignado: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion3">Si   <input type="radio" id="noti3si" name="noti3" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion3">No   <input type="radio" id="noti3no" name="noti3" value="0"></label>
                                                    </div>
                                                </div>
                                            </div>													
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Cambio de estado En espera de cliente: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion4">Si   <input type="radio" id="noti4si" name="noti4" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion4">No   <input type="radio" id="noti4no" name="noti4" value="0"></label>
                                                    </div>	
                                                </div>
                                            </div>												
                                        </div>
                                        <br>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Cambio de estado En espera de respuesto: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion5">Si   <input type="radio" id="noti5si" name="noti5" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion5">No   <input type="radio" id="noti5no" name="noti5" value="0"></label>
                                                    </div>
                                                </div>
                                            </div>													
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Cambio de estado Reporte pendiente: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion6">Si   <input type="radio" id="noti6si" name="noti6" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion6">No   <input type="radio" id="noti6no" name="noti6" value="0"></label>
                                                    </div>	
                                                </div>
                                            </div>												
                                        </div>
                                        <br>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Cambio de estado Resuelto: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion7">Si   <input type="radio" id="noti7si" name="noti7" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion7">No   <input type="radio" id="noti7no" name="noti7" value="0"></label>
                                                    </div>	
                                                </div>
                                            </div>												
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Comentarios públicos: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion8">Si   <input type="radio" id="noti8si" name="noti8" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion8">No   <input type="radio" id="noti8no" name="noti8" value="0"></label>
                                                    </div>		
                                                </div>
                                            </div>											
                                        </div>
                                        <br>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Comentarios privados: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion9">Si   <input type="radio" id="noti9si" name="noti9" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion9">No   <input type="radio" id="noti9no" name="noti9" value="0"></label>
                                                    </div>	
                                                </div>
                                            </div>												
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Adjuntos: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion10">Si   <input type="radio" id="noti10si" name="noti10" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion10">No   <input type="radio" id="noti10no" name="noti10" value="0"></label>
                                                    </div>
                                                </div>
                                            </div>													
                                        </div>
                                        <br>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Cambio de solicitante: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion11">Si   <input type="radio" id="noti11si" name="noti11" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion11">No   <input type="radio" id="noti11no" name="noti11" value="0"></label>
                                                    </div>		
                                                </div>
                                            </div>											
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Programacion de preventivos (domingos): </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion12">Si   <input type="radio" id="noti12si" name="noti12" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion12">No   <input type="radio" id="noti12no" name="noti12" value="0"></label>
                                                    </div>	
                                                </div>
                                            </div>												
                                        </div>
                                        <br>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-6">
                                                        <p>Notificacion de vida útil de activo: </p>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion13">Si   <input type="radio" id="noti13si" name="noti13" value="1"></label>
                                                    </div>
                                                    <div class="col-sm-3 col-md-3">
                                                        <label for="notificacion13">No   <input type="radio" id="noti13no" name="noti13" value="0"></label>
                                                    </div>	
                                                </div>
                                            </div>												
                                        </div>
                                    </div>
                                    <div class="text-right col-xs-12 mt-3">
                                        <button type="button" class="btn btn-primary btn-xs" onClick="guardar()"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
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
    <!-- Toastr -->
    <script src="./vendor/toastr/js/toastr.min.js"></script>
    <!-- Select - Font -->
    <script src="./js/select2/select2.min.js"></script>
    <script src="./js/select2/select2-es.min.js"></script>
    <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <!-- Usuarios -->
    <script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
    <script src="./js/permiso.js?<?php autoVersiones(); ?>"></script>
    <!--sweetalert2-->
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
</body>

</html>