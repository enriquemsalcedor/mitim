$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$("select").select2({ language: "es" });
var idincidente = getQueryVariable('id');
$("#listado").click(function(){
	location.href = "postventas.php";
});

// CARGAR COMBOS FORMULARIO	
function cargarCombos() {
	//ESTADOS MANTENIMIENTO
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"Postventa" }, function(result){ 
		$("#idestadosmantenimiento").empty();
		$("#idestadosmantenimiento").append(result); 
	});	
	//PRIORIDAD
	/*$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#idprioridades").empty();
		$("#idprioridades").append(result); 
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
		var ininot = $('#notificar').find('option:first').val();
		if(ininot == ''){
			$('#notificar').find('option:first').remove().trigger('change');
		}
	});
}

//function cargarCombosDep() {
	//EMPRESAS
	/* $.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresas").empty();
		$("#idempresas").append(result);
		$("#idempresas").select2({placeholder: ""});
	}); */
	//EMPRESAS
	/* $("#idempresas").change(function(e,data){
		var idempresas = $("#idempresas option:selected").val();
		$("#idclientes, #iddepartamentos, #idproyectos, #idcategorias, #idsubcategorias").empty();
		//CLIENTES
		$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
			$("#idclientes").empty();
			$("#idclientes").append(result);
			$("#idclientes").select2({placeholder: ""});
		});
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgrupos&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
			$("#iddepartamentos").empty();
			$("#iddepartamentos").append(result);
			$("#iddepartamentos").select2({placeholder: ""});
		});
	}); */
	let idempresas = 1;
	//Clientes
	$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
		$("#idclientes").empty();
		$("#idclientes").append(result); 
		optionDefault('idclientes');					  
	});
	//Departamentos
	$.get( "controller/combosback.php?oper=departamentosgrupos&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
		$("#iddepartamentos").empty();
		$("#iddepartamentos").append(result); 
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
		//AMBIENTES
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idambientes").empty();
			$("#idambientes").append(result); 
			optionDefault('idambientes');					
		});
		//ESTADOS
		/* $.get( "controller/combosback.php?oper=estados", { idempresas: idempresas, idclientes: idclientes, tipo:"postventa" }, function(result){ 
			$("#idestados").empty();
			$("#idestados").append(result); 
		}); */ 
	});
	//DEPARTAMENTOS		
	$("#iddepartamentos").change(function(e,data){
		var iddepartamentos = $("#iddepartamentos option:selected").val();
		//ASIGNADO A
		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: iddepartamentos }, function(result){ 
			$("#asignadoa").empty();
			$("#asignadoa").append(result); 
		});
	});
	//CATEGORIAS
	$("#idcategorias").select2({placeholder: ""});
	$("#idproyectos").on('select2:select',function(){
		var idempresas = $("#idempresas option:selected").val();
		var idclientes = $("#idclientes option:selected").val();
		var idproyectos = $("#idproyectos option:selected").val();
		if(idproyectos != '' && idproyectos != undefined ){
			$.get( "controller/combosback.php?oper=categorias", { idproyectos: idproyectos, tipo: "Postventa" }, function(result){ 
				$("#idcategorias").empty();
				$("#idcategorias").append(result);
				optionDefault('idcategorias');
			});
		}
		//AMBIENTES
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes+"&idproyectos="+idproyectos, { onlydata:"true" }, function(result){ 
			$("#idambientes").empty();
			$("#idambientes").append(result); 
			optionDefault('idambientes'); 
		});
		//ESTADOS
		$.get( "controller/combosback.php?oper=estados", { idempresas: idempresas, idclientes: idclientes, idproyectos: idproyectos, tipo:"Postventa" }, function(result){ 
			$("#idestados").empty();
			$("#idestados").append(result); 
		});
		
		$.get( "controller/combosback.php?oper=prioridades", { idclientes: idclientes, idproyectos: idproyectos }, function(result){ 
    		$("#idprioridades").empty();
    		$("#idprioridades").append(result); 
    	});
	});
	//SUBCATEGORIAS
	$("#idcategorias").on('select2:select',function(){
		//MÓDULOS CATEGORÍAS//							
		
		let idproyectos = $("#idproyectos option:selected").val();
		let idcategoria = $("#idcategorias option:selected").val();
		$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+idcategoria, { tipo: 'Postventa', idproyectos: idproyectos }, function(result){ 
			$("#idsubcategorias").empty();
			$("#idsubcategorias").append(result); 
			optionDefault('idsubcategorias'); 
		}); 
	});
