$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() {
	$("select").select2({ language: "es" });
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false, lang : 'es', cancelText: 'Cancelar' });
	$('#fecharesolucion').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true, clearButton: true, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar' });
	$('#fechacreacion, #fechareal').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, clearButton: true, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar'  });

	var idincidente = getQueryVariable('id');
	if(idincidente != ''){
		var misCookies = document.cookie;
		var listaCookies = misCookies.split(";");
		var micookie = '';
		for (i in listaCookies) {
			busca = listaCookies[i].search("nivel");
			if (busca > -1) {
				micookie=listaCookies[i];
			}
		} 
		igual = micookie.indexOf("=");
		nivel = micookie.substring(igual+1);
		if(nivel == 4){
			$(".inc-edit").attr('disabled', true);	
			$('#adjuntonuevocliente').css('display','none');
		}
	}else{
		if(nivel == 4){
			$('.nonivelcliente').css('display','none');
		}
	}

	//Validación días fecha
	$('#fechacreacion').bootstrapMaterialDatePicker({ 
		weekStart : 0, 
		format:'YYYY-MM-DD',  
		lang : 'es', 
		switchOnClick:true, 
		time:false,
		clearButton: true, 
		cancelText: 'Cancelar',
		clearText: 'Limpiar' 
	}).on('change', function(e, date){
		$('#fecharesolucion').bootstrapMaterialDatePicker('setMinDate', date);
	});

	$('#horacreacion').bootstrapMaterialDatePicker({switchOnClick:true, date:false, clearButton: true, format : 'HH:mm', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar' });
	
	$('#calendarhidendesde').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar' }).on('change',function(){
	    var fechadesdeoculto = $('#calendarhidendesde').val();
	    $('#desdef').val(fechadesdeoculto);
	});	
	$('#calendarhidenhasta').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar' }).on('change',function(){
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
var idincidente = getQueryVariable('id');

function formatNumber(num) {
	return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
  }
  
  $("#ingresos").on('change',function(){
	  //console.log($(this).val());
	  var monto = $(this).val();
	  //console.log(formatNumber(monto));
	  $(this).val(formatNumber(monto));
  });


$("#listado").click(function(){
	location.href = "baseconocimientos.php";
});


function resultadosEncuesta(){
	if(idincidente){
		$.get( "controller/incidentesback.php?oper=encuestasIncidente", { idincidente: idincidente }, function(result){ 		
			$("#resultadosEncuesta").html(result);
		});
	}
}
$('a[href="#boxenc"]').click(function(){
	resultadosEncuesta();
});

function cargarCombosEditar() {
	//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#prioridad").empty();
		$("#prioridad").append(result);
	});
	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
		$("#solicitante").empty();
		$("#solicitante").append(result);
	});
	//NOTIFICAR A
	$.get( "controller/combosback.php?oper=usuariosGrupos", { onlydata:"true"}, function(result){ 
		$("#notificar").empty();
		$("#notificar").append(result);
	});
}

