function cargarCombosMas(){
	//EMPRESAS
	$.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresasmas").empty();
		$("#idempresasmas").append(result);
		$("#idempresasmas").select2({placeholder: ""});
	});
	//EMPRESAS / CLIENTES - DEPARTAMENTOS
	$('#idempresasmas').on('select2:select',function(){
		//CLIENTES
		var idempresas = $("#idempresasmas option:selected").val();
		$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresas }, function(result){ 
			$("#idclientesmas").empty();
			$("#idclientesmas").append(result);
			$("#idclientesmas").select2({placeholder: ""});
		});
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: idempresas }, function(result){ 
			$("#iddepartamentosmas").empty();
			$("#iddepartamentosmas").append(result);
			$("#iddepartamentosmas").select2({placeholder: ""});
		});		
	});
	//CLIENTES / PROYECTOS - CENTROS DE COSTO - ACTIVIDADES
	$('#idclientesmas').on('select2:select',function(){
		var idclientes = $("#idclientesmas option:selected").val();
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
			$("#idproyectosmas").empty();
			$("#idproyectosmas").append(result);
			$("#idproyectosmas").select2({placeholder: ""});
		});
		//CENTROS DE COSTO
		$.get( "controller/combosback.php?oper=centrocostos", { idclientes: idclientes }, function(result){ 
			$("#idcentrocostosmas").empty();
			$("#idcentrocostosmas").append(result);
			$("#idcentrocostosmas").select2({placeholder: ""});
		});
		//ACTIVIDADES
		$.get( "controller/combosback.php?oper=actividades", { idclientes: idclientes }, function(result){ 
			$("#idactividadesmas").empty();
			$("#idactividadesmas").append(result);
			$("#idactividadesmas").select2({placeholder: ""});
		});
	});
	//DEPARTAMENTOS / ASIGNADO A
	$('#iddepartamentosmas').on('select2:select',function(){
		var iddepartamentos = $("#iddepartamentosmas option:selected").val();
		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: iddepartamentos }, function(result){ 
			$("#asignadoamas").empty();
			$("#asignadoamas").append(result);
			$('#asignadoamas').select2({placeholder: ""});		
		});
	});
	//CATEGORIAS 
	$('#idproyectosmas').on('select2:select',function(){
		var idproyectos = $("#idproyectosmas option:selected").val(); 
			$.get( "controller/combosback.php?oper=categorias", { idproyectos: idproyectos }, function(result){ 
			$("#idcategoriasmas").empty();
			$("#idcategoriasmas").append(result);	
			$("#idcategoriasmas").select2({placeholder: ""});
		});
	});	
	//SUBCATEGORIAS 
	$('#idcategoriasmas').on('select2:select',function(){
		var idcategorias = $("#idcategoriasmas option:selected").val(); 
		$.get( "controller/combosback.php?oper=subcategorias", { idcategorias: idcategorias }, function(result){ 
			$("#idsubcategoriasmas").empty();
			$("#idsubcategoriasmas").append(result);
			$("#idsubcategoriasmas").select2({placeholder: ""});
		}); 
	});
	//SECTORES 
	$.get( "controller/combosback.php?oper=ambientesget", { onlydata:"true" }, function(result){ 
		$("#idambientesmas").empty();
		$("#idambientesmas").append(result);	
		$("#idambientesmas").select2({placeholder: ""});
	});
	//SUBSECTORES - ACTIVOS
	$('#idambientesmas').on('select2:select',function(){
		var idambientes = $("#idambientesmas option:selected").val(); 
		$.get( "controller/combosback.php?oper=subambientes", { id: idambientes }, function(result){ 
			$("#idsubambientesmas").empty();
			$("#idsubambientesmas").append(result);
			$("#idsubambientesmas").select2({placeholder: ""});
		});
		$.get( "controller/combosback.php?oper=activos", { idambientes: idambientes }, function(result){ 
			$("#idactivosmas").empty();
			$("#idactivosmas").append(result);
			$("#idactivosmas").select2({placeholder: ""});
		});
	});
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"incidente" }, function(result){ 
		$("#idestadosmas").empty();
		$("#idestadosmas").append(result);
		$("#idestadosmas").select2({placeholder: ""});
	});
	//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#idprioridadesmas").empty();
		$("#idprioridadesmas").append(result);
		$("#idprioridadesmas").select2({placeholder: ""});
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
	$('#idclientesmas').val(null).trigger("change");
	$('#idproyectosmas').val(null).trigger("change");
	$('#idcategoriasmas').val(null).trigger("change");
	$('#idsubcategoriasmas').val(null).trigger("change");
	$('#idserviciosmas').val(null).trigger("change");
	$('#idsistemasmas').val(null).trigger("change");
	$('#idprioridadesmas').val(null).trigger("change");
	$('#idambientesmas').val(null).trigger("change");
	$('#idsubambientesmas').val(null).trigger("change");
	$('#iddepartamentosmas').val(null).trigger("change");	
	$('#asignadoamas').val(null).trigger("change");
	$('#idcentrocostosmas').val(null).trigger("change");
	$('#idactividadesmas').val(null).trigger("change");	
	$('#idestadosmas').val(null).trigger("change");	
	limpiarFormularioMasivo("#form_incidentes_mas");
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
		if( dataserialize[i].name == 'idempresasmas' 		|| dataserialize[i].name == 'idclientesmas' || 
			dataserialize[i].name == 'idproyectosmas' 		|| dataserialize[i].name == 'idcategoriasmas' || 
			dataserialize[i].name == 'idsubcategoriasmas' 	|| dataserialize[i].name == 'idserviciosmas' || 
			dataserialize[i].name == 'idsistemasmas' 		|| dataserialize[i].name == 'idambientesmas' || 
			dataserialize[i].name == 'idsubambientesmas'  	|| dataserialize[i].name == 'iddepartamentosmas' || 
			dataserialize[i].name == 'asignadoamas' 		|| dataserialize[i].name == 'idcentrocostosmas' || 
			dataserialize[i].name == 'idactividadesmas' 	 ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/planactividadesback.php',
		data: { 
			'oper'	: 'guardarActividadMasivo',
			'id'	: id,
			'data' 	: data
		},
		beforeSend: function() {
			$(".loader-maxia").show();
			cerrarDialogIncidenteMasivo();
		},
		success: function (response) {
			$(".loader-maxia").hide();
			demo.showSwal('success-message','Buen trabajo!','Incidentes actualizados satisfactoriamente');			
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			tablactividades.ajax.reload(null, false);
		},
		error: function () {
			$(".loader-maxia").hide();				
			demo.showSwal('error-message','ERROR!','Ha ocurrido un error al actualizar los Registro, intente mas tarde');
		}
	});		
	$(".modal-container").removeClass('swal2-in');		
}

function limpiarFormularioMasivo(form){	
	//$.get( "controller/preventivosback.php?oper=limpiarFiltrosMasivos");
	var dataserialize = $(form).serializeArray();
	for (var i in dataserialize) {
		$("#"+dataserialize[i].name).val(null).trigger("change");
	}
}


