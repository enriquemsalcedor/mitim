$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$("select").select2({ language: "es" });
var idincidente = getQueryVariable('id');
$("#listado").click(function(){
	location.href = "laboratorios.php";
});

function cargarCombos() {
	//PRIORIDAD
	/* $.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#idprioridades").empty();
		$("#idprioridades").append(result); 
	}); */
	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuariosLab", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
		$("#solicitante").empty();
		$("#solicitante").append(result); 
	});
	//NOTIFICAR A
	$.get( "controller/combosback.php?oper=usuariosGrupos", { onlydata:"true"}, function(result){ 
		$("#notificar").empty();
		$("#notificar").append(result); 
		var ininot = $('#notificar').find('option:first').val();
		if(ininot == ''){
			$('#notificar').find('option:first').remove().trigger('change');
		}
	});
}
  
let idempresas = 1;
//Clientes
$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
	$("#idclientes").empty();
	$("#idclientes").append(result);  
	optionDefault('idclientes');						  
});
//Departamentos 
$.get( "controller/combosback.php?oper=departamentosgruposLab&idempresas=1", { onlydata:"true" }, function(result){ 
	$("#iddepartamentos").empty();
	$("#iddepartamentos").append(result); 
	$("#iddepartamentos").val(12).trigger("change");
});
//Estados
$.get( "controller/combosback.php?oper=estadosLaboratorio", { tipo: 'Laboratorio', formulario:"nuevo" }, function(result){ 
	$("#idestados").empty();
	$("#idestados").append(result); 
	$("#idestados").val(35).trigger("change");
});
//Asignado
$.get( "controller/combosback.php?oper=usuariosDepLab", { iddepartamentos: 12, tipo: 'nuevo' }, function(result){ 
	$("#asignadoa").empty();
	$("#asignadoa").append(result); 
	$("#asignadoa").val('laboratorio@correo.com').trigger("change");	
});

$("#idclientes").on('select2:select',function(){
	var idempresas = $("#idempresas option:selected").val();
	var idclientes = $("#idclientes option:selected").val();		
	//PROYECTOS
	$.get( "controller/combosback.php?oper=proyectos&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
		$("#idproyectos").empty();
		$("#idproyectos").append(result); 
		optionDefault('idproyectos');					   
	});				
	//SITIOS
	$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
		$("#unidadejecutora").empty();
		$("#unidadejecutora").append(result); 
		optionDefault('unidadejecutora'); 
	}); 
	if (idincidente == "") { 
		//Estados
		$.get( "controller/combosback.php?oper=estadosLaboratorio", { idempresas: idempresas, idclientes: idclientes, tipo: 'Laboratorio', formulario:"nuevo" }, function(result){ 
			$("#idestados").empty();
			$("#idestados").append(result); 
			$("#idestados").val(35).trigger("change");
		}); 
	} 
});	
//DEPARTAMENTOS		
$("#iddepartamentos").change(function(e,data){
	var iddepartamentos = $("#iddepartamentos option:selected").val(); 
	//ASIGNADO A
	$.get( "controller/combosback.php?oper=usuariosDepLab", { iddepartamentos: iddepartamentos, tipo: 'nuevo' }, function(result){ 
		$("#asignadoa").empty();
		$("#asignadoa").append(result); 
		$("#asignadoa").val('laboratorio@correo.com').trigger("change");	
	});
});	
//CATEGORIAS
$("#categoria").select2({placeholder: ""});
$("#idproyectos").on('change',function(){
	var idempresas = $("#idempresas option:selected").val();
	var idclientes = $("#idclientes option:selected").val();
	var idproyectos = $("#idproyectos option:selected").val();
	if(idproyectos != '' && idproyectos != undefined ){
		$.get( "controller/combosback.php?oper=categorias&tipo=incidente&idproyectos="+idproyectos, { onlydata:"true" }, function(result){ 
			$("#categoria").empty();
			$("#categoria").append(result);				
			optionDefault('categoria');					 
		});
	}
	 
	if (idincidente == "") {
		 
		//ESTADOS
		$.get( "controller/combosback.php?oper=estadosLaboratorio", { tipo: 'Laboratorio', formulario:"nuevo" }, function(result){ 
			$("#idestados").empty();
			$("#idestados").append(result); 
			$("#idestados").val(35).trigger("change");
		});
	}else{
		//ESTADOS
		$.get( "controller/combosback.php?oper=estadosLaboratorio", { idclientes: idclientes, idproyectos: idproyectos, tipo: 'Laboratorio', formulario:"editar" }, function(result){ 
			$("#idestados").empty();
			$("#idestados").append(result); 
			//$("#idestados").val(35).trigger("change");
		});
	}
	
	if(idproyectos == 35){ 
		$.get( "controller/combosback.php?oper=serie", { onlydata:"true" }, function(result){ 
			$("#serie").empty();
			$("#serie").append(result); 
			$('#marca, #modelo').val('');
		}); 
		//OCULTAR
		$(".box-titulo").html("Orden de trabajo");
		$(".box-cc, .box-sitio, .box-categorias, .box-subcategorias, .box-servicio, .box-horastrabajadas").hide();
	}else{ 
	}
	//PRIORIDADES
	$.get( "controller/combosback.php?oper=prioridades", { idclientes: idclientes, idproyectos: idproyectos }, function(result){ 
		$("#idprioridades").empty();
		$("#idprioridades").append(result); 
	});
});
//SUBCATEGORIAS
$("#categoria").change(function(e,data){
	var idcategoria = $("#categoria option:selected").val();
	$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+idcategoria, { onlydata:"true" }, function(result){ 
		$("#subcategoria").empty();
		$("#subcategoria").append(result); 
		optionDefault('subcategoria');						
	});
}); 

