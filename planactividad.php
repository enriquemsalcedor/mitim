<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Plan de mantenimiento', 'Solicitud de interfaz de plan de mantenimiento', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $sistemaactual ?> | Plan de mantenimiento</title>
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--  Fonts and icons -->
    <link href="../repositorio-tema/assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- Material color picker -->
    <link href="./vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <!-- Datatable -->
    <link href="./vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <!-- Ajustes -->
    <script type="text/javascript"> var nivel = "'.$_SESSION['nivel'].'"; var idproyectos = "'.$_SESSION['idproyectos'].'"; var temp = "'.$_SESSION['user_id'].'"; </script>
    <!-- <link href="./css/ajustes1.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="./css/ajustes.css<?php autoVersiones(); ?>">
</head>

<style>
/* Altura exacta 
@media (height: 360px) {
#DZ_W_Filtros_Body{
   height:300px !important
   overflow-y: scroll;
 }
}*/

/* Altura mínima */
@media (min-height: 500px) {
#DZ_W_Filtros_Body{
   height:560px !important;
   overflow-y: scroll;
 }
}

/* Altura máxima */
@media (max-height: 600px) {
 #DZ_W_Filtros_Body{
   height:480px !important;
   overflow-y: scroll;
 }
}

@media (max-height: 500px) {
 #DZ_W_Filtros_Body{
   height:400px !important;
   overflow-y: scroll;
 }
}

