function cargarCombosF(){
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
		});
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgruposLab", { idempresas: idempresasf }, function(result){ 
			$("#iddepartamentosf").empty();
			$("#iddepartamentosf").append(result); 
			$("#iddepartamentosf").val(12).trigger("change");
		});
		//Asignado a
		$.get( "controller/combosback.php?oper=usuariosDepLab", { iddepartamentos: 12 /*, tipo: 'filtros'*/ }, function(result){ 
			$("#asignadoaf").empty();
			$("#asignadoaf").append(result);
			//$("#asignadoaf").val(asignadoaf.split(',')).trigger("change");						
		});
	//});
	
	//CLIENTES / PROYECTOS - DEPARTAMENTOS
	function recargarClientes(){
		var idclientesf 		= $("#idclientesf").val().join();		
		var idproyectosf 		= $("#idproyectosf").val().join(); 
		//var idestadosf 			= $("#idestadosf").val().join(); 
		
		//Proyectos				
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientesf }, function(result){ 
			$("#idproyectosf").empty();
			$("#idproyectosf").append(result);
			$("#idproyectosf").val(idproyectosf.split(',')).trigger("change"); 
		}); 
		
		//Solicitantes
		$.get( "controller/combosback.php?oper=usuarios", { tipo : 'filtrosmasivos', idclientes: idclientesf }, function(result){ 
			$("#solicitantef").empty();
			$("#solicitantef").append(result);
		});
		
		//ESTADOS
		/* $.get( "controller/combosback.php?oper=estadosLaboratorio", { idclientes: idclientesf, idproyectos: idproyectosf, tipo:"laboratorio" }, function(result){ 
			$("#idestadosf").empty();
			$("#idestadosf").append(result);
			$("#idestadosf").val(idestadosf.split(',')).trigger("change");
		}); */
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
			//$("#idestadosf").empty();
		}        
    }); 
	
	//PROYECTOS / CATEGORIAS
	function recargarProyectos(){
		var idclientesf 		= $("#idclientesf").val().join();
		var idproyectosf 		= $("#idproyectosf").val().join(); 
		//var idestadosf 			= $("#idestadosf").val().join();
		 
		//ESTADOS
		/* $.get( "controller/combosback.php?oper=estadosLaboratorio", { idclientes: idclientesf, idproyectos: idproyectosf, tipo:"laboratorio" }, function(result){ 
			$("#idestadosf").empty();
			$("#idestadosf").append(result);
			$("#idestadosf").val(idestadosf.split(',')).trigger("change");
		}); */
		//Solicitantes
		$.get( "controller/combosback.php?oper=usuarios", { tipo : 'filtrosmasivos', idclientes: idclientesf, idproyectos: idproyectosf }, function(result){ 
			$("#solicitantef").empty();
			$("#solicitantef").append(result);
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
			//$("#idestadosf").empty();
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
		$("#idprioridadesf").empty();
		$("#idprioridadesf").append(result); 
	});	 
	//MARCAS
	$.get( "controller/combosback.php?oper=marcas", { onlydata:"true" }, function(result){ 
		$("#marcaf").empty();
		$("#marcaf").append(result); 
	});	
	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
		$("#solicitantef").empty();
		$("#solicitantef").append(result); 
	});
	//ESTADOS
	$.get( "controller/combosback.php?oper=estadosLaboratorio", { tipo:"laboratorio" }, function(result){ 
		$("#idestadosf").empty();
		$("#idestadosf").append(result); 
	}); 
}	

function verificarfiltros(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/laboratorioback.php',
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
	//$("#idempresasf").val('1').trigger("change");
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/laboratorioback.php',
		data: { 
			'oper'	: 'abrirfiltros'
		},
		beforeSend: function() {
		$('#preloader').css('display','block');
		},
		success: function (response) {
		$('#preloader').css('display','none');
			if (response.data!="") {
				$('#overlay').css('display','none');
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
						$("#idclientesf").val(obj.idclientesf).trigger("change");
					});
					//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgruposLab", { idempresas: obj.idempresasf }, function(result){ 
						$("#iddepartamentosf").empty();
						$("#iddepartamentosf").append(result); 
						$("#iddepartamentosf").val(12).trigger("change");
					});
				});
				//CLIENTES / PROYECTOS - DEPARTAMENTOS
				$.when( $('#idclientesf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//PROYECTOS
					$.get( "controller/combosback.php?oper=proyectos", { idclientes: obj.idclientesf }, function(result){ 
						$("#idproyectosf").empty();
						$("#idproyectosf").append(result); 
						$("#idproyectosf").val(obj.idproyectosf).trigger("change");
					}); 
				});
				//PROYECTOS / CATEGORIAS
				$.when( $('#idproyectosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//ESTADOS
					/* $.get( "controller/combosback.php?oper=estadosLaboratorio", { idempresas: obj.idempresasf, idclientes: obj.idclientesf, idproyectos: obj.idproyectosf, tipo:"laboratorio" }, function(result){ 
						$("#idestadosf").empty();
						$("#idestadosf").append(result);
						$('#idestadosf').select2({placeholder: ""});
						$("#idestadosf").val(obj.estado).trigger("change");
					}); */
				});  
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: obj.iddepartamentosf }, function(result){ 
						$("#asignadoaf").empty();
						$("#asignadoaf").append(result); 
						$("#asignadoaf").val(obj.asignadoaf).trigger("change");
					});
				});				
				if ('diagnosticof' in obj) $("#diagnosticof").val(obj.diagnosticof).trigger('change');
				if ('marcaf' in obj) $("#marcaf").val(obj.marcaf).trigger('change');
				if ('solicitantef' in obj) $("#solicitantef").val(obj.solicitantef).trigger('change');
				if ('idestadosf' in obj) $("#idestadosf").val(obj.idestadosf).trigger('change');
				if ('idprioridadesf' in obj) $("#idprioridadesf").val(obj.idprioridadesf).trigger('change');
			}
		//	$('#modalfiltrosmasivos').modal('show');
		//	$('#modalfiltrosmasivos .modal-lg').css('width','1000px');
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
			dataserialize[i].name == 'idprioridadesf' ||  dataserialize[i].name == 'solicitantef'  ||
			dataserialize[i].name == 'idestadosf' || dataserialize[i].name == 'asignadoaf'  ||  
			dataserialize[i].name == 'marcaf' || dataserialize[i].name == 'diagnosticof' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	data = JSON.stringify(data);	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/laboratorioback.php',
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
		}
	});
}
function limpiarFiltrosMasivos(){
    $('#icono-filtrosmasivos').removeClass('bg-warning');
	$('#icono-filtrosmasivos').addClass('bg-success');
	$.get( "controller/laboratorioback.php?oper=limpiarFiltrosMasivos");
	var dataserialize = $("#form_filtrosmasivos").serializeArray();
	for (var i in dataserialize) {
		$("#"+dataserialize[i].name).val(null).trigger("change");
		tablaincidentes.ajax.reload(null, false);
		$(".chatbox-close").click();
	}
}

$(document).ready(function() {
	cargarCombosF();
});
$("select").select2()


