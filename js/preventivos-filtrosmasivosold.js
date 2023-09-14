function calendario(elem){ 
	$(elem).bootstrapMaterialDatePicker({weekStart:0, time:false, switchOnClick:true});
}
function calendarioFiltro(elem){ 
	$(elem).bootstrapMaterialDatePicker({
		weekStart:0, time:false, switchOnClick:true
	}).on('change', function(e){
		 
		e.preventDefault();
		e.stopPropagation();
	});
}
function cargarCombosF(){
	 
		var idempresasf = 1;
		//CLIENTES
		$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresasf }, function(result){ 
			$("#idclientesf").empty();
			$("#idclientesf").append(result);
		});
		//DEPARTAMENTOS
		$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: idempresasf }, function(result){ 
			$("#iddepartamentosf").empty();
			$("#iddepartamentosf").append(result);
		});
	//});
	
	//CLIENTES / PROYECTOS - DEPARTAMENTOS
	function recargarClientes(){
		var idclientesf 		= $("#idclientesf").val().join();		
		var idproyectosf 		= $("#idproyectosf").val().join();
		var idcategoriaf 		= $("#categoriaf").val().join();
		var subcategoriaf 		= $("#subcategoriaf").val().join();
		var unidadejecutoraf 	= $("#unidadejecutoraf").val().join();
		var estadof 			= $("#estadof").val().join(); 
		
		//PROYECTOS				
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientesf }, function(result){ 
			$("#idproyectosf").empty();
			$("#idproyectosf").append(result);
			$("#idproyectosf").val(idproyectosf.split(',')).trigger("change");
			//CATEGORIAS
			$.get( "controller/combosback.php?oper=categorias", { idproyectos: idproyectosf }, function(result){ 
				$("#categoriaf").empty();
				$("#categoriaf").append(result);
				$("#categoriaf").val(idcategoriaf.split(',')).trigger("change");
				//SUBCATEGORIAS
				$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: idcategoriaf }, function(result){ 
					$("#subcategoriaf").empty();
					$("#subcategoriaf").append(result);
					$("#subcategoriaf").val(subcategoriaf.split(',')).trigger("change");				
				});
			});  
		}); 
		//Ubicaciones
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientesf }, function(result){ 
			$("#unidadejecutoraf").empty();
			$("#unidadejecutoraf").append(result);
			$("#unidadejecutoraf").val(unidadejecutoraf.split(',')).trigger("change");
		});
		
		//Marcas
		$.get( "controller/combosback.php?oper=marcas", { tipo : 'filtrosmasivos', idclientes: idclientesf, idproyectos: idproyectosf }, function(result){ 
			$("#marcaf").empty();
			$("#marcaf").append(result);
		});
		//Estados
		$.get( "controller/combosback.php?oper=estadosfiltrosmasivos", { idclientes: idclientesf, idproyectos: idproyectosf, tipo:"preventivo" }, function(result){ 
			$("#estadof").empty();
			$("#estadof").append(result);
			$("#estadof").val(estadof.split(',')).trigger("change");
		}); 
		//Solicitantes
		$.get( "controller/combosback.php?oper=usuarios", { tipo : 'filtrosmasivos', idclientes: idclientesf, idproyectos: idproyectosf }, function(result){ 
			$("#solicitantef").empty();
			$("#solicitantef").append(result);
		});
		
		//Tipos
		$.get( "controller/combosback.php?oper=modalidades", { tipo : 'filtrosmasivos', idclientes: idclientesf, idproyectos: idproyectosf }, function(result){ 
			$("#modalidadf").empty();
			$("#modalidadf").append(result);
		});
	}
	
	$('#idclientesf').on('select2:select',function(){
		console.log("aquiii2");
		recargarClientes();
	});
	 
	$('#idclientesf').on("select2:unselect", function(e){
		console.log("aquiii");
		var idclientesf = $("#idclientesf").val().join();
		if(idclientesf != ""){
			recargarClientes();
		}else{
			$("#idproyectosf").empty();
			$("#categoriaf").empty();
			$("#subcategoriaf").empty();
			$("#unidadejecutoraf").empty();
			//$("#idproveedoresf").empty();
			//$("#estadof").empty();
		}        
    }); 
	
	//PROYECTOS / CATEGORIAS
	function recargarProyectos(){
		var idclientesf 	= $("#idclientesf").val().join();
		var idproyectosf 	= $("#idproyectosf").val().join();
		var idcategoriaf 	= $("#categoriaf").val().join();
		var subcategoriaf 	= $("#subcategoriaf").val().join();
		var estadof 		= $("#estadof").val().join();
		//var idproveedoresf 	= $("#idproveedoresf").val().join();
		
		//CATEGORIAS
		$.get( "controller/combosback.php?oper=categorias", { idproyectos: idproyectosf }, function(result){ 
			$("#categoriaf").empty();
			$("#categoriaf").append(result);
			$("#categoriaf").val(idcategoriaf.split(',')).trigger("change");
			//SUBCATEGORIAS
			$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: idcategoriaf }, function(result){ 
				$("#subcategoriaf").empty();
				$("#subcategoriaf").append(result);
				$("#subcategoriaf").val(subcategoriaf.split(',')).trigger("change");				
			});
		}); 
		
		//Ubicaciones
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientesf, idproyectos: idproyectosf  }, function(result){ 
			$("#unidadejecutoraf").empty();
			$("#unidadejecutoraf").append(result);
			$("#unidadejecutoraf").val(unidadejecutoraf.split(',')).trigger("change");
		});
		
		//Marcas
		$.get( "controller/combosback.php?oper=marcas", { tipo : 'filtrosmasivos', idclientes: idclientesf, idproyectos: idproyectosf }, function(result){ 
			$("#marcaf").empty();
			$("#marcaf").append(result);
		});
		
		//Estados
		$.get( "controller/combosback.php?oper=estadosfiltrosmasivos", { idclientes: idclientesf, idproyectos: idproyectosf, tipo:"preventivo" }, function(result){ 
			$("#estadof").empty();
			$("#estadof").append(result);
			$("#estadof").val(estadof.split(',')).trigger("change");
		}); 
		
		//Solicitantes
		$.get( "controller/combosback.php?oper=usuarios", { tipo : 'filtrosmasivos', idclientes: idclientesf, idproyectos: idproyectosf }, function(result){ 
			$("#solicitantef").empty();
			$("#solicitantef").append(result);
		});
		
		//Tipos
		$.get( "controller/combosback.php?oper=modalidades", { tipo : 'filtrosmasivos', idclientes: idclientesf, idproyectos: idproyectosf }, function(result){ 
			$("#modalidadf").empty();
			$("#modalidadf").append(result);
		});		
	}
	
	$('#idproyectosf').on('select2:select',function(){
		recargarProyectos();
	});
	
	$('#idproyectosf').on("select2:unselect", function(e){
		var idproyectosf = $("#idproyectosf").val().join();  
		if(idproyectosf != ""){
			recargarProyectos();
		}else{
			$("#categoriaf").empty();
			$("#subcategoriaf").empty();
			//$("#estadof").empty();
		}
    });
	
	//CATEGORIAS - SUBCATEGORIAS
	function recargarCategorias(){
		var idcategoriaf 	= $("#categoriaf").val().join();
		var subcategoriaf 	= $("#subcategoriaf").val().join();
		//SUBCATEGORIAS
		$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: idcategoriaf }, function(result){ 
			$("#subcategoriaf").empty();
			$("#subcategoriaf").append(result);
			$("#subcategoriaf").val(subcategoriaf.split(',')).trigger("change");
		});
	}
	
	$('#categoriaf').on('select2:select',function(){
		recargarCategorias();
	});
	
	$('#categoriaf').on('select2:unselect',function(){
		var categoriaf = $("#categoriaf").val().join();
		if(categoriaf != ""){
			recargarCategorias();
		}else{
			$("#subcategoriaf").empty();
		}
	});
	
	//SITIOS / SERIE
	function recargarSitios(){
		var idsitiof = $("#unidadejecutoraf").val().join();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idsitiof }, function(result){ 
			$("#serief").empty();
			$("#serief").append(result);
		});
	}
	$('#unidadejecutoraf').on('select2:select',function(){
		recargarSitios();
	});
	
	$('#unidadejecutoraf').on('select2:unselect',function(){
		var unidadejecutoraf = $("#unidadejecutoraf").val().join();
		if(unidadejecutoraf != ""){
			recargarSitios();
		}else{
			$("#serief").empty();
		}
	});
	
	//DEPARTAMENTOS / ASIGNADO A
	function recargarDepartamentos(){
		var iddepartamentosf = $("#iddepartamentosf").val().join();	
		var asignadoaf 		= $("#asignadoaf").val().join();
		
		$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: iddepartamentosf, tipo: 'filtros' }, function(result){ 
			$("#asignadoaf").empty();
			$("#asignadoaf").append(result);
			$("#asignadoaf").val(asignadoaf.split(',')).trigger("change");						
		});
	}
	
	if(nivel == 7){ 
		$.get( "controller/combosback.php?oper=usuariosDep", {tipo: 'filtros'}, function(result){ 
			$("#asignadoaf").empty();
			$("#asignadoaf").append(result); 
		});
	}
	
	$('#iddepartamentosf').on('select2:select',function(){
		var iddepartamentosf = $("#iddepartamentosf").val().join(); 
			recargarDepartamentos(); 
	});	
	
	$('#iddepartamentosf').on('select2:unselect',function(){
		var iddepartamentosf = $("#iddepartamentosf").val().join(); 
		if(iddepartamentosf != ""){
			recargarDepartamentos(); 
		}else{
			$("#asignadoaf").empty(); 
		}
		
	});	
	
	//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { onlydata:"true" }, function(result){ 
		$("#prioridadf").empty();
		$("#prioridadf").append(result);
	});	
	//MODALIDADES
	$.get( "controller/combosback.php?oper=modalidades", { onlydata:"true" }, function(result){ 
		$("#modalidadf").empty();
		$("#modalidadf").append(result);
	});
	//MARCAS
	$.get( "controller/combosback.php?oper=marcas", { onlydata:"true" }, function(result){ 
		$("#marcaf").empty();
		$("#marcaf").append(result);
	});	
	//SOLICITANTE
	$.get( "controller/combosback.php?oper=usuarios", { onlydata:"true" /*, nivel:"14"*/}, function(result){ 
		$("#solicitantef").empty();
		$("#solicitantef").append(result);
	});
	//ESTADOS
	$.get( "controller/combosback.php?oper=estadosfiltrosmasivos", { tipo:"preventivo" }, function(result){ 
		$("#estadof").empty();
		$("#estadof").append(result);
	});
}	

