function cargarCombosMas(){
	//console.log('function cargarCombosMas');
//	$("select").select2();
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
		$("#idclientesmas, #iddepartamentosmas, #idproyectosmas, #idcategoriasmas, #idsubcategoriasmas").empty();
		//CLIENTES
		$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
			$("#idclientesmas").empty();
			$("#idclientesmas").append(result);
			$("#idclientesmas").select2({placeholder: ""});
		});
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgrupos&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
			$("#iddepartamentosmas").empty();
			$("#iddepartamentosmas").append(result);
			$("#iddepartamentosmas").select2({placeholder: ""});
		});
	//});
	//PROYECTOS
	$.get( "controller/combosback.php?oper=proyectos", { onlydata:"true" }, function(result){ 
		$("#idproyectosmas").empty();
		$("#idproyectosmas").append(result);
		$("#idproyectosmas").select2({placeholder: ""});
	});
	//SITIOS
	$.get( "controller/combosback.php?oper=sitio", { onlydata:"true" }, function(result){ 
		$("#idambientesmas").empty();
		$("#idambientesmas").append(result);
		$('#idambientesmas').select2({placeholder: ""});
	});
	//ASIGNADO A
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true", nivel:"2,3" }, function(result){ 
		$("#asignadoamas").empty();
		$("#asignadoamas").append(result);
		$('#asignadoamas').select2({placeholder: ""});		
	});
	//CLIENTES
	$("#idclientesmas").change(function(e,data){
		var idclientes = $("#idclientesmas option:selected").val();			
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idproyectosmas").empty();
			$("#idproyectosmas").append(result);
			$("#idproyectosmas").select2({placeholder: ""});
		});
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idambientesmas").empty();
			$("#idambientesmas").append(result);
			$('#idambientesmas').select2({placeholder: ""});
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idclientes: idclientes, tipo:"incidente" }, function(result){ 
			$("#idestadosmas").empty();
			$("#idestadosmas").append(result);
			$("#idestadosmas").select2({placeholder: ""});
		});
	});	
	//CLIENTES
	$("#idproyectosmas").change(function(e,data){
		var idclientes = $("#idclientesmas option:selected").val();
		var idproyectos = $("#idproyectosmas option:selected").val();
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idclientes: idclientes, idproyectos: idproyectos, tipo:"incidente" }, function(result){ 
			$("#idestadosmas").empty();
			$("#idestadosmas").append(result);
			$("#idestadosmas").select2({placeholder: ""});
		});
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes+"&idproyectos="+idproyectos, { onlydata:"true" }, function(result){ 
			$("#idambientesmas").empty();
			$("#idambientesmas").append(result);
			$('#idambientesmas').select2({placeholder: ""});
		});
	});	
	//ASIGNADO A
	$("#iddepartamentosmas").change(function(e,data){
		var iddepartamentosmas = $("#iddepartamentosmas option:selected").val();
		$.get( "controller/combosback.php?oper=usuariosDep&iddepartamentos="+iddepartamentosmas, { onlydata:"true", nivel:"2,3" }, function(result){ 
			$("#asignadoamas").empty();
			$("#asignadoamas").append(result);
			$('#asignadoamas').select2({placeholder: ""});
		});
	});		
	//CATEGORIAS
	$("#idproyectosmas").change(function(e,data){
		var idproyectos = $("#idproyectosmas option:selected").val();
		$.get( "controller/combosback.php?oper=categorias&tipo=incidente&idproyectos="+idproyectos, { onlydata:"true" }, function(result){ 
			$("#idcategoriasmas").empty();
			$("#idcategoriasmas").append(result);
			$("#idcategoriasmas").select2({placeholder: ""});
		});
	});
	//SUBCATEGORIAS
	$("#idcategoriasmas").change(function(e,data){
		var idcategoria = $("#idcategoriasmas option:selected").val();
		$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+idcategoria, { onlydata:"true" }, function(result){ 
			$("#idsubcategoriasmas").empty();
			$("#idsubcategoriasmas").append(result);
			$("#idsubcategoriasmas").select2({placeholder: ""});
		});
	});
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"incidente" }, function(result){ 
		$("#idestadosmas").append(result);
		$("#idestadosmas").select2({placeholder: ""});
	});
	$("#idambientesmas").change(function(){
		var idsitio = $("#idambientesmas option:selected").val();
		$.get( "controller/combosback.php?oper=serie&idsitio="+idsitio, { onlydata:"true" }, function(result){ 
			$("#idactivosmas").empty();
			$("#idactivosmas").append(result);
			$('#idactivosmas').select2({placeholder: ""});
			$('#idmarcasmas, #idmodelosmas').val('');
		});
	});
	//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#idprioridadesmas").empty();
		$("#idprioridadesmas").append(result);
		$("#idprioridadesmas").select2({placeholder: ""});
	});		
	//SERIE
	/*
	$.get( "controller/combosback.php?oper=serie", { onlydata:"true" }, function(result){ 
		$("#idactivosmas").empty();
		$("#idactivosmas").append(result);
		$('#idactivosmas').select2({placeholder: ""});
	});
	*/
	$("#idactivosmas").change(function(){
		var idserie = $("#idactivosmas option:selected").val();
		$.ajax({
		  url: "controller/combosback.php",
		  type:"POST",
		  data: { oper:"seriesel", idserie: idserie },
		  dataType:"json",
		  success: function(response){
			  $.map(response, function (item) {
				$('#idmarcasmas').val(item.marca);
				$('#idmodelosmas').val(item.modelo);
			  });
		  }
		});
	});
}

