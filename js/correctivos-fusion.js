const cargarCombosFusion=(id)=>{ 
	//Correctivos
	$.get( "controller/combosback.php?oper=incidentes", { id: id }, function(result){ 
		$("#incidenteafusionar").empty();
		$("#incidenteafusionar").append(result);
	});
}

function fusionarIncidentes() {		
	var fusioninc 	= $('.incidente-fusion').html();		
	
	if($("#incidenteafusionar").select2("val") != ''){
		idincidentes = JSON.stringify($("#incidenteafusionar").select2("val"));
	}else{
		notification("Advertencia!",'Seleccione el/los incidentes a Fusionar','warning');
		return;
	}
	$.ajax({
		type: 'post',
		url: 'controller/incidentesback.php',
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
				notification("Fusión realizada satisfactoriamente","¡Exito!",'success');
			}else{
				notification("Ha ocurrido un error al realizar la Fusión, Asegúrese de seleccionar los Incidentes a Fusionar","Error",'error');
			}
			$(".modal-container").removeClass('swal2-in');
		},
		error: function () {
			$('#overlay').css('display','none');
			notification("Ha ocurrido un error al realizar la Fusión, Asegúrese de seleccionar los Incidentes a Fusionar","Error",'error');
		}
	});
	return;
}

function cerrarDialogFusion() {
	$('#incidenteafusionar').val('');
	$('#incidenteafusionar').val(null).trigger("change");
	$('#modalfusion').modal('hide');
}

/* function mergeIncidente(){
	var s;
	var radios = '';
	
	var id = filasSeleccionadas;
	console.log("FILAS SELECCIONADAS ES:"+filasSeleccionadas)
	if(id == ''){
		notification("Advertencia!",'Seleccione el Incidente a Prevalecer','warning');
		return;
	}
	$('.incidente-fusion').html('');
	$('.incidente-fusion').html(id);
	cargarCombosFusion();
	$('#modalfusion').modal('show');
} */

const mergeIncidente=()=>{
	let id = filasSeleccionadas; 
	if(id == ''){
		notification("Advertencia!",'Seleccione el correctivo a prevalecer','warning');
		return;
	}else{
		let strfilas = filasSeleccionadas.toString();
		let result	 = strfilas.indexOf(","); 
		if(result == -1){
			//Seleccionó un solo correctivo 
			$('.incidente-fusion').html('');
			$('.incidente-fusion').html(id);
			cargarCombosFusion(strfilas);
			$('#modalfusion').modal('show');
		}else{
			//Seleccionó más de un correctivo 
			notification("Advertencia!",'Debe seleccionar un solo correctivo','warning');
		}
	} 
}