function cargarCombosEditarDepEd() {
	console.log("entroaqui");
	//EMPRESAS
	$.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresas").empty();
		$("#idempresas").append(result);
	});
	//EMPRESAS / CLIENTES - DEPARTAMENTOS
	$('#idempresas').on('select2:select',function(){
		//CLIENTES
		var idempresas = $("#idempresas option:selected").val();
		$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresas }, function(result){ 
			$("#idclientes").empty();
			$("#idclientes").append(result);
		});
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: idempresas }, function(result){ 
			$("#iddepartamentos").empty();
			$("#iddepartamentos").append(result);
		});
	});
	//CLIENTES / PROYECTOS - SITIOS
	$('#idclientes').on('select2:select',function(){
		var idempresas = $("#idempresas option:selected").val();
		var idclientes = $("#idclientes option:selected").val();
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
			$("#idproyectos").empty();
			$("#idproyectos").append(result);
		});				
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientes }, function(result){ 
			$("#unidadejecutora").empty();
			$("#unidadejecutora").append(result);
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idempresas: idempresas, idclientes: idclientes, tipo:"incidente" }, function(result){ 
			$("#estado").empty();
			$("#estado").append(result);
		});
	});	
	//PROYECTOS / CATEGORIAS
	$('#idproyectos').on('select2:select',function(){
		var idproyectos = $("#idproyectos option:selected").val();
		var idempresas = $("#idempresas option:selected").val();
		var idclientes = $("#idclientes option:selected").val();
		//AMBIENTES
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes+"&idproyectos="+idproyectos, { onlydata:"true" }, function(result){ 
			$("#unidadejecutora").empty();
			$("#unidadejecutora").append(result);
		});
		$.get( "controller/combosback.php?oper=categorias", { tipo: "incidente", idproyectos: idproyectos }, function(result){ 
			$("#categoria").empty();
			$("#categoria").append(result);
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idempresas: idempresas, idclientes: idclientes, idproyectos: idproyectos, tipo:"incidente" }, function(result){ 
			$("#estado").empty();
			$("#estado").append(result);
		});
	});
	//CATEGORIAS - SUBCATEGORIAS
	$('#categoria').on('select2:select',function(){
		var idcategoria = $("#categoria option:selected").val();
		$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: idcategoria }, function(result){ 
			$("#subcategoria").empty();
			$("#subcategoria").append(result);
		});
	});
	//SITIOS / SERIE
	$('#unidadejecutora').on('select2:select',function(){
		var idsitio = $("#unidadejecutora option:selected").val();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitio }, function(result){ 
			$("#serie, #marca, #modelo").empty();
			$("#serie").append(result);
		});
	});
	//SERIE / MARCA - MODELO
	$('#serie').on('select2:select',function(){
		var idserie = $("#serie option:selected").val();
		//SERIE
		$.ajax({
			url: "controller/combosback.php",
			type:"POST",
			data: { oper:"seriesel", idserie: idserie },
			dataType:"json",
			success: function(response){
				$.map(response, function (item) {
					$('#marca').val(item.marca);
					$('#modelo').val(item.modelo);
				});
			}
		});
	});
	/*
	//PROYECTOS		
	$("#idproyectos").change(function(e,data){
		var idproyectos = $("#idproyectos option:selected").val();
		if(idproyectos == 35){ 
			//OCULTAR
			$(".box-titulo").html("Orden de trabajo");
			$(".box-serie-t, .box-marca-t, .box-modelo-t").show();
			$(".box-cc, .box-sitio, .box-categorias, .box-subcategorias, .box-servicio, .box-horastrabajadas, .box-serie, .box-marca, .box-modelo").hide();
		}else{ 
			//MOSTRAR
			$(".box-titulo").html("Incidente");
			$(".box-serie-t, .box-marca-t, .box-modelo-t").hide();
			$(".box-cc, .box-sitio, .box-categorias, .box-subcategorias, .box-servicio, .box-horastrabajadas, .box-serie, .box-marca, .box-modelo").show();
		}
	});
	*/
	//DEPARTAMENTOS / ASIGNADO A
	$('#iddepartamentos').on('select2:select',function(){
		var iddepartamentos = $("#iddepartamentos option:selected").val();
		var idproyectos 	= $("#idproyectos option:selected").val();
		$.get( "controller/combosback.php?oper=usuariosDep", { idproyectos: idproyectos, iddepartamentos: iddepartamentos }, function(result){ 
			$("#asignadoa").empty();
			$("#asignadoa").append(result);
		});
	});
}