//} 

$(document).ready(function() {
	cargarCombos();
	//cargarCombosDep();
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false, clearButton: true, clearText: 'Limpiar', lang : 'es' });
	$('#fecharesolucion, #fechareal').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true, clearButton: true, clearText: 'Limpiar', lang : 'es' });
	$('#fechacreacion').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, clearButton: true, clearText: 'Limpiar', lang : 'es'  });
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

function guardar() {
	
    if ( idincidente) {
        editarIncidente();
    }else{
        guardarIncidente();
    }
}

function guardarIncidente() {
	var id 				= $('#incidente').val();
	var dataserialize 	= $("#form_postventa").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'idcategoriasf' || dataserialize[i].name == 'idsubcategoriasf' || 
			dataserialize[i].name == 'idempresasf' || dataserialize[i].name == 'idclientesf'  ||
			dataserialize[i].name == 'idproyectosf' || dataserialize[i].name == 'iddepartamentosf'  ||			
			dataserialize[i].name == 'idprioridadesf' || dataserialize[i].name == 'solicitantef'  || 
			dataserialize[i].name == 'idestadosf' || dataserialize[i].name == 'asignadoaf'  || 
			dataserialize[i].name == 'idambientesf'	){
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
	if(data['titulo'] == ''){
		$("#"+dataserialize['titulo']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['titulo']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Título",'warning'); 
	}else if(data['descripcion'] == ''){
		$("#"+dataserialize['descripcion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['descripcion']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Descripción",'warning');
	}
	/*
	else if(data['idempresas'] == '0'){
		$("#"+dataserialize['idempresas']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idempresas']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR','Debe llenar el campo Empresa');
	}
	else if(data['iddepartamentos'] == '0'){
		$("#"+dataserialize['iddepartamentos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['iddepartamentos']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR','Debe llenar el campo Departamento');
	}*/
	else if(data['idclientes'] == '0' || data['idclientes'] == ''){
		$("#"+dataserialize['idclientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Cliente",'warning');
	}else if(data['idproyectos'] == '0' || data['idproyectos'] == ''){
		$("#"+dataserialize['idproyectos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Proyecto",'warning');
	}else if(data['idcategorias'] == '0' || data['idcategorias'] == ''){
		$("#"+dataserialize['idcategorias']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idcategorias']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Categoría",'warning');
	}else if(data['idestados'] == '16' && data['fecharesolucion'] == ''){
		$("#"+dataserialize['fecharesolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucion']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo de Fecha y Hora de Resolución",'warning');
	}else if(data['idestados'] == '16' && data['resolucion'] == ''){
		$("#"+dataserialize['resolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['resolucion']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo de Resolución",'warning');
	}else{			
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/postventasback.php',
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
				if(response == 1){  
					notification('','Buen trabajo!','success');
					vaciar();  
					swal({		
							title: 'Visita almacenada satisfactoriamente',	
							text: "¿Desea registrar otra visita?",
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
							location.href = "postventas.php";
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

function editarIncidente() {
	var id 				= $('#incidente').val();
	var dataserialize 	= $("#form_postventa").serializeArray();
	var data 			= {};
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'idcategorias' || dataserialize[i].name == 'idsubcategorias' || 
			dataserialize[i].name == 'idempresas' || dataserialize[i].name == 'idclientes'  ||
			dataserialize[i].name == 'idproyectos' || dataserialize[i].name == 'iddepartamentos'  ||			
			dataserialize[i].name == 'idprioridades' || dataserialize[i].name == 'solicitante'  || 
			dataserialize[i].name == 'idestados' || dataserialize[i].name == 'asignadoa'  || 
			dataserialize[i].name == 'idambientes'	){
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
	if(data['titulo'] == ''){
		$("#"+dataserialize['titulo']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['titulo']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Título",'warning'); 
	}/*
	else if(data['idempresas'] == '0'){
		$("#"+dataserialize['idempresas']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idempresas']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR','Debe llenar el campo Empresa');
	}
	else if(data['iddepartamentos'] == '0'){
		$("#"+dataserialize['iddepartamentos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['iddepartamentos']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR','Debe llenar el campo Departamento');
	}*/
	else if(data['idclientes'] == '0' || data['idclientes'] == ''){
		$("#"+dataserialize['idclientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idclientes']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Cliente",'warning'); 
	}else if(data['idproyectos'] == '0' || data['idproyectos'] == ''){
		$("#"+dataserialize['idproyectos']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idproyectos']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Proyecto",'warning'); 
	}else if(data['idcategorias'] == '0' || data['categoria'] == ''){
		$("#"+dataserialize['idcategorias']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idcategorias']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo Categoría",'warning'); 
	}/*
	else if(data['idambientes'] == '0' && data['idambientes'] == ''){
		$("#"+dataserialize['idambientes']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['idambientes']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR','Debe llenar el campo Unidad Ejecutora');
	}else if(data['serie'] == ''){
		$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['serie']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR','Debe llenar el campo Serie');
	}
	else if( (data['categoria'] == '10' || data['categoria'] == '11') && data['serie'] == ''){
		$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['serie']).css({'border':'1px solid red'});
		demo.showSwal('error-message','ERROR','Debe llenar el campo Serie');
	}*/
	else if(data['idestados'] == '16' && data['fecharesolucion'] == ''){
		$("#"+dataserialize['fecharesolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['fecharesolucion']).css({'border':'1px solid red'}); 
		notification("Advertencia!","Debe llenar el campo de Fecha y Hora de Resolución",'warning'); 
	}else if(data['idestados'] == '16' && data['resolucion'] == ''){
		$("#"+dataserialize['resolucion']).addClass('form-valide-error-bottom');
		$("#"+dataserialize['resolucion']).css({'border':'1px solid red'});
		notification("Advertencia!","Debe llenar el campo de Resolución",'warning'); 
	}else{			
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/postventasback.php',
			data: { 
				'oper'	: 'actualizarIncidente',
				'id'	: id,
				'data' 	: data
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
				$(".modal-container").addClass('swal2-in');
			},
			success: function (response) { 
				if(response == 1){
					notification('Buen trabajo!','Visita actualizada satisfactoriamente','success'); 
					//location.href = "postventas.php";
				}else{
					notification("Error","Error al actualizar la visita",'error'); 
				} 
			},
			error: function () {
				$('#overlay').css('display','none');				 
				notification("Error","Error al actualizar la visita",'error'); 
			}
		});		 
	}
}

if (idincidente != '') {
    $('.tipo').html('Editar postventa');
	$('.eseditar').css('display','block');
	$('.navpost').css('display','block');
	$('.navcom').css('display','block');
	$('.navhist').css('display','block');
	 
	$('#fusion, #btnrevertirfusion').hide();
	//$("#form_incidentes")[0].reset();
	incidenteselect = idincidente;
	//$("#idambientes, #solicitante, #asignadoa, #notificar").select2("val", "");
	//$("#idcategorias, #idsubcategorias, #idambientes, #idestados").select2("val", "");
		
	$.ajax({
		type: 'post',
		url: 'controller/postventasback.php',
		data: { 
			'oper'		    : 'comentariosleidos', 
			'idincidente'   : idincidente,
		},
		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		success: function (response) {
			$('#overlay').css('display','none');
			$(".boton-coment-"+idincidente+"").removeClass("green");
			$(".boton-coment-"+idincidente+"").addClass("blue");
		},
		error: function () {
			$('#overlay').css('display','none');
		}
	});
	$('#incidente').val(idincidente);
	$('.content-incidente, .content-fechacierre').show();
	
	$.ajax({
		type: 'get',
		dataType: 'json',
		url: 'controller/postventasback.php',
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
				var mensajeinfo = "Visita: "+item.id;
				$("#nombreregistro").html(mensajeinfo);
				$('#divnombreregistro').show();
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
						document.ready = document.getElementById("idclientes").value = item.idclientes;
					});
					//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: item.idempresas }, function(result){ 
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
						//$("#idproyectos").val(item.idproyectos).trigger("change");
						document.ready = document.getElementById("idproyectos").value = item.idproyectos;
					});				
					//AMBIENTES
					$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: item.idclientes }, function(result){ 
						$("#idambientes").empty();
						$("#idambientes").append(result); 
						document.ready = document.getElementById("idambientes").value = item.idambientes;
					});
				});
				//PROYECTOS / CATEGORIAS
				$.when( $('#idproyectos').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=categorias", { tipo: "Postventa", idproyectos: item.idproyectos }, function(result){ 
						$("#idcategorias").empty();
						$("#idcategorias").append(result); 
						document.ready = document.getElementById("idcategorias").value = item.idcategorias;
					});
					//ESTADOS
					$.get( "controller/combosback.php?oper=estados", { idempresas: item.idempresas, idclientes: item.idclientes, idproyectos: item.idproyectos, tipo:"Postventa" }, function(result){ 
						$("#idestados").empty();
						$("#idestados").append(result); 
						document.ready = document.getElementById("idestados").value = item.idestados;
					});
				});
				//CATEGORIAS - SUBCATEGORIAS
				$.when( $('#idcategorias').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					
					//MÓDULOS CATEGORÍAS//
					
					
					$.get( "controller/combosback.php?oper=subcategorias", { tipo: 'Postventa', idproyectos: item.idproyectos, idcategoria: item.idcategorias }, function(result){ 
						$("#idsubcategorias").empty();
						$("#idsubcategorias").append(result);
						$("#idsubcategorias").val(item.idsubcategorias).trigger("change");
					});
					
					
					//MÓDULOS CATEGORÍAS//
					
					/* $.get( "controller/combosback.php?oper=subcategorias", { idcategoria: item.idcategorias }, function(result){ 
						$("#idsubcategorias").empty();
						$("#idsubcategorias").append(result); 
						document.ready = document.getElementById("idsubcategorias").value = item.idsubcategorias;
					}); */
				});
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentos').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					var iddepartamentos = $("#iddepartamentos option:selected").val();
					$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: item.iddepartamentos }, function(result){ 
						$("#asignadoa").empty();
						$("#asignadoa").append(result); 
						document.ready = document.getElementById("asignadoa").value = item.asignadoa;
					});
				});
				
				//Notificar
				if(item.notificar!=''){
					var elem = [];
					$.each($.parseJSON(item.notificar), function(i, item) {
						if(item != ''){
							elem.push(item);
						}
					});
					//NOTIFICAR A
					$.get( "controller/combosback.php?oper=usuariosGrupos", { onlydata:"true"}, function(result){ 
						$("#notificar").empty();
						$("#notificar").append(result); 
						$("#notificar").val(elem).trigger("change");
						/* var ininot = $('#notificar').find('option:first').val();
						if(ininot == ''){
							$('#notificar').find('option:first').remove().trigger('change');
						} */
					});
					//$("#notificar").val(elem).trigger("change");
				}else{
					$("#notificar").val(null).trigger("change"); 
				}  
				
				//Prioridad
				$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
					$("#idprioridades").empty();
					$("#idprioridades").append(result); 
					document.ready = document.getElementById("idprioridades").value = item.idprioridades;
				});
				
				//Solicitante
				$.get( "controller/combosback.php?oper=usuarios", function(result){ 
					$("#solicitante").empty();
					$("#solicitante").append(result); 
					document.ready = document.getElementById("solicitante").value = item.solicitante;
				});
				$('#fechacierre').val(item.fechacierre);
				$('#horacierre').val(item.horacierre);						
				$('#fusionado').val(item.fusionado);
				if(item.fusionado !=' - '){
					$('#fusion, #btnrevertirfusion').show();
				}else{
					$('#fusion, #btnrevertirfusion').hide();
				}
				$('#resolucion').val(item.resolucion);					
				$('#reporteservicio').val(item.reporteservicio);
				$('#creadopor').val(item.creadopor);
				$('#resueltopor').val(item.resueltopor);
				$('#fechacreacion').val(item.fechacreacion);
				$('#horacreacion').val(item.horacreacion);
				/*
				if(nivel > 2){
					$("#fechacreacion").prop('disabled', true);
					$("#horacreacion").prop('disabled', true);
				} else {
					$("#fechacreacion").prop('disabled', false);
					$("#horacreacion").prop('disabled', false);
				}
				*/
				$('#fecharesolucion').val(item.fecharesolucion);
				$('#fechacierre').val(item.fechacierre);
				$('#horastrabajadas').val(item.horastrabajadas);
				$('#fechareal').val(item.fechareal);
			});
		},
		complete: function(data,status){
			abrirComentarios(idincidente); 
			abrirHistorial(idincidente); 			
		}
	}); 
}else{
    $('.tipo').html('Nuevo postventa');
}