function verificarfiltros(){
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/preventivosback.php',
		data: { 
			'oper'	: 'verificarfiltros'
		},
		success: function (response) {
			if (response == 1) {
			    $('#icono-filtrosmasivos').removeClass('bg-success');
				$('#icono-filtrosmasivos').addClass('bg-warning');
			}else{
				$('#icono-filtrosmasivos').removeClass('bg-warning');
				$('#icono-filtrosmasivos').addClass('bg-success');
			}
		}
	});
}
verificarfiltros();

function abrirFiltrosMasivos(){
	//$("#idempresasf").val('1').trigger("change");
	$.ajax({
		type: 'post',
		dataType: "json",
		url: 'controller/preventivosback.php',
		data: { 
			'oper'	: 'abrirfiltros'
		},
		beforeSend: function() {
		$('#preloader').css('display','block');
		},
		success: function (response) {
		$('#preloader').css('display','none');
			if (response.data!="") {
				var obj = JSON.parse(response.data);					
				$("#relleno").val(obj.relleno);
				$("#desdef").val(obj.desdef);
				$("#hastaf").val(obj.hastaf);
				
				//EMPRESAS
				$("#idempresasf").val(obj.idempresas).trigger("change");
				//EMPRESAS / CLIENTES - DEPARTAMENTOS
				$.when( $('#idempresasf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//CLIENTES
					$.get( "controller/combosback.php?oper=clientes", { idempresas: obj.idempresasf }, function(result){ 
						$("#idclientesf").empty();
						$("#idclientesf").append(result);
						$("#idclientesf").val(obj.idclientesf).trigger("change");
					});
					//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgrupos", { idempresas: obj.idempresasf }, function(result){ 
						$("#iddepartamentosf").empty();
						$("#iddepartamentosf").append(result);
						$("#iddepartamentosf").val(obj.iddepartamentosf).trigger("change");
					});
				});
				//CLIENTES / PROYECTOS - DEPARTAMENTOS
				$.when( $('#idclientesf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//PROYECTOS
					$.get( "controller/combosback.php?oper=proyectos", { idclientes: obj.idclientesf }, function(result){ 
						$("#idproyectosf").empty();
						$("#idproyectosf").append(result);
						$("#idproyectosf").val(obj.idproyectosf).trigger("change");
					});				
					//SITIOS
					$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: obj.idclientesf }, function(result){ 
						$("#unidadejecutoraf").empty();
						$("#unidadejecutoraf").append(result);
						$("#unidadejecutoraf").val(obj.unidadejecutoraf).trigger("change");
					});
				});
				//PROYECTOS / CATEGORIAS
				$.when( $('#idproyectosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=categorias", { tipo: "preventivo", idproyectos: obj.idproyectosf }, function(result){ 
						$("#categoriaf").empty();
						$("#categoriaf").append(result);
						$("#categoriaf").val(obj.categoriaf).trigger("change");
					});
					//Proveedores
					/* $.get("controller/combosback.php?oper=proveedores", { idcliente: obj.idclientesf, idproyecto: obj.idproyectosf }, function(result){
						$("#idproveedoresf").empty();
						$("#idproveedoresf").append(result);
						$("#idproveedoresf").val(obj.idproveedoresf).trigger("change"); 
					}); */
					//ESTADOS
					/* $.get( "controller/combosback.php?oper=estados", { idempresas: obj.idempresasf, idclientes: obj.idclientesf, idproyectos: obj.idproyectosf, tipo:"incidente" }, function(result){ 
						$("#estadof").empty();
						$("#estadof").append(result);
						$("#estadof").val(obj.estado).trigger("change");
					}); */
				});
				//CATEGORIAS - SUBCATEGORIAS
				$.when( $('#categoriaf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=subcategorias", { idcategoria: obj.idcategoriaf }, function(result){ 
						$("#subcategoriaf").empty();
						$("#subcategoriaf").append(result);
						$("#subcategoriaf").val(obj.subcategoriaf).trigger("change");
					});
				});
				//SITIOS / SERIE
				$.when( $('#unidadejecutoraf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SERIE
					$.get( "controller/combosback.php?oper=serie", { idsitio: obj.unidadf }, function(result){ 
						$("#serief").empty();
						$("#serief").append(result);
						$("#serief").val(obj.serief).trigger("change");
					});
				});
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentosf').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					$.get( "controller/combosback.php?oper=usuariosDep", { iddepartamentos: obj.iddepartamentosf }, function(result){ 
						$("#asignadoaf").empty();
						$("#asignadoaf").append(result);
						$("#asignadoaf").val(obj.asignadoaf).trigger("change");
					});
				});				
				if ('modalidadf' in obj) $("#modalidadf").val(obj.modalidadf).trigger('change');
				if ('marcaf' in obj) $("#marcaf").val(obj.marcaf).trigger('change');
				if ('solicitantef' in obj) $("#solicitantef").val(obj.solicitantef).trigger('change');
				if ('estadof' in obj) $("#estadof").val(obj.estadof).trigger('change');	
				if ('prioridadf' in obj) $("#prioridadf").val(obj.prioridadf).trigger('change');	
				if(obj.fueraserviciof == 1){
					$("#fueraserviciof").prop("checked", true); 
				}else{
					$("#fueraserviciof").prop("checked", false); 
				}
			}else{				
				if( $('#fueraserviciof').prop('checked') ) {
					$("#fueraserviciof").prop("checked", false);
				}
			}
		},
		error: function () {
		$('#preloader').css('display','none');
		}
	});
}