//	FORMULARIO INCIDENTE  //
var dialogIncidenteMasivo = $( "#dialog-form-incidentes-masivo" ).dialog({
	width: '72%', 
	maxWidth: 600,
	height: 'auto',
	//modal: true,
	fluid: true,
	resizable: false,
	autoOpen: false,
	open: function(event, ui) {
		//$("#idproyectosmas").focus();
	},
	close: function(event, ui) {
		
	}
});

function cerrarDialogIncidenteMasivo() {
	$('#idempresasmas').val(null).trigger("change");
	$('#iddepartamentosmas').val(null).trigger("change");
	$('#idclientesmas').val(null).trigger("change");
	$('#idproyectosmas').val(null).trigger("change");
	$('#idcategoriasmas').val(null).trigger("change");
	$('#idsubcategoriasmas').val(null).trigger("change");
	$('#idprioridadesmas').val(null).trigger("change");
	$('#idambientesmas').val(null).trigger("change");
	$('#idactivosmas').val(null).trigger("change");	
	$('#asignadoamas').val(null).trigger("change");	
	$('#idestadosmas').val(null).trigger("change");	
	limpiarFormulario("#form_incidentes_mas");
	dialogIncidenteMasivo.dialog('close');	
}

function editarMasivo(){
	var id = filasSeleccionadas;	
	if(id.length == 0){
		demo.showSwal('error-message','','Registros no Seleccionados');
		return;
	}
	
	if(!id[1]){
		abrirdialogIncidenteMasivo(id[0]);
	}else{
		abrirdialogIncidenteMasivo(id);
	}
}

function abrirdialogIncidenteMasivo(id){
	console.log('abrirdialogIncidenteMasivo');
	$("#form_incidentes_mas")[0].reset();
	$("#incidentemas").val(id);
	cargarCombosMas();
	dialogIncidenteMasivo.dialog( "open" );
}

function guardarFormIncidenteMasivo() {
	var id 				= $('#incidentemas').val();
	var dataserialize 	= $("#form_incidentes_mas").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'idempresasmas' || dataserialize[i].name == 'iddepartamentosmas' || 
			dataserialize[i].name == 'idclientesmas' || dataserialize[i].name == 'idproyectosmas' || 
			dataserialize[i].name == 'idcategoriasmas' || dataserialize[i].name == 'idsubcategoriasmas' || 
			dataserialize[i].name == 'idprioridadesmas'  || dataserialize[i].name == 'idambientesmas' || 
			dataserialize[i].name == 'idactivosmas'  || dataserialize[i].name == 'asignadoamas' || 
			dataserialize[i].name == 'idestadosmas' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/postventasback.php',
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
			demo.showSwal('success-message','Buen trabajo','Registros actualizados satisfactoriamente');			
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			tablaincidentes.ajax.reload(null, false);
		},
		error: function () {
			$('#overlay').css('display','none');				
			demo.showSwal('error-message','ERROR','Ha ocurrido un error al actualizar los Registro, intente mas tarde');
		}
	});		
	$(".modal-container").removeClass('swal2-in');		
}