function limpiarComentario(){
	$('#comentario').val('');
}

$('#tablacomentario').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})
	
function abrirComentarios(idincidentecom){
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
			"url"	: "controller/postventasback.php?oper=comentarios&id="+incidenteselect,
		},
		"columns"	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "comentario" },
			{ 	"data": "nombre" },
			{ 	"data": "visibilidad" },
			{ 	"data": "fecha" },
			{ 	"data": "adjuntos" },
			{ 	"data": "estado" },
			{ 	"data": "resolucion" }
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
}

function abrirHistorial(idincidentehis){ 
	//HISTORIAL
	if(nivel == 1 || nivel == 2){
		tablabitacora = $("#tablabitacora").DataTable({
			scrollCollapse: true,
			destroy: true,
			ordering: false,
			processing: true,
			autoWidth : false,
			"ajax"		: {
				"url"	: "controller/postventasback.php?oper=historial&id="+incidenteselect,
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
}

function eliminarcomentario(id){
	var idcomentario = id;
	swal({
		title: "Confirmar",
		text: "¿Esta seguro de eliminar el Compromiso?",
		type: "warning",
		showCancelButton: true,
		cancelButtonColor: 'red',
		confirmButtonColor: '#09b354',
		confirmButtonText: 'Si',
		cancelButtonText: "No"
	}).then(
		function(isConfirm){
			if (isConfirm.value === true) {
				$.get( "controller/postventasback.php?oper=eliminarcomentarios", 
				{ 
					onlydata : "true",
					idcomentario : idcomentario
				}, function(result){
					if(result == 1){
						notification("¡Exito!",'Compromiso eliminado satisfactoriamente',"success");
						tablacomentario.ajax.reload(null, false);
					} else if(result == 2){ 
						notification("Advertencia!",'No tiene permisos para eliminar este Compromiso',"warning");
					} else {
						notification("Error!",'Ha ocurrido un error al eliminar el Compromiso',"error"); 
					}
				});
			}
		}, function (isRechazo){
			// NADA
		}
	);
}

$('#tablacomentario').on( 'draw.dt', function () {	
     // DAR FUNCIONALIDAD AL BOTON EDITAR COMENTARIO
        $('.boton-editar-compromisos').each(function(){ 
			var idcomentario = $(this).attr("data-id"); 
			$(this).on( 'click', function() {
				jQuery.ajax({
                   url: "controller/postventasback.php?oper=getcomentario&idcomentario="+idcomentario,
                   dataType: "json",
                   beforeSend: function(){
                       $('#overlay').css('display','block');
                   },success: function(item) {
						$('#overlay').css('display','none');
                        $("#modal-compromisos").modal("show");
                        $('#idcomentario').val(idcomentario);
                		$('#compromiso_editar').val(item.comentario);
                		$.get( "controller/combosback.php?oper=usuarioscompromisos", { }, function(result){ 
						$("#cusuario_editar").append(result);
						$("#cusuario_editar").val(item.usuario).trigger("change");
				    	});
                		$('#cfecha_editar').val(item.fecha);
                		$('#cidestados_editar').val(item.estado);
                		$('#cresolucion_editar').val(item.resolucion);
                   }
                });
			});  
			});  
	       
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

var form,
	comentario = $( "#comentario" ),
	allFields = $( [] ).add( comentario ),
	tips = $( ".validateTips" );

function agregarComentario() {	
	var coment  = $('#comentario').val();		
	var visibilidad  = $('input[name=visibilidad]:checked').val();
	var estado = 'Nuevo';
	var resolucion = '';
	
	/* if(coment==''){
		$('#comentario').addClass('form-valide-error-bottom');
		return;
	} */
	if(!visibilidad){
		$('input[name=visibilidad]').addClass('form-valide-error-bottom');
		return;

		return;
	}

	if (coment != '') {
		$.ajax({
			type: 'post',
			url: 'controller/postventasback.php',
			data: { 
				'oper'		: 'agregarComentario',
				'id' 		: incidenteselect,
				'coment' 	: coment,
				'visibilidad' : visibilidad,
				'estado' 	: estado,
				'resolucion': resolucion
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
				$('#dialog-form-coment').hide();
			},
			success: function (response) {
				$('#overlay').css('display','none');
				if(response){					
					$('#comentario').val("");					
					if ($('.boton-coment-'+incidenteselect+'').length > 0 ) {
						$('.boton-coment-'+incidenteselect+'').removeClass("blue");
						$('.boton-coment-'+incidenteselect+'').addClass('green');
					}else{
						$('.msj-'+incidenteselect+'').append('<span class="icon-col green fa fa-comment boton-coment-'+incidenteselect+'" data-id="" data-toggle="tooltip" data-original-title="Comentarios" data-placement="right"></span>');
					} 
					notification("¡Exito!",'Compromiso agregado satisfactoriamente',"success");					
					tablacomentario.ajax.reload(null, false);
				}else{ 
					notification("Error!",'Ha ocurrido un error al agregar el Compromiso',"error"); 
				}				
			},
			error: function () {
				$('#overlay').css('display','none');
				notification("Error!",'Ha ocurrido un error al agregar el Compromiso',"error"); 
			}
		});
	}else{
		notification("Advertencia!",'Debe ingresar el campo comentario',"warning");
	}
	return;
}

var dirxdefecto = 'incidente';
$('#fevidenciascom').attr('src','filegator/postventascom.php#/?cd=%2F'+dirxdefecto);
 
function adjuntosComentarios(incidentecomentario) {
	var arr = incidentecomentario.split('-');
	var incidente = arr[0];
	var comentario = arr[1];
	// console.log("ARR:"+arr);
	// console.log("incidente:"+incidente);
	// console.log("comentario:"+comentario);
	var valid = true;
	if ( valid ) {
		$.ajax({
			  type: 'post',
			  url: 'controller/postventasback.php',
			  data: { 
				'oper': 		'adjuntosComentarios',
				'incidentecom': incidentecomentario
			  },
			  success: function (response) {
				$('#fevidenciascom').attr('src','filegator/postventascom.php#/?cd=postventas/'+idincidente+'/compromisos/'+incidente);
				$('.titulo-modal').text('Adjuntos de Compromisos');										 
				$('#modalEvidenciasCom').modal('show');
				$('#modalEvidenciasCom .modal-lg').css('width','1000px');
				$('#idincidentesevidenciascom').val(incidente);
				$('#idcomentariosevidencias').val(comentario);
				console.log('fevidenciascom');  
				/* elfInstanceAdj.bind('load', function(event) { 
					elfInstanceAdj.exec('open', response);
				}); */
				//dialogSol.dialog( "open" );
				/* $('.titulo-modal').text('Adjuntos de Compromisos');
				$('#modalEvidenciasCom').modal('show');
				$('#modalEvidenciasCom').css('z-index','1150'); */								
				//elfInstanceAdj.exec('reload');
			  },
			  error: function () {
				demo.showSwal('error-message','ERROR',response);
			  }
		});
		tablacomentario.ajax.reload(null, false);
	}
	return valid;
}

//Editar compromiso
function actualizar(){
	var idcomentario	= $('#idcomentario').val();
	var usuario			= $("#cusuario_editar").val();
	var estado			= $("#cestado_editar").val();
	var resolucion		= $("#cresolucion_editar").val();		

	$.ajax({
		type: 'post',
		url: 'controller/postventasback.php',
		data: { 
			'oper'		 : 'updatecomentario',
			'id'		 : idcomentario,
			'usuario' 	 : usuario,
			'estado' 	 : estado,
			'resolucion' : resolucion
		},

		beforeSend: function() {
			$('#overlay').css('display','block');
		},
		success: function (response) {	
			$('#overlay').css('display','none');			 
			notification('Buen trabajo!','Compromiso actualizado satisfactoriamente','success'); 
			tablacomentario.ajax.reload(null, false);
			$('#modal-compromisos').modal('hide');	
		},
		error: function () {
			$('#overlay').css('display','none');
			notification("Error","Ha ocurrido un error al guardar el Compromiso",'error');  
		}
	});
}
	
$("#guardarcompromiso").on("click",function(){
	actualizar();
}); 

	$('#modalEvidenciasCom').on('hidden.bs.modal', function(){
    console.log('paso')
         tablacomentario.ajax.reload(null, false);
    });
function vaciar(){
	$('#solicitante').val(null).trigger("change");
	$('#notificar').val(null).trigger("change");
	$('#fechacreacion').val("");
	$('#horacreacion').val("");
	$('#titulo').val("");
	$('#descripcion').val("");
	$('#fechareal').val("");
	$('#idempresas').val(null).trigger("change");
	$('#iddepartamentos').val(null).trigger("change");
	$('#idclientes').val(null).trigger("change");
	$('#idproyectos').val(null).trigger("change");
	$('#idcategorias').val(null).trigger("change");
	$('#idsubcategorias').val(null).trigger("change");
	$('#idprioridades').val(null).trigger("change");
	$('#idambientes').val(null).trigger("change");
	$('#idprioridades').val(null).trigger("change");
	$('#asignadoa').val(null).trigger("change");	
	$('#idestados').val(null).trigger("change");
}


