<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Maestros', 'Solicitud de interfaz de marca', 0, '');
	permisosUrl();
	$nivel = $_SESSION["nivel"]; 
	$tipo="";
	if(!isset($_GET['type'])){//NUEWVO
		$tipo="new";
	}else{
		$tipo = $_GET['type'];


		if($tipo=="edit" || $tipo=="view" ){//EDITAR O VER
			if($nivel>2 &&  $tipo=="edit"){//NO Authorized
				header("Location: tipos2.php");
				exit;
			}
		}else{//NO VALID
			header("Location: tipos2.php");
			exit;
		}


	}

?>

<!DOCTYPE html>
	<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title><?php echo $sistemaactual ?> | tipo</title>
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
	    <link rel="stylesheet" href="./css/ajustes.css<?php autoVersiones(); ?>">

        <style type="text/css">
            
            textarea.text{
                height:40px;
                min-height: 40px;
            }
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
                        <button type="button" class="btn btn-primary btn-xs" id="salir-tipos">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<form id="form_tipo_ce" autocomplete="none">

									<div class="pt-4">
										<div class="form-row">

											<div class="col-xs-12 col-sm-12 col-md-12">
												<div class="form-group label-floating">
													<input type="hidden" name="id" id="id" >
													<label class="control-label" for="nombre" >Nombre  
														<span class="text-red">*</span>
													</label>

													<!--textarea class="form-control text" name="nombre" id="nombre" autocomplete="off"
													rows="1"
													></textarea-->
													<input type="text" class="form-control text" name="nombre" id="nombre" autocomplete="off">


												</div>
											</div>


										</div><!--form-row-->
									</div><!--pt-4-->
        						</form>
									<?php if($tipo=="new" || $tipo=="edit"): ?>

		                                    <button type="button" class="btn btn-primary btn-xs" style="float:right" id="guardar-tipo"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
									<?php endif; ?>


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
	    <script src="./vendor/toastr/js/toastr.min.js"></script>

	    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
	    <!-- Usuarios -->
	    <script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
	    <script src="./js/tipos-ne.js?<?php autoVersiones(); ?>"></script>
	    <!--sweetalert2-->
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>

	</body>
</html>