$(document).ready(function() {
	if(idincidente == ""){ 
		cargarCombos();
		$('.tipo').html('Nuevo laboratorio');
	}else{
	 $('.tipo').html('Editar laboratorio');
	}
	//cargarCombosDep();
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false, clearButton: true, clearText: 'Limpiar', lang : 'es' });
	$('#fecharesolucion, #fechareal').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true, clearButton: true, clearText: 'Limpiar', lang : 'es' });
	$('#fechacreacion, #fechaentrada').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, clearButton: true, clearText: 'Limpiar', lang : 'es'  });
	$('#horacreacion').bootstrapMaterialDatePicker({switchOnClick:true, date:false, format : 'HH:mm', clearButton: true, clearText: 'Limpiar', lang : 'es' });
	
	$('#calendarhidendesde').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD' }).on('change',function(){
	    var fechadesdeoculto = $('#calendarhidendesde').val();
	    $('#desdef').val(fechadesdeoculto);
	});	
	$('#calendarhidenhasta').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD' }).on('change',function(){
	    var fechahastaoculto = $('#calendarhidenhasta').val();
	    $('#hastaf').val(fechahastaoculto);
	});	
	$('.iconcalfdesde').on( 'click', function (e) { 
	    $('#calendarhidendesde').dblclick();
	});	
	$('.iconcalfhasta').on( 'click', function (e) { 
	    $('#calendarhidenhasta').dblclick();
	});
});

