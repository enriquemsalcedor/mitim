<?php
    include_once("conexion.php");
	include_once("funciones.php");
	
	verificarLogin();
	$nombre = $_SESSION['nombreUsuario'];
	$arrnombre = explode(' ', $nombre);
	$inombre = substr($arrnombre[0], 0, 1).''.substr($arrnombre[1], 0, 1);
	//bitacora($_SESSION['usuario'], 'Seguridad', 'Solicitud de interfaz de activos', 0, '');
	permisosUrl();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> <?php echo $sistemaactual ?> | Activos</title>
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
                                Activos
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
		<?php 
		    global $mysqli;
        	$nivel = $_SESSION['nivel'];
        	$usuario = $_SESSION['usuario'];
        	$res = 0;
        	if($nivel == 3){
        	    $query = "SELECT * from usuarios where usuario = '$usuario' and nivel = '$nivel' and cargo = 'Implementador'";
        	    $result = $mysqli->query($query);
        	    if($result->num_rows >0){
        		    $res = 1;
        	    }    
        	}
		?>
        <div class="content-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-4 text-right">
						<?php if($nivel!=3 && $nivel!=4): ?>
						<button type="button" class="btn btn-primary btn-xs" id="nuevo">
							<i class="fa fa-plus-circle mr-2"></i> Nuevo
						</button>
						<?php endif; ?>
						<button type="button" class="btn btn-primary btn-xs" id="editarmasivo" onclick="editarMasivo()">
							<i class="fa fa-edit"></i> <span class="ml-2">Editar Masivo</span>
						</button>
						<?php if($nivel==1 || $nivel==2 || $nivel==3 || $nivel==7 || $res==1 || $usuario == 'abarrancos'): ?>
						<button type="button" class="btn btn-primary btn-xs" id="importar" onclick="abrirdialogImportar()">
							<i class="fa fa-upload"></i> <span class="ml-2">Importar</span>
						</button> 
						<?php endif; ?>
						<button type="button" class="btn btn-primary btn-xs" id="reportesexcel" onclick="reportes()">
							<i class="fa fa-file-excel"></i> <span class="ml-2">Reportes</span>
						</button>
                    </div>
                </div>

                <!--tabla-->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="table-responsive">
                            <!--<table id="tbactivos" class="mdl-data-table display nowrap table-striped" style="width:100%">-->
                            <table id="tbactivos" class="display min-w850 ">
                                <thead>
                                    <tr>
										<th>-</th>
                                        <th></th>  
                                        <th>Acción</th>
                                        <th id="cserial1">Serial 1</th>
                                        <th id="cserial2">Serial 2</th>
                                        <th id="cnombre">Nombre</th>
                                        <th id="cmodalidad">Tipo</th>
        								<th id="cmarca">Marca</th>
                                        <th id="cmodelo">Modelo</th>
                                        <th id="cresponsable">Responsable</th>
                                        <th id="cidambientes">Id Ambiente</th>
                                        <th id="cambiente">Ubicación</th>
                                        <th id="csubambiente">Área</th>
                                        <th id="cfase">Fase</th>
                                        <th id="cfechatopemant">Fecha Tope Mant.</th>
                                        <th id="cfechainst">Fecha instalación</th>
                                        <th id="cempresas">Empresas</th>
                                        <th id="cclientes">Clientes</th>
                                        <th id="cproyectos">Proyectos</th>
        								<th id="cestado">Estado</th>
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
    </div>
    <!--**********************************
            Content body end
        ***********************************-->

	<?php include_once "activos-adjuntos.php"; ?>
	<?php include_once "activos-reportes.php"; ?>
	<?php include_once "activos-importar.php"; ?>
	<?php include_once "activos-masivo.php"; ?>
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
    <script src="./js/funciones1.js<?php autoVersiones(); ?>"></script>
    <script src="./js/activos.js<?php autoVersiones(); ?>"></script>
	<script src="js/activos-importar.js<?php autoVersiones(); ?>" ></script>
	<script src="js/activos-masivo.js<?php autoVersiones(); ?>" ></script>
	<script src="../repositorio-lib/uploader-master/dist/js/jquery.dm-uploader.min.js"></script>
    <!--sweetalert2-->
    <script src="./vendor/sweetalert2/dist/sweetalert2.min.js"></script>
</body>

</html>