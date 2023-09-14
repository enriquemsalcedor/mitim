//$("select").select2({ language: "es" });
function cargarCombosMas(){
	//console.log('function cargarCombosMas');
	/* $("#idempresasmas, #idclientesmas, #iddepartamentosmas, #idproyectosmas, #diagnosticomas").select2(); */
	 
	//CLIENTES
	//$("#idempresasmas").change(function(e,data){
		//var idempresas = $("#idempresasmas option:selected").val();
		var idempresas = 1;
		$("#idclientesmas, #iddepartamentosmas, #idproyectosmas, #categoriamas, #subcategoriamas").empty();
		//CLIENTES
		$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
			$("#idclientesmas").empty();
			$("#idclientesmas").append(result); 
		}); 
	//});
	//PROYECTOS
	$.get( "controller/combosback.php?oper=proyectos", { onlydata:"true" }, function(result){ 
		$("#idproyectosmas").empty();
		$("#idproyectosmas").append(result); 
	}); 
	//ESTADOS
	$.get( "controller/combosback.php?oper=estadosLaboratorio", { tipo:"laboratorio" }, function(result){ 
		$("#estadomas").empty();
		$("#estadomas").append(result); 
	});
	//CLIENTES
	$("#idclientesmas").change(function(e,data){
		var idclientes = $("#idclientesmas option:selected").val();
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idproyectosmas").empty();
			$("#idproyectosmas").append(result); 
		}); 
		//ESTADOS
		$.get( "controller/combosback.php?oper=estadosLaboratorio", { idclientes: idclientes, tipo:"laboratorio" }, function(result){ 
			$("#estadomas").empty();
			$("#estadomas").append(result); 
		});
	});
	//CLIENTES
	$("#idproyectosmas").change(function(e,data){
		var idclientes = $("#idclientesmas option:selected").val();
		var idproyectos = $("#idproyectosmas option:selected").val();			
		//ESTADOS
		$.get( "controller/combosback.php?oper=estadosLaboratorio", { idclientes: idclientes, idproyectos: idproyectos, tipo:"laboratorio" }, function(result){ 
			$("#estadomas").empty();
			$("#estadomas").append(result); 
		});
		//PRIORIDADES
		$.get( "controller/combosback.php?oper=prioridades", { idclientes: idclientes, idproyectos: idproyectos }, function(result){ 
			$("#prioridadmas").empty();
			$("#prioridadmas").append(result); 
		});
	});
	
	//PRIORIDAD
	/* $.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#prioridadmas").empty();
		$("#prioridadmas").append(result); 
	});  */
}

function cerrarDialogIncidenteMasivo() {
	$('#idempresasmas').val(null).trigger("change");
	//$('#iddepartamentosmas').val(null).trigger("change");
	$('#idclientesmas').val(null).trigger("change");
	$('#idproyectosmas').val(null).trigger("change");
	$('#prioridadmas').val(null).trigger("change");
	//$('#asignadoamas').val(null).trigger("change");	
	$('#estadomas').val(null).trigger("change");	
	limpiarFormulario("#form_incidentes_mas");
	$('#modalmasivos').modal('hide');
}
function editarMasivo(){
	var id = filasSeleccionadas;	
	if(id.length == 0){ 
		notification("Advertencia!","Registros no seleccionados",'warning'); 
		return;
	}
	
	if(!id[1]){
		abrirdialogIncidenteMasivo(id[0]);
	}else{
		abrirdialogIncidenteMasivo(id);
	}
}
	
function abrirdialogIncidenteMasivo(id){ 
	$("#form_incidentes_mas")[0].reset();
	$("#incidentemas").val(id);
	let idincant     = id.toString() 
	let idincantview = idincant.replaceAll(',',', ');
	$("#incidentesview").val(idincantview);
	cargarCombosMas();
	$('#modalmasivos').modal('show');
}
function guardarFormIncidenteMasivo() {
	var id 				= $('#incidentemas').val();
	var dataserialize 	= $("#form_incidentes_mas").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'idempresasmas' || /* dataserialize[i].name == 'iddepartamentosmas' ||  */
			dataserialize[i].name == 'idclientesmas' || dataserialize[i].name == 'idproyectosmas' ||  
			dataserialize[i].name == 'prioridadmas' ||  /* dataserialize[i].name == 'asignadoamas' ||  */
			dataserialize[i].name == 'estadomas' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/laboratorioback.php',
		data: { 
			'oper'	: 'guardarIncidenteMasivo',
			'id'	: id,
			'data' 	: data
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
			cerrarDialogIncidenteMasivo();
		},
		success: function (response) {	
			$('#overlay').css('display','none'); 
			notification("Exito!","Registros actualizados satisfactoriamente",'success'); 			
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			tablaincidentes.ajax.reload(null, false);
		},
		error: function () {
			$('#overlay').css('display','none');				 
			notification("Error!","Ha ocurrido un error al actualizar los Registro, intente mas tarde",'error'); 			
		}
	});		
	$(".modal-container").removeClass('swal2-in');		
}