@media (max-height: 400px) {
 #DZ_W_Filtros_Body{
   height:330px !important;
   overflow-y: scroll;
 }
}
</style>

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
                                Plan de mantenimiento
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
							<i class="fas fa-th-list"></i></i> <span class="ml-2">Listado</span>
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
								<form id="formulario">
									<div class="row">
										<input type="hidden" id="id"> 
										<div class="form-group col-md-12">
											<label class="text-label">Título <span class="text-red">*</span></label>
											<input type="text" name="titulo" id="titulo" class="form-control text">
										</div>
										<div class="form-group col-md-12">
											<label class="text-label">Descripcion <span class="text-red">*</span></label>
											<textarea name="descripcion" id="descripcion" class="form-control text"></textarea>
										</div> 
										<div class="form-group col-md-3">
											<label class="text-label">Cliente  <span class="text-red">*</span></label>
											<select type="text" name="idclientes" id="idclientes" class="form-control text"></select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Proyecto  <span class="text-red">*</span></label>
											<select type="text" name="idproyectos" id="idproyectos" class="form-control text"></select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Categoría  <span class="text-red">*</span></label>
											<select type="text" name="idcategorias" id="idcategorias" class="form-control text"></select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Subcategoría  <span class="text-red"></span></label>
											<select type="text" name="idsubcategorias" id="idsubcategorias" class="form-control text"></select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Ubicación  <span class="text-red">*</span></label>
											<select type="text" name="idambientes" id="idambientes" class="form-control text"></select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Áreas  <span class="text-red">*</span></label>
											<select type="text" name="idsubambientes" id="idsubambientes" class="form-control text" multiple></select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Activos  <span class="text-red">*</span></label>
											<select type="text" name="idactivos" id="idactivos" class="form-control text"></select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Prioridad  <span class="text-red">*</span></label>
											<select type="text" name="idprioridades" id="idprioridades" class="form-control text"></select>
										</div>
										<div class="col-md-12">
											<div class="form-group group-frecuencia">
												<label class="control-label" for="frecuencia">Frecuencia<span class="text-red">*</span></label>
												<div class="clearfix"></div>
												<div class="checkbox-radios">
													<div class="row">
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="fdiaria" value="Diaria"> Diaria
															</label>
														</div>
														<div class="col-md-12 dias-frecuencia">
															<div class="row">
																<div class="checkbox col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="checkbox" name="frecuenciaDias" id="fdlunes" value="Lunes"><span class="checkbox-material"></span> Lunes
																	</label>
																</div>
																<div class="checkbox col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="checkbox" name="frecuenciaDias" id="fdmartes" value="Martes"><span class="checkbox-material"></span> Martes
																	</label>
																</div>
																<div class="checkbox col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="checkbox" name="frecuenciaDias" id="fdmiercoles" value="Miercoles"><span class="checkbox-material"></span> Miercoles
																	</label>
																</div>
																<div class="checkbox col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="checkbox" name="frecuenciaDias" id="fdjueves" value="Jueves"><span class="checkbox-material"></span> Jueves
																	</label>
																</div>
																<div class="checkbox col-sm-6 col-md-3 col-lg-4">
																	<label>
																		<input type="checkbox" name="frecuenciaDias" id="fdviernes" value="Viernes"><span class="checkbox-material"></span> Viernes
																	</label>
																</div>
																<div class="checkbox col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="checkbox" name="frecuenciaDias" id="fdsabado" value="Sabado"><span class="checkbox-material"></span> Sábado
																	</label>
																</div>
																<div class="checkbox col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="checkbox" name="frecuenciaDias" id="fddomingo" value="Domingo"><span class="checkbox-material"></span> Domingo
																	</label>
																</div>
																<div class="clearfix clr_nva_act"></div>
															</div> 
														</div>  
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="fquincenal" value="Quincenal"><span class="circle"></span><span class="check"></span> 
																Quincenal
															</label>
														</div>
														<div class="row col-md-12 dias-frecuencia-quincenal">
															<div class="row col-md-12">
																<div class="radio col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="radio" name="frecuenciaQuincenal" id="fqlunes" value="Lunes"><span class="checkbox-material" checked="checked"></span> Lunes
																	</label>
																</div>
																<div class="radio col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="radio" name="frecuenciaQuincenal" id="fqmartes" value="Martes"><span class="checkbox-material"></span> Martes
																	</label>
																</div>
																<div class="radio col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="radio" name="frecuenciaQuincenal" id="fqmiercoles" value="Miercoles"><span class="checkbox-material"></span> Miercoles
																	</label>
																</div>
																<div class="radio col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="radio" name="frecuenciaQuincenal" id="fqjueves" value="Jueves"><span class="checkbox-material"></span> Jueves
																	</label>
																</div>
																<div class="radio col-sm-6 col-md-3 col-lg-4">
																	<label>
																		<input type="radio" name="frecuenciaQuincenal" id="fqviernes" value="Viernes"><span class="checkbox-material"></span> Viernes
																	</label>
																</div>
																<div class="radio col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="radio" name="frecuenciaQuincenal" id="fqsabado" value="Sabado"><span class="checkbox-material"></span> Sábado
																	</label>
																</div>
																<div class="radio col-sm-6 col-md-3 col-lg-2">
																	<label>
																		<input type="radio" name="frecuenciaQuincenal" id="fqdomingo" value="Domingo"><span class="checkbox-material"></span> Domingo
																	</label>
																</div>
																<div class="clearfix clr_nva_act"></div>
															</div> 
														</div>
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="fmensual" value="Mensual"><span class="circle"></span><span class="check"></span> 
																Mensual
															</label>
														</div>
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="fbimestral" value="Bimestral"><span class="circle"></span><span class="check"></span> 
																Bimestral
															</label>
														</div>
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="ftrimestral" value="Trimestral"><span class="circle"></span><span class="check"></span> 
																Trimestral
															</label>
														</div>						
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="fcuatrimestral" value="Cuatrimestral"><span class="circle"></span><span class="check"></span> 
																Cuatrimestral
															</label>
														</div>
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="fpentamestral" value="Pentamestral"><span class="circle"></span><span class="check"></span> 
																Pentamestral
															</label>
														</div>
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="fsemestral" value="Semestral"><span class="circle"></span><span class="check"></span> 
															       Semestral
															</label>
														</div>
														<div class="radio col-sm-6 col-md-3">
															<label>
																<input type="radio" name="frecuencia" id="fanual" value="Anual"><span class="circle"></span><span class="check"></span> 
																Anual
															</label>
														</div>
													</div> 
												</div>
											</div>
										</div>
										<div class="col-md-12 mes-frecuencia">
											<div class="form-group label-floating">
												<label class="control-label" style="font-weight: bold;color: #555555;">Inicio de frecuencia</label>
												<div class="row col-xs-12 meses-frecuencia">
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifenero" value="Enero"><span class="checkbox-material"> Enero
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="iffebrero" value="Febrero"><span class="checkbox-material"></span> Febrero
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifmarzo" value="Marzo"><span class="checkbox-material"></span> Marzo
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifabril" value="Abril"><span class="checkbox-material"></span> Abril
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifmayo" value="Mayo"><span class="checkbox-material"></span> Mayo
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifjunio" value="Junio"><span class="checkbox-material"></span> Junio
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifjulio" value="Julio"><span class="checkbox-material"></span> Julio
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifagosto" value="Agosto"><span class="checkbox-material"></span> Agosto
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifseptiembre" value="Septiembre"><span class="checkbox-material"></span> Septiembre
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifoctubre" value="Octubre"><span class="checkbox-material"></span> Octubre
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifnoviembre" value="Noviembre"><span class="checkbox-material"></span> Noviembre
														</label>
													</div>
													<div class="radio col-sm-6 col-md-3 col-lg-2" style="margin-top: 10px !important">
														<label>
															<input type="radio" name="iniciofrecuencia" id="ifdiciembre" value="Diciembre"><span class="checkbox-material"></span> Diciembre
														</label>
													</div>
													<div class="clearfix clr_nva_act"></div>
												</div>
											</div>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Tipo de plan</label>
											<select name="tipoplan" id="tipoplan" class="form-control text">
												<option value="Automático">Automático</option>
												<option value="Desactivar">Desactivar</option>
											</select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Día de inicio de frecuencia <span class="text-red">*</span> </label>
											<input type="number" min="0" name="diainiciofrecuencia" id="diainiciofrecuencia" class="form-control text">
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Departamento  <span class="text-red">*</span></label>
											<select type="text" name="iddepartamentos" id="iddepartamentos" class="form-control text"></select>
										</div>
										<div class="form-group col-md-3">
											<label class="text-label">Responsable  <span class="text-red">*</span></label>
											<select type="text" name="responsable" id="responsable" class="form-control text"></select>
										</div>
										<div class="form-group col-md-12">
											<label class="text-label">Observación</label>
											<textarea name="observacion" id="observacion" class="form-control text"></textarea>
										</div>
										<div class="col-xs-12 col-md-12 text-right">
											<button type="button" class="btn btn-primary" onClick="guardar();">
												<i class="fas fa-check-circle mr-2"></i>Guardar
											</button> 										
										</div>
									</div>
								</form> 	
													 
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
    <!-- registro -->
    <script src="./js/funciones1.js<?php autoVersiones(); ?>"></script>
	<script src="./js/planactividad.js?<?php autoVersiones(); ?>"></script>
</body>

</html>