if (idincidente) {
	$('.eseditar').css('display','block');
	$('.navcom').css('display','block');
	$('.navest').css('display','block');
	$('.navhist').css('display','block');
	$('.navfus').css('display','block');
	
	//console.log('function abrirdialogIncidenteEditar');
	$('#fusion, #btnrevertirfusion').hide();
	//$("#form_incidentes")[0].reset();
	incidenteselect = idincidente;
	//$("#unidadejecutora, #solicitante, #asignadoa, #notificar").select2("val", "");
	//$("#categoria, #subcategoria, #unidadejecutora, #estado").select2("val", "");
		
	$.ajax({
		type: 'post',
		url: 'controller/laboratorioback.php',
		data: { 
			'oper'		    : 'comentariosleidos', 
			'idincidente'   : idincidente,
		},
		beforeSend: function() {
			//$('#overlay').css('display','block');
		},
		success: function (response) {
			//$('#overlay').css('display','none');
			$(".boton-coment-"+idincidente+"").removeClass("green");
			$(".boton-coment-"+idincidente+"").addClass("blue");
		},
		error: function () {
			//$('#overlay').css('display','none');
		}
	});
	$('#incidente').val(idincidente);
	$('.content-incidente, .content-fechacierre').show();
	
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'controller/laboratorioback.php',
		data: { 
			'oper'	: 'abrirIncidente',
			'id'	: idincidente			
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		success: function (response) {  
			$('#overlay').css('display','none');
			$(".label-floating").removeClass("is-empty");	
			$.map(response, function (item) {
				var mensajeinfo = "Laboratorio: "+item.id;
				$("#nombreregistro").html(mensajeinfo);
				$('#divnombreregistro').show();
			
				if(item.estado==40){
					if((nivel != 2 && item.departamentous==0) && (nivel != 1) && (nivel != 2 && item.estecnico == 0)){
						$(".inc-edit").attr('disabled', true);	
						$("#btnguardarincidenteeditar").hide();	
						console.log('paso1');
					}else{
						console.log('paso2');
					}
				}else{ 
					if((nivel != 2 && item.departamentous==0) && (nivel != 1) && (nivel != 2 && item.estecnico == 0)){
						$(".inc-edit").attr('disabled', false);
						$("#btnguardarincidenteeditar").show();
						$("#fechaentrada").attr('disabled', true);	 
						$("#estado").attr('disabled', true);	 
						$("#iddepartamentos").attr('disabled', true);	 
						$("#asignadoa").attr('disabled', true);	 
						$("#fecharesolucion").attr('disabled', true);	 
						$("#diagnostico").attr('disabled', true);	 
						console.log('paso3');
					}else{
						$(".inc-edit").attr('disabled', false);
						console.log('paso4');
					}
				}
				$('#incidente').val(item.id);
				$('#titulo').val(item.titulo);
				$('#descripcion').val(item.descripcion);
				$('#serie').val(item.serie);
				$('#marca').val(item.marca);
				$('#modelo').val(item.modelo);
				
				$('#diagnostico').val(item.diagnostico).trigger("change");;
				//EMPRESAS
				$("#idempresas").val(item.idempresas).trigger("change"); 
				
				$.when( $('#idempresas').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//CLIENTES
					$.get( "controller/combosback.php?oper=clientes", { idempresas: 1 }, function(result){ 
						$("#idclientes").empty();
						$("#idclientes").append(result); 
						document.ready = document.getElementById("idclientes").value = item.idclientes;
					});
					//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgruposLab", { idempresas: 1 }, function(result){ 
						$("#iddepartamentos").empty();
						$("#iddepartamentos").append(result); 
						document.ready = document.getElementById("iddepartamentos").value = item.iddepartamentos;
					});
				});
				
				//CLIENTES / PROYECTOS - DEPARTAMENTOS
				$.when( $('#idclientes').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//PROYECTOS
					$.get( "controller/combosback.php?oper=proyectos", { idclientes: item.idclientes }, function(result){ 
						$("#idproyectos").empty();
						$("#idproyectos").append(result); 
						$("#idproyectos").val(item.idproyectos).trigger("change");	
						document.ready = document.getElementById("idproyectos").value = item.idproyectos;

						$.when( $('#idproyectos').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
							
						  	//ESTADOS
							$.get( "controller/combosback.php?oper=estadosLaboratorio", { idempresas: item.idempresas, idclientes: item.idclientes, idproyectos: item.idproyectos, tipo: 'Laboratorio',
							formulario: 'editar' }, function(result){ 
								$("#idestados").empty();
								$("#idestados").append(result); 
								//$("#idestados").val(item.idestados).trigger("change"); 
								document.ready = document.getElementById("idestados").value = item.estado;
							});
						}); 
					}); 
				}); 
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentos').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					var iddepartamentos = $("#iddepartamentos option:selected").val();
					var idproyectos     = $("#idproyectos option:selected").val();
					$.get( "controller/combosback.php?oper=usuariosDepLab", { idproyectos: item.idproyectos, iddepartamentos: item.iddepartamentos }, function(result){ 
						$("#asignadoa").empty();
						$("#asignadoa").append(result); 
						$("#asignadoa").val(item.asignadoa).trigger("change"); 
						document.ready = document.getElementById("asignadoa").value = item.asignadoa;
					});
				});
				//NOTIFICAR
				//$('#notificar').val(item.notificar).trigger("change");
				//NOTIFICAR
				//console.log('item.notificar: '+item.notificar);
				/* if(item.notificar != '' && item.notificar != 'null' && item.notificar != null){
					$("#notificar").val($.parseJSON(item.notificar)).trigger("change");
				}else{
					$("#notificar").val(null).trigger("change");
				} */
				
				//Notificar  
				if(item.notificar!=''){
					 
					$.get( "controller/combosback.php?oper=usuariosGrupos", { onlydata:"true"}, function(result){ 
						$("#notificar").empty();
						$("#notificar").append(result);  
						
						var elem = [];
						$.each($.parseJSON(item.notificar), function(i, item) {
							if(item != ''){
								elem.push(item);
							}
						});
						$("#notificar").val(elem).trigger("change");
					}); 
				}else{
					$("#notificar").val(null).trigger("change"); 
				}	
				
				//Prioridad
				$.get( "controller/combosback.php?oper=prioridades", { idclientes: item.idclientes, idproyectos: item.idproyectos }, function(result){ 
					$("#idprioridades").empty();
					$("#idprioridades").append(result);  
					document.ready = document.getElementById("idprioridades").value = item.prioridad;
				});
				
				//Solicitante
				$.get( "controller/combosback.php?oper=usuariosLab", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
					$("#solicitante").empty();
					$("#solicitante").append(result); 
					document.ready = document.getElementById("solicitante").value = item.solicitante;
				});
				//$("#solicitante").val(item.solicitante).trigger("change");
				$('#departamento').val(item.departamento); 
				$('#fusionado').val(item.fusionado);
				if(item.fusionado !=' - '){
					$('#fusion, #btnrevertirfusioneditar').show();
				}else{
					$('#fusion, #btnrevertirfusioneditar').hide();
				}
				$('#resolucion').val(item.resolucion);			 
				$('#fecharesolucion').val(item.fecharesolucion); 
				$('#fechacreacion').val(item.fechacreacion);
				if(item.fechaentrada != ' '){
					$('#fechaentrada').val(item.fechaentrada);
				}				
				$('#fecharesolucionant').val(item.fecharesolucion);
			});
		},
		complete: function(data,status){
			abrirComentarios(idincidente);
			abrirEstados(idincidente);
			abrirHistorial(idincidente);
			abrirFusionados(idincidente);			
		}
	});
}

