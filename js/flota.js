$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
var botonfusionar = 0;
$(document).ready(function() {
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false, lang : 'es', cancelText: 'Cancelar' });
	$('#fecharesolucion,#fecharetiro,#fechadevolucion,#fechasolicituddesde,#fechasolicitudhasta').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true, clearButton: true, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar' });
	$('#fechacreacion, #fechareal').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, clearButton: true, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar'  });

	var idincidente = getQueryVariable('id');
	var vercom = getQueryVariable('vercom');
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
	$('.tipo').html('Editar Solicitud de Flota');
		if(vercom == 1){
	$("a[href$='#comentarios']").click();	    
		}
	    
	    
	}else{
		if(nivel == 4){
			$('.nonivelcliente').css('display','none');
		}
	$('.tipo').html('Nueva Solicitud de Flota');
	}


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
		$('#fechasolicitud').bootstrapMaterialDatePicker({ 
		weekStart : 0, 
		format:'YYYY-MM-DD',  
		lang : 'es', 
		switchOnClick:true, 
		time:false,
		clearButton: true, 
		cancelText: 'Cancelar',
		clearText: 'Limpiar' 
	}).on('change', function(e, date){
	    var fechasolicitudoculto = $('#fechasolicitud').val();
	});
});


var idincidente = getQueryVariable('id');
var fusionado = '';
var idincidentefusion = '';
const formatNumber = (num)=>( num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') )


$("#listado").click(function(){
	location.href = "flotas.php";
});

$('a[href="#comentarios"]').click(function(){
    var id = idincidente;
	$.ajax({
		type: 'post',
		url: 'controller/flotasback.php',
		data: { 
			'oper'		    : 'comentariosleidos', 
			'idincidente'   : id,
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		success: function (response) {
			$('#overlay').css('display','none');
			$(".boton-coment-"+id+"").removeClass("green");
			$(".boton-coment-"+id+"").addClass("blue");
		},
		error: function () {
			$('#overlay').css('display','none');
		}
	});
});


const cargarCombosEditarDepEd = ()=>{
    	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuarios", { }, function(result){ 
		$("#solicitante").empty();
		$("#solicitante").append(result);
	});
		//AMBIENTES
		$.get( "controller/combosback.php?oper=unidades", { onlydata:"true" }, function(result){ 
			$("#unidadejecutora,#serie").empty();
			$("#unidadejecutora").append(result);
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estadosflotas", {tipo:"Flota" }, function(result){ 
			$("#estado").empty();
			$("#estado").append(result);
		});
	//SITIOS / SERIE
	$('#unidadejecutora').on('select2:select',function(){
		var idsitio = $("#unidadejecutora option:selected").val();
	//	console.log("fechasolicitudoculto"+fechasolicitudoculto);
		//SERIE
		$.get( "controller/combosback.php?oper=autos", { }, function(result){ 
			$("#serie, #marca, #modelo").empty();
			$("#serie").append(result);
		});
	});
	
	//SERIE / MARCA - MODELO
	$('#serie').on('select2:select',function(){
	    $("#marca, #modelo").val("");
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
	
	//DEPARTAMENTOS / ASIGNADO A
		$.get( "controller/combosback.php?oper=usuariosDep", {iddepartamentos: 18 }, function(result){ 
			$("#asignadoa").empty();
			$("#asignadoa").append(result);
		});
}

const eliminarcomentario = (id)=>{
	var idincidente  = $("#incidente").val();											 
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
			if (isConfirm.dismiss!="cancel"){
				$.get( "controller/flotasback.php?oper=eliminarcomentarios", 
				{ 
					onlydata : "true",
					idcomentario : idcomentario,
					idincidente  : idincidente
				}, function(result){
					if(result == 1){
						notification("Comentario eliminado satisfactoriamente","¡Exito!",'success');
						tablacomentario.ajax.reload(null, false);
					} else if(result == 2){
						notification("No tiene permisos para eliminar este comentario","Error",'error');
					} else {
						notification("Ha ocurrido un error al eliminar el comentario","Error",'error');
					}
				});
			}
		}, function (isRechazo){
			
		}
	);
}

