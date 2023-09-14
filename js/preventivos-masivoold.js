function cargarCombosMas(){
	
	//EMPRESAS
	/*
	$.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresasf").empty();
		$("#idempresasf").append(result);
		$("#idempresasf").select2({placeholder: ""});
	});
	*/
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
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgrupos&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
			$("#iddepartamentosmas").empty();
			$("#iddepartamentosmas").append(result);
		});
	//});
	//PROYECTOS
	$.get( "controller/combosback.php?oper=proyectos", { onlydata:"true" }, function(result){ 
		$("#idproyectosmas").empty();
		$("#idproyectosmas").append(result);
	});
	//SITIOS
	$.get( "controller/combosback.php?oper=unidades", { onlydata:"true" }, function(result){ 
		$("#unidadejecutoramas").empty();
		$("#unidadejecutoramas").append(result);
	});
	//ASIGNADO A
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true", nivel:"2,3" }, function(result){ 
		$("#asignadoamas").empty();
		$("#asignadoamas").append(result);
	});
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { tipo:"preventivo" }, function(result){ 
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
		//AMBIENTES
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#unidadejecutoramas").empty();
			$("#unidadejecutoramas").append(result);
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idclientes: idclientes, tipo:"preventivo" }, function(result){ 
			$("#estadomas").empty();
			$("#estadomas").append(result);
		});
	});
	//CLIENTES
	$("#idproyectosmas").change(function(e,data){
		var idclientes = $("#idclientesmas option:selected").val();
		var idproyectos = $("#idproyectosmas option:selected").val();			
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idclientes: idclientes, idproyectos: idproyectos, tipo:"preventivo" }, function(result){ 
			$("#estadomas").empty();
			$("#estadomas").append(result);
		});
		//AMBIENTES
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes+"&idproyectos="+idproyectos, { onlydata:"true" }, function(result){ 
			$("#unidadejecutoramas").empty();
			$("#unidadejecutoramas").append(result);
		});
	});
	//ASIGNADO A
	$("#iddepartamentosmas").change(function(e,data){
		var iddepartamentosmas = $("#iddepartamentosmas option:selected").val();	
		$.get( "controller/combosback.php?oper=usuariosDep&iddepartamentos="+iddepartamentosmas, { onlydata:"true", nivel:"2,3" }, function(result){ 
			$("#asignadoamas").empty();
			$("#asignadoamas").append(result);
		});
	});
	//CATEGORIAS
	$("#idproyectosmas").change(function(e,data){
		var idproyectos = $("#idproyectosmas option:selected").val();	
		$.get( "controller/combosback.php?oper=categorias&tipo=incidente&idproyectos="+idproyectos, { onlydata:"true" }, function(result){ 
			$("#categoriamas").empty();
			$("#categoriamas").append(result);
		});
	});
	//SUBCATEGORIAS
	$("#categoriamas").change(function(e,data){
		var idcategoria = $("#categoriamas option:selected").val();		
		$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+idcategoria, { onlydata:"true" }, function(result){ 
			$("#subcategoriamas").empty();
			$("#subcategoriamas").append(result);
		});
	});
	$("#unidadejecutoramas").change(function(){
		var idsitio = $("#unidadejecutoramas option:selected").val();
		$.get( "controller/combosback.php?oper=serie&idsitio="+idsitio, { onlydata:"true" }, function(result){ 
			$("#seriemas").empty();
			$("#seriemas").append(result);
			$('#marcamas, #modelomas').val('');			
		});
	});
	//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#prioridadmas").empty();
		$("#prioridadmas").append(result);
	});		
	//SERIE
	/*
	$.get( "controller/combosback.php?oper=serie", { onlydata:"true" }, function(result){ 
		$("#seriemas").empty();
		$("#seriemas").append(result);
		$('#seriemas').select2({placeholder: ""});
	});
	*/
	$("#seriemas").change(function(){
		var idserie = $("#seriemas option:selected").val();
		$.ajax({
		  url: "controller/combosback.php",
		  type:"POST",
		  data: { oper:"seriesel", idserie: idserie },
		  dataType:"json",
		  success: function(response){
			  $.map(response, function (item) {
				$('#marcamas').val(item.marca);
				$('#modelomas').val(item.modelo);
			  });
		  }
		});
	});
}

function cerrarDialogIncidenteMasivo() {
	$('#idempresasmas').val(null).trigger("change");
	$('#iddepartamentosmas').val(null).trigger("change");
	$('#idclientesmas').val(null).trigger("change");
	$('#idproyectosmas').val(null).trigger("change");
	$('#categoriamas').val(null).trigger("change");
	$('#subcategoriamas').val(null).trigger("change");
	$('#prioridadmas').val(null).trigger("change");
	$('#unidadejecutoramas').val(null).trigger("change");
	$('#seriemas').val(null).trigger("change");	
	$('#asignadoamas').val(null).trigger("change");	
	$('#estadomas').val(null).trigger("change");
	$('#marcamas').val(null).trigger("change");	
	$('#modelomas').val(null).trigger("change");	
	$('#fecharesolucionmas').val('');	
	$('#resolucionmas').val('');	
	$('#modalmasivos').modal('hide');
}
function editarMasivo(){
	var id = filasSeleccionadas;	
	if(id.length == 0){
		notification("Advertencia!",'Registros no Seleccionados','warning');
		return;
	}
	
	if(!id[1]){
		abrirdialogIncidenteMasivo(id[0]);
	}else{
		abrirdialogIncidenteMasivo(id);
	}
}
	
function abrirdialogIncidenteMasivo(id){
	 
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
		if( dataserialize[i].name == 'idempresasmas' || dataserialize[i].name == 'iddepartamentosmas' || 
			dataserialize[i].name == 'idclientesmas' || dataserialize[i].name == 'idproyectosmas' || 
			dataserialize[i].name == 'categoriamas' || dataserialize[i].name == 'subcategoriamas' || 
			dataserialize[i].name == 'prioridadmas'  || dataserialize[i].name == 'unidadejecutoramas' || 
			dataserialize[i].name == 'seriemas'  || dataserialize[i].name == 'asignadoamas' || 
			dataserialize[i].name == 'estadomas' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	if(data['estadomas'] == '16' && data['fecharesolucionmas'] == ''){
		$("#"+dataserialize['fecharesolucionmas']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucionmas']).css({'border':'1px solid red'});
		notification("Advertencia!",'Debe llenar el campo de Resolución','warning');
	}else if(data['estadomas'] == '16' && data['resolucionmas'] == ''){
		$("#"+dataserialize['resolucionmas']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['resolucionmas']).css({'border':'1px solid red'});
		notification("Advertencia!",'Debe llenar el campo de Resolución','warning');
	}else{
		
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/incidentesback.php',
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
				notification("Correctivos actualizados satisfactoriamente","¡Exito!",'success');
				// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
				tablaincidentes.ajax.reload(null, false);
			},
			error: function () {
				$('#overlay').css('display','none');
				notification("Ha ocurrido un error al actualizar los Registro, intente mas tarde","Error",'error');
			}
		});		
		$(".modal-container").removeClass('swal2-in');		
   }
}
$('#fecharesolucionmas').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true });																																  