if (idincidente) {
	cargarCombosEditar();
	cargarCombosEditarDepEd();
    //toma la ruta 
        // var currURL = window.location.href;
        // //devuelve la ruta apartir de la vista
        // var afterDomain = currURL.substring(currURL.lastIndexOf('/') + 1);
        // //extrae los  parametros dejando solo la vista
        // var newURL = afterDomain.split("?")[1];
        // //cosntruye la ruta sitio y vista
        // URL=currURL.replace("?"+newURL,"");
        // //cambia el historial con la nueva ruta document.title el titulo para el historial{} es para la funcion  
        // window.history.replaceState({}, document.title, URL);
        
    $('#tabcom').css('display','block');
	$('#tabhis').css('display','block');
	$('#divfechresol').css('display','block');
	$('#divrepserv').css('display','block');
	$('#divhorastrab').css('display','block');
	$('#divaten').css('display','block');
	$('#divresol').css('display','block');

    $.ajax({
		type: 'get',
		dataType: 'json',
		url: 'controller/incidentesback.php',
		data: { 
			'oper'	: 'abrirIncidente',
			'id'	: idincidente
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		success: function (response) {
			$('#overlay').css('display','none');
			resultadosEncuesta();
			$.map(response, function (item) {
				var mensajeinfo = "Correctivo: "+item.id;
				$('#incidente').val(item.id);
				$('#ingresos').val(item.ingresos);
				$('#titulo').val(item.titulo);
				$('#descripcion').val(item.descripcion);
				//EMPRESAS
				$("#idempresas").val(item.idempresas).trigger("change");
				//EMPRESAS / CLIENTES - DEPARTAMENTOS
				$.when( $('#idempresas').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//CLIENTES
					$.get( "controller/combosback.php?oper=clientes", { idempresas: item.idempresas }, function(result){ 
						$("#idclientes").empty();
						$("#idclientes").append(result);
						$("#idclientes").val(item.idclientes).trigger("change");
					});
					//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: item.idempresas }, function(result){ 
						$("#iddepartamentos").empty();
						$("#iddepartamentos").append(result);
						$("#iddepartamentos").val(item.iddepartamentos).trigger("change");
					});
				});
				//CLIENTES / PROYECTOS - DEPARTAMENTOS
				$.when( $('#idclientes').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//PROYECTOS
					$.get( "controller/combosback.php?oper=proyectos", { idclientes: item.idclientes }, function(result){ 
						$("#idproyectos").empty();
						$("#idproyectos").append(result);
						$("#idproyectos").val(item.idproyectos).trigger("change");	
						//PROYECTOS / CATEGORIAS
						$.when( $('#idproyectos').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
							$.get( "controller/combosback.php?oper=categorias", { tipo: "incidente", idproyectos: item.idproyectos }, function(result){ 
								$("#categoria").empty();
								$("#categoria").append(result);
								$("#categoria").val(item.categoria).trigger("change");
							});
							//ESTADOS
							$.get( "controller/combosback.php?oper=estados", { idempresas: item.idempresas, idclientes: item.idclientes, idproyectos: item.idproyectos, tipo:"incidente" }, function(result){ 
								$("#estado").empty();
								$("#estado").append(result);
								$("#estado").val(item.estado).trigger("change");
							});
						});
					});
					//SITIOS
					$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: item.idclientes }, function(result){ 
						$("#unidadejecutora").empty();
						$("#unidadejecutora").append(result);
						$("#unidadejecutora").val(item.unidad).trigger("change"); 
					});
				});
				//CATEGORIAS - SUBCATEGORIAS
				$.when( $('#categoria').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: item.categoria }, function(result){ 
						$("#subcategoria").empty();
						$("#subcategoria").append(result);
						$("#subcategoria").val(item.subcategoria).trigger("change");
					});
				});
				//SITIOS / SERIE
				$.when( $('#unidadejecutora').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SERIE
					$.get( "controller/combosback.php?oper=serie", { idsitio: item.unidad }, function(result){ 
						$("#serie, #marca, #modelo").empty();
						$("#serie").append(result);
						$("#serie").val(item.serie).trigger("change");						
						$('#marca').val(item.marca);
						$('#modelo').val(item.modelo);
					});
				});				
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentos').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					var iddepartamentos = $("#iddepartamentos option:selected").val();
					$.get( "controller/combosback.php?oper=usuariosDep", { idproyectos: item.idproyectos, iddepartamentos: item.iddepartamentos }, function(result){ 
						$("#asignadoa").empty();
						$("#asignadoa").append(result);
						$("#asignadoa").val(item.asignadoa).trigger("change");
					});
				});
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
				$("#prioridad").val(item.prioridad).trigger("change");
				$("#solicitante").val(item.solicitante).trigger("change");
				$('#departamento').val(item.departamento);
				$('#modalidad').val(item.modalidad);
				$('#fechacierre').val(item.fechacierre);
				$('#horacierre').val(item.horacierre);
				$('#fechadesdefueraservicio').val(item.fechadesdefueraservicio);
				$('#fechafinfueraservicio').val(item.fechafinfueraservicio);
				$('#diasfueraservicio').val(item.diasfueraservicio);
				$('#fusionado').val(item.fusionado);
				if(item.fusionado !=' - '){
					mensajeinfo += " - Fusionado con: "+item.fusionado;
					$('#revertir-fusion-incidente').show();
				}else{
					$('#revertir-fusion-incidente').hide();
				}
				$("#nombreusuario").html(mensajeinfo);
				$('#divnombrecedula').show();
				$('#resolucion').val(item.resolucion);					
				$('#reporteservicio').val(item.reporteservicio);
				$('#numeroaceptacion').val(item.numeroaceptacion);
				$('#estadomantenimiento > option[value="'+item.estadomantenimiento+'"]').prop("selected", true);
				$('#observaciones').val(item.observaciones);					
				$('#horario').val(item.horario);
				$('#marca').val(item.marca);
				$('#modelo').val(item.modelo);
				$('#origen').val(item.origen);
				$('#creadopor').val(item.creadopor);
				$('#modalidad').val(item.modalidad);
				$('#comentariosatisfaccion').val(item.comentariosatisfaccion);
				$('#resueltopor').val(item.resueltopor);
				$('#periodo').val(item.periodo);
				$('#fechacreacion').val(item.fechacreacion);
				$('#horacreacion').val(item.horacreacion);
				$('#fechamodif').val(item.fechamodif);
				$('#fechavencimiento').val(item.fechavencimiento);
				$('#fecharesolucion').val(item.fecharesolucion);
				if(item.fechacreacion != ""){
					$('#fecharesolucion').bootstrapMaterialDatePicker('setMinDate', item.fechacreacion);
				}
				$('#fechacierre').val(item.fechacierre);
				$('#fechacertificar').val(item.fechacertificar);
				if(item.horastrabajadas!=0){
					horastrabajadas = item.horastrabajadas.split(':');
					$('#horast').val(horastrabajadas[0]);
					$('#minutost').val(horastrabajadas[1]); 
				}
				else{
					$('#horast').val('');
					$('#minutost').val('');
				}
				$('#atencion').val(item.atencion).trigger("change");	
				$fueraservicioanterior = item.fueraservicio;
				if(item.fueraservicio == 1){ 
					$("#fueraservicio").prop("checked", true); 
				}	
				$('#estadoant').val(item.estado);
				$('#fecharesolucionant').val(item.fecharesolucion);
				$('#contacto').val(item.contacto);
			});
		},
		complete: function(data,status){
			abrirComentarios(idincidente);
			abrirHistorial(idincidente);
		}
	});
    
}else{
	cargarCombosEditar();
	cargarCombosEditarDepEd();
}


