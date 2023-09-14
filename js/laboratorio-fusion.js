function cargarCombosFusion(id){
	//console.log('function cargarCombosFusion');
	//INCIDENTES
	$.get( "controller/combosback.php?oper=laboratorios&idfusion="+id, { onlydata:"true" }, function(result){ 
		$("#incidenteafusionar").empty();
		$("#incidenteafusionar").append(result); 
	});
}

function fusionarIncidentes() {		
	var fusioninc 	= $('.incidente-fusion').html();		
	
	if($("#incidenteafusionar").select2("val") != ''){
		idincidentes = JSON.stringify($("#incidenteafusionar").select2("val"));
	}else{
		notification("Advertencia!",'Seleccione el/los registros a Fusionar',"warning");
		return;
	}
	$.ajax({
		type: 'post',
		url: 'controller/laboratorioback.php',
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
			$('#overlay').css('display','none');
			if(response){					
				// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
				tablaincidentes.ajax.reload(null, false); 
				notification("Exito!",'Fusión realizada satisfactoriamente',"success");
			}else{ 
				notification("Error!",'Ha ocurrido un error al realizar la Fusión, Asegúrese de seleccionar los Incidentes a Fusionar',"error");
			}
			$(".modal-container").removeClass('swal2-in');
		},
		error: function () {
			$('#overlay').css('display','none'); 
			notification("Error!",'Ha ocurrido un error al realizar la Fusión, intente mas tarde',"error");
		}
	});
	return;
}

function cerrarDialogFusion() {
	$('#incidenteafusionar').val('');
	$('#incidenteafusionar').val(null).trigger("change");
	$('#modalfusion').modal('hide');
}

function mergeIncidente(){
	var s;
	var radios = '';
	
	var id = filasSeleccionadas;
	var seleccion = valoritems;
	if(seleccion>1){ 
		notification("Advertencia!",'Seleccione solo un incidente a prevalecer',"warning");
		return;
	}
	if(id == ''){ 
		notification("Advertencia!",'Seleccione el Incidente a Prevalecer',"warning");
		return;
	}
	$('.incidente-fusion').html('');
	$('.incidente-fusion').html(id);
	cargarCombosFusion(id);
	$('#modalfusion').modal('show');
} 


