function cargarCombosF(){
	$("#idempresasf, #idclientesf, #iddepartamentosf, #idproyectosf, #categoriaf, #subcategoriaf, #unidadejecutoraf, #asignadoaf ").select2({placeholder: ""});
	//EMPRESAS
	/*
	$.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresasf").empty();
		$("#idempresasf").append(result);
		$("#idempresasf").select2({placeholder: ""});
	});
	*/
	//EMPRESAS / CLIENTES - DEPARTAMENTOS
	//$('#idempresasf').on('select2:select',function(){
		//var idempresasf = $("#idempresasf option:selected").val();
		var idempresasf = 1;
		//CLIENTES
		$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresasf }, function(result){ 
			$("#idclientesf").empty();
			$("#idclientesf").append(result);
			$("#idclientesf").select2({placeholder: ""});
		});
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: idempresasf }, function(result){ 
			$("#iddepartamentosf").empty();
			$("#iddepartamentosf").append(result);
			$("#iddepartamentosf").select2({placeholder: ""});
		});
	//});
	//CLIENTES / PROYECTOS - DEPARTAMENTOS
	$('#idclientesf').on('select2:select',function(){
		var idclientesf = $("#idclientesf option:selected").val();	
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientesf }, function(result){ 
			$("#idproyectosf").empty();
			$("#idproyectosf").append(result);
			$("#idproyectosf").select2({placeholder: ""});
		});				
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientesf }, function(result){ 
			$("#unidadejecutoraf").empty();
			$("#unidadejecutoraf").append(result);
			$('#unidadejecutoraf').select2({placeholder: ""});
		});
		//CATEGORIAS
		$("#categoriaf").empty();
	});
	//PROYECTOS / CATEGORIAS
	$('#idproyectosf').on('select2:select',function(){
		var idproyectosf = $("#idproyectosf option:selected").val();	
		$.get( "controller/combosback.php?oper=categorias", { tipo: "incidente", idproyectos: idproyectosf }, function(result){ 
			$("#categoriaf").empty();
			$("#categoriaf").append(result);
			$("#categoriaf").select2({placeholder: ""});
		});
	});
	//CATEGORIAS - SUBCATEGORIAS
	$('#categoriaf').on('select2:select',function(){
		var idcategoriaf = $("#categoriaf option:selected").val();		
		$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: idcategoriaf }, function(result){ 
			$("#subcategoriaf").empty();
			$("#subcategoriaf").append(result);
			$("#subcategoriaf").select2({placeholder: ""});
		});
	});
	//SITIOS / SERIE
	$('#unidadejecutoraf').on('select2:select',function(){
		var idsitiof = $("#unidadejecutoraf option:selected").val();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitiof }, function(result){ 
			$("#serief").empty();
			$("#serief").append(result);
			$('#serief').select2({placeholder: ""});
		});
	});
	//DEPARTAMENTOS / ASIGNADO A
	$('#iddepartamentosf').on('select2:select',function(){
		var iddepartamentosf = $("#iddepartamentosf option:selected").val();	
		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: iddepartamentosf }, function(result){ 
			$("#asignadoaf").empty();
			$("#asignadoaf").append(result);
			$('#asignadoaf').select2({placeholder: ""});						
		});
	});	
	//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#prioridadf").empty();
		$("#prioridadf").append(result);
		$("#prioridadf").select2({placeholder: ""});
	});	
	//MODALIDADES
	$.get( "controller/combosback.php?oper=modalidades", { onlydata:"true" }, function(result){ 
		$("#modalidadf").empty();
		$("#modalidadf").append(result);
		$('#modalidadf').select2({placeholder: ""});
	});
	//MARCAS
	$.get( "controller/combosback.php?oper=marcas", { onlydata:"true" }, function(result){ 
		$("#marcaf").empty();
		$("#marcaf").append(result);
		$('#marcaf').select2({placeholder: ""});
	});
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"incidente" }, function(result){ 
		$("#estadof").empty();
		$("#estadof").append(result);
		$("#estadof").select2({placeholder: ""});
	});
	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
		$("#solicitantef").empty();
		$("#solicitantef").append(result);
		$('#solicitantef').select2({placeholder: ""});
	});		
}

var dialogfiltrosmasivos = $( "#dialog-filtrosmasivos" ).dialog({		
	width: '60%', 
	maxWidth: 600,
	height: 'auto',
	//modal: true,
	fluid: true,
	resizable: true,
	autoOpen: false
});	

