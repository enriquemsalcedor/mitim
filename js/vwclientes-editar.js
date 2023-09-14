var tablabitacora	 = '';
var tablacomentario = '';

$("#atencion_editar").select2({placeholder: ""});
// CARGAR COMBOS FORMULARIO	
function cargarCombosEditar() {
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"incidente" }, function(result){ 
		$("#estado_editar").empty();
		$("#estado_editar").append(result);
		$("#estado_editar").select2({placeholder: ""});
	});
	//ESTADOS MANTENIMIENTO
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"mantenientoprev" }, function(result){ 
		$("#estadomantenimiento_editar").empty();
		$("#estadomantenimiento_editar").append(result);
		$("#estadomantenimiento_editar").select2({placeholder: ""});
	});	
	//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#prioridad_editar").empty();
		$("#prioridad_editar").append(result);
		$("#prioridad_editar").select2({placeholder: ""});
	});
	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
		$("#solicitante_editar").empty();
		$("#solicitante_editar").append(result);
		$('#solicitante_editar').select2({placeholder: ""});
	});
	//NOTIFICAR A
	$.get( "controller/combosback.php?oper=usuariosGrupos", { onlydata:"true"}, function(result){ 
		$("#notificar_editar").empty();
		$("#notificar_editar").append(result);
		$('#notificar_editar').select2({placeholder: ""});
	});
}

function cargarCombosEditarDepEd() {
	//EMPRESAS
	$.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresas_editar").empty();
		$("#idempresas_editar").append(result);
		$("#idempresas_editar").select2({placeholder: ""});
	});
	//EMPRESAS / CLIENTES - DEPARTAMENTOS
	$('#idempresas_editar').on('select2:select',function(){
		//CLIENTES
		var idempresas = $("#idempresas_editar option:selected").val();
		$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresas }, function(result){ 
			$("#idclientes_editar").empty();
			$("#idclientes_editar").append(result);
			$("#idclientes_editar").select2({placeholder: ""});
		});
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: idempresas }, function(result){ 
			$("#iddepartamentos_editar").empty();
			$("#iddepartamentos_editar").append(result);
			$("#iddepartamentos_editar").select2({placeholder: ""});
		});
	});
	//CLIENTES / PROYECTOS - SITIOS
	$('#idclientes_editar').on('select2:select',function(){
		var idclientes = $("#idclientes_editar option:selected").val();
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
			$("#idproyectos_editar").empty();
			$("#idproyectos_editar").append(result);
			$("#idproyectos_editar").select2({placeholder: ""});
		});				
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientes }, function(result){ 
			$("#unidadejecutora_editar").empty();
			$("#unidadejecutora_editar").append(result);
			$('#unidadejecutora_editar').select2({placeholder: ""});
		});
	});
	//PROYECTOS / CATEGORIAS
	$('#idproyectos_editar').on('select2:select',function(){
		var idproyectos = $("#idproyectos_editar option:selected").val();
		$.get( "controller/combosback.php?oper=categorias", { tipo: "incidente", idproyectos: idproyectos }, function(result){ 
			$("#categoria_editar").empty();
			$("#categoria_editar").append(result);
			$("#categoria_editar").select2({placeholder: ""});
		});
	});
	//CATEGORIAS - SUBCATEGORIAS
	$('#categoria_editar').on('select2:select',function(){
		var idcategoria = $("#categoria_editar option:selected").val();
		$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: idcategoria }, function(result){ 
			$("#subcategoria_editar").empty();
			$("#subcategoria_editar").append(result);
			$("#subcategoria_editar").select2({placeholder: ""});
		});
	});
	//SITIOS / SERIE
	$('#unidadejecutora_editar').on('select2:select',function(){
		var idsitio = $("#unidadejecutora_editar option:selected").val();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitio }, function(result){ 
			$("#serie_editar").empty();
			$("#serie_editar").append(result);
			$('#serie_editar').select2({placeholder: ""});
			$('#marca_editar, #modelo_editar').val('');
		});
	});
	//SERIE / MARCA - MODELO
	$('#serie_editar').on('select2:select',function(){
		var idserie = $("#serie_editar option:selected").val();
		//SERIE
		$.ajax({
			url: "controller/combosback.php",
			type:"POST",
			data: { oper:"seriesel", idserie: idserie },
			dataType:"json",
			success: function(response){
				$.map(response, function (item) {
					$('#marca_editar').val(item.marca);
					$('#modelo_editar').val(item.modelo);
				});
			}
		});
	});
	//DEPARTAMENTOS / ASIGNADO A
	$('#iddepartamentos_editar').on('select2:select',function(){
		var iddepartamentos = $("#iddepartamentos_editar option:selected").val();
		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: iddepartamentos }, function(result){ 
			$("#asignadoa_editar").empty();
			$("#asignadoa_editar").append(result);
			$('#asignadoa_editar').select2({placeholder: ""});		
		});
	});
}

