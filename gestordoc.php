<?php 
	include_once "funciones.php"; 
	if(!isset($_SESSION['usuario'])) {
		header("Location: index.php");
		exit;
	}
	$_SESSION['proyecto']=1;
	$_SESSION['usergestor']='admin';
	$_SESSION['clavegestor']='admin';
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
<link rel="apple-touch-icon" sizes="76x76" href="images/favicon.png" />
<link rel="icon" type="image/png" href="images/favicon.png" />
<title>Maxia Toolkit | Soporte | Inicio</title>
<?php linksheader(); ?>
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link rel="stylesheet" type="text/css" href="styles/framework.css">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<!-- page specific plugin styles -->
<link rel="stylesheet" href="elFinder/css/elfinder.min.css" />
<link rel="stylesheet" href="../repositorio-lib/elFinder/themes/Material/theme-gray.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap.min.css">
<style>
.img-list-round {border-radius:100% !important; overflow: hidden;}
.text-derecha  {text-align:right !important;}
ul {margin-bottom: 0px!important;}
.btn-menu {width:150px;}
.btn-menu option {width:150px;}
.tab-menup {z-index:995!important;}
.tab-content {padding-left:25px !important;padding-right:25px !important;}
.card.years {padding-top:0px!important;margin:auto!important;}
.card .nav-pills, .card .tab-content {margin-top:0px!important;}
.content {margin-bottom:20px!important;margin-top:0px!important;padding-top: 0px!important;}
.tab-pane{margin:auto!important;text-aling:center!important;}	
	.tabs {
		margin-top: 0px;
		padding-top: 0px;
	}
	.tab-titles {
		position: fixed;
		width: 100%;
		z-index: 996;
	}	
	.content {
		padding: 0px;
		margin: 0px;
	}
	.toggle-vis {
		color: #ffffff!important;
	}
	.ui-jqgrid .ui-jqgrid-bdiv {
		overflow: auto;
	}
	#pg_pager-mttoc table tr td {
		border: 0px;
	}
	
	#load {
		padding: 0px;
		margin: 0px;
		width: 100%;
		height:600px;
	}
	
</style>

<!-- Upload archivos -->
    <link href="../repositorio-lib/uploader-master/dist/css/jquery.dm-uploader.min.css" rel="stylesheet">
	<link href="../repositorio-lib/uploader-master/src/css/styles.css" rel="stylesheet">

</head>

<body>

<div id="page-transitions">
	<?php menusup(); ?>
	<?php menu(); ?>
	<div id="page-content" class="page-content">	
		<div id="page-content-scroll" class="header-clear-larger"><!--Enables this element to be scrolled --> 	
			<div class="content" style="height:100%;">
				<!--<iframe id="load" src="http://localhost/seeddms51x/seeddms-5.1.7/out/out.Login.php?referuri=%2Fseeddms51x%2Fseeddms-5.1.7%2Fout%2Fout.ViewFolder.php" ></iframe>
				-->
				<iframe id="load" src="http://toolkit.maxialatam.com/seeddms51x/seeddms-5.1.7/restapi/login.php" ></iframe>
			</div>
			<div class="footer footer-dark">
				<a href="#" class="footer-logo"></a>
				<p class="copyright-text">Copyright &copy; Maxia Latam <span id="copyright-year">2018</span>. All Rights Reserved.</p>
			</div>
			
		</div>  	
	</div>
	
	<a href="#" class="back-to-top-badge back-to-top-small"><i class="fa fa-angle-up"></i>Subir</a>
	
	<div id="menu-4" data-menu-size="440" class="menu-wrapper menu-light menu-top menu-large">
		<div class="menu-scroll">
			<div class="menu">
				<em class="menu-divider">Equipos Abajo<i class="fa fa-navicon"></i></em>
				<div class="content" style="padding-left: 20px!important;padding-right: 20px!important;">
					<?php timedown(); ?>
					<div class="clear"></div>
				</div>
				<em class="menu-divider">Disponibilidad de Equipos<i class="fa fa-navicon"></i></em>
				<div class="content" style="padding-left: 20px!important;padding-right: 20px!important;">
					<div class="content-fullscreen">
						<div class="content">
							<canvas class="chart" id="pie-chart"/></canvas>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				

				<div class="content demo-buttons">
					<a href="#" class="button button-full button-round button-red-3d button-red uppercase ultrabold close-menu">Cerrar</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php linksfooter(); ?>
<script src="scripts/custom.js"></script>
<script src="scripts/plugins.js"></script>
<script>
jQuery(function($) {
});

var pUnidad = '<?php echo $_SESSION['unidad']; ?>';

</script>
</body>