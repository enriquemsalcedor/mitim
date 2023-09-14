
function cargarCombosF(){

		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: 18, tipo: 'filtros' }, function(result){ 
			$("#asignadoaf").empty();
			$("#asignadoaf").append(result);					
		});
					$.get( "controller/combosback.php?oper=unidades", { onlydata:"true" }, function(result){ 
						$("#unidadejecutoraf").empty();
						$("#unidadejecutoraf").append(result);
					});
	//Autos
	$.get( "controller/combosback.php?oper=autos", { idempresas: 1,idclientes: 9,idproyectos:34 }, function(result){ 
		$("#serief").empty();
		$("#serief").append(result);
	}); 
	//Solicitantes
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true"}, function(result){ 
		$("#solicitantef").empty();
		$("#solicitantef").append(result);
	});
	//Estados
	$.get( "controller/combosback.php?oper=estadosflotas", { tipo:"flotas" }, function(result){ 
		$("#estadof").empty();
		$("#estadof").append(result);
	}); 
}	

function verificarfiltros(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/flotasback.php',
		data: { 
			'oper'	: 'verificarfiltros'
		},
		success: function (response) {
			if (response == 1) {
				$('#icono-filtrosmasivos').removeClass('bg-success');
				$('#icono-filtrosmasivos').addClass('bg-warning');
			}else{
				$('#icono-filtrosmasivos').removeClass('bg-warning');
				$('#icono-filtrosmasivos').addClass('bg-success');
			}
		}
	});
}
verificarfiltros();

function abrirFiltrosMasivos(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/flotasback.php',
		data: { 
			'oper'	: 'abrirfiltros'
		},
		beforeSend: function() {
			$('#preloader').css('display','block');
		},
		success: function (response) {
			$('#preloader').css('display','none');
			if (response.data!="") {
				var obj = JSON.parse(response.data);					
				$("#relleno").val(obj.relleno);
				$("#desdef").val(obj.desdef);
				$("#hastaf").val(obj.hastaf);
				$("#fechadevolucionf").val(obj.fechadevolucionf);
					//SITIOS
					$.get( "controller/combosback.php?oper=unidades", { onlydata:"true" }, function(result){ 
						$("#unidadejecutoraf").empty();
						$("#unidadejecutoraf").append(result);
						$("#unidadejecutoraf").val(obj.unidadejecutoraf).trigger("change");
					});
					//ESTADOS
					$.get( "controller/combosback.php?oper=estadosflotas", { tipo:"flotas"}, function(result){ 
						$("#estadof").empty();
						$("#estadof").append(result);
						$("#estadof").val(obj.estadof).trigger("change");
					}); 
					//SERIE
					$.get( "controller/combosback.php?oper=autos", { idempresas: 1,idclientes: 9,idproyectos:34 }, function(result){ 
						$("#serief").empty();
						$("#serief").append(result);
						$("#serief").val(obj.serief).trigger("change");
					});
					
					$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: 18 }, function(result){ 
						$("#asignadoaf").empty();
						$("#asignadoaf").append(result);
						$("#asignadoaf").val(obj.asignadoaf).trigger("change");
					});		
									//SOLICITANTE
				$.get( "controller/combosback.php?oper=usuarios", {  }, function(result){ 
				$("#solicitantef").empty();
				$("#solicitantef").append(result);
				$("#solicitantef").val(obj.solicitantef).trigger("change");
				});
			}
			$('#modalfiltrosmasivos').modal('show');
		},
		error: function () {
			$('#preloader').css('display','none');
		}
	});
}

$("#filtrarmasivo").on('click', function() {
	filtrosMasivos();
});

$("#limpiarfiltros").on('click', function() {
	limpiarFiltrosMasivos();
});

function filtrosMasivos() {
	var dataserialize 	= $("#form_filtrosmasivos").serializeArray();
	var data 			= {};
	
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'unidadejecutoraf' || dataserialize[i].name == 'serief' || 
			dataserialize[i].name == 'estadof' || dataserialize[i].name == 'asignadoaf' || 
			dataserialize[i].name == 'solicitantef' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	data = JSON.stringify(data);	
	$.ajax({
		type: 'post',
		dataType: "json",
		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		url: 'controller/flotasback.php',
		data: { 
			'oper'	: 'guardarfiltros',
			'data'	: data
		},
		beforeSend: function() {
		    $('#preloader').css('display','block');
		},
		success: function (response) {
	    	$('#preloader').css('display','none');
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			tablaincidentes.ajax.reload(null, false);
		    $(".chatbox-close").click();
			verificarfiltros();
		},
		error: function () {
		  $('#preloader').css('display','none');
		}
	});
}
function limpiarFiltrosMasivos(){
    $('#icono-filtrosmasivos').removeClass('bg-warning');
	$('#icono-filtrosmasivos').addClass('bg-success');
	$.get( "controller/flotasback.php?oper=limpiarFiltrosMasivos");
	var dataserialize = $("#form_filtrosmasivos").serializeArray();
	for (var i in dataserialize) {
		if(dataserialize[i].name != 'fueraserviciof'){
			$("#"+dataserialize[i].name).val(null).trigger("change");
		}
		tablaincidentes.ajax.reload(null, false);
		$(".chatbox-close").click();
	}
}

$(document).ready(function() {
	cargarCombosF();
});
    $("select").select2();