$('#tablacomentario').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})

function abrirComentarios(idincidentecom){
	if(idincidentecom == undefined){
		idincidentecom = 0;
	}
	//COMENTARIOS
	if(nivel != 4){
		var cvisible = true;
	}else{
		var cvisible = false;
	}
	//COMENTARIOS
	tablacomentario = $("#tablacomentario").DataTable({
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : false,
		"ajax"		: {
			"url"	: "controller/laboratorioback.php?oper=comentarios&id="+idincidentecom,
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
			},
			{
				visible		: cvisible,
				targets		: [4]
			}
		],
		"language": {
			"url": "js/Spanish.json"
		}
	});
}

function abrirEstados(idincidenteest){
	if(idincidenteest == undefined){
		idincidenteest = 0;
	}
	tablaestados = $("#tablaestados").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		"ajax"		: {
			"url"	: "controller/laboratorioback.php?oper=estadosbit&id="+idincidenteest,
		},
		"columns"	: [
			{ 	"data": "estadoant" },
			{ 	"data": "estadoact" },
			{ 	"data": "fecha" },
			{ 	"data": "dias" },
			{ 	"data": "horas" }
			],
		"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		"columnDefs": [ //OCULTAR LA COLUMNA ID
			{
				targets		: [0,1,2],
				className	: "dt-left"
			}
		],
		"language": {
			"url": "js/Spanish.json"
		}
	});
}

