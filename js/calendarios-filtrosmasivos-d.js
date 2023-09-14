function cargarCombosF(){
	//$("#idempresasf, #idclientesf, #iddepartamentosf, #idproyectosf, #categoriaf, #subcategoriaf, #idambientesf, #asignadoaf ").select2({placeholder: ""});
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
		$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: idempresasf }, function(result){ 
			$("#iddepartamentosf").empty();
			$("#iddepartamentosf").append(result);
		});
	//});
	//CLIENTES / PROYECTOS - DEPARTAMENTOS
	$('#idclientesf').on('select2:select',function(){
		var idclientesf = $("#idclientesf option:selected").val();	
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientesf }, function(result){ 
			$("#idproyectosf").empty();
			$("#idproyectosf").append(result);
		});				
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientesf }, function(result){ 
			$("#idambientesf").empty();
			$("#idambientesf").append(result);
		});
		//CATEGORIAS
		$("#categoriaf").empty();
	}); 
	
	//Checkbox Carga Categorías según el tipo de registro
	$("#tipoinc").click(function(){
		if($(this).is(':checked')){  
			$("#tipoprev").prop("checked", false); 
		}else{  
			$("#tipoprev").prop("checked", true);
		} 
		var idproyectosf = $("#idproyectosf option:selected").val();
		if(idproyectosf != "" || idproyectosf != undefined || idproyectosf != 0){		
			$.get( "controller/combosback.php?oper=categorias", { tipo: 'incidente', idproyectos: idproyectosf }, function(result){  
				$("#categoriaf").empty();
				$("#categoriaf").append(result);
			});
		}
	});	

	$("#tipoprev").click(function(){
		if($(this).is(':checked')){  
			$("#tipoinc").prop("checked", false);
		}else{  
			$("#tipoinc").prop("checked", true);
		} 
		var idproyectosf = $("#idproyectosf option:selected").val();
		if(idproyectosf != "" || idproyectosf != undefined || idproyectosf != 0){		
			$.get( "controller/combosback.php?oper=categorias", { tipo: 'preventivo', idproyectos: idproyectosf }, function(result){  
				$("#categoriaf").empty();
				$("#categoriaf").append(result);
			});
		}		
	}); 
	
	//PROYECTOS / CATEGORIAS
	$('#idproyectosf').on('select2:select',function(){ 
		if($('#tipoprev').is(':checked')){ 
			var tipo = 'preventivo'; 
		}else if($('#tipoinc').is(':checked')){ 
			var tipo = 'incidente'; 
		}
		var idproyectosf = $("#idproyectosf option:selected").val();	
		$.get( "controller/combosback.php?oper=categorias", { tipo: tipo, idproyectos: idproyectosf }, function(result){  
			$("#categoriaf").empty();
			$("#categoriaf").append(result);
		});
	});
	
	//CATEGORIAS - SUBCATEGORIAS
	$('#categoriaf').on('select2:select',function(){
		var idcategoriaf = $("#categoriaf").val();		
		console.log(idcategoriaf);
		$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+idcategoriaf, function(result){ 
			$("#subcategoriaf").empty();
			$("#subcategoriaf").append(result);
		});
	});
	//SITIOS / SERIE
	$('#idambientesf').on('select2:select',function(){
		var idsitiof = $("#idambientesf option:selected").val();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitiof }, function(result){ 
			$("#serief").empty();
			$("#serief").append(result);
		});
	});
	//DEPARTAMENTOS / ASIGNADO A
	$('#iddepartamentosf').on('select2:select',function(){
		var iddepartamentosf = $("#iddepartamentosf option:selected").val();	
		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: iddepartamentosf }, function(result){ 
			$("#asignadoaf").empty();
			$("#asignadoaf").append(result);
		});
	});	
	//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#prioridadf").empty();
		$("#prioridadf").append(result);
	});	
	//MODALIDADES
	$.get( "controller/combosback.php?oper=modalidades", { onlydata:"true" }, function(result){ 
		$("#modalidadf").empty();
		$("#modalidadf").append(result);
	});
	//MARCAS
	$.get( "controller/combosback.php?oper=marcas", { onlydata:"true" }, function(result){ 
		$("#marcaf").empty();
		$("#marcaf").append(result);
	});
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"incidente" }, function(result){ 
		$("#estadof").empty();
		$("#estadof").append(result);
	});
	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
		$("#solicitantef").empty();
		$("#solicitantef").append(result);
	});		
}	

