$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() {
	
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false, lang : 'es', cancelText: 'Cancelar' });
	$('#fecharesolucion, #fechareal').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true, clearButton: true, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar' });
	$('#fechacreacion').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, clearButton: true, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar'  });

	var idpreventivo = getQueryVariable('id');
	if(idpreventivo != ''){
	    $('.tipo').html('Editar preventivo');
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
		$('.tipo').html('Nuevo preventivo');
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
		$('#fecharesolucion, #fechareal').bootstrapMaterialDatePicker('setMinDate', date);
	});

	$('#horacreacion').bootstrapMaterialDatePicker({switchOnClick:true, date:false, clearButton: true, format : 'HH:mm', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar' });
});
var idpreventivo = getQueryVariable('id');
const formatNumber = (num) => (num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,'))
  
  $("#costo").on('change',function(){
	  //console.log($(this).val());
	  var monto = $(this).val();
	  //console.log(formatNumber(monto));
	  $(this).val(formatNumber(monto));
  });


$("#listado").click(function(){
	location.href = "preventivos.php";
});

$('a[href="#comentarios"]').click(function(){
    var id = idpreventivo;
	$.ajax({
		type: 'post',
		url: 'controller/preventivosback.php',
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

const cargarCombosEditar = () =>{
	//PRIORIDAD
	/*$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#prioridad").empty();
		$("#prioridad").append(result);
	});*/
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

const cargarCombosEditarDepEd = () =>{
	
	var idempresas = 1;
	//EMPRESAS
	$.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresas").empty();
		$("#idempresas").append(result);
	});
	//CLIENTES
	
	$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresas }, function(result){ 
		$("#idclientes").empty();
		$("#idclientes").append(result); 
		if(idpreventivo == '') optionDefault('idclientes');	   
	});
	//CLIENTES / PROYECTOS - SITIOS
	$('#idclientes').on('select2:select',function(){
		let idclientes = $("#idclientes option:selected").val();
		
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
			$("#idproyectos").empty();
			$("#idproyectos").append(result);
			if(idpreventivo == '') optionDefault('idproyectos');					 
		});				
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientes }, function(result){  
			$("#unidadejecutora").empty();
			$("#unidadejecutora").append(result);
			if(idpreventivo == '') optionDefault('unidadejecutora');
		});
	});	
	//PROYECTOS / CATEGORIAS
	$('#idproyectos').on('select2:select',function(){
		var idproyectos = $("#idproyectos option:selected").val();
		var idclientes = $("#idclientes option:selected").val(); 
		//AMBIENTES
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes+"&idproyectos="+idproyectos, { onlydata:"true" }, function(result){  
			$("#unidadejecutora").empty();
			$("#unidadejecutora").append(result);
			if(idpreventivo == '') optionDefault('unidadejecutora');
		});
		$.get( "controller/combosback.php?oper=categorias", { tipo: "Preventivo", idproyectos: idproyectos }, function(result){ 
			$("#categoria").empty();
			$("#categoria").append(result);
			if(idpreventivo == '') optionDefault('categoria');				  
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idproyectos: idproyectos, tipo:"Preventivo" },
		function(result){ 
			$("#estado").empty();
			$("#estado").append(result);
		});
		//DEPARTAMENTOS
    	$.get( "controller/combosback.php?oper=departamentosgrupos", { idproyectos: idproyectos }, function(result){ 
    		$("#iddepartamentos").empty();
    		$("#iddepartamentos").append(result);
    	});
    	//PRIORIDADES
    	$.get( "controller/combosback.php?oper=prioridades", { idclientes: idclientes, idproyectos: idproyectos }, function(result){ 
    		$("#prioridad").empty();
    		$("#prioridad").append(result);
    	});
	});
	//CATEGORIAS - SUBCATEGORIAS
	$('#categoria').on('select2:select',function(){
		//MÓDULOS CATEGORÍAS //
		
		var idproyectos = $("#idproyectos option:selected").val();
		var idcategoria = $("#categoria option:selected").val();
		$.get( "controller/combosback.php?oper=subcategorias", { tipo: 'Preventivo', idproyectos: idproyectos, idcategoria: idcategoria}, function(result){ 
			$("#subcategoria").empty();
			$("#subcategoria").append(result);
			if(idpreventivo == '') optionDefault('subcategoria');
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
		$.get( "controller/combosback.php?oper=areas", { idubicacion: idsitio }, function(result){ 
			$("#area").empty();
			$("#area").append(result);
			if(idpreventivo == '') optionDefault('area');			 
		});
	});
	//AREA / SERIE
	/* $('#area').on('select2:select',function(){
		var idsitio = $("#unidadejecutora option:selected").val();
		var idarea = $("#area option:selected").val();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitio, idarea: idarea }, function(result){ 
			$("#serie, #marca, #modelo").empty();
			$("#serie").append(result);
		});
	}); */
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

//Deshabilitar activo si categoría es Infraestructura
/*
$('#categoria').on("select2:select", function(e) { 
    let textcat = $("#categoria option:selected").text();
	let arrcat = textcat.split(' - ');
	let nomcat = arrcat[0];
	if(nomcat == 'Infraestructura'){
		$("#serie").prop('disabled', true);
		$("#area").prop('disabled', false);
		$("#serie").val(null).trigger('change');
		$("#marca").val('');
		$("#modelo").val('');
	}else{
		$("#serie").prop('disabled', false);
		$("#area").prop('disabled', true);
		$("#area").val(null).trigger('change');
	}  
});
*/

const eliminarcomentario = (id) =>{
	var idpreventivo = getQueryVariable('id');	
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
				$.get( "controller/preventivosback.php?oper=eliminarcomentarios", 
				{ 
					onlydata : "true",
					idcomentario : idcomentario,
					idincidente  : idpreventivo
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

if (idpreventivo) {
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
	$('#tabest').css('display','block');
	$('#tabhis').css('display','block');
	$('#tabfus').css('display','block');
	$('#tabcost').css('display','block');
	$('#divfechresol').css('display','block');
	$('#divrepserv').css('display','block');
	$('#divhorastrab').css('display','block');
	$('#divaten').css('display','block');
	$('#divresol').css('display','block');


    $.ajax({
		type: 'get',
		dataType: 'json',
		url: 'controller/preventivosback.php',
		data: { 
			'oper'	: 'abrirIncidente',
			'id'	: idpreventivo
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		success: function (response) {
			$('#overlay').css('display','none');
			$.map(response, function (item) {
				var mensajeinfo = "Preventivo: "+item.id;
				$('#incidente').val(item.id);
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
							$.get( "controller/combosback.php?oper=categorias", { tipo: "Preventivo", idproyectos: item.idproyectos }, function(result){ 
								$("#categoria").empty();
								$("#categoria").append(result);
								$("#categoria").val(item.categoria).trigger("change");
							});
							//ESTADOS
							$.get( "controller/combosback.php?oper=estados", { idproyectos: item.idproyectos, tipo:"Preventivo" }, function(result){ 
								$("#estado").empty();
								$("#estado").append(result);
								$("#estado").val(item.estado).trigger("change");
							});
												//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgrupos", { idproyectos: item.idproyectos }, function(result){ 
						$("#iddepartamentos").empty();
						$("#iddepartamentos").append(result);
						$("#iddepartamentos").val(item.iddepartamentos).trigger("change");
					});
					    //PRIORIDADES
						$.get( "controller/combosback.php?oper=prioridades", { idclientes: item.idclientes, idproyectos: item.idproyectos }, function(result){ 
								$("#prioridad").empty();
								$("#prioridad").append(result);
								$("#prioridad").val(item.prioridad).trigger("change");
							});
						});
					});
					//SITIOS
					$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: item.idclientes }, function(result){ 
						$("#unidadejecutora").empty();
						$("#unidadejecutora").append(result);
						$("#unidadejecutora").val(item.unidad).trigger("change"); 
					});
					$.get( "controller/combosback.php?oper=areas", { idubicacion: item.unidad }, function(result){ 
		            	$("#area").empty();
			            $("#area").append(result);
			            $("#area").val(item.idsubambientes.split(',')).trigger("change"); 
		            });
				});
				//CATEGORIAS - SUBCATEGORIAS
				$.when( $('#categoria').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					
					//MÓDULOS CATEGORÍAS //
					
					
					$.get( "controller/combosback.php?oper=subcategorias", { tipo: 'Preventivo', idproyectos: item.idproyectos, idcategoria: item.categoria }, function(result){ 
						$("#subcategoria").empty();
						$("#subcategoria").append(result);
						$("#subcategoria").val(item.subcategoria).trigger("change");
					}); 
				}); 
				//SITIOS / SERIE
				$.when( $('#unidadejecutora').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SERIE
					$.get( "controller/combosback.php?oper=serie", { idsitio: item.unidad/*, idarea: item.idsubambientes*/}, function(result){ 
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
				$('#fechareal').val(item.fechareal);
				$('#frecuencia').val(item.frecuencia).trigger("change");
				$('#idcorrectivos').val(item.idcorrectivos);								
				if((item.estado == 16) && (item.idcorrectivos == null || item.idcorrectivos == '')){
					$('.btn-nuevocorrectivo').css('display','block');
				}
			});
		},
		complete: function(data,status){
			abrirComentarios(idpreventivo);
			abrirEstados(idpreventivo);
			abrirHistorial(idpreventivo);
			abrirFusionados(idpreventivo);
			abrirCostos(idpreventivo);
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

$('#tablacostos').on( 'draw.dt', function () {	
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR COMENTARIOS
	$('.boton-eliminar-costos').each(function(){
		var id = $(this).attr("data-id"); 
		$(this).on( 'click', function() {
			eliminarcostos(id);
		});
	});
	// DAR FUNCIONALIDAD AL BOTON EVIDENCIAS
	$('.boton-adjuntos-costos').each(function(){
		var id = $(this).attr("data-id");
		$(this).on( 'click', function() {
			adjuntoscostos(id);
		});
	});
});

const guardar = () =>{ 
    idpreventivo ? editar() : guardarincidente();
}

const editar = () => {
	var id 				= idpreventivo;
	var dataserialize 	= $("#form_incidentes").serializeArray();
	var data 			= {};
	let textcategoria   = $('#categoria :selected'). text();
	let arrcategoria 	= textcategoria.split(' - ');
	let nomcategoria 	= arrcategoria[0];
	
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'categoria' 		|| dataserialize[i].name == 'subcategoria' 	|| 
			dataserialize[i].name == 'idempresas' 		|| dataserialize[i].name == 'iddepartamentos'||
			dataserialize[i].name == 'idclientes' 		|| dataserialize[i].name == 'idproyectos'  	||				
			dataserialize[i].name == 'prioridad' 		|| dataserialize[i].name == 'solicitante'  	|| 
			dataserialize[i].name == 'estado' 			|| dataserialize[i].name == 'asignadoa'  	|| 
			dataserialize[i].name == 'unidadejecutora'	|| dataserialize[i].name == 'area'	        || 
			dataserialize[i].name == 'serie' || dataserialize[i].name == 'frecuencia'){
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
	if($('#fueraservicio').is(':checked')){
		data['fueraservicio'] = 1;
	}else{
		data['fueraservicio'] = 0;
	} 
	if(data['titulo'] == ''){
		$("#"+dataserialize['titulo']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['titulo']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Título es obligatorio','warning');

	} 
	else if(data['idclientes'] == '0' || data['idclientes'] == ''){
		$("#"+dataserialize['idclientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Cliente es obligatorio','warning');
	}else if(data['idproyectos'] == '0' || data['idproyectos'] == ''){
		$("#"+dataserialize['idproyectos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Proyecto es obligatorio','warning');
	}else if(data['categoria'] == '0' || data['categoria'] == ''){
		$("#"+dataserialize['categoria']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['categoria']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Categoria es obligatoria','warning');
	}else if(((nivel != 4 && nivel != 7)) && (data['prioridad'] == '0' || data['prioridad'] == '' || data['prioridad'] == undefined)){
		$("#"+dataserialize['prioridad']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['prioridad']).css({'border':'1px solid red'});
		notification("Advertencia!",'La prioridad es obligatoria','warning');
	} 
	else if((data['estado'] == '16' || data['estado'] == '55') && data['fecharesolucion'] == ''){
		$("#"+dataserialize['fecharesolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucion']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Fecha y Hora de Resolución es obligatoria','warning');
	}else if((data['estado'] == '16' || data['estado'] == '55') && data['resolucion'] == ''){
		$("#"+dataserialize['resolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['resolucion']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Resolución es obligatoria','warning');
	}else if(data['estado'] == '' || data['estado'] == null  || data['estado'] == undefined || data['estado'] == '0' ){
		notification("Advertencia!",'El estado es obligatorio','warning');
	}else {		
		
		//Verifico estado y frecuencia 
		
		if((data['estado'] == '16' || data['estado'] == '55') && data['frecuencia'] != ""){
			
			//Pregunta si desea crear próximo preventivo para tipos No Infraestructura
			if(nomcategoria != 'Infraestructura'){
			
				swal({
					title: "Confirmar",
					text: "¿Desea crear el próximo preventivo?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){ 
						if (isConfirm.value === true){
							
							//Actualiza y crea nuevo preventivo
							ajaxActualizar(id,data,1,data['estadoant'],data['estado'],data['idcorrectivos']);
						}else{ 
							//Actualiza
							ajaxActualizar(id,data,0,data['estadoant'],data['estado'],data['idcorrectivos']);
						} 
					}, function (isRechazo){
						
						//Actualiza
						ajaxActualizar(id,data,0);
					}
				);
			}else{
				
				//Crea próximo preventivo para tipos Infraestructura
				ajaxActualizar(id,data,1,data['estadoant'],data['estado'],data['idcorrectivos']);
			} 
		}else{
			
			//Actualiza
			ajaxActualizar(id,data,0,data['estadoant'],data['estado'],data['idcorrectivos']);
		} 
		 	
		$(".modal-container").removeClass('swal2-in');
	}
}

const ajaxActualizar = (id,data,nuevoprev,estadoant,estado,idcorrectivos) =>{
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/preventivosback.php',
		data: { 
			'oper'		: 'actualizarIncidente',
			'id'		: id,
			'data' 		: data,
			'nuevoprev' : nuevoprev
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
			$(".modal-container").addClass('swal2-in'); 
		},
		success: function (response) {
			$('#overlay').css('display','none');
			 
			notification("Preventivo actualizado satisfactoriamente","¡Exito!",'success'); 
			
			if(estadoant != estado && estado == 16 && idcorrectivos == ""){
				if(nivel != 1 && nivel != 2 && nivel != 5 && nivel != 7){
					location.href = "preventivos.php";
				}else{
					$('.btn-nuevocorrectivo').css('display','block');
				} 
			}else{
				location.href = "preventivos.php";
			} 
		},
		error: function () {
			$('#overlay').css('display','none');
			notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
		}
	});	
}

const guardarincidente = () =>{
	
	var id 				= idpreventivo;
	var dataserialize 	= $("#form_incidentes").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'categoria' 		|| dataserialize[i].name == 'subcategoria' 	|| 
			dataserialize[i].name == 'idempresas' 		|| dataserialize[i].name == 'iddepartamentos'||
			dataserialize[i].name == 'idclientes' 		|| dataserialize[i].name == 'idproyectos'  	||				
			dataserialize[i].name == 'prioridad' 		|| dataserialize[i].name == 'solicitante'  	|| 
			dataserialize[i].name == 'estado' 			|| dataserialize[i].name == 'asignadoa'  	|| 
			dataserialize[i].name == 'unidadejecutora'	|| dataserialize[i].name == 'area'			||
			dataserialize[i].name == 'frecuencia'){
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
	if($('#fueraservicio').is(':checked')){
		data['fueraservicio'] = 1;
	}else{
		data['fueraservicio'] = 0;
	}
	
	if(data['titulo'] == ''){
		$("#"+dataserialize['titulo']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['titulo']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Título es obligatorio','warning');
	}else if(data['idclientes'] == '0' || data['idclientes'] == ''){
		$("#"+dataserialize['idclientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Cliente es obligatorio','warning');
	}else if(data['idproyectos'] == '0' || data['idproyectos'] == ''){
		$("#"+dataserialize['idproyectos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Proyecto es obligatorio','warning');
	}else if(data['categoria'] == '0' || data['categoria'] == ''){
		$("#"+dataserialize['categoria']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['categoria']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Categoria es obligatoria','warning');
	}else if(((nivel != 4 && nivel != 7)) && (data['prioridad'] == '0' || data['prioridad'] == '' || data['prioridad'] == undefined)){
		$("#"+dataserialize['prioridad']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['prioridad']).css({'border':'1px solid red'});
		notification("Advertencia!",'La prioridad es obligatoria','warning');
	}else if(data['estado'] == '' || data['estado'] == null  || data['estado'] == undefined || data['estado'] == '0' ){
		notification("Advertencia!",'El estado es obligatorio','warning');
	}else{	
		//Verifico si la categoría tiene Subcategorías
		//$.get( "controller/incidentesback.php?oper=existeSubcategoria", { idcategoria: $('#categoria').val() }, function(result){  
			//if((result == 1 && data['subcategoria'] == '') || (result == 1 && data['subcategoria'] == '0')){ 
			//	notification("Advertencia!",'La Subcategoria es obligatoria','warning');
			//}else{  
				//$.get( "controller/incidentesback.php?oper=validarComentarios", { id: id, idestadosnew : data['estado'], asignadoa: data['asignadoa'] }, function(result){  
					//if(result == 1){
						//notification("Advertencia!",'Debe agregar un comentario antes de asignar el correctivo','warning');
				//	}else{
						$.ajax({
							type: 'post',
							dataType: "json",
							url: 'controller/preventivosback.php',
							data: { 
								'oper'	: 'guardarIncidente',
								'id'	: id,
								'data' 	: data
							},
							beforeSend: function() {
								$('#overlay').css('display','block');
								$(".modal-container").addClass('swal2-in');
							},
							success: function (response) {
								$('#overlay').css('display','none');
								// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
								notification("Preventivo creado satisfactoriamente","¡Exito!",'success');
								location.href = "preventivos.php";
							},
							error: function () {
								$('#overlay').css('display','none');
								notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
							}
						}); 
					//}
				//}); 
			//}
	//	});
			
		$(".modal-container").removeClass('swal2-in');
	}
}

$('#tablacomentario').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})

const abrirComentarios = (idincidentecom) =>{
	nivel !=4 ? cvisible = true : cvisible = false;
	//COMENTARIOS
	tablacomentario = $("#tablacomentario").DataTable({
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : false,
		"ajax"		: {
			"url"	: "controller/preventivosback.php?oper=comentarios&id="+idincidentecom,
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

const abrirCostos = (idincidentecom)=>{
	  
	tablacostos = $("#tablacostos").DataTable({ 
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : false, 
		"ajax"		: {
			"url"	: "controller/preventivosback.php?oper=costos&id="+idincidentecom,
		},
		"columns"	: [
			{ 	"data": "id" },			//0
			{ 	"data": "acciones" },	//1
			{ 	"data": "descripcion" },//2
			{ 	"data": "monto" },		//3
			{ 	"data": "usuario" },	//4
			{ 	"data": "fecha" },		//5
			{ 	"data": "adjuntos" }	//6
			],
		"rowId": 'id', 	
		"columnDefs": [ 
			{
				"targets"	: [ 0 ],
				"visible"	: false,
				"searchable": false
			} 
		],
		"language": {
			"url": "js/Spanish.json"
		},
		dom: '<"toolbarC toolbarDT">Blfrtip',
		"footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
			var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '$' ).display;
            $( api.column( 3 ).footer() ).html(
                numFormat(pageTotal) +' ( '+ numFormat(total) +' Total)'
            );
        }
	});
}

$('a[href="#boxest"]').click(function(){
	//tablaestados.columns.adjust().draw();
});
const abrirEstados = (idincidenteest)=>{
	//ESTADOS
	tablaestados = $("#tablaestados").DataTable({
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : false,
		"ajax"		: {
			"url"	: "controller/preventivosback.php?oper=estadosbit&id="+idincidenteest,
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
const abrirHistorial = (idincidentehis) =>{
	//HISTORIAL
	
	if(nivel == 1 || nivel == 2 || nivel == 7){
	
		tablabitacora = $("#tablabitacora").DataTable({
			scrollCollapse: true,
			destroy: true,
			ordering: false,
			processing: true,
			autoWidth : false,
			"ajax"		: {
				"url"	: "controller/preventivosback.php?oper=historial&id="+idincidentehis,
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

$('a[href="#boxfun"]').click(function(){
	//tablafusionados.columns.adjust().draw();
});

const abrirFusionados = (idincidentefus) =>{
	//FUSIONADOS
	tablafusionados = $("#tablafusionados").DataTable({
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : false,
		"ajax"		: {
			"url"	: "controller/preventivosback.php?oper=fusionados&id="+idincidentefus,
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

$("#guardar-evidenciacom").on('click',function(){
	//var idincidentesevidencias  = $('#idincidentesevidencias').val();
	//var idcomentariosevidencias = $('#idcomentariosevidencias').val();
	/* $.ajax({
			type: 'post',
			url: 'controller/incidentesback.php',
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			data: { 
				'oper'	   : 'notificacionAdjunto',
				'incidente': idincidentesevidencias,
				//'imagen'   : nimagen,
				'idcoment' : idcomentariosevidencias		 
			},
			success: function (response) {
				$('#overlay').css('display','none');
				tablacomentario.ajax.reload(null, false);
				demo.showSwal('success-message','Archivo Adjunto','Notificación enviada');
			},
			error: function () {
				$('#overlay').css('display','none');
				demo.showSwal('error-message','ERROR',response);
			}
		}); */
		//notification("Archivos Adjuntos","¡Exito!",'Satisfactoriamente');

		//tablacomentario.ajax.reload(null, false);
		$('#modalEvidenciascom').modal('hide');
}); 
	
	
$('#modalEvidenciasCom').on('hidden.bs.modal', function(){
        tablacomentario.ajax.reload(null, false);
    });
	
var dirxdefecto = 'incidente';
$('#fevidenciascom').attr('src','filegator/preventivoscom.php#/?cd=%2F'+dirxdefecto);
   
//Adjuntos de comentarios
const adjuntosComentarios = (incidentecomentario) =>{
	var arr = incidentecomentario.split('-');
	var incidente = arr[0];
	var comentario = arr[1]; 
	var valid = true;
	if ( valid ) {
		$.ajax({
			type: 'post',
			url: 'controller/preventivosback.php',
			data: { 
				'oper': 		'adjuntosComentarios',
				'incidentecom': incidentecomentario
			},
			success: function (response) {
				$('#fevidenciascom').attr('src','filegator/preventivoscom.php#/?cd=incidentes/'+incidente+'/comentarios/'+comentario);									 
				$('#modalEvidenciasCom').modal('show');
				$('#modalEvidenciasCom .modal-lg').css('width','1000px');
				$('#idincidentesevidenciascom').val(incidente);
				$('#idcomentariosevidencias').val(comentario);
				$('.titulo-evidencia').html('Preventivo: '+incidente+' - Evidencia comentario');
				 
				/* var elfInstanceAdj = $('#elfinderCom').elfinder(optionsAdj).elfinder('instance');
				$.ajax({
					type: 'get',
					url: 'https://toolkit.maxialatam.com/soporte/elFinder/php/connector.incidentes.php?cmd=open&init=0&target=l1_Lw', //+response,
					success: function (resp) {
						console.log('reload Adj');
						elfInstanceAdj.exec('reload');
					},
					error: function () {
						console.log('error elfInstanceAdj');
					}
				}); */
				//$('.titulo-modal').text('Evidencias de comentarios');
				//$('#modalEvidenciasCom').modal('show');
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
const abrirGridAdjunto = (idincidente,id) =>{
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

//COMENTARIO AGREGAR
var form,
	comentario = $( "#comentario" ),
	allFields = $( [] ).add( comentario ),
	tips = $( ".validateTips" );

const agregarComentario = () =>{	
	var coment  = $('#comentario').val();		
	var visibilidad  = $('input[name=visibilidad]:checked').val();
	
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
	var incidenteselect = getQueryVariable('id');

	if (coment != '') {
		$.ajax({
			type: 'post',
			url: 'controller/preventivosback.php',
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
					} /* else if(response == 2){
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
						}).then(
							function() {
								//Envío de la encuesta
								$.ajax({
									type: 'post',
									dataType: "json",
									url: 'controller/incidentesback.php',
									data: { 
										'oper'		: 'enviarEncuesta',
										'id'		: incidenteselect 
									}, 
									beforeSend: function() {
										$('#overlay').css('display','block');
									},
									success: function (response) { 
										$('#overlay').css('display','none');
										notification("Encuesta enviada satisfactoriamente","¡Exito!",'success');
																		
									},
									error: function () {
										$('#overlay').css('display','none'); 
										notification("Ha ocurrido un error al enviar la encuesta, intente mas tarde","Error",'error');
									}
								});
							},
							function (dismiss) {
								//console.log("NO QUISO ENVIAR LA ENCUESTA");
							}
						);
					} */
				}else{
					$('#overlay').css('display','none');					 
					notification("Ha ocurrido un error al grabar el Comentario, intente mas tarde","Error",'error');
				}
			},
			error: function () {
				$('#overlay').css('display','none');
				notification("Ha ocurrido un error al grabar el Comentario, intente mas tarde","Error",'error');
			}
		});
	}
	return;
}

const limpiarComentario = () =>  ( $('#comentario').val(''))

/************************************************* COSTOS ***********************************************/

const vcosto = (descripcion,monto)=>{
	var respuesta = 1;
	if (descripcion == ""){ 
		notification('El campo Descripción es obligatorio','Advertencia!','warning');
		respuesta = 0;
	}else if(monto == "") {
		notification('El campo Monto es obligatorio','Advertencia!','warning');
		respuesta = 0;
	}
	return respuesta;
}

const limpiarCosto = ()=> {
	$('#desccosto').val('');
	$('#monto').val('');
}

const agregarCosto = ()=> {
	
	let descripcion = $("#desccosto").val();
	let monto 		= $("#monto").val();
	let montofinal	= monto.replace(/,/g, '');	
	let idincidente = getQueryVariable('id');
	
	var existe = existeFila(descripcion);
	
	if(existe == 1){
		if(vcosto(descripcion,monto) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/preventivosback.php',
				data: { 
					'oper'		  : 'agregarCosto',
					'id' 		  : idincidente,
					'descripcion' : descripcion,
					'monto'		  : montofinal
				},
				beforeSend: function() {
					$('#preloader').css('display','block');
				},
				success: function (response) {
					$('#preloader').css('display','none');
					if(response == 1){					
						limpiarCosto();					
						tablacostos.ajax.reload(null, false);  
						notification("Costo registrado satisfactoriamente","¡Exito!",'success');
					}else{
						$('#preloader').css('display','none');					 
						notification("Ha ocurrido un error al grabar el Comentario, intente mas tarde","Error",'error');
					}
				},
				error: function () {
					$('#preloader').css('display','none');
					notification("Ha ocurrido un error al grabar el Costo, intente mas tarde","Error",'error');
				}
			});
		}
	}else{
		notification('El registro ya existe',"Advertencia!","warning")
	}  
}

const eliminarcostos = (id)=>{
	var idincidente  = $("#incidente").val();											 
	var idcosto = id;
	swal({
		title: "Confirmar",
		text: "¿Esta seguro de eliminar el registro?",
		type: "warning",
		showCancelButton: true,
		cancelButtonColor: 'red',
		confirmButtonColor: '#09b354',
		confirmButtonText: 'Si',
		cancelButtonText: "No"
	}).then(
		function(isConfirm){
			if (isConfirm.dismiss!="cancel"){
				$.get( "controller/preventivosback.php?oper=eliminarcostos", 
				{ 
					onlydata	 : "true",
					idcosto		 : idcosto,
					idincidente  : idincidente
				}, function(result){
					if(result == 1){
						notification("Registro eliminado satisfactoriamente","¡Exito!",'success');
						tablacostos.ajax.reload(null, false);
					} else {
						notification("Ha ocurrido un error al eliminar el registro","Error",'error');
					}
				});
			}
		}, function (isRechazo){
			
		}
	);
}

var dirxdefecto = 'incidente'; 
$('#fevidenciascost').attr('src','filegator/preventivoscost.php#/?cd=%2F'+dirxdefecto);

//Adjuntos de costos
const adjuntoscostos =(incidentecosto)=>{
	let arr = incidentecosto.split('-');
	let incidente = arr[0];
	let costo = arr[1]; 
	let valid = true;
	if ( valid ) {
		$.ajax({
			type: 'post',
			url: 'controller/preventivosback.php',
			data: { 
				'oper'		    : 'adjuntoscostos',
				'incidentecosto': incidentecosto
			},
			success: function (response) {
				 $('#fevidenciascost').attr('src','filegator/preventivoscost.php#/?cd=incidentes/'+incidente+'/costos/'+costo);
				$('#modalEvidenciasCost').modal('show');
				$('#modalEvidenciasCost .modal-lg').css('width','1000px');
				$('#idincidentesevidenciascost').val(incidente);
				$('#idcomentariosevidencias').val(costo);
				$('.titulo-evidencia').html('Preventivo: '+incidente+' - Evidencia costo'); 
				$('#modalEvidenciasCost').css('z-index','1150'); 
			},
			error: function () {
				notification("Ha ocurrido un error, intente mas tarde","Error",'error'); 
			}
		});
	}
	return valid; 
}

$('#modalEvidenciasCost').on('hidden.bs.modal', function(){
    tablacostos.ajax.reload(null, false);
});

const existeFila=(label)=>{
	var lbls = []; 
	tablacostos.rows().data().each(function (value) {
		lbls.push(value.descripcion); 
	}); 
	var resultado = lbls.indexOf(label); 
	if(resultado == -1){
		//No existe el campo
		return 1;
	}else{
		//Sí existe el campo 
		return 0;
	}
}

$("#monto").on({
  "focus": function(event) {
	$(event.target).select();
  },
  "keyup": function(event) {
	$(event.target).val(function(index, value) {
	  return value.replace(/\D/g, "")
		.replace(/([0-9])([0-9]{2})$/, '$1.$2')
		.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
	});
  }
});
const guardarCorrectivoTemp = () =>{
	
	var dataserialize 	= $("#form_incidentes").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES 
		if( dataserialize[i].name == 'idempresas' 		|| dataserialize[i].name == 'iddepartamentos'||
			dataserialize[i].name == 'idclientes' 		|| dataserialize[i].name == 'idproyectos'  	||				
			dataserialize[i].name == 'prioridad' 		|| dataserialize[i].name == 'solicitante'  	|| 
			dataserialize[i].name == 'estado' 			|| dataserialize[i].name == 'asignadoa'  	|| 
			dataserialize[i].name == 'unidadejecutora'	|| dataserialize[i].name == 'area'			 ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	} 
	if($("#area").select2("val") != ''){
		data['area'] = $("#area").val().join();
	}else{
		data['area'] = '';
	}	
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/preventivosback.php',
		data: { 
			'oper'			: 'guardarCorrectivoTemp', 
			'data' 			: data,
			'idpreventivos' : idpreventivo
		}, 
		success: function (response) { 
			location.href="correctivo.php?id=0";
		},
		error: function () {
			console.log('NO registró correctivo temp');
		}
	});  
} 

$("select").select2({ language: "es" });