function abrirHistorial(idincidentehis){ 
	if(idincidentehis == undefined){
		idincidentehis = 0;
	}
	//HISTORIAL
	//if(nivel == 1 || nivel == 2){
		tablabitacora = $("#tablabitacora").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: { 
				"url"	: "controller/laboratorioback.php?oper=historial&id="+idincidentehis,
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "usuario" },
				{ 	"data": "nombre" },
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
	//}
}

function abrirFusionados(idincidentefus){
	if(idincidentefus == undefined){
		idincidentefus = 0;
	}
	//FUSIONADOS
	tablafusionados = $("#tablafusionados").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		"ajax"		: {
			"url"	: "controller/laboratorioback.php?oper=fusionados&id="+idincidentefus,
		},
		"columns"	: [
			{ 	"data": "id" }, 
			{ 	"data": "titulo" }, 
			{ 	"data": "descripcion" }, 
			{ 	"data": "fechacreacion" }
			],
		"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		"columnDefs": [ //OCULTAR LA COLUMNA ID
			{
				targets		: [ 1, 2, 3 ],
				className	: "dt-left"
			} 
		],
		"language": {
			"url": "js/Spanish.json"
		}
	});
} 
 
function revertirfusionEditar() {
	var id 			= $('#incidente').val();
	var incidente	= $('#incidente').val();
	var fusionado 	= $("#fusionado").val();
	//console.log('Inc:'+incidente+'- Fusionado:'+fusionado);		
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/laboratorioback.php',
		data: { 
			'oper'	: 'revertirfusion',
			'id'	: id,
			'incidente'	: incidente,
			'fusionado'	: fusionado
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		success: function (response) {
			$('#overlay').css('display','none'); 
			notification("¡Exito!",'Fusión Revertida Exitosamente',"success");					
			location.href="laboratorios.php";							
		},
		error: function () {
			$('#overlay').css('display','none'); 
			notification("Error!",'Ha ocurrido un error al revertir la fusión',"error");
		}
	});	
	$(".modal-container").removeClass('swal2-in');
}
 
function limpiarComentario(){
	$('#comentario').val('');
}

//COMENTARIO AGREGAR
var form,
	comentario = $( "#comentario" ),
	allFields = $( [] ).add( comentario ),
	tips = $( ".validateTips" );

function agregarComentario() {	
	var coment  = $('#comentario').val();		
	var visibilidad  = $('input[name=visibilidad]:checked').val();
	
	/* if(coment==''){
		$('#comentario').addClass('form-valide-error-bottom');
		return;
	} */
	if(visibilidad == undefined ){
		visibilidad  = 'Público';		
	}else if(visibilidad == ''){
		$('input[name=visibilidad]').addClass('form-valide-error-bottom');
		return;
	}

	if (coment != '') {
		$.ajax({
			type: 'post',
			url: 'controller/laboratorioback.php',
			data: { 
				'oper'	: 'agregarComentario',
				'id' : incidenteselect,
				'coment' : coment,
				'visibilidad' : visibilidad
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
				$('#dialog-form-coment').hide();
			},
			success: function (response) {
				$('#overlay').css('display','none');
				if(response){					
					$('#comentario').val("");					
					if ( $('.boton-coment-'+incidenteselect+'').length > 0 ) {
						$('.boton-coment-'+incidenteselect+'').removeClass("blue");
						$('.boton-coment-'+incidenteselect+'').addClass('green');
					}else{
						$('.msj-'+incidenteselect+'').append('<span class="icon-col green fa fa-comment boton-coment-'+incidenteselect+'" data-id="" data-toggle="tooltip" data-original-title="Comentarios" data-placement="right"></span>');
					}
					notification("¡Exito!",'Comentario Almacenado Satisfactoriamente',"success");					
					tablacomentario.ajax.reload(null, false);
				}else{ 
					notification("Error!",'Ha ocurrido un error al grabar el Comentario',"error");
				}
			},
			error: function () {
				$('#overlay').css('display','none');
				notification("Error!",'Ha ocurrido un error al grabar el Comentario',"error");
			}
		});
	}else{
		notification("Advertencia!",'Debe ingresar el campo comentario',"warning"); 
	}
	return;
}

