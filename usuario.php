<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Seguridad', 'Solicitud de interfaz de usuario', 0, '');
	$nivel = $_SESSION["nivel"]; 
	permisosUrl();
    $var_nivel = '    <script type="text/javascript"> var nivel = "'.$_SESSION['nivel'].'"; var idproyectos = "'.$_SESSION['idproyectos'].'"; var temp = "'.$_SESSION['user_id'].'"; </script>';

	$tipo="";

	if(!isset($_GET['type'])){//NUEWVO
		$tipo="new";
	}else{
		$tipo = $_GET['type'];
		if($tipo=="edit" || $tipo=="view" || $tipo=="new"){//EDITAR O VER
            

		}else{//NO VALID
			header("Location: usuarios.php");
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
        <title><?php echo $sistemaactual ?> | Usuario</title>
        <!-- Favicon icon -->

        <?php echo $var_nivel ?>

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
	<link rel="stylesheet" href="./css/ajustes.css<?php autoVersiones(); ?>">
        <style type="text/css">
            textarea.text{
                height:40px;
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
                    <div class="col-md-12 mb-4 text-right barraOpc">
                        <button type="button" class="btn btn-primary btn-xs" id="salir-usuarios">
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
						</button>
                    </div>
                </div>
				<div class="row">
                    <div class="col-xl-12">
						<div class="card">
                            <div class="card-body">
								<form id="form_usuario_ce" autocomplete="none">
									<div class="pt-4">
										<div class="form-row">
										<input type="hidden" class="form-control text" name="idusuario" id="idusuario" autocomplete="off">

										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
												<label class="control-label" for="ususuario">Usuario <span class="text-red">*</span></label>
												<input type="text" class="form-control text" name="ususuario" id="ususuario" onfocus="this.removeAttribute('readonly');" readonly />
												<span class="material-input"></span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
												<label class="control-label" for="clavusuario">Clave <span class="text-red">*</span></label>
												<div class="input-group">
													<input type="password" class="form-control text" name="clavusuario" id="clavusuario" onfocus="this.removeAttribute('readonly');" readonly />
											      	<div class="input-group-append">
													
<?php if($_SESSION["nivel"] == 1 || $_SESSION["nivel"] == 2 || $_SESSION["nivel"] == 5):?>				
											            <button
											                style="height:40px"
															title="Mostrar contraseña"
															id="showPassword" class="btn btn-sm btn-primary" type="button"> <span class="fa fa-eye icon"></span> </button>
														<?php endif; ?>
														<?php if($_SESSION["nivel"] != 1 && $_SESSION["nivel"] != 2 && $_SESSION["nivel"] != 5):?>
														 <button
											                style="height:40px"
															title="Mostrar contraseña"
															id="showPassword" class="btn btn-sm btn-primary" type="button" disabled> <span class="fa fa-eye icon"></span> </button>
														<?php endif; ?>
											          </div>
													
												</div>
												<span class="material-input"></span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
												<label class="control-label" for="nombusuario">Nombre <span class="text-red">*</span></label>
												<input type="text" class="form-control text" name="nombusuario" id="nombusuario" autocomplete="off">
												<span class="material-input"></span>
											</div>
										</div>							
										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
												<label class="control-label" for="corrusuario">Correo <span class="text-red">*</span></label> 
												<input type="text" class="form-control text" name="corrusuario" id="corrusuario" autocomplete="off">
												<span class="material-input"></span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
												<label class="control-label" for="telfusuario">Teléfono</label> 
												<input type="text" class="form-control text" name="telfusuario" id="telfusuario" autocomplete="off">
												<span class="material-input"></span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
												<label class="control-label" for="cargusuario">Cargo</label> 
												<input type="text" class="form-control text" name="cargusuario" id="cargusuario" autocomplete="off">
												<span class="material-input"></span>
											</div>
										</div>							
										<div class="col-xs-12 col-sm-12 select2-multiple">
											<div class="form-group label-floating selectsr selectcr2"> 
												<label class="control-label" for="clieusuario">Clientes <span class="text-red">*</span></label> 
												<select class="form-control text" name="clieusuario" id="clieusuario" multiple="multiple"></select>
												<span class="material-input"></span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 select2-multiple" >
											<div class="form-group label-floating selectsr selectcr2">
												<label class="control-label" for="proyusuario">Proyectos <span class="text-red">*</span></label> 
												<select class="form-control text" name="proyusuario" id="proyusuario" multiple="multiple"></select>
												<span class="material-input"></span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 select2-multiple">
											<div class="form-group label-floating selectsr selectcr2">
												<label class="control-label" for="ambienteusuario">Ubicaciones</label> 
												<select class="form-control text" name="ambienteusuario" id="ambienteusuario" multiple="multiple" autocomplete="off"></select>
												<span class="material-input"></span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 select2-multiple">
											<div class="form-group label-floating selectsr selectcr2">
												<label class="control-label" for="idproveedor">Proveedores</label> 
												<select class="form-control text" name="idproveedor" id="idproveedor" multiple autocomplete="off"></select>
												<span class="material-input"></span>
											</div>
										</div>

										<?php if($_SESSION["nivel"] != 7): ?>
										<div class="col-xs-12 col-sm-12">
											<div class="form-group label-floating">
												<label class="control-label" for="deparusuario">Departamentos</label> 
												<select class="form-control text" name="deparusuario" id="deparusuario"></select>
												<span class="material-input"></span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-12 select2-multiple">
											<div class="form-group label-floating selectsr selectcr2">
												<label class="control-label" for="grupusuario">Grupos</label> 
												<select class="form-control text" name="grupusuario" id="grupusuario" multiple></select>
												<span class="material-input"></span>
											</div>
										</div>	 					
										<?php endif; ?>


										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">

												<label class="control-label" for="niveusuario">Nivel <span class="text-red">*</span></label> 
												<select class="form-control text" name="niveusuario" id="niveusuario"autocomplete="off"></select>
												<span class="material-input"></span>
											</div>
										</div> 
										<div class="col-xs-12 col-sm-6 col-md-6">
											<div class="form-group label-floating">
												<label class="control-label" for="edousuario">Estado</label> 
												<select type="text" class="form-control text" name="edousuario" id="edousuario"autocomplete="off">
													<option value="Activo">Activo</option>
													<option value="Inactivo">Inactivo</option>
												</select>
												<!--<span class="material-input"></span>-->
											</div>
										</div>




										</div><!--form-row-->
									</div><!--pt-4-->



        						</form>

									<?php if($tipo=="new" || $tipo=="edit"): ?>

		                                    <button type="button" class="btn btn-primary btn-xs" style="float:right" id="guardar-usuario"><i class="fas fa-check-circle mr-2"></i>Guardar</button>
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
	    <script src="./vendor/select2/js/select2.full.min.js"></script>  
    	<script src="./js/select2/select2-es.min.js"></script>
	    <script src="https://kit.fontawesome.com/7f9e31f86a.js" crossorigin="anonymous"></script>
	    <!-- Datatable -->
	    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
	    <!-- Usuarios -->
	    <script src="./js/funciones1.js?<?php autoVersiones(); ?>"></script>
	    <script src="./js/usuario.js?<?php autoVersiones(); ?>"></script>
	    <!--sweetalert2-->
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
	    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script> 
	</body>
</html>