function abrirdialogIncidenteEditar(id){
	console.log('function abrirdialogIncidenteEditar');
	$('#fusion_editar, #btnrevertirfusion').hide();
	$("#form_incidentes_editar")[0].reset();
	incidenteselect = id;
	//$("#unidadejecutora, #solicitante, #asignadoa, #notificar").select2("val", "");
	//$("#categoria, #subcategoria, #unidadejecutora, #estado").select2("val", "");
		
	$.ajax({
		type: 'post',
		url: 'controller/incidentesback.php',
		data: { 
			'oper'		    : 'comentariosleidos', 
			'idincidente'   : id,
		},
		beforeSend: function() {
			$(".loader-maxia").show();
		},
		success: function (response) { 
			$(".boton-coment-"+id+"").removeClass("green");
			$(".boton-coment-"+id+"").addClass("blue");
		},
		error: function () {
			
		}
	});
	$('#incidente').val(id);
	$('.content-incidente, .content-fechacierre').show();
	
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'controller/incidentesback.php',
		data: { 
			'oper'	: 'abrirIncidente',
			'id'	: id			
		},
		beforeSend: function() {
			$(".loader-maxia").show();
		},
		success: function (response) {
			$(".label-floating").removeClass("is-empty");	
			$.map(response, function (item) {
				$('#incidente_editar').val(item.id);
				$('#titulo_editar').val(item.titulo);
				$('#descripcion_editar').val(item.descripcion);
				//EMPRESAS
				$("#idempresas_editar").val(item.idempresas).trigger("change");
				//EMPRESAS / CLIENTES - DEPARTAMENTOS
				$.when( $('#idempresas_editar').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//CLIENTES
					$.get( "controller/combosback.php?oper=clientes", { idempresas: item.idempresas }, function(result){ 
						$("#idclientes_editar").empty();
						$("#idclientes_editar").append(result);
						$("#idclientes_editar").select2({placeholder: ""});
						$("#idclientes_editar").val(item.idclientes).trigger("change");
					});
					//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: item.idempresas }, function(result){ 
						$("#iddepartamentos_editar").empty();
						$("#iddepartamentos_editar").append(result);
						$("#iddepartamentos_editar").select2({placeholder: ""});
						$("#iddepartamentos_editar").val(item.iddepartamentos).trigger("change");
					});
				});
				//CLIENTES / PROYECTOS - DEPARTAMENTOS
				$.when( $('#idclientes_editar').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//PROYECTOS
					$.get( "controller/combosback.php?oper=proyectos", { idclientes: item.idclientes }, function(result){ 
						$("#idproyectos_editar").empty();
						$("#idproyectos_editar").append(result);
						$("#idproyectos_editar").select2({placeholder: ""});
						$("#idproyectos_editar").val(item.idproyectos).trigger("change");
					});				
					//SITIOS
					$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: item.idclientes }, function(result){ 
						$("#unidadejecutora_editar").empty();
						$("#unidadejecutora_editar").append(result);
						$('#unidadejecutora_editar').select2({placeholder: ""});
						$("#unidadejecutora_editar").val(item.unidad).trigger("change");
					});
				});
				//PROYECTOS / CATEGORIAS
				$.when( $('#idproyectos_editar').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=categorias", { tipo: "incidente", idproyectos: item.idproyectos }, function(result){ 
						$("#categoria_editar").empty();
						$("#categoria_editar").append(result);
						$("#categoria_editar").select2({placeholder: ""});
						$("#categoria_editar").val(item.categoria).trigger("change");
					});
				});
				//CATEGORIAS - SUBCATEGORIAS
				$.when( $('#categoria_editar').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: item.categoria }, function(result){ 
						$("#subcategoria_editar").empty();
						$("#subcategoria_editar").append(result);
						$("#subcategoria_editar").select2({placeholder: ""});
						$("#subcategoria_editar").val(item.subcategoria).trigger("change");
					});
				});
				//SITIOS / SERIE
				$.when( $('#unidadejecutora_editar').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SERIE
					$.get( "controller/combosback.php?oper=serie", { idsitio: item.unidad }, function(result){ 
						$("#serie_editar").empty();
						$("#serie_editar").append(result);
						$('#serie_editar').select2({placeholder: ""});
						$("#serie_editar").val(item.serie).trigger("change");
						$('#marca_editar, #modelo_editar').val('');
					});
				});
				//SERIE / MARCA - MODELO
				$.when( $('#serie_editar').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SERIE
					$.ajax({
						url: "controller/combosback.php",
						type:"POST",
						data: { oper:"seriesel", idserie: item.serie },
						dataType:"json",
						success: function(response){
							$.map(response, function (item) {
								$('#marca_editar').val(item.marca);
								$('#modelo_editar').val(item.modelo);
							});
						}
					});
				});
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentos_editar').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					var iddepartamentos = $("#iddepartamentos_editar option:selected").val();
					$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: item.iddepartamentos }, function(result){ 
						$("#asignadoa_editar").empty();
						$("#asignadoa_editar").append(result);
						$('#asignadoa_editar').select2({placeholder: ""});
						$("#asignadoa_editar").val(item.asignadoa).trigger("change");
					});
				});
				//NOTIFICAR
				//$('#notificar_editar').val(item.notificar).trigger("change");
				//NOTIFICAR
				if(item.notificar!=''){
					var elem = [];
					$.each($.parseJSON(item.notificar), function(i, item) {
						if(item != ''){
							elem.push(item);
						}
					});
					$("#notificar_editar").val(elem).trigger("change");
				}else{
					$("#notificar_editar").val(null).trigger("change"); 
				}
				
				$("#estado_editar").val(item.estado).trigger("change");
				$("#prioridad_editar").val(item.prioridad).trigger("change");
				$("#solicitante_editar").val(item.solicitante).trigger("change");
				$('#departamento_editar').val(item.departamento);
				$('#modalidad_editar').val(item.modalidad);
				$('#fechacierre_editar').val(item.fechacierre);
				$('#horacierre_editar').val(item.horacierre);						
				$('#fusionado_editar').val(item.fusionado);
				if(item.fusionado !=' - '){
					$('#fusion_editar, #btnrevertirfusioneditar').show();
				}else{
					$('#fusion_editar, #btnrevertirfusioneditar').hide();
				}
				$('#resolucion_editar').val(item.resolucion);					
				$('#reporteservicio_editar').val(item.reporteservicio);
				$('#numeroaceptacion_editar').val(item.numeroaceptacion);
				$('#estadomantenimiento_editar > option[value="'+item.estadomantenimiento+'"]').prop("selected", true);
				$('#observaciones_editar').val(item.observaciones);					
				$('#horario_editar').val(item.horario);
				$('#marca_editar').val(item.marca);
				$('#modelo_editar').val(item.modelo);
				$('#origen_editar').val(item.origen);
				$('#creadopor_editar').val(item.creadopor);
				$('#modalidad_editar').val(item.modalidad);
				$('#comentariosatisfaccion_editar').val(item.comentariosatisfaccion);
				$('#resueltopor_editar').val(item.resueltopor);
				$('#periodo_editar').val(item.periodo);
				$('#fechacreacion_editar').val(item.fechacreacion);
				$('#horacreacion_editar').val(item.horacreacion);
				if(nivel > 2){
					$("#fechacreacion_editar").prop('disabled', true);
					$("#horacreacion_editar").prop('disabled', true);
				} else {
					$("#fechacreacion_editar").prop('disabled', false);
					$("#horacreacion_editar").prop('disabled', false);
				}
				$('#fechamodif_editar').val(item.fechamodif);
				$('#fechavencimiento_editar').val(item.fechavencimiento);
				$('#fecharesolucion_editar').val(item.fecharesolucion);
				$('#fechacierre_editar').val(item.fechacierre);
				$('#fechacertificar_editar').val(item.fechacertificar);
				$('#horastrabajadas_editar').val(item.horastrabajadas);
				$('#atencion_editar').val(item.atencion).trigger("change");						
			});
		},
		complete: function(data,status){
			//console.log('completado');
			//cargarCombosEditarDepEd();			
		}
	});
	dialogIncidenteEditar.dialog( "open" );
}