$("#filtrarmasivo").on('click', function() {
	filtrosMasivos();
});

$("#limpiarfiltros").on('click', function() {
	limpiarFiltrosMasivos();
});

function filtrosMasivos() {
	var dataserialize 	= $("#form_filtrosmasivos").serializeArray();
	var data 			= {};
	
	for (var i in dataserialize) {
		//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
		if( dataserialize[i].name == 'categoriaf' || dataserialize[i].name == 'subcategoriaf' || 
			dataserialize[i].name == 'idempresasf' || dataserialize[i].name == 'iddepartamentosf' || 
			dataserialize[i].name == 'idclientesf' || dataserialize[i].name == 'idproyectosf' || 
			dataserialize[i].name == 'modalidadf'  || dataserialize[i].name == 'prioridadf' || 
			dataserialize[i].name == 'solicitantef'  || dataserialize[i].name == 'estadof' || 
			dataserialize[i].name == 'asignadoaf'  || dataserialize[i].name == 'unidadejecutoraf' ||
			dataserialize[i].name == 'marcaf' || dataserialize[i].name == 'idproveedoresf' ){
			data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
		}else{
			data[dataserialize[i].name] = dataserialize[i].value;	
		}		
	}
	
	data = JSON.stringify(data);	
	$.ajax({
		type: 'post',
		dataType: "json",
		beforeSend: function() {
		    $('#preloader').css('display','block');
		},
		url: 'controller/preventivosback.php',
		data: { 
			'oper'	: 'guardarfiltros',
			'data'	: data
		},
		beforeSend: function() {
			$('#preloader').css('display','block');
		},
		success: function (response) {
		  $('#preloader').css('display','none');
			// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
			tablaincidentes.ajax.reload(null, false);
		    $(".chatbox-close").click();
			verificarfiltros();
		},
		error: function () {
		$('#preloader').css('display','none');
		}
	});
}
function limpiarFiltrosMasivos(){
    $('#icono-filtrosmasivos').removeClass('bg-warning');
	$('#icono-filtrosmasivos').addClass('bg-success');
	$.get( "controller/preventivosback.php?oper=limpiarFiltrosMasivos");
	var dataserialize = $("#form_filtrosmasivos").serializeArray();
	for (var i in dataserialize) {
		if(dataserialize[i].name != 'fueraserviciof'){
			$("#"+dataserialize[i].name).val(null).trigger("change");
		}
		tablaincidentes.ajax.reload(null, false);
		$(".chatbox-close").click();
	}
}

$(document).ready(function() {
	cargarCombosF();
});


