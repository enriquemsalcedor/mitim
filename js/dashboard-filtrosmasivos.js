function cargarCombosF(){
	$("#idempresasf, #idclientesf, #iddepartamentosf, #idproyectosf, #categoriaf, #subcategoriaf, #unidadejecutoraf, #asignadoaf ").select2({placeholder: ""});
	//EMPRESAS
	$.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresasf").empty();
		$("#idempresasf").append(result);
		$("#idempresasf").select2({placeholder: ""});
	});
	
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
	function recargarClientes(){
		var idclientesf 		= $("#idclientesf").val().join();		
		var idproyectosf 		= $("#idproyectosf").val().join();
		var idcategoriaf 		= $("#categoriaf").val().join();
		var subcategoriaf 		= $("#subcategoriaf").val().join();
		var unidadejecutoraf 	= $("#unidadejecutoraf").val().join();
		var estadof 			= $("#estadof").val().join();
		//var modalidadf 		= $("#modalidadf").val().join();
		//var marcaf 			= $("#marcaf").val().join();
		//var prioridadf 		= $("#prioridadf").val().join();
		//var iddepartamentosf 	= $("#iddepartamentosf").val().join();
		//var asignadoaf 		= $("#asignadoaf").val().join();
		//var solicitantef 		= $("#solicitantef").val().join();
		
		//PROYECTOS				
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientesf }, function(result){ 
			$("#idproyectosf").empty();
			$("#idproyectosf").append(result);
			$("#idproyectosf").val(idproyectosf.split(',')).trigger("change");
			//CATEGORIAS
			$.get( "controller/combosback.php?oper=categorias", { tipo: "incidente", siglasproy: "si", idproyectos: idproyectosf }, function(result){ 
				$("#categoriaf").empty();
				$("#categoriaf").append(result);
				$("#categoriaf").val(idcategoriaf.split(',')).trigger("change");
				//SUBCATEGORIAS
				$.get( "controller/combosback.php?oper=categorias", { idcategoria: idcategoriaf, siglascatproy: "si" }, function(result){ 
					$("#subcategoriaf").empty();
					$("#subcategoriaf").append(result);
					$("#subcategoriaf").val(subcategoriaf.split(',')).trigger("change");				
				});
			});
		});
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientesf }, function(result){ 
			$("#unidadejecutoraf").empty();
			$("#unidadejecutoraf").append(result);
			$("#unidadejecutoraf").val(unidadejecutoraf.split(',')).trigger("change");
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idclientes: idclientesf, idproyectos: idproyectosf, tipo:"incidente" }, function(result){ 
			$("#estadof").empty();
			$("#estadof").append(result);
			$("#estadof").val(estadof.split(',')).trigger("change");
		});
	}
	$('#idclientesf').on('select2:select',function(){
		recargarClientes();
	});
	 
	$('#idclientesf').on("select2:unselect", function(e){
		var idclientesf = $("#idclientesf").val().join();
		if(idclientesf != ""){
			recargarClientes();
		}else{
			$("#idproyectosf").empty();
			$("#categoriaf").empty();
			$("#subcategoriaf").empty();
			$("#unidadejecutoraf").empty();
			$("#estadof").empty();
		}        
    }); 
	
	//PROYECTOS / CATEGORIAS
	function recargarProyectos(){
		var idclientesf 		= $("#idclientesf").val().join();
		var idproyectosf 		= $("#idproyectosf").val().join();
		var idcategoriaf 		= $("#categoriaf").val().join();
		var subcategoriaf 		= $("#subcategoriaf").val().join();
		var estadof 			= $("#estadof").val().join();
		
		//CATEGORIAS
		$.get( "controller/combosback.php?oper=categorias", { tipo: "incidente", siglasproy: "si", idproyectos: idproyectosf }, function(result){ 
			$("#categoriaf").empty();
			$("#categoriaf").append(result);
			$("#categoriaf").val(idcategoriaf.split(',')).trigger("change");
			//SUBCATEGORIAS
			$.get( "controller/combosback.php?oper=categorias", { idcategoria: idcategoriaf, siglascatproy: "si" }, function(result){ 
				$("#subcategoriaf").empty();
				$("#subcategoriaf").append(result);
				$("#subcategoriaf").val(subcategoriaf.split(',')).trigger("change");				
			});
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idclientes: idclientesf, idproyectos: idproyectosf, tipo:"incidente" }, function(result){ 
			$("#estadof").empty();
			$("#estadof").append(result);
			$("#estadof").val(estadof.split(',')).trigger("change");
		});
	}
	
	$('#idproyectosf').on('select2:select',function(){
		recargarProyectos();
	});
	
	$('#idproyectosf').on("select2:unselect", function(e){
		var idproyectosf = $("#idproyectosf").val().join();  
		if(idproyectosf != ""){
			recargarProyectos();
		}else{
			$("#categoriaf").empty();
			$("#subcategoriaf").empty();
			$("#estadof").empty();
		}
    });
	
	//CATEGORIAS - SUBCATEGORIAS
	function recargarCategorias(){
		var idcategoriaf 		= $("#categoriaf").val().join();
		var subcategoriaf 		= $("#subcategoriaf").val().join();
		//SUBCATEGORIAS
		$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: idcategoriaf, siglascatproy: "si" }, function(result){ 
			$("#subcategoriaf").empty();
			$("#subcategoriaf").append(result);
			$("#subcategoriaf").val(subcategoriaf.split(',')).trigger("change");
		});
	}
	
	$('#categoriaf').on('select2:select',function(){
		recargarCategorias();
	});
	
	$('#categoriaf').on('select2:unselect',function(){
		var categoriaf = $("#categoriaf").val().join();
		if(categoriaf != ""){
			recargarCategorias();
		}else{
			$("#subcategoriaf").empty();
		}
	});
	
	//SITIOS / SERIE
	function recargarSitios(){
		var idsitiof = $("#unidadejecutoraf").val().join();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitiof }, function(result){ 
			$("#serief").empty();
			$("#serief").append(result);
			$('#serief').select2({placeholder: ""});
		});
	}
	$('#unidadejecutoraf').on('select2:select',function(){
		recargarSitios();
	});
	
	$('#unidadejecutoraf').on('select2:unselect',function(){
		var unidadejecutoraf = $("#unidadejecutoraf").val().join();
		if(unidadejecutoraf != ""){
			recargarSitios();
		}else{
			$("#serief").empty();
		}
	});
	
	//DEPARTAMENTOS / ASIGNADO A
	function recargarDepartamentos(){
		var iddepartamentosf = $("#iddepartamentosf").val().join();	
		var asignadoaf 		= $("#asignadoaf").val().join();
		
		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: iddepartamentosf, tipo: 'filtros' }, function(result){ 
			$("#asignadoaf").empty();
			$("#asignadoaf").append(result);
			$("#asignadoaf").val(asignadoaf.split(',')).trigger("change");						
		});
	}
	
	$('#iddepartamentosf').on('select2:select',function(){
		var iddepartamentosf = $("#iddepartamentosf").val().join(); 
			recargarDepartamentos(); 
	});	
	
	$('#iddepartamentosf').on('select2:unselect',function(){
		var iddepartamentosf = $("#iddepartamentosf").val().join(); 
		if(iddepartamentosf != ""){
			recargarDepartamentos(); 
		}else{
			$("#asignadoaf").empty(); 
		}
		
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
	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
		$("#solicitantef").empty();
		$("#solicitantef").append(result);
		$('#solicitantef').select2({placeholder: ""});
	});		
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { tipo:"incidente" }, function(result){ 
		$("#estadof").empty();
		$("#estadof").append(result);
		$('#estadof').select2({placeholder: ""});
	});	 
}