var dirxdefecto = 'incidente';
$('#fevidenciascom').attr('src','filegator/laboratoriocom.php#/?cd=%2F'+dirxdefecto); 

$(document).on('click','.boton-eliminar-comentarios', function(e){ 
	var id 	   = $(this).attr("data-id"); 
	eliminarcomentario(id);
 });
 
$(document).on('click','.boton-adjuntos-comentarios', function(e){
	var id = $(this).attr("data-id"); 
	//$('#fevidencias').attr('src','filegator/activos.php#/?cd=activos/'+id);
	adjuntosComentarios(id);
});

function eliminarcomentario(id){
	var idcomentario = id;
	swal({
		title: "Confirmar",
		text: "¿Esta seguro de eliminar el comentario?",
		type: "warning",
		showCancelButton: true,
		cancelButtonColor: 'red',
		confirmButtonColor: '#09b354',
		confirmButtonText: 'Si',
		cancelButtonText: "No"
	}).then(
		function(isConfirm){
			if (isConfirm.value === true) {
				$.get( "controller/laboratorioback.php?oper=eliminarcomentarios", 
				{ 
					onlydata : "true",
					idcomentario : idcomentario
				}, function(result){
					if(result == 1){
						notification("¡Exito!",'comentario eliminado satisfactoriamente',"success");
						tablacomentario.ajax.reload(null, false);
					} else if(result == 2){ 
						notification("Advertencia!",'No tiene permisos para eliminar este comentario',"warning");
					} else {
						notification("Error!",'Ha ocurrido un error al eliminar el comentario',"error"); 
					}
				});
			}
		}, function (isRechazo){
			// NADA
		}
	);
}

$('#modalEvidenciasCom').on('hidden.bs.modal', function(){
    console.log('paso')
        tablacomentario.ajax.reload(null, false);
    });

function adjuntosComentarios(incidentecomentario) {
	var idincidente  = $("#incidente").val();
	var arr = incidentecomentario.split('-');
	var comentario = arr[0];
	//var comentario = arr[1]; 
	var valid = true;
	if ( valid ) {
		$.ajax({
			  type: 'post',
			  url: 'controller/laboratorioback.php',
			  data: { 
				'oper'		 : 'adjuntosComentarios',
				'idincidente': idincidente,
				'idcomentario': comentario
			  },
			  success: function (response) {
					$('#fevidenciascom').attr('src','filegator/laboratoriocom.php#/?cd=laboratorio/'+idincidente+'/comentarios/'+comentario);
					$('#modalEvidenciasCom').modal('show');
					$('#modalEvidenciasCom .modal-lg').css('width','1000px');
					$('#idincidentesevidenciascom').val(idincidente);
					$('#idcomentariosevidencias').val(comentario);  
					$('.titulo-evidencia').html('Laboratorio: '+idincidente+' - Evidencia comentario');
			  },
			  error: function () { 
				notification("Error!",'Eroor!',"error"); 
			  }
		});
		tablacomentario.ajax.reload(null, false);
	}
	return valid;
}

function guardar() {
	
    if ( idincidente) {
        editarIncidente();
    }else{
        guardarIncidente();
    }
}

