function cargarCombosF(){
	$("#idserviciosf, #idsistemasf, #idambientesf, #idsubambientesf, #responsablef").select2({placeholder: ""});
	  
	//AMBIENTES
	$.get( "controller/combosback.php?oper=ambientesget", { onlydata:"true" }, function(result){ 
		$("#idambientesf").empty();
		$("#idambientesf").append(result);
		$('#idambientesf').select2({placeholder: ""});
	});
	//AMBIENTES -  SUBAMBIENTES
	$("#idambientesf").change(function(e,data){
		var idambientesf = $("#idambientesf option:selected").val();
    	//SUBAMBIENTES
    	$.get( "controller/combosback.php?oper=subambientes", { onlydata:"true", id: idambientesf }, function(result){ 
    		$("#idsubambientesf").empty();
    		$("#idsubambientesf").append(result);
    		$('#idsubambientesf').select2({placeholder: ""});
    	}); 
	}); 
	//RESPONSABLES
	$.get( "controller/combosback.php?oper=responsables", { onlydata: "true", tipo: 'responsables' }, function(result){ 
		$("#responsablef").empty();
		$("#responsablef").append(result);
		$("#responsablef").select2({placeholder: ""});
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

function verificarfiltros(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/planactividadesback.php',
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
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/planactividadesback.php',
		data: { 
			'oper'	: 'abrirfiltros'
		},
		beforeSend: function() {
			$(".loader-maxia").show();
		},
		success: function (response) {	
			$(".loader-maxia").hide();
			if (response.data!="") {
				var obj = JSON.parse(response.data);					
				$("#relleno").val(obj.relleno); 
				
				//SERVICIOS
				$("#idserviciosf").val(obj.idserviciosf).trigger("change");
				//SERVICIOS - SISTEMAS
				$.when( $('#idserviciosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SISTEMAS					
					$.get( "controller/combosback.php?oper=sistemas", { onlydata:"true", idservicios: obj.idserviciosf }, function(result){ 
						$("#idsistemasf").empty();
						$("#idsistemasf").append(result);
						$("#idsistemasf").select2({placeholder: ""});
						$("#idsistemasf").val(obj.idsistemasf).trigger("change");
					});
				});
				 
				if ('idambientesf' in obj) $("#idambientesf").val(obj.idambientesf).trigger('change');
				if ('idsubambientesf' in obj) $("#idsubambientesf").val(obj.idsubambientesf).trigger('change');
				if ('responsablef' in obj) $("#responsablef").val(obj.responsablef).trigger('change'); 	
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
		if( dataserialize[i].name == 'idserviciosf' ||  dataserialize[i].name == 'idsistemasf' ||
			dataserialize[i].name == 'idambientesf' || dataserialize[i].name == 'idsubambientesf'  ||
			dataserialize[i].name == 'responsablef' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	data = JSON.stringify(data);	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/planactividadesback.php',
		data: { 
			'oper'	: 'guardarfiltros',
			'data'	: data
		},
		success: function (response) {			
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			tablactividades.ajax.reload(null, false);
			dialogfiltrosmasivos.dialog("close");
			verificarfiltros();
		}
	});
}
function limpiarFiltrosMasivos(){
	$('.filtro-mas').removeClass('filtro-exist');
	$.get( "controller/planactividadesback.php?oper=limpiarFiltrosMasivos");
	var dataserialize = $("#form_filtrosmasivos").serializeArray();
	for (var i in dataserialize) {
		$("#"+dataserialize[i].name).val(null).trigger("change");
		tablactividades.ajax.reload(null, false);
		dialogfiltrosmasivos.dialog("close");	
	}
}

$(document).ready(function() {
	cargarCombosF();
});