function verificarfiltros(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/calendarioeventos.php',
		data: { 
			'oper'	: 'verificarfiltros'
		},
		success: function (response) {
			if (response == 1) {
				$('.filtro-mas').addClass('filtro-exist');
			}else{
				$('.filtro-mas').removeClass('filtro-exist');
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
		url: 'controller/calendarioeventos.php',
		data: { 
			'oper'	: 'abrirfiltros'
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
				
				if(obj.tipoprev == 1){ 
					$("#tipoprev").prop("checked", true); 
					$("#tipoinc").prop("checked", false);
					$("#tiposol").prop("checked", false); 
				} 
				
				if(obj.tipoinc == 1){ 
					$("#tipoinc").prop("checked", true); 
				} 
				if(obj.tiposol == 1){ 
					$("#tiposol").prop("checked", true); 
					$("#tipoprev").prop("checked", false); 
					$("#tipoinc").prop("checked", false);
				} 
				 
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
					$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: obj.idempresasf }, function(result){ 
						$("#iddepartamentosf").empty();
						$("#iddepartamentosf").append(result);
						$("#iddepartamentosf").val(obj.iddepartamentosf).trigger("change");
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
					//SITIOS
					$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: obj.idclientesf }, function(result){ 
						$("#idambientesf").empty();
						$("#idambientesf").append(result);
						$("#idambientesf").val(obj.idambientesf).trigger("change");
					});
				}); 
				
				//PROYECTOS / CATEGORIAS
				$.when( $('#idproyectosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					if($('#tipoprev').is(':checked')){ 
						var tipo = 'preventivo'; 
					}else if($('#tipoinc').is(':checked')){ 
						var tipo = 'incidente';
						
					}
					$.get( "controller/combosback.php?oper=categorias", { tipo: tipo, idproyectos: obj.idproyectosf }, function(result){ 
						$("#categoriaf").empty();
						$("#categoriaf").append(result);
						$("#categoriaf").val(obj.categoriaf).trigger("change");
					});
				});
				//CATEGORIAS - SUBCATEGORIAS
				$.when( $('#categoriaf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: obj.idcategoriaf }, function(result){ 
						$("#subcategoriaf").empty();
						$("#subcategoriaf").append(result);
						$("#subcategoriaf").val(obj.subcategoriaf).trigger("change");
					});
				});
				//SITIOS / SERIE
				$.when( $('#idambientesf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SERIE
					$.get( "controller/combosback.php?oper=serie", { idsitio: obj.idambientesf }, function(result){ 
						$("#serief").empty();
						$("#serief").append(result);
						$("#serief").val(obj.serief).trigger("change");
					});
				});
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: obj.iddepartamentosf }, function(result){ 
						$("#asignadoaf").empty();
						$("#asignadoaf").append(result);
						$("#asignadoaf").val(obj.asignadoaf).trigger("change");
					});
				});
				
				if ('modalidadf' in obj) $("#modalidadf").val(obj.modalidadf).trigger('change');
				if ('marcaf' in obj) $("#marcaf").val(obj.marcaf).trigger('change');
				if ('solicitantef' in obj) $("#solicitantef").val(obj.solicitantef).trigger('change');
				if ('estadof' in obj) $("#estadof").val(obj.estadof).trigger('change');				
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
			dataserialize[i].name == 'asignadoaf'  || dataserialize[i].name == 'idambientesf' ||
			dataserialize[i].name == 'marcaf' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	} 
	if($('#tipoinc').is(':checked')){
		data['tipoinc'] = 1;
	}else{
		data['tipoinc'] = '';
	}
	if($('#tipoprev').is(':checked')){
		data['tipoprev'] = 1;
	}else{
		data['tipoprev'] = '';
	}
	
	data = JSON.stringify(data);	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/calendarioeventos.php',
		data: { 
			'oper'	: 'guardarfiltros',
			'data'	: data
		},
		success: function (response) {			
			$('#calendar').fullCalendar( 'refetchEvents' );	
			$('#modalfiltrosmasivos').modal('hide');
			verificarfiltros();
		}
	});
}
function limpiarFiltrosMasivos(){
	$('.filtro-mas').removeClass('filtro-exist');
	$.get( "controller/calendarioeventos.php?oper=limpiarFiltrosMasivos");
	$("#tipoprev").prop("checked", false); 
	$("#tiposol").prop("checked", false); 
	var dataserialize = $("#form_filtrosmasivos").serializeArray();
	for (var i in dataserialize) {
		$("#"+dataserialize[i].name).val(null).trigger("change");
		$('#calendar').fullCalendar( 'refetchEvents' );
		$('#modalfiltrosmasivos').modal('hide');
	}
	$("#tipoinc").prop("checked", true); 
} 

//CALENDARIO
//$('#desdef, #hastaf').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true });

$(document).ready(function() {
	cargarCombosF();
});


