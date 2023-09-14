const cargarCombosMas = ()=>{
	 
	var idempresas = 1;
	//$("#idclientesmas, #iddepartamentosmas, #idproyectosmas, #categoriamas, #subcategoriamas").empty();
	
	//Clientes
	$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, function(result){ 
		$("#idclientesmas").empty();
		$("#idclientesmas").append(result);
	});  
	
	//Proyectos
	$.get( "controller/combosback.php?oper=proyectos", function(result){ 
		$("#idproyectosmas").empty();
		$("#idproyectosmas").append(result);
	});
	
	//Marcas
	$.get( "controller/combosback.php?oper=marcas", function(result){ 
		$("#idmarcasmas").empty();
		$("#idmarcasmas").append(result);
	}); 
	 
	//Ubicaciones
	$.get( "controller/combosback.php?oper=unidades", function(result){ 
		$("#idubicacionesmas").empty();
		$("#idubicacionesmas").append(result);
	});
	
	//Tipos
	$.get( "controller/combosback.php?oper=tipos", function(result){ 
		$("#idtiposmas").empty();
		$("#idtiposmas").append(result);
	});
	
	
	//Tipos
	$("#idtiposmas").change(function(e,data){
		let idtipos = $("#idtiposmas option:selected").val();
		//Subtipos
		$.get( "controller/combosback.php?oper=subtipos&idtipo="+idtipos, function(result){ 
			$("#idsubtiposmas").empty();
			$("#idsubtiposmas").append(result);
		}); 
	});
	
	//Clientes
	$("#idclientesmas").change(function(e,data){
		let idclientes = $("#idclientesmas option:selected").val();
		//Proyectos
		$.get( "controller/combosback.php?oper=proyectos&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idproyectosmas").empty();
			$("#idproyectosmas").append(result);
		});
		//Ubicaciones
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idubicacionesmas").empty();
			$("#idubicacionesmas").append(result);
		}); 
		//Responsables
		$.get( "controller/combosback.php?oper=responsablesactivos", { idclientes: idclientes }, function(result){ 
			$("#idresponsablesmas").empty();
			$("#idresponsablesmas").append(result);
		});
	});
	
	//Proyectos
	$("#idproyectosmas").change(function(e,data){
		let idclientes = $("#idclientesmas option:selected").val();
		let idproyectos = $("#idproyectosmas option:selected").val();	 
		
		//Responsables
		$.get( "controller/combosback.php?oper=responsablesactivos", { idclientes: idclientes, idproyectos: idproyectos }, function(result){ 
			$("#idresponsablesmas").empty();
			$("#idresponsablesmas").append(result);
		});
		
	});  
	
	//Modelos
	$("#idmarcasmas").change(function(e,data){
		
		let idmarcas = $("#idmarcasmas option:selected").val();
		
		//Modelos
		$.get( "controller/combosback.php?oper=modelos&idmarcas="+idmarcas, function(result){ 
			$("#idmodelosmas").empty();
			$("#idmodelosmas").append(result);
		}); 
		
	});
	
	//Ubicaciones
	$("#idubicacionesmas").change(function(e,data){
		
		let id = $("#idubicacionesmas option:selected").val();
		
		//Áreas
		$.get( "controller/combosback.php?oper=subambientes&id="+id, function(result){ 
			$("#idareasmas").empty();
			$("#idareasmas").append(result);
		}); 
	});
}

const cerrarDialogIncidenteMasivo = ()=>{
	$('#idempresasmas').val(null).trigger("change"); 
	$('#idclientesmas').val(null).trigger("change");
	$('#idproyectosmas').val(null).trigger("change");  
	$('#idmarcasmas').val(null).trigger("change");  
	$('#idmodelosmas').val(null).trigger("change");  
	$('#idresponsablesmas').val(null).trigger("change");	
	$('#idubicacionesmas').val(null).trigger("change");
	$('#idareasmas').val(null).trigger("change");	
	$('#estadomas').val(null).trigger("ACTIVO");
	$('#idtiposmas').val(null).trigger("change");	
	$('#idsubtiposmas').val(null).trigger("change");	
	$('#modalmasivos').modal('hide');
}

const editarMasivo = ()=>{
	let id = filasSeleccionadas;
	let series = seriesSeleccionadas; 
	
	if(id.length == 0){
		notification("Advertencia!",'Registros no Seleccionados','warning');
		return;
	} 
	
	!id[1] ? abrirdialogIncidenteMasivo(id[0],series) : abrirdialogIncidenteMasivo(id,series);
}
	
const abrirdialogIncidenteMasivo = (id,series)=>{ 

	$("#incidentemas").val(id); 
	let seriesant     = series.toString() 
	let seriesantview = seriesant.replaceAll(',',', '); 
	
	$("#incidentesview").val(seriesantview);
	cargarCombosMas();
	$('#modalmasivos').modal('show');
}

const guardarFormIncidenteMasivo = ()=>{
	let id 				= $('#incidentemas').val();
	let dataserialize 	= $("#form_activos_mas").serializeArray();
	let data 			= {};
	
	for (let i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'idempresasmas' || dataserialize[i].name == 'idclientesmas' || 
			dataserialize[i].name == 'idproyectosmas' || dataserialize[i].name == 'idmarcasmas' || 
			dataserialize[i].name == 'idmodelosmas' || dataserialize[i].name == 'idresponsablesmas' || 
			dataserialize[i].name == 'idubicacionesmas' || dataserialize[i].name == 'idareasmas' || 
			dataserialize[i].name == 'estadomas' || dataserialize[i].name == 'idtiposmas' ||
			dataserialize[i].name == 'idsubtiposmas' 
			){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	} 
		
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/activosback.php',
		data: { 
			'oper'	: 'editarMasivo',
			'id'	: id,
			'data' 	: data
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
			cerrarDialogIncidenteMasivo();
		},
		success: function (response) {
			$('#overlay').css('display','none');								
			notification("Activos actualizados satisfactoriamente","¡Exito!",'success'); 
			tbactivos.ajax.reload(null, false);
		},
		error: function () {
			$('#overlay').css('display','none');
			notification("Ha ocurrido un error al actualizar los activos, intente mas tarde","Error",'error');
		}
	});		
	$(".modal-container").removeClass('swal2-in');	 
} 