function abrirFiltrosMasivos(){
	//$("#idempresasf").val('1').trigger("change");
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/incidentesback.php',
		data: { 
			'oper'	: 'abrirfiltros'
		},
		beforeSend: function() {
			$(".loader-maxia").show();
		},
		success: function (response) {	
			if (response.data!="") {
				$(".loader-maxia").hide();
				var obj = JSON.parse(response.data);					
				$("#relleno").val(obj.relleno);
				$("#desdef").val(obj.desdef);
				$("#hastaf").val(obj.hastaf);
				
				//EMPRESAS
				$("#idempresasf").val(obj.idempresas).trigger("change");
				//EMPRESAS / CLIENTES - DEPARTAMENTOS
				$.when( $('#idempresasf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//CLIENTES
					$.get( "controller/combosback.php?oper=clientes", { idempresas: obj.idempresasf }, function(result){ 
						$("#idclientesf").empty();
						$("#idclientesf").append(result);
						$("#idclientesf").select2({placeholder: ""});
						$("#idclientesf").val(obj.idclientesf).trigger("change");
					});
					//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: obj.idempresasf }, function(result){ 
						$("#iddepartamentosf").empty();
						$("#iddepartamentosf").append(result);
						$("#iddepartamentosf").select2({placeholder: ""});
						$("#iddepartamentosf").val(obj.iddepartamentosf).trigger("change");
					});
				});
				//CLIENTES / PROYECTOS - DEPARTAMENTOS
				$.when( $('#idclientesf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//PROYECTOS
					$.get( "controller/combosback.php?oper=proyectos", { idclientes: obj.idclientesf }, function(result){ 
						$("#idproyectosf").empty();
						$("#idproyectosf").append(result);
						$("#idproyectosf").select2({placeholder: ""});
						$("#idproyectosf").val(obj.idproyectosf).trigger("change");
					});				
					//SITIOS
					$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: obj.idclientesf }, function(result){ 
						$("#unidadejecutoraf").empty();
						$("#unidadejecutoraf").append(result);
						$('#unidadejecutoraf').select2({placeholder: ""});
						$("#unidadejecutoraf").val(obj.unidadf).trigger("change");
					});
				});
				//PROYECTOS / CATEGORIAS
				$.when( $('#idproyectosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=categorias", { tipo: "incidente", idproyectos: obj.idproyectosf }, function(result){ 
						$("#categoriaf").empty();
						$("#categoriaf").append(result);
						$("#categoriaf").select2({placeholder: ""});
						$("#categoriaf").val(obj.categoriaf).trigger("change");
					});
				});
				//CATEGORIAS - SUBCATEGORIAS
				$.when( $('#categoriaf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: obj.idcategoriaf }, function(result){ 
						$("#subcategoriaf").empty();
						$("#subcategoriaf").append(result);
						$("#subcategoriaf").select2({placeholder: ""});
						$("#subcategoriaf").val(obj.subcategoriaf).trigger("change");
					});
				});
				//SITIOS / SERIE
				$.when( $('#unidadejecutoraf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SERIE
					$.get( "controller/combosback.php?oper=serie", { idsitio: obj.unidadf }, function(result){ 
						$("#serief").empty();
						$("#serief").append(result);
						$('#serief').select2({placeholder: ""});
						$("#serief").val(obj.serief).trigger("change");
					});
				});
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: obj.iddepartamentosf }, function(result){ 
						$("#asignadoaf").empty();
						$("#asignadoaf").append(result);
						$('#asignadoaf').select2({placeholder: ""});
						$("#asignadoaf").val(obj.asignadoaf).trigger("change");
					});
				});
				
				if ('modalidadf' in obj) $("#modalidadf").val(obj.modalidadf).trigger('change');
				if ('marcaf' in obj) $("#marcaf").val(obj.marcaf).trigger('change');
				if ('solicitantef' in obj) $("#solicitantef").val(obj.solicitantef).trigger('change');
				if ('estadof' in obj) $("#estadof").val(obj.estadof).trigger('change');				
			}
			dialogfiltrosmasivos.dialog( "open" );						
		}
	});
}

function filtrosMasivos() {
	var dataserialize 	= $("#form_filtrosmasivos").serializeArray();
	var data 			= {};
	
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'categoriaf' || dataserialize[i].name == 'subcategoriaf' || 
			dataserialize[i].name == 'idempresasf' || dataserialize[i].name == 'iddepartamentosf' || 
			dataserialize[i].name == 'idclientesf' || dataserialize[i].name == 'idproyectosf' || 
			dataserialize[i].name == 'modalidadf'  || dataserialize[i].name == 'prioridadf' || 
			dataserialize[i].name == 'solicitantef'  || dataserialize[i].name == 'estadof' || 
			dataserialize[i].name == 'asignadoaf'  || dataserialize[i].name == 'unidadejecutoraf' ||
			dataserialize[i].name == 'marcaf' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	data = JSON.stringify(data);	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/incidentesback.php',
		data: { 
			'oper'	: 'guardarfiltros',
			'data'	: data
		},
		success: function (response) {			
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			tablaincidentes.ajax.reload(null, false);
			dialogfiltrosmasivos.dialog("close");						
		}
	});
}
function limpiarFiltrosMasivos(){	
	$.get( "controller/incidentesback.php?oper=limpiarFiltrosMasivos");
	var dataserialize = $("#form_filtrosmasivos").serializeArray();
	for (var i in dataserialize) {
		$("#"+dataserialize[i].name).val(null).trigger("change");
		tablaincidentes.ajax.reload(null, false);
		dialogfiltrosmasivos.dialog("close");	
	}
}

$(document).ready(function() {
	cargarCombosF();
});


