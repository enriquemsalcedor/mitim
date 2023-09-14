$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
var botonfusionar = 0;
$(document).ready(function() {
	
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false, lang : 'es', cancelText: 'Cancelar' });
	$('#fecharesolucion').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true, clearButton: true, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar' });
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
		$('.tipo').html('Editar correctivo');
		if(vercom == 1)	$("a[href$='#comentarios']").click(); 
	}else{
		if(nivel == 4){
			$('.nonivelcliente').css('display','none');
		}
		$('.tipo').html('Nuevo correctivo');
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
var fusionado = '';
var idincidentefusion = '';
const formatNumber = (num)=>( num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,') )

if(idincidente != '' && idincidente != null && idincidente != false){
	$("#agregar_nuevo_cliente").css("display","none");	
	$(".div_cliente").css("width","100%");	
}else{
	$("#agregar_nuevo_cliente").css("display","block");	
	$(".div_cliente").css("width","85%");	
}
$("#listado").click(function(){
	location.href = "correctivos.php";
});

const resultadosEncuesta = ()=>{
	if(idincidente){
		$.get( "controller/incidentesback.php?oper=encuestasIncidente", { idincidente: idincidente }, function(result){ 		
			$("#resultadosEncuesta").html(result);
		});
	}
}
$('a[href="#boxenc"]').click(function(){
	resultadosEncuesta();
});
$('a[href="#comentarios"]').click(function(){
    var id = idincidente;
	$.ajax({
		type: 'post',
		url: 'controller/incidentesback.php',
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

function solicitantes(id){
    $.get("controller/combosback.php?oper=usuarios", {}, function(result)
    {
        $("#solicitante").empty();
        $("#solicitante").append(result);
        if (id != 0){
			$("#solicitante").val(id).trigger('change');
        }
    });
}
const cargarCombosEditar = ()=>{
	
	//NOTIFICAR A
	$.get( "controller/combosback.php?oper=usuariosGrupos", { onlydata:"true"}, function(result){ 
		$("#notificar").empty();
		$("#notificar").append(result);
	});
}

const cargarCombosEditarDepEd = ()=>{
	
	//EMPRESAS
	$.get( "controller/combosback.php?oper=empresas", function(result){ 
		$("#idempresas").empty();
		$("#idempresas").append(result);
	});
	
	//CLIENTES
	var idempresas = 1;
	$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresas }, function(result){ 
		$("#idclientes").empty();
		$("#idclientes").append(result);
		if(idincidente == '') optionDefault('idclientes');
	});
		 
	//CLIENTES / PROYECTOS - SITIOS
	$('#idclientes').on('select2:select',function(){
		var idempresas = $("#idempresas option:selected").val();
		var idclientes = $("#idclientes option:selected").val();
					
		//SITIOS
		/* $.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientes }, function(result){ 
			$("#unidadejecutora").empty();
			$("#unidadejecutora").append(result);
			if(idincidente == '') optionDefault('unidadejecutora'); 
		}); */
		 
	});	
	
	//PROYECTOS
	$.get( "controller/combosback.php?oper=proyectos", function(result){ 
		$("#idproyectos").empty();
		$("#idproyectos").append(result);
		if(idincidente == '') optionDefault('idproyectos');					
	});	
	
	//CATEGORÍAS
	$.get( "controller/combosback.php?oper=categorias", { tipo: 'Correctivo' }, function(result){ 
		$("#categoria").empty();
		$("#categoria").append(result);
		if(idincidente == '') optionDefault('categoria');				  
	});
	
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { tipo:"Correctivo" }, function(result){ 
		$("#estado").empty();
		$("#estado").append(result);
		
		//Seleccionar estado Nuevo por defecto
		if(idincidente == ''){
			$("#estado").val(3).trigger('change');
		} 
	});
	
	//PRIORIDADES
	$.get( "controller/combosback.php?oper=prioridades", function(result){ 
		$("#prioridad").empty();
		$("#prioridad").append(result);
	});	
	
	//DEPARTAMENTOS		 
	$.get( "controller/combosback.php?oper=departamentosgrupos", function(result){ 
		$("#iddepartamentos").empty();
		$("#iddepartamentos").append(result);
	});
	
	//AMBIENTES
	$.get( "controller/combosback.php?oper=sitiosclientes", function(result){ 
		$("#unidadejecutora").empty();
		$("#unidadejecutora").append(result);
		if(idincidente == '') optionDefault('unidadejecutora');
	});	
		
	//PROYECTOS / CATEGORIAS
	$('#idproyectos').on('select2:select',function(){
		var idproyectos = $("#idproyectos option:selected").val();
		var idempresas = $("#idempresas option:selected").val();
		var idclientes = $("#idclientes option:selected").val();
		
		//ETIQUETAS
		getEtiquetas(idclientes,idproyectos,''); 
	});
	//CATEGORIAS - SUBCATEGORIAS
	$('#categoria').on('select2:select',function(){
		//MÓDULOS CATEGORÍAS //
		
		let idproyectos = $("#idproyectos option:selected").val();
		let idcategoria = $("#categoria option:selected").val();
		
		//SUBCATEGORÍAS
		$.get( "controller/combosback.php?oper=subcategorias", { tipo: 'Correctivo', idcategoria: idcategoria }, function(result){ 
			$("#subcategoria").empty();
			$("#subcategoria").append(result);
			if(idincidente == '') optionDefault('subcategoria'); 
		});
		 
	});
	//SITIOS / SERIE
	$('#unidadejecutora').on('select2:select',function(){
		var idsitio = $("#unidadejecutora option:selected").val();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitio }, function(result){ 
			$("#serie, #marca, #modelo, #areaact").empty();
			$("#serie").append(result);
		});
		$.get( "controller/combosback.php?oper=areas", { idubicacion: idsitio }, function(result){ 
			$("#area").empty();
			$("#area").append(result);
			if(idincidente == '') optionDefault('area');			 
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
					$('#areact').val(item.areact);
				});
			}
		});
	}); 
	
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
				$.get( "controller/incidentesback.php?oper=eliminarcomentarios", 
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
	if(idincidente == 0){
		solicitantes(0);
		cargarCombosEditar();
		cargarCombosEditarDepEd();
		 
		$.ajax({
			type: 'get',
			dataType: 'json',
			url: 'controller/incidentesback.php',
			data: { 
				'oper'	: 'abrirCorrectivoTemp' 
			},
			beforeSend: function() {
			$('#preloader').css('display','block');
			},
			success: function (response) {
				 
				$.map(response, function (item) {
					$("#idpreventivos").val(item.idpreventivos); 	
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
								$.get( "controller/combosback.php?oper=categorias", { idproyectos: item.idproyectos, tipo: 'Correctivo' }, function(result){ 
									$("#categoria").empty();
									$("#categoria").append(result);
									$("#categoria").val(item.categoria).trigger("change");
								});
								//ESTADOS
								$.get( "controller/combosback.php?oper=estados", { idproyectos: item.idproyectos, tipo:"Correctivo" }, function(result){ 
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
					
					//SITIOS / SERIE
					$.when( $('#unidadejecutora').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
						//SERIE
						$.get( "controller/combosback.php?oper=serie", { idsitio: item.unidad/*, idarea: item.idsubambientes*/ }, function(result){ 
							$("#serie, #marca, #modelo").empty();
							$("#serie").append(result);
							$("#serie").val(item.serie).trigger("change");	 
							$('#marca').val(item.marca);
							$('#modelo').val(item.modelo);
							$('#areaact').val(item.areaact);
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
					
					//PRIORIDADES
					$.get( "controller/combosback.php?oper=prioridades", { idclientes: item.idclientes, idproyectos: item.idproyectos }, function(result){ 
						$("#prioridad").empty();
						$("#prioridad").append(result);
						$("#prioridad").val(item.prioridad).trigger("change");
					}); 
				}); 
				
			}
		});	
				
	} else {
		cargarCombosEditar();
		cargarCombosEditarDepEd(); 
			
		
		$('#tabcom').css('display','block');
		$('#tabest').css('display','block');
		$('#tabhis').css('display','block');
		$('#tabfus').css('display','block');
		$('#tabenc').css('display','block');
		$('#tabcost').css('display','block');
		$('#tabfact').css('display','block');
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
			$('#preloader').css('display','block');
			},
			success: function (response) {
				$('#preloader').css('display','none');
				resultadosEncuesta();
				$.map(response, function (item) {
					var mensajeinfo = "Correctivo: "+item.id;
					idincidentefusion = item.id;
					$('#incidente').val(item.id); 
					$('#titulo').val(item.titulo);
					quill_descripcion.root.innerHTML = item.descripcion;
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
								$.get( "controller/combosback.php?oper=categorias", { tipo: "Correctivo", idproyectos: item.idproyectos }, function(result){ 
									$("#categoria").empty();
									$("#categoria").append(result);
									$("#categoria").val(item.categoria).trigger("change");
								});
								//ESTADOS
								$.get( "controller/combosback.php?oper=estados", { idproyectos: item.idproyectos, tipo:"Correctivo" }, function(result){ 
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
						 
						$.get( "controller/combosback.php?oper=subcategorias", { idproyectos: item.idproyectos, idcategoria: item.categoria, tipo: 'correctivo' }, function(result){ 
							$("#subcategoria").empty();
							$("#subcategoria").append(result);
							$("#subcategoria").val(item.subcategoria).trigger("change");
						}); 
					}); 
					//SITIOS / SERIE
					$.when( $('#unidadejecutora').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
						//SERIE
						$.get( "controller/combosback.php?oper=serie", { idsitio: item.unidad, idarea: item.idsubambientes }, function(result){ 
							$("#serie, #marca, #modelo").empty();
							$("#serie").append(result);
							$("#serie").val(item.serie).trigger("change"); 
							$('#marca').val(item.marca);
							$('#modelo').val(item.modelo);
							$('#areaact').val(item.areaact); 
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
					//PRIORIDADES
					$.get( "controller/combosback.php?oper=prioridades", { idclientes: item.idclientes, idproyectos: item.idproyectos }, function(result){ 
						$("#prioridad").empty();
						$("#prioridad").append(result);
						$("#prioridad").val(item.prioridad).trigger("change"); 
					});
					//SOLICITANTES
					/* $.get( "controller/combosback.php?oper=usuarios", function(result){ 
						$("#solicitante").empty();
						$("#solicitante").append(result); 
						document.getElementById('solicitante').value=item.solicitante; 
					}); */
					solicitantes(item.solicitante);
					$('#departamento').val(item.departamento);
					$('#modalidad').val(item.modalidad);
					$('#fechacierre').val(item.fechacierre);
					$('#horacierre').val(item.horacierre);
					$('#fechadesdefueraservicio').val(item.fechadesdefueraservicio);
					$('#fechafinfueraservicio').val(item.fechafinfueraservicio);
					$('#diasfueraservicio').val(item.diasfueraservicio);
					if(item.fusionado !=' - '){
						mensajeinfo += " - Fusionado con: "+item.fusionado;
						fusionado = item.fusionado;
						
						botonfusionar = 1;
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
					$('#areaact').val(item.areaact);
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
					$('#idetiquetas').val(item.idetiquetas);
					getEtiquetas(item.idclientes,item.idproyectos,item.idetiquetas);
				}); 
			},
			complete: function(data,status){
				abrirComentarios(idincidente);
				abrirEstados(idincidente);
				abrirHistorial(idincidente);
				abrirFusionados(idincidente);
				abrirCostos(idincidente);
				abrirFacturacion(idincidente);
			}
		});
	}   
}else{ 
	solicitantes(0);
	cargarCombosEditar();
	cargarCombosEditarDepEd();
}

/***********************************INICIO DE CAMPOS QUILL*******************************/	
	var toolbarOptions = [
	['bold', 'italic', 'underline'/*, 'strike'*/],        	// toggled buttons
	//['blockquote', 'code-block'],
	//[{ 'header': 1 }, { 'header': 2 }],               // custom button values
	[/*{ 'list': 'ordered'},*/ { 'list': 'bullet' }],
	//[{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
//	[{ 'indent': '-1'}, { 'indent': '+1' }],          	// outdent/indent
	//[{ 'direction': 'rtl' }],                        	// text direction
	//[{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
	//[{ 'header': [1, 2, 3, 4, 5, 6, false] }],
//	[{ 'color': [] }, { 'background': [] }],          	// dropdown with defaults from theme
	//[{ 'font': [] }],
//	[{ 'align': [] }],	
//	['link'/*, 'image'*/],	
	['clean']                                         	// remove formatting button
];	


//OBSERVACIONES CALIDAD DE SERVICIOS
var quill_descripcion = new Quill('#descripcion', {
    theme: 'snow',
	modules: {
		toolbar: toolbarOptions
	}
});	


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

$('#tablafacturacion').on( 'draw.dt', function () {	
	// DAR FUNCIONALIDAD AL BOTON ELIMINAR COMENTARIOS
	$('.boton-eliminar-facturacion').each(function(){
		var id = $(this).attr("data-id"); 
		$(this).on( 'click', function() {
			eliminar_item_facturacion(id);
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

const guardar = ()=>{

    if ( idincidente) {
		if(idincidente == 0){
			guardarincidente();
		}else{
			editar();
		}
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
		if( dataserialize[i].name == 'categoria' 		|| dataserialize[i].name == 'subcategoria' 	|| 
			dataserialize[i].name == 'idempresas' 		|| dataserialize[i].name == 'iddepartamentos'||
			dataserialize[i].name == 'idclientes' 		|| dataserialize[i].name == 'idproyectos'  	||				
			dataserialize[i].name == 'prioridad' 		|| dataserialize[i].name == 'solicitante'  	|| 
			dataserialize[i].name == 'estado' 			|| dataserialize[i].name == 'asignadoa'  	|| 
			dataserialize[i].name == 'unidadejecutora'  || dataserialize[i].name == 'serie' || dataserialize[i].name == 'area'	){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	data['descripcion'] = quill_descripcion.root.innerHTML;
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
	if(data['descripcion'] == ''){
		$("#"+dataserialize['descripcion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['descripcion']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Descripción es obligatoria','warning');

	}else if(data['idclientes'] == '0' || data['idclientes'] == ''){
		$("#"+dataserialize['idclientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Cliente es obligatorio','warning');
	}/* else if(data['idproyectos'] == '0' || data['idproyectos'] == ''){
		$("#"+dataserialize['idproyectos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Proyecto es obligatorio','warning');
	} */else if(data['categoria'] == '0' || data['categoria'] == ''){
		$("#"+dataserialize['categoria']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['categoria']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Categoria es obligatoria','warning');
	}else if(((nivel != 4 && nivel != 7)) && (data['prioridad'] == '0' || data['prioridad'] == '' || data['prioridad'] == undefined)){
		$("#"+dataserialize['prioridad']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['prioridad']).css({'border':'1px solid red'});
		notification("Advertencia!",'La prioridad es obligatoria','warning');
	}else if(data['estado'] == '16' && (data['atencion'] == '0' || data['atencion'] == '' || data['atencion'] == undefined)){
		$("#"+dataserialize['atencion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['atencion']).css({'border':'1px solid red'});
		notification("Advertencia!",'El campo Atención es obligatorio','warning');
	}
	else if(data['estado'] == '' || data['estado'] == null  || data['estado'] == undefined || data['estado'] == '0' ){
		notification("Advertencia!",'El estado es obligatorio','warning');
	}else if(data['estado'] == '16' && data['fecharesolucion'] == ''){
		$("#"+dataserialize['fecharesolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucion']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Fecha y Hora de Resolución es obligatoria','warning');
	}else if(data['estado'] == '16' && data['resolucion'] == ''){
		$("#"+dataserialize['resolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['resolucion']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Resolución es obligatoria','warning');
	}else{	
		//Verifico si la categoría tiene Subcategorías
		$.get( "controller/incidentesback.php?oper=existeSubcategoria", { idproyectos: $('#idproyectos').val(), idcategorias: $('#categoria').val() }, function(result){ 
			if((result == 1 && data['subcategoria'] == '') || (result == 1 && data['subcategoria'] == '0')){ 
				notification("Advertencia!",'La Subcategoría es obligatoria','warning');
			}else{  
				$.get( "controller/incidentesback.php?oper=validarComentarios", { id: id, idestadosnew : data['estado'], asignadoa: data['asignadoa'] }, function(result){  
					if(result == 1){
						notification("Advertencia!",'Debe agregar un comentario antes de asignar el correctivo','warning');
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
								$('#preloader').css('display','block');
								$(".modal-container").addClass('swal2-in');
							},
							success: function (response) {
								$('#preloader').css('display','none');
								
								notification("Correctivo actualizado satisfactoriamente","¡Exito!",'success');
								location.href = "correctivos.php";
							},
							error: function () {
								$('#preloader').css('display','none');
								notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
							}
						}); 
					}
				}); 
			}
		});
			
		$(".modal-container").removeClass('swal2-in');
	}
}

$("#revertir-fusion-incidente").on('click',function(){ 
	revertirfusion();
});

$(".correctivo").on('click',function(){ 
	if(botonfusionar == 1){
	    $('#revertir-fusion-incidente').show();
	}
});

$(".comentarios, .estados, .historial, .fusionado, .encuesta").on('click',function(){ 
	$('#revertir-fusion-incidente').hide();
});


const revertirfusion = ()=>{
	var id 			= getQueryVariable('id');;
	var incidente	= idincidentefusion;
	var idfusionado 	= fusionado;
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/incidentesback.php',
		data: { 
			'oper'	: 'revertirfusion',
			'id'	: id,
			'incidente'	: incidente,
			'fusionado'	: idfusionado
		},
		beforeSend: function() {
			$('#preloader').css('display','block');
		},
		success: function (response) {
			$('#preloader').css('display','none');
			notification("Fusión Revertida Exitosamente","¡Exito!",'success');
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			location.href = "correctivos.php";		
		},
		error: function () {
			$('#preloader').css('display','none');
			notification("Ha ocurrido un error al Revertida la Fusión, intente mas tarde","Error",'error');
		}
	});
}


const guardarincidente = ()=>{
	var id 				= idincidente;
	var dataserialize 	= $("#form_incidentes").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'categoria' 		|| dataserialize[i].name == 'subcategoria' 	|| 
			dataserialize[i].name == 'idempresas' 		|| dataserialize[i].name == 'iddepartamentos'||
			dataserialize[i].name == 'idclientes' 		|| dataserialize[i].name == 'idproyectos'  	||				
			dataserialize[i].name == 'prioridad' 		|| dataserialize[i].name == 'solicitante'  	|| 
			dataserialize[i].name == 'estado' 			|| dataserialize[i].name == 'asignadoa'  	|| 
			dataserialize[i].name == 'unidadejecutora'  || dataserialize[i].name == 'area'	){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	data['descripcion'] = quill_descripcion.root.innerHTML;
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
	if(data['descripcion'] == ''){
		$("#"+dataserialize['descripcion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['descripcion']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Descripción es obligatoria','warning');
	} else if(data['idclientes'] == '0' || data['idclientes'] == ''){
		$("#"+dataserialize['idclientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Cliente es obligatorio','warning');
	}/* else if((nivel != 4) && (data['idproyectos'] == '0' || data['idproyectos'] == '')){
		$("#"+dataserialize['idproyectos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos']).css({'border':'1px solid red'});
		notification("Advertencia!",'El Proyecto es obligatorio','warning');
	} */else if((nivel != 4) && (data['categoria'] == '0' || data['categoria'] == '')){
		$("#"+dataserialize['categoria']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['categoria']).css({'border':'1px solid red'});
		notification("Advertencia!",'La Categoria es obligatoria','warning');
	}else if(((nivel != 4 && nivel != 7)) && (data['prioridad'] == '0' || data['prioridad'] == '' || data['prioridad'] == undefined)){
		$("#"+dataserialize['prioridad']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['prioridad']).css({'border':'1px solid red'});
		notification("Advertencia!",'La prioridad es obligatoria','warning');
	}else if((nivel != 4) && (data['estado'] == '' || data['estado'] == null  || data['estado'] == undefined || data['estado'] == '0' )){
		notification("Advertencia!",'El estado es obligatorio','warning');
	}else{	
		//Verifico si la categoría tiene Subcategorías
		$.get( "controller/incidentesback.php?oper=existeSubcategoria", { idproyectos: $('#idproyectos').val(), idcategorias: $('#categoria').val() }, function(result){  
			if((result == 1 && data['subcategoria'] == '') || (result == 1 && data['subcategoria'] == '0')){ 
				notification("Advertencia!",'La Subcategoria es obligatoria','warning');
			}else{  
				$.get( "controller/incidentesback.php?oper=validarComentarios", { id: id, idestadosnew : data['estado'], asignadoa: data['asignadoa'] }, function(result){  
					if(result == 1){
						notification("Advertencia!",'Debe agregar un comentario antes de asignar el correctivo','warning');
					}else{
						$.ajax({
							type: 'post',
							dataType: "json",
							url: 'controller/incidentesback.php',
							data: { 
								'oper'	: 'guardarIncidente',
								'id'	: id,
								'data' 	: data
							},
							beforeSend: function() {
								$('#preloader').css('display','block');
								$(".modal-container").addClass('swal2-in');
							},
							success: function (response) {
								$('#preloader').css('display','none');
								// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
								notification("Correctivo creado satisfactoriamente","¡Exito!",'success');
								location.href = "correctivos.php";
							},
							error: function () {
								$('#preloader').css('display','none');
								notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
							}
						}); 
					}
				}); 
			}
		});
			
		$(".modal-container").removeClass('swal2-in');
	}
}

$('#tablacomentario').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})

const abrirComentarios = (id_incidente)=>{
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
			"url"	: "controller/incidentesback.php?oper=comentarios&id="+id_incidente,
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

const abrirCostos = (id_incidente)=>{
	  
	tablacostos = $("#tablacostos").DataTable({ 
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: false,
		autoWidth : false,
		"ajax"		: {
			"url"	: "controller/incidentesback.php?oper=costos&id="+id_incidente,
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
			}, 
			{
				visible		: cvisible,
				targets		: [4]
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

const abrirFacturacion = (id_incidente)=>{
	  
	tablafacturacion = $("#tablafacturacion").DataTable({ 
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: false,
		autoWidth : false,
		"ajax"		: {
			"url"	: "controller/incidentesback.php?oper=facturacion&id="+id_incidente,
		},
		"columns"	: [
			{ 	"data": "id" },			//0
			{ 	"data": "acciones" },	//1
			{ 	"data": "descripcion" },//2
			{ 	"data": "monto" },		//3
			{ 	"data": "usuario" },	//4
			{ 	"data": "fecha" }		//5 
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
		processing: false,
		autoWidth : false,
		"ajax"		: {
			"url"	: "controller/incidentesback.php?oper=estadosbit&id="+idincidenteest,
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
	
	if(nivel == 1 || nivel == 2 || nivel == 5 || nivel == 7){
		
		tablabitacora = $("#tablabitacora").DataTable({
			scrollCollapse: true,
			destroy: true,
			ordering: false,
			processing: false,
			autoWidth : false,
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

$('a[href="#boxfun"]').click(function(){
	//tablafusionados.columns.adjust().draw();
});
const abrirFusionados = (idincidentefus)=>{
	//FUSIONADOS
	tablafusionados = $("#tablafusionados").DataTable({
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: false,
		autoWidth : false,
		"ajax"		: {
			"url"	: "controller/incidentesback.php?oper=fusionados&id="+idincidentefus,
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
	var idincidentesevidencias  = $('#idincidentesevidenciascom').val();
	var idcomentariosevidencias = $('#idcomentariosevidencias').val();
	$.ajax({
			type: 'post',
			url: 'controller/incidentesback.php',
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
	
var dirxdefecto = 'incidente'; 
$('#fevidenciascom').attr('src','filegator/incidentescom.php#/?cd=%2F'+dirxdefecto);
	
//Adjuntos de comentarios
const adjuntosComentarios =(incidentecomentario)=>{
	let arr = incidentecomentario.split('-');
	let incidente = arr[0];
	let comentario = arr[1]; 
	let valid = true;
	if ( valid ) {
		$.ajax({
			type: 'post',
			url: 'controller/incidentesback.php',
			data: { 
				'oper': 		'adjuntosComentarios',
				'incidentecom': incidentecomentario
			},
			success: function (response) {
				$('#fevidenciascom').attr('src','filegator/incidentescom.php#/?cd=incidentes/'+incidente+'/comentarios/'+comentario);									 
				$('#modalEvidenciasCom').modal('show');
				$('#modalEvidenciasCom .modal-lg').css('width','1000px');
				$('#idincidentesevidenciascom').val(incidente);
				$('#idcomentariosevidencias').val(comentario);
				$('.titulo-evidencia').html('Correctivo: '+incidente+' - Evidencia comentario'); 
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
			url: 'controller/incidentesback.php',
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
									url: 'controller/incidentesback.php',
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
					notification("Ha ocurrido un error al grabar el Comentario","Error",'error');
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
				url: 'controller/incidentesback.php',
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
				$.get( "controller/incidentesback.php?oper=eliminarcostos", 
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

const vfacturacion = (descripcion,monto)=>{
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
const limpiarFacturacion = ()=> {
	$('#descripcion_facturacion').val('');
	$('#monto_facturacion').val('');
}

const agregar_item_facturacion = ()=> {
	
	let descripcion = $("#descripcion_facturacion").val();
	let monto 		= $("#monto_facturacion").val();
	let montofinal	= monto.replace(/,/g, '');	
	let idincidente = getQueryVariable('id');
	
	var existe = existeFila(descripcion);
	
	if(existe == 1){
		if(vfacturacion(descripcion,monto) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/incidentesback.php',
				data: { 
					'oper'		  : 'agregar_item_facturacion',
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
						limpiarFacturacion();					
						tablafacturacion.ajax.reload(null, false);  
						notification("Registro creado satisfactoriamente","¡Exito!",'success');
					}else{
						$('#preloader').css('display','none');					 
						notification("Ha ocurrido un error al grabar el registro, intente mas tarde","Error",'error');
					}
				},
				error: function () {
					$('#preloader').css('display','none');
					notification("Ha ocurrido un error al grabar el registro, intente mas tarde","Error",'error');
				}
			});
		}
	}else{
		notification('El registro ya existe',"Advertencia!","warning")
	}  
}

const eliminar_item_facturacion = (id_item)=>{
	var idincidente  = $("#incidente").val();											 
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
				$.get( "controller/incidentesback.php?oper=eliminar_item_facturacion", 
				{ 
					onlydata	 : "true",
					id_item		 : id_item,
					idincidente  : idincidente
				}, function(result){
					if(result == 1){
						notification("Registro eliminado satisfactoriamente","¡Exito!",'success');
						tablafacturacion.ajax.reload(null, false);
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
$('#fevidenciascost').attr('src','filegator/incidentescost.php#/?cd=%2F'+dirxdefecto);

//Adjuntos de costos
const adjuntoscostos =(incidentecosto)=>{
	let arr = incidentecosto.split('-');
	let incidente = arr[0];
	let costo = arr[1]; 
	let valid = true;
	if ( valid ) {
		$.ajax({
			type: 'post',
			url: 'controller/incidentesback.php',
			data: { 
				'oper'		    : 'adjuntoscostos',
				'incidentecosto': incidentecosto
			},
			success: function (response) {
				 $('#fevidenciascost').attr('src','filegator/incidentescost.php#/?cd=incidentes/'+incidente+'/costos/'+costo);
				$('#modalEvidenciasCost').modal('show');
				$('#modalEvidenciasCost .modal-lg').css('width','1000px');
				$('#idincidentesevidenciascost').val(incidente);
				$('#idcomentariosevidencias').val(costo);
				$('.titulo-evidencia').html('Correctivo: '+incidente+' - Evidencia costo'); 
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

//Obtener lista de tabla de etiquetas
const getEtiquetas = (idclientes,idproyectos,etiqueta_c) =>{
		 
	$.ajax({ 
		url: 'controller/combosback.php',
		dataType: "json",
		data: { 
			'oper' 		 : 'proyectosetiquetas', 
			'idclientes' : idclientes,
			'idproyectos': idproyectos
		}, 
		success: function (response) {  
			
			let etiquetas = ``;
			let arretiquetas = response.map((a, indexc) => {
				let color = a.color; 
				let nombre = a.nombre;
				let idetiquetas = a.idetiquetas;
				let icon_check = '';
				let color_fin = '#A3A5B4';

				if(idetiquetas == etiqueta_c && etiqueta_c != ''){
					icon_check = 'fa fa-check';
					color_fin = color;
				}  
			
			etiquetas += `<a class="badge badge-md ml-2 mr-2" data-color="${a.color}" data-id="${idetiquetas}" style="color: #FFFFFF; cursor: pointer; background-color:${color_fin}">${nombre} <i class="badge-icon active ${icon_check}" aria-hidden="true"></i></a>`;
			});  
			etiquetas == '' ? etiquetas = 'No hay etiquetas para mostrar' : etiquetas = etiquetas;
			$('.etiquetas-lista').html(etiquetas); 
		}
		
	 });
}  

//Seleccionar etiquetas de la lista
$(".etiquetas-lista").delegate(".badge", "click", function(){ 
	let tienecheck = $(this).find("i").hasClass('fa fa-check');
	$(".etiquetas-lista .badge-icon").removeClass('fa fa-check');
	$(".badge").css('background-color','#A3A5B4');		
	$('#idetiquetas').val(''); 
	if(tienecheck == true){
		$(this).find("i").removeClass('fa fa-check');
		$(".badge").css('background-color','#A3A5B4');
	}else{ 
		let idetiquetas__check = $(this).attr('data-id');
		let idetiquetas__color = $(this).attr('data-color');
		$('#idetiquetas').val(idetiquetas__check);
		$(this).find("i").addClass('fa fa-check');
		$(this).css('background-color',idetiquetas__color);
	} 
});  

//Creación rápida de cliente
 $('#agregar_nuevo_cliente').on('click', function() {
		$('#modal_cliente_creacionrapida').modal('show');
});

$("select").select2({ language: "es" });