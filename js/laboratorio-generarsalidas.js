function cerrarDialogSalidas() {
	$('#modalsalidas').modal('hide');
	$('#incidentesalidas').val(null).trigger("change");		
	//limpiarFormulario("#form_incidentes_salidas");		
}
function generarSalidas(){
	var id = filasSeleccionadas;	
	if(id.length == 0){ 
		notification("Advertencia!",'Registros no Seleccionados',"warning"); 
		return;
	}
	
	if(!id[1]){
		abrirdialogIncidenteSalidas(id[0]);
	}else{
		abrirdialogIncidenteSalidas(id);
	}
}
	
function abrirdialogIncidenteSalidas(id){ 
	//Consultar si hay registros a los que ya se les hizo el cierre
	$("#form_incidentes_salidas")[0].reset();
	$("#incidentesalidas").val(id);
	var idsalidas 	= $('#incidentesalidas').val();
	$.ajax({
		type: 'post', 
		dataType: "json",
		url: 'controller/laboratorioback.php',
		data: { 
			'oper'	: 'consultarCierres',
			'id'	: idsalidas 
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
		}, 
		success: function (response) {
			$('#overlay').css('display','none');
			if(response.entregados!="" && response.noresueltos!=""){ 
				notification("Advertencia!",'Ya ha sido realizado el cierre de los siguientes registros:'+response.entregados+' y los siguientes registros no han sido resueltos:'+response.noresueltos,"warning"); 
			}else if(response.entregados!=""){
				notification("Advertencia!",'Ya ha sido realizado el cierre de los siguientes registros:'+response.entregados,"warning"); 
			}else if(response.noresueltos!=""){ 
				notification("Advertencia!",'Los siguientes registros no han sido resueltos:'+response.noresueltos,"warning"); 
			}else{
				$('#modalsalidas').modal('show');
			} 
		},
		error: function () {
			$('#overlay').css('display','none');	
			notification("Error!",'Ha ocurrido un error al actualizar los registros, intente más tarde',"error");			 
		}
	});  
}

function guardarFormIncidenteSalidas() {  
	
	var id 	= $('#incidentesalidas').val(); 
	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/laboratorioback.php',
		data: { 
			'oper'	: 'generarSalidas',
			'id'	: id 
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
			cerrarDialogSalidas();
		},
		success: function (response) {
			//Exportar cierres realizados
			window.open('reportes/laboratorioexportarcierres.php?ids='+id+'&tipo=generar');
			$('#overlay').css('display','none'); 
			notification("¡Exito!",'Registros actualizados satisfactoriamente',"success");
			tablaincidentes.ajax.reload(null, false); 
			tablasalidas.ajax.reload(null, false); 
		},
		error: function () {
			$('#overlay').css('display','none');				
			notification("¡Error!",'Ha ocurrido un error al actualizar los registros, intente más tarde',"error");
		}
	});		
	$(".modal-container").removeClass('swal2-in');		
}