function guardarIncidente() {
	var id 				= $('#incidente').val();
	var dataserialize 	= $("#form_laboratorio").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'categoriaf' || dataserialize[i].name == 'subcategoriaf' || 
			dataserialize[i].name == 'idempresasf' || dataserialize[i].name == 'idempresasf'  ||
			dataserialize[i].name == 'iddepartamentosf' || dataserialize[i].name == 'iddepartamentosf'  ||
			dataserialize[i].name == 'idclientesf' || dataserialize[i].name == 'idclientesf'  ||
			dataserialize[i].name == 'idproyectosf' || dataserialize[i].name == 'idproyectosf'  ||				
			dataserialize[i].name == 'prioridadf' || dataserialize[i].name == 'solicitantef'  || 
			dataserialize[i].name == 'estadof' || dataserialize[i].name == 'asignadoaf'  || 
			dataserialize[i].name == 'unidadejecutoraf'	){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	if($("#notificar").select2("val") != ''){
		data['notificar'] = JSON.stringify($("#notificar").select2("val"));
	}else{
		data['notificar'] = '';
	}
	/* if($('#fueraservicio').is(':checked')){
		data['fueraservicio'] = 1;
	}else{
		data['fueraservicio'] = 0;
	} */

	if(data['solicitante'] == '0' || data['solicitante'] == ''){
		$("#"+dataserialize['solicitante']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['solicitante']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Solicitante",'warning');
	}else if(data['fechacreacion'] == ''){
		$("#"+dataserialize['fechacreacion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fechacreacion']).css({'border':'1px solid red'});
		notification("Advertencia!","Debe llenar el campo Fecha",'warning');
	}else if(data['titulo'] == ''){
		$("#"+dataserialize['titulo']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['titulo']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Nombre de Activo",'warning');
	}else if(data['descripcion'].trim() == ''){
		$("#"+dataserialize['descripcion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['descripcion']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Detalle del daño",'warning');
	}else if(data['idclientes'] == '0' || data['idclientes'] == ''){
		$("#"+dataserialize['idclientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Cliente",'warning');
	}else if(data['idproyectos'] == '0' || data['idproyectos'] == ''){
		$("#"+dataserialize['idproyectos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Proyecto",'warning');
	}else if(data['serie'] == ''){
		$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['serie']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Serie",'warning');
	}else if(data['marca'] == ''){
		$("#"+dataserialize['marca']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['marca']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Marca",'warning');
	}else if(data['modelo'] == ''){
		$("#"+dataserialize['modelo']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['modelo']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Modelo",'warning');
	}else if(data['prioridad'] == ''){
		$("#"+dataserialize['prioridad']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['prioridad']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Prioridad",'warning');
	}else if(data['iddepartamentos'] == ''){
		$("#"+dataserialize['iddepartamentos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['iddepartamentos']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Departamentos",'warning');
	}else{			
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/laboratorioback.php',
			data: { 
				'oper'	: 'guardarIncidente',
				'id'	: id,
				'data' 	: data
			},
			beforeSend: function() {
				$('#overlay').css('display','block'); 
			},
			success: function (response) {
				if(response == 1){  
					notification('','Buen trabajo!','success');
					vaciar();  
					swal({		
							title: 'Registro guardado satisfactoriamente',	
							text: "¿Desea registrar otro registro?",
							type: "success",
							allowEscapeKey : false,
							allowOutsideClick: false,
							showCancelButton: true,
							cancelButtonColor: 'red',
							confirmButtonColor: '#09b354',
							confirmButtonText: 'Sí',
							cancelButtonText: "No"
					}).then(function(isConfirm) {
						if (isConfirm.value === true) {
							document.getElementById('titulo').focus();
						}else{
							location.href = "laboratorios.php";
						}
					}); 
				} 
			},
			error: function () {
				$('#overlay').css('display','none');				 
				notification("Error","Error al guardar",'error');
			}
		});		 
	}
}

function vaciar(){
	$('#solicitante').val(null).trigger("change");
	$('#notificar').val(null).trigger("change");
	$('#fechaentrada').val("");
	$('#horacreacion').val("");
	$('#titulo').val("");
	$('#descripcion').val(""); 
	$('#serie').val("");
	$('#marca').val("");
	$('#modelo').val("");
	$('#idempresas').val(null).trigger("change"); 
	$('#idclientes').val(null).trigger("change");
	$('#idproyectos').val(null).trigger("change");
	$('#idcategorias').val(null).trigger("change");
	$('#idsubcategorias').val(null).trigger("change");
	$('#idprioridades').val(null).trigger("change");
	$('#idambientes').val(null).trigger("change");
	$('#idprioridades').val(null).trigger("change");  
	$('#unidadejecutora').val(null).trigger("change");
}

function editarIncidente() {
	var id 				= $('#incidente').val();
	var dataserialize 	= $("#form_laboratorio").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'categoria' || dataserialize[i].name == 'subcategoria' || 
			dataserialize[i].name == 'idempresas' || dataserialize[i].name == 'idempresas'  ||
			dataserialize[i].name == 'iddepartamentos' || dataserialize[i].name == 'iddepartamentos'  ||
			dataserialize[i].name == 'idclientes' || dataserialize[i].name == 'idclientes'  ||
			dataserialize[i].name == 'idproyectos' || dataserialize[i].name == 'idproyectos'  ||				
			dataserialize[i].name == 'prioridad' || dataserialize[i].name == 'solicitante'  || 
			dataserialize[i].name == 'estado' || dataserialize[i].name == 'asignadoa'  || 
			dataserialize[i].name == 'unidadejecutora'	){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	if($("#notificar").select2("val") != ''){
		data['notificar'] = JSON.stringify($("#notificar").select2("val"));
	}else{
		data['notificar'] = '';
	}
	/* if ($("#fueraservicio")!=$fueraservicioanterior) {
		if($('#fueraservicio').is(':checked')){
			data['fueraservicio'] = 1;
		}else{
			data['fueraservicio'] = 0;
		}
	} */
	if(data['fechaentrada']  == ''){
		$("#"+dataserialize['fechaentrada']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fechaentrada']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Fecha de Entrada",'warning');
	}else if(data['solicitante'] == '0' || data['solicitante'] == ''){
		$("#"+dataserialize['solicitante']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['solicitante']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Solicitante",'warning');
	}else if(data['fechacreacion'] == ''){
		$("#"+dataserialize['fechacreacion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fechacreacion']).css({'border':'1px solid red'});
		notification("Advertencia!","Debe llenar el campo Fecha",'warning');
	}else if(data['titulo'] == ''){
		$("#"+dataserialize['titulo']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['titulo']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Nombre de Activo",'warning');
	}else if(data['descripcion'].trim() == ''){
		$("#"+dataserialize['descripcion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['descripcion']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Detalle del daño",'warning');
	}else if(data['serie'] == ''){
		$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['serie']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Serie",'warning');
	}else if(data['marca'] == ''){
		$("#"+dataserialize['marca']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['marca']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Marca",'warning');
	}else if(data['modelo'] == ''){
		$("#"+dataserialize['modelo']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['modelo']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Modelo",'warning');
	}else if(data['prioridad'] == ''){
		$("#"+dataserialize['prioridad']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['prioridad']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Prioridad",'warning');
	}else if(data['iddepartamentos'] == ''){
		$("#"+dataserialize['iddepartamentos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['iddepartamentos']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Departamentos",'warning');
	}else if(data['idclientes'] == '0' || data['idclientes'] == ''){
		$("#"+dataserialize['idclientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Cliente",'warning');
	}else if(data['idproyectos'] == '0' || data['idproyectos'] == ''){
		$("#"+dataserialize['idproyectos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Proyecto",'warning');
	}else if(data['estado'] == '39' && data['fecharesolucion'] == ''){
		$("#"+dataserialize['fecharesolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucion']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Fecha y Hora de Resolución",'warning');
	}else if(data['estado'] == '39' && data['diagnostico'] == 'sinasignar'){
		$("#"+dataserialize['fecharesolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucion']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Estado de activo",'warning');
	}else{
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/laboratorioback.php',
			data: { 
				'oper'	: 'actualizarIncidente',
				'id'	: id,
				'data' 	: data
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			success: function (response) {
				if(response == 1){
					notification('Buen trabajo!','Registro actualizado satisfactoriamente','success'); 
					location.href = "laboratorios.php";
				}else{
					notification("Error","Error al actualizar el registro",'error'); 
				}				
			},
			error: function () {
				$('#overlay').css('display','none');				
				notification("Error","Error al actualizar el registro",'error'); 
			}
		});		
		$(".modal-container").removeClass('swal2-in');
	}
}


