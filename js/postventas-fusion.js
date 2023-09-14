function cargarCombosFusion(){
	console.log('function cargarCombosFusion');
	//INCIDENTES
	$.get( "controller/combosback.php?oper=incidentes", { onlydata:"true" }, function(result){ 
		$("#incidenteafusionar").empty();
		$("#incidenteafusionar").append(result);
		$('#incidenteafusionar').select2({placeholder: ""});
	});
}

var dialogFusion;
dialogFusion = $( "#dialog-form-fusion" ).dialog({					
	width: '50%', // overcomes width:'auto' and maxWidth bug
	maxWidth: 500,
	height: 'auto',
	//modal: true,
	fluid: true, //new option
	resizable: false,
	autoOpen: false
});

function fusionarIncidentes() {		
	var fusioninc 	= $('.incidente-fusion').html();		
	
	if($("#incidenteafusionar").select2("val") != ''){
		idincidentes = JSON.stringify($("#incidenteafusionar").select2("val"));
	}else{
		demo.showSwal('error-message','Alerta!','Seleccione el/los incidentes a Fusionar');
		return;
	}
	$.ajax({
		type: 'post',
		url: 'controller/postventasback.php',
		data: { 
			'oper'			: 'fusionarIncidentes',
			'idincidentes'  : idincidentes,
			'fusioninc' 	: fusioninc
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
			$(".modal-container").addClass('swal2-in');
			cerrarDialogFusion();
		},
		success: function (response) {
			if(response){					
				// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
				tablaincidentes.ajax.reload(null, false);
				demo.showSwal('success-message','Buen trabajo','Fusión realizada satisfactoriamente');
			}else{
				demo.showSwal('error-message','','Ha ocurrido un error al realizar la Fusión, Asegúrese de seleccionar los Incidentes a Fusionar');
			}
			$('#overlay').css('display','none');
			$(".modal-container").removeClass('swal2-in');
		},
		error: function () {
			demo.showSwal('error-message','ERROR','Ha ocurrido un error al realizar la Fusión, intente mas tarde');
		}
	});
	return;
}

function cerrarDialogFusion() {
	$('#incidenteafusionar').val('');
	$('#incidenteafusionar').val(null).trigger("change");
	dialogFusion.dialog('close');
}

function mergeIncidente(){
	var s;
	var radios = '';
	
	var id = filasSeleccionadas;
	if(id == ''){
		demo.showSwal('error-message','Alerta!','Seleccione el Incidente a Prevalecer');
		return;
	}
	$('.incidente-fusion').html('');
	$('.incidente-fusion').html(id);
	cargarCombosFusion();
	dialogFusion.dialog( "open" );
}

function revertirfusion() {
	var id 			= $('#incidente').val();
	var incidente	= $('#incidente').val();
	var fusionado 	= $("#fusionado").val();		
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/postventasback.php',
		data: { 
			'oper'	: 'revertirfusion',
			'id'	: id,
			'incidente'	: incidente,
			'fusionado'	: fusionado
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
			$(".modal-container").addClass('swal2-in');
			cerrarDialogIncidenteNuevo();
		},
		success: function (response) {			
			demo.showSwal('success-message','Buen trabajo','Fusión Revertida Exitosamente');			
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			tablaincidentes.ajax.reload(null, false);								
		},
		error: function () {
			demo.showSwal('error-message','ERROR','Ha ocurrido un error al Revertida la Fusión, intente mas tarde');
		}
	});
	$('#overlay').css('display','none');
	$(".modal-container").removeClass('swal2-in');
}