if (idincidente) {
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
	$('#tabest').css('display','block');
	$('#tabhis').css('display','block');
	$('#divsolic').css('display','block');
	$('#divfechresol').css('display','block');
	$('#divkilometrajes').css('display','block');
	$('#divgasolina').css('display','block');
	$('#divtarjetacontrol').css('display','block');
	$('#divresol').css('display','block'); 
	$('#divhorac').css('display','block'); 
	$('#divfechac').css('display','block'); 
	$('#divfecharetiro').css('display','block');
	$('#divvehiculos').css('display','block');
	$('#divmarca').css('display','block');
	$('#divmodelo').css('display','block');
	$('#divsolic').css('display','block');
	$('#divestado').css('display','block');


    $.ajax({
		type: 'get',
		dataType: 'json',
		url: 'controller/flotasback.php',
		data: { 
			'oper'	: 'abrirIncidente',
			'id'	: idincidente
		},
		beforeSend: function() {
		$('#preloader').css('display','block');
		},
		success: function (response) {
			$('#preloader').css('display','none');
			$.map(response, function (item) {
				var mensajeinfo = "Solicitud de Flota: "+item.id;
				$('#incidente').val(item.id); 
				$('#descripcion').val(item.descripcion);

							//ESTADOS
							$.get( "controller/combosback.php?oper=estadosflotas", { tipo:"Flota" }, function(result){ 
								$("#estado").empty();
								$("#estado").append(result);
								$("#estado").val(item.estado).trigger("change");
							});
					//SITIOS
						$("#destino").val(item.destino); 
					//SERIE
					$.get( "controller/combosback.php?oper=autos", { idempresas: 1,idclientes: 9,idproyectos:34  }, function(result){ 
						$("#serie, #marca, #modelo").empty();
						$("#serie").append(result);
						$("#serie").val(item.serie).trigger("change");		
				//		console.log("paso"+item.serie);
						$('#marca').val(item.marca);
						$('#modelo').val(item.modelo);
					});
				
				$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: 18 }, function(result){ 
						$("#asignadoa").empty();
						$("#asignadoa").append(result);
						$("#asignadoa").val(item.asignadoa).trigger("change");
				});
				//SOLICITANTE
				$.get( "controller/combosback.php?oper=usuarios", {  }, function(result){ 
				$("#solicitante").empty();
				$("#solicitante").append(result);
				$("#solicitante").val(item.solicitante).trigger("change");
				console.log("solicitante"+item.solicitante);
				});
				$('#modalidad').val(item.modalidad);
				$('#fechacierre').val(item.fechacierre);
				$('#horacierre').val(item.horacierre);
				$("#nombreusuario").html(mensajeinfo);
				$('#divnombrecedula').show();
				$('#resolucion').val(item.resolucion);					
				$('#marca').val(item.marca);
				$('#modelo').val(item.modelo);
				$('#fechacreacion').val(item.fechacreacion);
				$('#horacreacion').val(item.horacreacion);
				$('#fechasolicituddesde').val(item.fechasolicituddesde);
				$('#fechasolicitudhasta').val(item.fechasolicitudhasta);
				$('#fecharetiro').val(item.fecharetiro);
				$('#fecharesolucion').val(item.fecharesolucion);
				$('#fechacierre').val(item.fechacierre);
				$('#kilometrajeinicial').val(item.kilometrajeinicial);
				$('#kilometrajefinal').val(item.kilometrajefinal);
			    $('#gasolinainicial').val(item.gasolinainicial).trigger("change");
				$('#gasolinafinal').val(item.gasolinafinal).trigger("change");
				$('#tarjetagasolina').val(item.tarjetagasolina).trigger("change");
				$('#controlpuerta').val(item.controlpuerta).trigger("change");
			});
		},
		complete: function(data,status){
			abrirComentarios(idincidente);
			abrirEstados(idincidente);
			abrirHistorial(idincidente);
		}
	});
    
}else{
	cargarCombosEditarDepEd();
}