function verificarfiltros(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/dashboardback.php',
		data: { 
			'opcion'	: 'verificarfiltros'
		},
		success: function (response) {
			if (response == 1) {
				$('.filtro-mas').addClass('filtro-exist');
			}else{
				$('.filtro-mas').removeClass('filtro-exist');
			}
			tablaincidentespendientes.ajax.reload(null, false); 
			tablahistorialequipos.ajax.reload(null, false);
			tablaincidentesequipos.ajax.reload(null, false);
			cargarDatos();
		}
	});
}
verificarfiltros();

function abrirFiltrosMasivos(){
	//$("#idempresasf").val('1').trigger("change");
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/dashboardback.php',
		data: { 
			'opcion'	: 'abrirfiltros'
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		success: function (response) {
			$('#overlay').css('display','none');
			if (response.data!="") {				
				var obj = JSON.parse(response.data);					
				$("#relleno").val(obj.relleno);
				$("#desdef").val(obj.desdef);
				$("#hastaf").val(obj.hastaf);
				
				//EMPRESAS
				$("#idempresasf").val(obj.idempresasf).trigger("change");
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
						$("#unidadejecutoraf").val(obj.unidadejecutoraf).trigger("change");
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
					//ESTADOS
					$.get( "controller/combosback.php?oper=estados", { idempresas: item.idempresas, idclientes: item.idclientes, idproyectos: item.idproyectos, tipo:"incidente" }, function(result){ 
						$("#estadof").empty();
						$("#estadof").append(result);
						$('#estadof').select2({placeholder: ""});
						$("#estadof").val(obj.estado).trigger("change");
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
				//if ('estadof' in obj) $("#estadof").val(obj.estadof).trigger('change');				
			}
			$('#modalfiltrosmasivos').modal('show');
			$('#modalfiltrosmasivos .modal-lg').css('width','1000px');
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
		url: 'controller/dashboardback.php',
		data: { 
			'opcion'	: 'guardarfiltros',
			'data'	: data
		},
		success: function (response) {			
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			// tablaincidentes.ajax.reload(null, false);
			$('#modalfiltrosmasivos').modal('hide');
			verificarfiltros();
			// window.location.reload();
		}
	});
}
function limpiarFiltrosMasivos(){
	$('.filtro-mas').removeClass('filtro-exist');
	$.get( "controller/dashboardback.php?opcion=limpiarFiltrosMasivos");
	var dataserialize = $("#form_filtrosmasivos").serializeArray();
	for (var i in dataserialize) {
		$("#"+dataserialize[i].name).val(null).trigger("change");
		// tablaincidentes.ajax.reload(null, false);
		tablaincidentespendientes.ajax.reload(null, false); 
		tablahistorialequipos.ajax.reload(null, false);
		tablaincidentesequipos.ajax.reload(null, false);
		cargarDatos();
		$('#modalfiltrosmasivos').modal('hide');
	}
}

//CALENDARIO
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false });
	$('#fecharesolucion').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true });
	$('#fechacreacion, #fechareal').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false  });
	$('#horacreacion').bootstrapMaterialDatePicker({switchOnClick:true, date:false, format : 'HH:mm' });
	
	$('#calendarhidendesde').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD' }).on('change',function(){
	    var fechadesdeoculto = $('#calendarhidendesde').val();
	    $('#desdef').val(fechadesdeoculto);
	});	
	$('#calendarhidenhasta').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD' }).on('change',function(){
	    var fechahastaoculto = $('#calendarhidenhasta').val();
	    $('#hastaf').val(fechahastaoculto);
	});	
	$('.iconcalfdesde').on( 'click', function (e) { 
	    $('#calendarhidendesde').dblclick();
	});	
	$('.iconcalfhasta').on( 'click', function (e) { 
	    $('#calendarhidenhasta').dblclick();
	});

$(document).ready(function() {
	cargarCombosF();
});