/*eventos */

$('#tablacomentario').on( 'draw.dt', function () {	
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR COMENTARIOS
	$('.boton-eliminar-comentarios').each(function(){
		var id = $(this).attr("data-id"); 
		$(this).on( 'click', function() {
		});
	});
	// DAR FUNCIONALIDAD AL BOTON EVIDENCIAS
	$('.boton-adjuntos-comentarios').each(function(){
		var id = $(this).attr("data-id");
		$(this).on( 'click', function() {
			console.log("paso por aqui");
		});
	});
});

function abrirComentarios(idincidentecom){
	//COMENTARIOS
	if(nivel != 4){
		var cvisible = true;
	}else{
		var cvisible = false;
	}
	//COMENTARIOS
	tablacomentario = $("#tablacomentario").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		"ajax"		: {
			"url"	: "controller/incidentesback.php?oper=comentarios&id="+idincidentecom,
		},
		"columns"	: [
			{ 	"data": "acciones" },
			{ 	"data": "id" },
			{ 	"data": "comentario" },
			{ 	"data": "nombre" },
			{ 	"data": "visibilidad" },
			{ 	"data": "fecha" },
			{ 	"data": "adjuntos" }
			],
		"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		"columnDefs": [ //OCULTAR LA COLUMNA ID
			{
				"targets"	: [ 0,1 ],
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

$('a[href="#boxhis"]').click(function(){  
	//tablabitacora.columns.adjust().draw();
});
function abrirHistorial(idincidentehis){
	//HISTORIAL
	console.log("historico");
	console.log(nivel);
	if(nivel == 1 || nivel == 2 || nivel == 7){
		console.log("consulto");
		tablabitacora = $("#tablabitacora").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: "controller/incidentesback.php?oper=historial&id="+idincidentehis,
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
					"targets"	: [ 0,1 ],
					"visible"	: false,
					"searchable": false
				},{
					targets		: [2,3,4],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}
		});
	}
}


//***** ***** ***** ADJUNTO COMENTARIOS ***** ***** ***** //
/* var dialogAdj;
var optionsAdj = {
	url  : 'elFinder/php/connector.incidentes.php',
	lang : 'es',
	rememberLastDir: false,
	reloadClearHistory: true,
	useBrowserHistory: false
}	
var elfInstanceAdj = $('#elfinderCom').elfinder(optionsAdj).elfinder('instance'); */
//ACTUALIZAR TABLA AL SUBIR O ELIMINAR ARCHIVOS
/*
elfInstanceAdj.bind('upload remove', function(event) {
	console.log('upload remove Adj');
	tablacomentario.ajax.reload(null, false);
});
*/

						   
																													  
   

//***** ***** ***** COMENTARIOS ***** ***** ***** //
/*

function abrirGridAdjunto(idincidente,id) {
	var isVisible = $("#dialog-grid-adjunto").is(":visible");
	if (!isVisible) {
		comentarioselect = id;
		if (comentarioselect!=''){
			//$('#dialog-grid-adjunto').css('z-index','1050 !important');
			$('#idincidentec').val(idincidente);
			$('#idcomentarioc').val(comentarioselect);
			$('#dialog-grid-adjunto').show();
		} else {
			notification("Advertencia!",'Debe seleccionar un comentario','warning');
		}
	} else {
		$('#dialog-grid-adjunto').hide();
	}
}

*/


