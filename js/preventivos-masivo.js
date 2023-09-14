








//Clientes 
const cargarClientesMas = idempresas =>{
	$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
		$("#idclientesmas").empty();
		$("#idclientesmas").append(result);
	});
}
//Proyectos
const cargarProyectosMas = idclientes =>{  
	$.get( "controller/combosback.php?oper=proyectos&idclientes="+idclientes, {  }, function(result){ 
		$("#idproyectosmas").empty();
		$("#idproyectosmas").append(result);
	});
} 
//Ambientes
const cargarAmbientesMas = (idclientes,idproyectos) => {
	$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes+"&idproyectos="+idproyectos, { }, function(result){ 
		$("#unidadejecutoramas").empty();
		$("#unidadejecutoramas").append(result);
	});	
}
//Estados
const cargarEstadosMas = idproyectos => {
	$.get( "controller/combosback.php?oper=estados", { idproyectos: idproyectos, tipo:"preventivo" }, function(result){ 
			$("#estadomas").empty();
			$("#estadomas").append(result);
		});
}
//Departamentos
const cargarDepartamentosMas = idproyectos => {
	$.get( "controller/combosback.php?oper=departamentosgrupos&idproyectos="+idproyectos, {  }, function(result){ 
			$("#iddepartamentosmas").empty();
			$("#iddepartamentosmas").append(result);
		});
}
//Prioridades
const cargarPrioridadesMas = (idclientes,idproyectos) => {
	$.get( "controller/combosback.php?oper=prioridades", { idclientes: idclientes, idproyectos: idproyectos }, function(result){ 
			$("#prioridadmas").empty();
			$("#prioridadmas").append(result);
		}); 
}
//Asignados
const cargarAsignadoMas = iddepartamentos =>{
	$.get( "controller/combosback.php?oper=usuariosDep&iddepartamentos="+iddepartamentos, { nivel:"2,3" }, function(result){ 
			$("#asignadoamas").empty();
			$("#asignadoamas").append(result);
		});
}
//Categorías
const cargarCategoriasMas = idproyectos =>{
	$.get( "controller/combosback.php?oper=categorias&tipo=Preventivo&idproyectos="+idproyectos, {  }, function(result){ 
			$("#categoriamas").empty();
			$("#categoriamas").append(result);
		});
}
//Subcategorías
const cargarSubcategoriasMas = (idproyectos,idcategorias) =>{
	$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+idcategorias, { idproyectos: idproyectos}, function(result){ 
			$("#subcategoriamas").empty();
			$("#subcategoriamas").append(result);
		});
}
//Series
const cargarSeriesMas = idsitio =>{
	$.get( "controller/combosback.php?oper=serie&idsitio="+idsitio, { onlydata:"true" }, function(result){ 
			$("#seriemas").empty();
			$("#seriemas").append(result);
			$('#marcamas, #modelomas').val('');			
		});
}
//Marcas y modelos
const cargarMarcasModelosMas = idserie =>{
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
}

//Eventos Select de combos

$("#idclientesmas").on('select2:select', function (e) { 
	let idclientes = $("#idclientesmas option:selected").val();
	cargarProyectosMas(idclientes);
});
	
$("#idproyectosmas").on('select2:select', function (e) {
	let idclientes = $("#idclientesmas option:selected").val();
	let idproyectos = $("#idproyectosmas option:selected").val();
	cargarCategoriasMas(idproyectos);
	cargarAmbientesMas(idclientes,idproyectos); 
	cargarEstadosMas(idproyectos);
	cargarDepartamentosMas(idproyectos); 
	cargarPrioridadesMas(idclientes,idproyectos);
});

$("#categoriamas").on('select2:select', function (e) {
	let idproyectos = $("#idproyectosmas option:selected").val();
	let idcategoria = $("#categoriamas option:selected").val();
	cargarSubcategoriasMas(idproyectos,idcategoria);
});

$("#iddepartamentosmas").on('select2:select', function (e) {
	let iddepartamentos = $("#iddepartamentosmas option:selected").val();
	cargarAsignadoMas(iddepartamentos);
});

$("#unidadejecutoramas").on('select2:select', function (e) {
	let idsitio = $("#unidadejecutoramas option:selected").val();
	cargarSeriesMas(idsitio);
});

$("#seriemas").on('select2:select', function (e) {
	let idserie = $("#seriemas option:selected").val();
	cargarMarcasModelosMas(idserie);
}); 

const limpiarCamposMasivo = () =>{
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
	$("#idclientesmas, #iddepartamentosmas, #idproyectosmas, #categoriamas, #subcategoriamas, #estadomas, #prioridadmas, #asignadoamas, #unidadejecutoramas, #seriemas").empty();
}

$('#modalmasivos').on('hidden.bs.modal', function (e) {
  limpiarCamposMasivo();
})

function cerrarDialogIncidenteMasivo() {
	limpiarCamposMasivo();	
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
	//cargarCombosMas();	
	cargarClientesMas(1);
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
			url: 'controller/preventivosback.php',
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
				notification("Preventivos actualizados satisfactoriamente","¡Exito!",'success');
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