function cerrarDialogIncidenteEditar() {
	$('#unidadejecutora_editar').val(null).trigger("change");
	$('#activo_editar').val(null).trigger("change");	
	$('#solicitante_editar').val(null).trigger("change");
	$('#asignadoa_editar').val(null).trigger("change");	
	$('#notificar_editar').val(null).trigger("change");
	limpiarFormularioEditar("#form_incidentes_editar");
	dialogIncidenteEditar.dialog('close');	
}

$('#tablacomentario').on( 'draw.dt', function () {	
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR COMENTARIOS
	$('.boton-eliminar-comentarios').each(function(){
		var id = $(this).attr("data-id"); 
		$(this).on( 'click', function() {
			eliminarcomentario(id);
		});
	});
	// DAR FUNCIONALIDAD AL BOTON EVIDENCIAS
	$('.boton-adjuntos-comentarios').each(function(){
		var id = $(this).attr("data-id");
		$(this).on( 'click', function() {
			adjuntosComentarios(id);
		});
	});
});

function guardarFormIncidenteEditar() {
	var id 				= $('#incidente_editar').val();
	var dataserialize 	= $("#form_incidentes_editar").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'categoria_editar' || dataserialize[i].name == 'subcategoria_editar' || 
			dataserialize[i].name == 'idempresas_editar' || dataserialize[i].name == 'idempresas_editar'  ||
			dataserialize[i].name == 'iddepartamentos_editar' || dataserialize[i].name == 'iddepartamentos_editar'  ||
			dataserialize[i].name == 'idclientes_editar' || dataserialize[i].name == 'idclientes_editar'  ||
			dataserialize[i].name == 'idproyectos_editar' || dataserialize[i].name == 'idproyectos_editar'  ||				
			dataserialize[i].name == 'prioridad_editar' || dataserialize[i].name == 'solicitante_editar'  || 
			dataserialize[i].name == 'estado_editar' || dataserialize[i].name == 'asignadoa_editar'  || 
			dataserialize[i].name == 'unidadejecutora_editar'	){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	if($("#notificar_editar").select2("val") != ''){
		data['notificar_editar'] = JSON.stringify($("#notificar_editar").select2("val"));
	}else{
		data['notificar_editar'] = '';
	}
	if(data['titulo_editar'] == ''){
		$("#"+dataserialize['titulo_editar']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['titulo_editar']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Título');
	}/*
	else if(data['idempresas'] == '0'){
		$("#"+dataserialize['idempresas']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idempresas']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Empresa');
	}
	else if(data['iddepartamentos'] == '0'){
		$("#"+dataserialize['iddepartamentos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['iddepartamentos']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Departamento');
	}*/
	else if(data['idclientes_editar'] == '0' || data['idclientes_editar'] == ''){
		$("#"+dataserialize['idclientes_editar']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes_editar']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Cliente');
	}else if(data['idproyectos_editar'] == '0' || data['idproyectos'] == ''){
		$("#"+dataserialize['idproyectos_editar']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos_editar']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Proyecto');
	}else if(data['categoria_editar'] == '0' || data['categoria'] == ''){
		$("#"+dataserialize['categoria_editar']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['categoria_editar']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Categoría');
	}/*
	else if(data['unidadejecutora'] == '0' && data['unidadejecutora'] == ''){
		$("#"+dataserialize['unidadejecutora']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['unidadejecutora']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Unidad Ejecutora');
	}else if(data['serie'] == ''){
		$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['serie']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Serie');
	}
	else if( (data['categoria'] == '10' || data['categoria'] == '11') && data['serie'] == ''){
		$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['serie']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo Serie');
	}*/
	else if(data['estado_editar'] == '16' && data['fecharesolucion_editar'] == ''){
		$("#"+dataserialize['fecharesolucion_editar']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucion_editar']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo de Fecha y Hora de Resolución');
	}else if(data['estado_editar'] == '16' && data['resolucion_editar'] == ''){
		$("#"+dataserialize['resolucion_editar']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['resolucion_editar']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR!','Debe llenar el campo de Resolución');
	}else{			
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/incidentesback.php',
			data: { 
				'oper'	: 'actualizarIncidente',
				'id'	: id,
				'data' 	: data
			},
			beforeSend: function() {
				$(".loader-maxia").show();
				$(".modal-container").addClass('swal2-in');
				cerrarDialogIncidenteEditar();
			},
			success: function (response) {
				// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
				$('#idempresas_editar').val(null).trigger("change");
				$('#iddepartamentos_editar').val(null).trigger("change");
				$('#idclientes_editar').val(null).trigger("change");
				$('#idproyectos_editar').val(null).trigger("change");
				$('#categoria_editar').val(null).trigger("change");
				$('#subcategoria_editar').val(null).trigger("change");
				$('#prioridad_editar').val(null).trigger("change");
				$('#unidadejecutora_editar').val(null).trigger("change");
				$('#serie_editar').val(null).trigger("change");	
				$('#asignadoa_editar').val(null).trigger("change");	
				$('#estado_editar').val(null).trigger("change");
				tablaincidentes.ajax.reload(null, false);
				demo.showSwal('success-message','Buen trabajo!','Incidente actualizado satisfactoriamente');			
				$(".loader-maxia").hide();					
			},
			error: function () {
				$(".loader-maxia").hide();				
				demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Registro, intente mas tarde');
			}
		});		
		$(".modal-container").removeClass('swal2-in');
	}
}

//	FORMULARIO INCIDENTE EDITAR //
var dialogIncidenteEditar = $( "#dialog-form-incidentes-editar" ).dialog({
	width: '72%', 
	maxWidth: 600,
	height: 'auto',
	//modal: true,
	fluid: true,
	resizable: false,
	autoOpen: false,
	open: function(event, ui) {
		//COMENTARIOS
		tablacomentario = $("#tablacomentario").DataTable({
			responsive: true,
			destroy: true,
			ordering: false,
			searching: false,
			"scrollY": "500px",
			"ajax"		: {
				"url"	: "controller/incidentesback.php?oper=comentarios&id="+incidenteselect,
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "acciones" },
				{ 	"data": "comentario" },
				{ 	"data": "nombre" },
				{ 	"data": "visibilidad" },
				{ 	"data": "fecha" },
				{ 	"data": "adjuntos" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					"targets"	: [ 0 ],
					"visible"	: false,
					"searchable": false
				},{
					targets		: [2],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}
		});
		//HISTORIAL
		if(nivel == 1 || nivel == 2){
			tablabitacora = $("#tablabitacora").DataTable({
				responsive: true,
				destroy: true,
				ordering: false,
				searching: false,
				"scrollY": "500px",
				"ajax"		: {
					"url"	: "controller/incidentesback.php?oper=historial&id="+incidenteselect,
				},
				"columns"	: [
					{ 	"data": "id" },
					{ 	"data": "usuario" },
					{ 	"data": "fecha" },
					{ 	"data": "accion" }
					],
				"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
				"columnDefs": [ //OCULTAR LA COLUMNA ID
					{
						"targets"	: [ 0 ],
						"visible"	: false,
						"searchable": false
					},{
						targets		: [3],
						className	: "dt-left"
					}
				],
				"language": {
					"url": "js/Spanish.json"
				}
			});
		}
	},
	close: function(event, ui) {
		
	}
});

//***** ***** ***** ADJUNTO COMENTARIOS ***** ***** ***** //
var dialogAdj;
var optionsAdj = {
	url  : 'elFinder/php/connector.minimal.incidentescomentarios.php',
	lang : 'es',
	rememberLastDir: false
}	
var elfInstanceAdj = $('#elfinder').elfinder(optionsAdj).elfinder('instance');
//Adjuntos de comentarios
function adjuntosComentarios(incidentecomentario) {	
	var valid = true;
	if ( valid ) {
		$.ajax({
			  type: 'post',
			  url: 'controller/incidentesback.php',
			  data: { 
				'oper': 		'adjuntosComentarios',
				'incidentecom': incidentecomentario
			  },
			  success: function (response) {
				elfInstanceAdj.bind('load', function(event) { 
					elfInstanceAdj.exec('open', response);
				});
				dialogSol.dialog( "open" );
				elfInstanceAdj.exec('reload'); 
			  },
			  error: function () {
				demo.showSwal('error-message','ERROR!',response);
			  }
		   }); 
		}
	return valid;
}

//***** ***** ***** COMENTARIOS ***** ***** ***** //
function abrirGridAdjunto(idincidente,id) {
	var isVisible = $("#dialog-grid-adjunto").is(":visible");
	if (!isVisible) {
		comentarioselect = id;
		if (comentarioselect!=''){
			//$('#dialog-grid-adjunto').css('z-index','1050 !important');
			$('#idincidentec').val(idincidente);
			$('#idcomentarioc').val(comentarioselect);
			$('#dialog-grid-adjunto').show();	
			//dialoGridAdjunto.dialog( "moveToTop" );
		} else {
			demo.showSwal('error-message','ERROR!','Debe seleccionar un comentario');	
		}
	} else {
		$('#dialog-grid-adjunto').hide();
	}
}

//COMENTARIO AGREGAR
var form,
	comentario = $( "#comentario" ),
	allFields = $( [] ).add( comentario ),
	tips = $( ".validateTips" );

function agregarComentario() {	
	var coment  = $('#comentario').val();		
	var visibilidad  = $('input[name=visibilidad]:checked').val();
	
	if(coment==''){
		$('#comentario').addClass('form-valide-error-bottom');
		return;
	}
	if(!visibilidad){
		$('input[name=visibilidad]').addClass('form-valide-error-bottom');
		return;
	}

	if (coment != '') {
		$.ajax({
			type: 'post',
			url: 'controller/incidentesback.php',
			data: { 
				'oper'	: 'agregarComentario',
				'id' : incidenteselect,
				'coment' : coment,
				'visibilidad' : visibilidad
			},
			beforeSend: function() {
				$(".loader-maxia").show();
				$('#dialog-form-coment').hide();
			},
			success: function (response) {
				if(response){					
					$('#comentario').val("");					
					if ( $('.boton-coment-'+incidenteselect+'').length > 0 ) {
						$('.boton-coment-'+incidenteselect+'').removeClass("blue");
						$('.boton-coment-'+incidenteselect+'').addClass('green');
					}else{
						$('.msj-'+incidenteselect+'').append('<span class="icon-col green fa fa-comment boton-coment-'+incidenteselect+'" data-id="" data-toggle="tooltip" data-original-title="Comentarios" data-placement="right"></span>');
					}
					demo.showSwal('success-message','Buen trabajo!','Comentario Almacenado Satisfactoriamente');			
					tablacomentario.ajax.reload(null, false);
				}else{
					demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Comentario, intente mas tarde');
				}
				$(".loader-maxia").hide();				
			},
			error: function () {
				demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Comentario, intente mas tarde');
			}
		});
	}
	return;
}

function limpiarFormularioEditar(form){	
	$.get( "controller/preventivosback.php?oper=limpiarFiltrosMasivos");
	var dataserialize = $(form).serializeArray();
	for (var i in dataserialize) {
		$("#"+dataserialize[i].name).val(null).trigger("change");
	}
}

$(document).ready(function() {
	var idincidenteurl = getQueryVariable('id');
	if(idincidenteurl != ''){
		setTimeout(function(){			
			abrirdialogIncidenteEditar(idincidenteurl);
		}, 1000);
	}
	cargarCombosEditar();
	cargarCombosEditarDepEd();
	
	//CALENDARIO
	$('#fechacierre_editar, #fechacertificar_editar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false });
	$('#fecharesolucion_editar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true });
	$('#fechacreacion_editar, #fechareal_editar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false  });
	$('#horacreacion_editar').bootstrapMaterialDatePicker({switchOnClick:true, date:false, format : 'HH:mm' });
	
	$('#calendarhidendesde_editar').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD' }).on('change',function(){
	    var fechadesdeoculto = $('#calendarhidendesde_editar').val();
	    $('#desdef_editar').val(fechadesdeoculto);
	});	
	$('#calendarhidenhasta_editar').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD' }).on('change',function(){
	    var fechahastaoculto = $('#calendarhidenhasta_editar').val();
	    $('#hastaf_editar').val(fechahastaoculto);
	});	
	$('.iconcalfdesde_editar').on( 'click', function (e) { 
	    $('#calendarhidendesde_editar').dblclick();
	});	
	$('.iconcalfhasta_editar').on( 'click', function (e) { 
	    $('#calendarhidenhasta_editar').dblclick();
	});
});