/*eventos */


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


const guardar = ()=>{

    if ( idincidente) {
        editar();
    }else{
        guardarincidente();
    }
}

const editar = ()=>{
	var id 				= idincidente;
	var dataserialize 	= $("#form_incidentes").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if(	dataserialize[i].name == 'solicitante'  	||
		    dataserialize[i].name == 'estado' 		||   dataserialize[i].name == 'asignadoa'  	    ||
		    dataserialize[i].name == 'serie' ||    dataserialize[i].name == 'gasolinainicial' ||
		    dataserialize[i].name == 'gasolinafinal' ||   dataserialize[i].name == 'tarjetagasolina' || 
		    dataserialize[i].name == 'controlpuerta'){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	if(data['fechasolicituddesde'] == ''){
		$("#"+dataserialize['fechasolicituddesde']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fechasolicituddesde']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Fecha de solicitud hasta es obligatorio','warning');
	}
	else if(data['fechasolicitudhasta'] == ''){
		$("#"+dataserialize['fechasolicitudhasta']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fechasolicitudhasta']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Fecha de solicitud hasta es obligatorio','warning');
	}
	else if(data['descripcion'] == ''){
		$("#"+dataserialize['descripcion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['descripcion']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Motivo por la cual requiere el auto es obligatorio','warning');
	}
	else if(data['destino'] == ''){
		$("#"+dataserialize['destino']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['destino']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Destino es obligatorio','warning');
	}
	else if(data['serie'] == '0' || data['serie'] == ''){
		$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['serie']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Los vehículos es obligatorio','warning');
	}
	else if(data['asignadoa'] == '0' || data['asignadoa'] == ''){
		$("#"+dataserialize['asignadoa']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['asignadoa']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Conductor es obligatorio','warning');
	}
	else if(data['estado'] == '16' && data['fecharetiro'] == ''){
		$("#"+dataserialize['fecharetiro']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharetiro']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Fecha y Hora de Retiro es obligatorio','warning');
	}
	else if(data['estado'] == '16' && data['fecharesolucion'] == ''){
		$("#"+dataserialize['fecharesolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucion']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Fecha y Hora de Devolución es obligatoria','warning');
	}
	else if(data['estado'] == '16' && data['kilometrajeinicial'] == ''){
		$("#"+dataserialize['kilometrajeinicial']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['kilometrajeinicial']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo kilometraje Inicial es obligatorio','warning');
	}
	else if(data['estado'] == '16' && data['kilometrajefinal'] == ''){
		$("#"+dataserialize['kilometrajefinal']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['kilometrajefinal']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo kilometraje Final es obligatorio','warning');
	}
	else if(data['estado'] == '16' && data['gasolinainicial'] == ''){
		$("#"+dataserialize['gasolinainicial']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['gasolinainicial']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Gasolina Inicial es obligatorio','warning');
	}
	else if(data['estado'] == '16' && data['gasolinafinal'] == ''){
		$("#"+dataserialize['gasolinafinal']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['gasolinafinal']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Gasolina Final es obligatorio','warning');
	}
	else if(data['estado'] == '16' && data['tarjetagasolina'] == ''){
		$("#"+dataserialize['tarjetagasolina']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['tarjetagasolina']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Tarjeta de Gasolina es obligatorio','warning');
	}
	else if(data['estado'] == '16' && data['controlpuerta'] == ''){
		$("#"+dataserialize['controlpuerta']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['controlpuerta']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Control de Puerta es obligatorio','warning');
	}
	else if(data['estado'] == '16' && data['resolucion'] == ''){
		$("#"+dataserialize['resolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['resolucion']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Estado en el cual entrega el auto es obligatorio','warning');
	}else{
	    
		$.ajax({
							type: 'post',
							dataType: "json",
							url: 'controller/flotasback.php',
							data: { 
								'oper'	: 'actualizarIncidente',
								'id'	: id,
								'data' 	: data
							},
							beforeSend: function() {
								$('#preloader').css('display','block');
							},
							success: function (response) {
								$('#preloader').css('display','none');
								// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
								notification("Solicitud actualizada satisfactoriamente","¡Exito!",'success');
							//	location.href = "flotas.php";
							},
							error: function () {
								$('#preloader').css('display','none');
								notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
							}
						}); 
					}
}


$(".flota").on('click',function(){ 
	if(botonfusionar == 1){
	    $('#revertir-fusion-incidente').show();
	}
});

$(".comentarios, .estados, .historial, .fusionado, .encuesta").on('click',function(){ 
	$('#revertir-fusion-incidente').hide();
});

const guardarincidente = ()=>{
	var id 				= idincidente;
	var dataserialize 	= $("#form_incidentes").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if(	dataserialize[i].name == 'solicitante'  	||
		    dataserialize[i].name == 'estado' 		||   dataserialize[i].name == 'asignadoa'  	|| 
		    dataserialize[i].name == 'serie'	){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	if(data['fechasolicituddesde'] == ''){
		$("#"+dataserialize['fechasolicituddesde']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fechasolicituddesde']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Fecha de solicitud hasta es obligatorio','warning');
	}
	else if(data['fechasolicitudhasta'] == ''){
		$("#"+dataserialize['fechasolicitudhasta']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fechasolicitudhasta']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Fecha de solicitud hasta es obligatorio','warning');
	}
	else if(data['descripcion'] == ''){
		$("#"+dataserialize['descripcion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['descripcion']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Motivo por la cual requiere el auto es obligatorio','warning');
	}
	else if(data['destino'] == ''){
		$("#"+dataserialize['destino']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['destino']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Destino es obligatorio','warning');
	}
	else if(data['asignadoa'] == '0' || data['asignadoa'] == ''){
		$("#"+dataserialize['asignadoa']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['asignadoa']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Campo Conductor es obligatorio','warning');
	}else{
						$.ajax({
							type: 'post',
							dataType: "json",
							url: 'controller/flotasback.php',
							data: { 
								'oper'	: 'guardarIncidente',
								'id'	: id,
								'data' 	: data
							},
							beforeSend: function() {
								$('#preloader').css('display','block');
							},
							success: function (response) {
								$('#preloader').css('display','none');
								// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
								notification("Flota creada satisfactoriamente","¡Exito!",'success');
								location.href = "flotas.php";
							},
							error: function () {
								$('#preloader').css('display','none');
								notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
							}
						}); 
					}
}

$('#tablacomentario').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})

const abrirComentarios = (idincidentecom)=>{
	//COMENTARIOS
	/* if(nivel != 4){
		let cvisible = true;
	}else{
		let cvisible = false;
	} */
	nivel != 4 ? cvisible = true : cvisible = false;
	
	//COMENTARIOS
	tablacomentario = $("#tablacomentario").DataTable({
		//scrollY: '100%',
		//scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: false,
		autoWidth : false,
		//select: { style: 'multi' }, 
		"ajax"		: {
			"url"	: "controller/flotasback.php?oper=comentarios&id="+idincidentecom,
		},
		"columns"	: [
			{ 	"data": "id" },			//0
			{ 	"data": "acciones" },	//1
			{ 	"data": "comentario" },	//2
			{ 	"data": "nombre" },		//3
			{ 	"data": "visibilidad" },//4
			{ 	"data": "fecha" },		//5
			{ 	"data": "adjuntos" }	//6
			],
		"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		"columnDefs": [ //OCULTAR LA COLUMNA ID
			{
				"targets"	: [ 0 ],
				"visible"	: false,
				"searchable": false
			},/* {
				targets		: [2,3,4,5],
				className	: "dt-left",
				width		: '100px'
			},  */
			{
				visible		: cvisible,
				targets		: [4]
			}
		],
		"language": {
			"url": "js/Spanish.json"
		},
		dom: '<"toolbarC toolbarDT">Blfrtip'
	});
}

$('a[href="#boxest"]').click(function(){
	//tablaestados.columns.adjust().draw();
});

const abrirEstados = (idincidenteest)=>{
	//ESTADOS
	tablaestados = $("#tablaestados").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		"ajax"		: {
			"url"	: "controller/flotasback.php?oper=estadosbit&id="+idincidenteest,
		},
		"columns"	: [
			{ 	"data": "estadoant" },
			{ 	"data": "estadoact" },
			{ 	"data": "fecha" },
			{ 	"data": "dias" }
			],
		"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		"columnDefs": [ //OCULTAR LA COLUMNA ID
			{
				targets		: [0,1,2,3],
				className	: "dt-left"
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
const abrirHistorial = (idincidentehis)=>{
	//HISTORIAL
	
	if(nivel == 1 || nivel == 2 || nivel == 7){
		
		tablabitacora = $("#tablabitacora").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: "controller/flotasback.php?oper=historial&id="+idincidentehis,
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

const abrirFusionados = (idincidentefus)=>{
	//FUSIONADOS
	tablafusionados = $("#tablafusionados").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		"ajax"		: {
			"url"	: "controller/flotasback.php?oper=fusionados&id="+idincidentefus,
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


$("#guardar-evidenciacom").on('click',function(){
	var idincidentesevidencias  = $('#idincidentesevidenciascom').val();
	var idcomentariosevidencias = $('#idcomentariosevidencias').val();
	$.ajax({
			type: 'post',
			url: 'controller/flotasback.php',
			beforeSend: function() {
				$('#preloader').css('display','block');
			},
			data: { 
				'oper'	   : 'notificacionAdjunto',
				'incidente': idincidentesevidencias,
				//'imagen'   : nimagen,
				'idcoment' : idcomentariosevidencias		 
			},
			success: function (response) { 
				$('#preloader').css('display','none'); 
				notification("Notificación enviada","¡Exito!",'success');
				
			},
			error: function () {
				$('#preloader').css('display','none');
				notification("Ha ocurrido un error","Error",'error');
			}
		});
		tablacomentario.ajax.reload(null, false); 
		$('#modalEvidenciasCom').modal('hide');
		//demo.showSwal('success-message','Archivos Adjuntos','Satisfactoriamente');
		//tablacomentario.ajax.reload(null, false);
		
});
	
	
$('#modalEvidenciasCom').on('hidden.bs.modal', function(){
    tablacomentario.ajax.reload(null, false);
});
	
var dirxdefecto = 'flota'; 
$('#fevidenciascom').attr('src','filegator/flotascom.php#/?cd=%2F'+dirxdefecto);
	
//Adjuntos de comentarios
const adjuntosComentarios =(incidentecomentario)=>{
	let arr = incidentecomentario.split('-');
	let incidente = arr[0];
	let comentario = arr[1]; 
	let valid = true;
	if ( valid ) {
		$.ajax({
			type: 'post',
			url: 'controller/flotasback.php',
			data: { 
				'oper': 		'adjuntosComentarios',
				'incidentecom': incidentecomentario
			},
			success: function (response) {
				$('#fevidenciascom').attr('src','filegator/flotascom.php#/?cd=flotas/'+incidente+'/comentarios/'+comentario);									 
				$('#modalEvidenciasCom').modal('show');
				$('#modalEvidenciasCom .modal-lg').css('width','1000px');
				$('#idincidentesevidenciascom').val(incidente);
				$('#idcomentariosevidencias').val(comentario);
				$('.titulo-evidencia').html('Solicitud de Flota: '+incidente+' - Evidencia comentario'); 
				$('#modalEvidenciasCom').css('z-index','1150');
			},
			error: function () {
				notification("Ha ocurrido un error, intente mas tarde","Error",'error'); 
			}
		});
	}
	return valid; 
}

		  
//***** ***** ***** COMENTARIOS ***** ***** ***** //
const abrirGridAdjunto = (idincidente,id)=>{
	let isVisible = $("#dialog-grid-adjunto").is(":visible");
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

//COMENTARIO AGREGAR
var form,
	comentario = $( "#comentario" ),
	allFields = $( [] ).add( comentario ),
	tips = $( ".validateTips" );

const agregarComentario = ()=>{	
	var coment  = $('#comentario').val();		
	var visibilidad  = $('input[name=visibilidad]:checked').val();
	var incidenteselect = getQueryVariable('id');
	if(coment==''){
		$('#comentario').addClass('form-valide-error-bottom');
		return;
	}
	if(visibilidad == undefined ){
		visibilidad  = 'Público';		
	}else if(visibilidad == ''){
		$('input[name=visibilidad]').addClass('form-valide-error-bottom');
		return;
	}

	if (coment != '') {
		$.ajax({
			type: 'post',
			url: 'controller/flotasback.php',
			data: { 
				'oper'	: 'agregarComentario',
				'id' : incidenteselect,
				'coment' : coment,
				'visibilidad' : visibilidad
			},
			beforeSend: function() {
				$('#preloader').css('display','block');
				$('#dialog-form-coment').hide();
			},
			success: function (response) {
				$('#preloader').css('display','none');
				if(response != 0){					
					$('#comentario').val("");					
					if ( $('.boton-coment-'+incidenteselect+'').length > 0 ) {
						$('.boton-coment-'+incidenteselect+'').removeClass("blue");
						$('.boton-coment-'+incidenteselect+'').addClass('green');
					}else{
						$('.msj-'+incidenteselect+'').append('<span class="icon-col green fa fa-comment boton-coment-'+incidenteselect+'" data-id="" data-toggle="tooltip" data-original-title="Comentarios" data-placement="right"></span>');
					}
					tablacomentario.ajax.reload(null, false);  
					if(response == 1){
						notification("Comentario Almacenado satisfactoriamente","¡Exito!",'success');
					} else if(response == 2){
						swal({
							title: 'Comentario Almacenado Satisfactoriamente',
							text: '¿Desea enviar la encuesta?',
							type: "success",
							buttonsStyling: false,
							showCloseButton: true,
							showCancelButton: true,
							confirmButtonClass: "btn btn-success",
							cancelButtonClass: 'btn btn-danger',
							confirmButtonText: 'SÍ',
							cancelButtonText: 'NO'
						}).then(function(isConfirm) {
						 console.log(isConfirm)
                        if (isConfirm.value === true) {
								//Envío de la encuesta
								$.ajax({
									type: 'post',
									dataType: "json",
									url: 'controller/flotasback.php',
									data: { 
										'oper'		: 'enviarEncuesta',
										'id'		: incidenteselect 
									}, 
									beforeSend: function() {
										$('#preloader').css('display','block');
									},
									success: function (response) { 
										$('#preloader').css('display','none');
										notification("Encuesta enviada satisfactoriamente","¡Exito!",'success');
																		
									},
									error: function () {
										$('#preloader').css('display','none'); 
										notification("Ha ocurrido un error al enviar la encuesta, intente mas tarde","Error",'error');
									}
								});
                        }else{
                        swal.close();
                        notification("Advertencia!",'Encuesta no enviada','warning');
                        }
							},
							function (dismiss) {
								//console.log("NO QUISO ENVIAR LA ENCUESTA");
							}
						);
					}
				}else{
					$('#preloader').css('display','none');					 
					notification("Ha ocurrido un error al grabar el Comentario, intente mas tarde","Error",'error');
				}
			},
			error: function () {
				$('#preloader').css('display','none');
				notification("Ha ocurrido un error al grabar el Comentario, intente mas tarde","Error",'error');
			}
		});
	}
	return;
}
const limpiarComentario = ()=> {
	$('#comentario').val('');
}
$("select").select2({ language: "es" });


