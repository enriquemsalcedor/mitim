var incidenteselect 	= '';
var tablabitacora 		= '';
var tablacomentario 	= '';
var tablaactivos;
var esNuevo;

jQuery(function($) {
	esNuevo=0;
});

//$(document).ready(function() {
	$('.switch-sidebar-mini input').change();
	$('#estado').select2();	
	//Permite ver el nombre del campo
	$(".select2").removeClass("is-empty");
	
	
	//MODALIDADES
	$.get( "controller/combosback.php?oper=modalidades", { onlydata:"true" }, function(result){ 
		$("#filtromod").append(result);
		$('#filtromod').select2();
	});
	//MARCAS
	$.get( "controller/combosback.php?oper=marcas", { onlydata:"true" }, function(result){ 
		$("#filtromarca").append(result);
		$('#filtromarca').select2();
	});
	//SERIE
	$("#unidadejecutora").change(function(){
		var idsitio = $("#unidadejecutora option:selected").val();
		$.get( "controller/combosback.php?oper=serie&idsitio="+idsitio, { onlydata:"true" }, function(result){ 
			$("#serie").html(result);
			$('#marca, #modelo').val('');
			$('#serie').select2();
		});
	});
	$("#unidadejecutoraf").change(function(){
		var idsitio = $("#unidadejecutoraf option:selected").val();
		$.get( "controller/combosback.php?oper=serie&idsitio="+idsitio, { onlydata:"true" }, function(result){ 
			$("#serief").html(result);
			$('#serief').select2();
		});
	});
	$.get( "controller/combosback.php?oper=serie", { onlydata:"true" }, function(result){ 
		$("#serie").html(result);
		$('#serie').select2();
	});
	$("#serie").change(function(){
		var idserie = $("#serie option:selected").val();
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
	//ESTADOS
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"incidente" }, function(result){ 
		$("#estado, #estadof").append(result);
		$("#estadof").select2();
	});
	//ESTADOS MANTENIMIENTO
	$.get( "controller/combosback.php?oper=estados", { onlydata:"true", tipo:"mantenientoprev" }, function(result){ 
		$("#estadomantenimiento").append(result);
	});
	
	//VALIDAR CAMPOS 
	$(".form-control").bind("keydown blur",function(e){validCampos(this);});
	
	// Setup - add a text input to each header cell
    $('#tablaactivos thead th').each( function () {
        var title = $(this).text();
		var ancho1 = $(this).width();
		var ancho2 = ($(this).width() * 0.4).toFixed(0);
		if ((title!='') && (title!='-')) 
			$(this).html( '<input type="text" placeholder="'+title+'" size=8 /> ' );
		else if (title=='-') 
			$(this).html( '<input id="chkSelectAll" class="fac fac-checkbox fac-white" type="checkbox" value="A11|" />' );
		
		$(this).width(ancho1);
    } );
	
	tablaactivos = $("#tablaactivos").DataTable({
		scrollX: true,
		searching: true,
		//stateSave: true,
		select: true,
		colReorder: true,
		ajax : {
			url	: "controller/equipamientoback.php?oper=incidentes&f=" + paramf
		},
		columns	: [
			{ 	"data": "check"},
			{ 	"data": "acciones"},
			{ 	"data": "id"},
			{ 	"data": "estado" },
			{ 	"data": "equipo" },
			{ 	"data": "cantidad"},
			{ 	"data": "ficha" },
			{ 	"data": "serie" },
			{ 	"data": "marca" },
			{ 	"data": "modelo" },
			{ 	"data": "activo" },
			{ 	"data": "casamedica" },
			{ 	"data": "area" },
			{ 	"data": "fechainst" },
			{ 	"data": "fechatopemant" }
			],
		rowId: 'ficha', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		columnDefs: [ //OCULTAR LA COLUMNA Descripcion
			{
				orderable	: false,
				className	: 'select-checkbox',
				targets		: 0
			},
			{
				orderable	: false,
				targets		: 1,
				searchable	: false
			},
			{
				targets		: [5],
				className	: "dt-left"
			}
		],
		select: {
            style:    'os',
            selector: 'td:first-child'
        },
		language: {
			url: "js/Spanish.json",
			info: "Mostrando página _PAGE_ de _PAGES_"
		}
		
	});
	
	$('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
		var column = tablaactivos.column( $(this).attr('data-column') );
		column.visible( ! column.visible() );
		if (column.visible()){
			$("#c"+$(this).attr('data-column')).css('background-color', '#267DBD');
		} else {
			$("#c"+$(this).attr('data-column')).css('background-color', 'red');
		}
    } );
	
	tablaactivos.columns().every( function () {
        var that = this;
 
        $( 'input', this.header() ).on( 'keyup change', function () {
			if (this.value!='A11|') {
				if ( that.search() !== this.value ) {
					that
						.search( this.value )
						.draw();
				}
			}
        } );
    } );
	
	$("#tablaactivos tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		abrirdialogIncidente(id);
	});
	
	$('#tablaactivos tbody').on('click', 'tr', function () {
		$(this).closest('tr').toggleClass('selected');
		var ficha = $(this).attr("id");
		if (ficha!='N/A') {
			$("#viewPdf").attr("data","fichas/FT " + ficha + ".pdf#toolbar=1&amp;navpanes=0&amp;scrollbar=1");
		}
	});  
	
	$('#chkSelectAll').click(function(){
		if($("#chkSelectAll").is(':checked'))
			tablaactivos.rows('current');
		else
			tablaactivos.rows('none');
	});
	
	// AL CARGARSE LA TABLA
	$('#tablaactivos').on( 'draw.dt', function () {		
		// DAR FUNCIONALIDAD AL BOTON ELIMINAR
        $('.boton-eliminar').each(function(){
			var id = $(this).attr("data-id");
			var nombre = $(this).parent().parent().next().next().html();
			$(this).on( 'click', function() {
				eliminarincidente(id,nombre);
			});
		});
		// DAR FUNCIONALIDAD AL BOTON EVIDENCIAS
        $('.boton-evidencias').each(function(){
			var id = $(this).attr("data-id");
			$(this).on( 'click', function() {
				abrirsolicitudes(id);
			});
		});
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();		
		// Cambiar color de fondo de columnas ocultas en el menú filtro
		for (var i=4; i<14; i++) {
			var column = tablaactivos.column( i );
			if (column.visible()){
				$("#c"+i).css('background-color', '#267DBD');
			} else {
				$("#c"+i).css('background-color', 'red');
			}
		}
    });
	
	//***** ***** ***** SOLICITUDES DE SERVICIO ***** ***** ***** //
	var dialogSol;
	var options = {
		url  : 'elFinder/php/connector.minimal.incidentes.php',
		lang : 'es',
		rememberLastDir: false
	}
	var elfInstance = $('#elfinder').elfinder(options).elfinder('instance');

	function abrirsolicitudes(incidente) {	
		var valid = true;
		if ( valid ) {
			$.ajax({
				  type: 'post',
				  url: 'controller/actividadesback.php',
				  data: { 
					'oper': 		'abrirSolicitudes',
					'incidente': 	incidente
				  },
				  success: function (response) {
					elfInstance.bind('load', function(event) { 
						elfInstance.exec('open', response);
					});
					dialogSol.dialog( "open" );
					elfInstance.exec('reload'); 
				  },
				  error: function () {
					demo.showSwal('error-message','ERROR!',response);
				  }
			   }); 
			}
		return valid;
	}

	dialogSol = $( "#dialog-form-sol" ).dialog({
		width: '50%', 
		maxWidth: 600,
		height: 'auto',
		modal: true,
		fluid: true,
		resizable: false,
		autoOpen: false
	});

	function cerrarDialogSol() {
		dialogSol.dialog('close');
		$('#dialog-grid-adjunto').hide();
	}

	//***** ***** ***** COMENTARIOS ***** ***** ***** //
	function abrirGridAdjunto(idincidente,id) {
		//console.log(idincidente+'-'+id);
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

		if (coment!='') {
			$.ajax({
				type: 'post',
				url: 'controller/actividadesback.php',
				data: { 
					'oper'	: 'agregarComentario',
					'id' : incidenteselect,
					'comentario' : coment,
					'visibilidad' : visibilidad
				},
				beforeSend: function() {
					$(".loader-maxia").show();
					$('#dialog-form-coment').hide();
				},
				success: function (response) {
					if(response){					
						$('#comentario').val("");					
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

	//CALENDARIO
	$('#filtrodesde').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false});
	$('#filtrohasta').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false});
	$('#fecharesolucion, #fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true });
	$('#fechacreacion, #fechareal').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false  });
	$('#horacreacion').bootstrapMaterialDatePicker({switchOnClick:true, date:false, format : 'HH:mm' });
	function calendario(elem){ 
		$(elem).bootstrapMaterialDatePicker({weekStart:0, time:false, switchOnClick:true});
	}
	function calendarioFiltro(elem){ 
		$(elem).bootstrapMaterialDatePicker({
			weekStart:0, time:false, switchOnClick:true
		}).on('change', function(e){
			//jQuery(grid_selector)[0].triggerToolbar();
			e.preventDefault();
			e.stopPropagation();
		});
	}

	//	FORMULARIO INCIDENTE  //
	var dialogIncidente = $( "#dialog-form-incidentes" ).dialog({
		width: '72%', 
		maxWidth: 600,
		height: 'auto',
		modal: true,
		fluid: true,
		resizable: false,
		autoOpen: false,
		open: function(event, ui) {
			if(incidenteselect != ''){
				$('.gridComent').show();
				//COMENTARIOS
				tablacomentario = $("#tablacomentario").DataTable({
						responsive: true,
						"scrollY": "500px",
						"ajax"		: {
							"url"	: "controller/actividadesback.php?oper=comentarios&id="+incidenteselect,
						},
						"columns"	: [
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
								"targets"	: [ 0 ],
								"visible"	: false,
								"searchable": false
							}
						],
						"language": {
							"url": "js/Spanish.json",
							"info": "Mostrando página _PAGE_ de _PAGES_"
						}
					});
				$('#form_incidentes .bnuevocoment').show();
				//HISTORIAL
				if(nivel == 1 || nivel == 2 || nivel == 3){
					$('.gridBit').show();
					tablabitacora = $("#tablabitacora").DataTable({
						responsive: true,
						"scrollY": "500px",
						"ajax"		: {
							"url"	: "controller/actividadesback.php?oper=historial&id="+incidenteselect,
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
							}
						],
						"language": {
							"url": "js/Spanish.json",
							"info": "Mostrando página _PAGE_ de _PAGES_"
						}
					});
				}
			}else{
				$('.gridComent, .gridBit').hide();
				$('#form_incidentes .bnuevocoment').hide();
			}
			
			$("#titulo").focus();
		},
		close: function(event, ui) {
			if (esNuevo!=1) {
				tablacomentario.destroy();
				tablabitacora.destroy();
			}
			esNuevo=0;
		}
	});

	function editarMasiva(){
		var id = jQuery("#grid-mttoc").jqGrid('getGridParam','selarrrow');	
		if(id.length == 0){
			demo.showSwal('error-message','','Registros no Seleccionados');
			return;
		}	
		if(!id[1]){
			abrirdialogIncidente(id[0]);
		}else{
			abrirdialogIncidente(id);
		}
	}
		
	function abrirdialogIncidente(id){	
		incidenteselect = id;
		//var idcategoria = $("#categoria option:selected").val();		
		
		$('#fusion, #btnrevertirfusion').hide();
		$("#form_incidentes")[0].reset();
		$("#unidadejecutora, #solicitante, #asignadoa, #notificar").select2("val", "");
		$("#unidadejecutora").val("");
		$("#categoria").val("");
		$("#subcategoria").val("");
		$("#estado").val("");
		
		if(id == ''){
			$('.content-incidente, .content-fechacierre').hide();		
			//$('#tituloincidente').html("Incidente");
			$('.gridComent, .gridBit').hide();
			esNuevo=1;
		}else{
		//if(id!=''){
			//$('#tituloincidente').html("Incidente N° "+id);
			$('#incidente').val(id);
			$('.content-incidente, .content-fechacierre').show();
			
			$.ajax({
				type: 'get',
				dataType: 'json',
				url: 'controller/actividadesback.php',
				data: { 
					'oper'	: 'abrirIncidente',
					'id'	: id			
				},
				success: function (response) {
					$(".label-floating").removeClass("is-empty");	
					$.map(response, function (item) {
						// $.getScript("js/ajax_script.js");
						/*var qui=$("#resolucion");
						console.log(item.estado);
						if (item.estado==17 || item.estado==18){
						   qui.removeAttr("disabled").focus().val("");
						}
						else
						{
						   qui.attr("disabled","disabled");
						}
						*/
						$('#incidente').val(item.id);
						$('#titulo').val(item.titulo);
						$('#descripcion').val(item.descripcion);
						//$('#proyecto > option[value="'+item.proyecto+'"]').prop("selected", true);
						$("#proyecto").val(item.proyecto).trigger("change",[{pcategoria:item.categoria}]);
						//$("#unidadejecutora").val(item.unidad).trigger("change");					
						//$('#unidadejecutora > option[value="'+item.unidad+'"]').prop("selected", true);
						$("#unidadejecutora").select2("val", item.unidad);
						//$("#serie").val(item.serie).trigger("change");
						//$('#serie > option[value="'+item.serie+'"]').prop("selected", true);
						$("#serie").select2("val", item.serie);
						$("#estado").select2("val", item.estado);
						//$("#estado").val(item.estado).trigger("change");
						//$('#estado > option[value="'+item.estado+'"]').prop("selected", true);
						//$("#categoria").val(item.categoria).trigger("change");
						$("#categoria").select2("val", item.categoria);
						$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+item.categoria, { form:"true" }, function(result){ 
							$("#subcategoria").html(result);
							$('#subcategoria > option[value="'+item.subcategoria+'"]').prop("selected", true);
						});
		
						//$("#subcategoria").val(item.subcategoria).trigger("change");
						$('#prioridad > option[value="'+item.prioridad+'"]').prop("selected", true);
						
						$("#solicitante").append('<option value="'+item.solicitante+'">'+item.solicitante+'</option>');
						$("#solicitante").val(item.solicitante).trigger("change");
						//$("#asignadoa").append('<option value="'+item.asignadoa+'">'+item.asignadoa+'</option>');
						$("#asignadoa").val(item.asignadoa).trigger("change");
						if(nivel > 3){
							$("#asignadoa").prop('disabled', true);
						} else {
							$("#asignadoa").prop('disabled', false);
						}
						$('#departamento').val(item.departamento);
						$('#modalidad').val(item.modalidad);
						$('#fechacierre').val(item.fechacierre);
						$('#horacierre').val(item.horacierre);
						if(item.notificar!='')
							$("#notificar").val($.parseJSON(item.notificar)).trigger("change");
						$('#fusionado').val(item.fusionado);
						if(item.fusionado !=' - '){
							$('#fusion, #btnrevertirfusion').show();
						}else{
							$('#fusion, #btnrevertirfusion').hide();
						}
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
						if(nivel > 2){
							$("#fechacreacion").prop('disabled', true);
							$("#horacreacion").prop('disabled', true);
						} else {
							$("#fechacreacion").prop('disabled', false);
							$("#horacreacion").prop('disabled', false);
						}
						$('#fechamodif').val(item.fechamodif);
						$('#fechavencimiento').val(item.fechavencimiento);
						$('#fecharesolucion').val(item.fecharesolucion);
						$('#fechacierre').val(item.fechacierre);
						$('#fechacertificar').val(item.fechacertificar);
						$('#horastrabajadas').val(item.horastrabajadas);
					});		
				}	
			});
		}
		dialogIncidente.dialog( "open" );
	}

	function cerrarDialogIncidente() {
		//$('#tituloincidente').html("Incidente");	
		$('#unidadejecutora').val(null).trigger("change");
		$('#activo').val(null).trigger("change");	
		$('#solicitante').val(null).trigger("change");
		$('#asignadoa').val(null).trigger("change");	
		$('#notificar').val(null).trigger("change");
		limpiarFormulario("#form_incidentes");	
		//vermas();
		dialogIncidente.dialog('close');	
	}

	function guardarFormIncidente() {
		var id 				= $('#incidente').val();
		var dataserialize 	= $("#form_incidentes").serializeArray();
		var data 			= {};
		for (var i in dataserialize) {
			//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
			if( dataserialize[i].name == 'filtrocat' || dataserialize[i].name == 'filtrosubcat' || 
				dataserialize[i].name == 'proyectof' || dataserialize[i].name == 'proyectof'  || 
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
		//data['resolucion'] = '0';
		
		
		if(id == ''){
			//id = jQuery("#grid-mttoc").jqGrid('getGridParam','selarrrow');	
		}
		
		//console.log(id);
		//console.log(data);
		// proyecto, categoria, sitio, serie
		if(data['titulo'] == ''){
			$("#"+dataserialize['titulo']).addClass('form-valide-error-bottom');
			$("#"+dataserialize['titulo']).css({'border':'1px solid red'});
			demo.showSwal('error-message','ERROR!','Debe llenar el campo Título');
		}else if(data['proyecto'] == '0'){
			$("#"+dataserialize['proyecto']).addClass('form-valide-error-bottom');
			$("#"+dataserialize['proyecto']).css({'border':'1px solid red'});
			demo.showSwal('error-message','ERROR!','Debe llenar el campo Proyecto');
		}else if(data['categoria'] == '0'){
			$("#"+dataserialize['categoria']).addClass('form-valide-error-bottom');
			$("#"+dataserialize['categoria']).css({'border':'1px solid red'});
			demo.showSwal('error-message','ERROR!','Debe llenar el campo Categoría');
		}else if(data['proyecto'] == '1' && data['unidadejecutora'] == ''){
			$("#"+dataserialize['unidadejecutora']).addClass('form-valide-error-bottom');
			$("#"+dataserialize['unidadejecutora']).css({'border':'1px solid red'});
			demo.showSwal('error-message','ERROR!','Debe llenar el campo Unidad Ejecutora');
		}/*else if(data['serie'] == ''){
			$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
			$("#"+dataserialize['serie']).css({'border':'1px solid red'});
			demo.showSwal('error-message','ERROR!','Debe llenar el campo Serie');
		}*/
		else if(data['proyecto'] == '1' && (data['categoria'] == '10' || data['categoria'] == '11') && data['serie'] == ''){
			$("#"+dataserialize['serie']).addClass('form-valide-error-bottom');
			$("#"+dataserialize['serie']).css({'border':'1px solid red'});
			demo.showSwal('error-message','ERROR!','Debe llenar el campo Serie');
		}else if(data['estado'] == '16' && data['fecharesolucion'] == ''){
			$("#"+dataserialize['fecharesolucion']).addClass('form-valide-error-bottom');
			$("#"+dataserialize['fecharesolucion']).css({'border':'1px solid red'});
			demo.showSwal('error-message','ERROR!','Debe llenar el campo de Fecha y Hora de Resolución');
		}else if(data['estado'] == '16' && data['resolucion'] == ''){
			$("#"+dataserialize['resolucion']).addClass('form-valide-error-bottom');
			$("#"+dataserialize['resolucion']).css({'border':'1px solid red'});
			demo.showSwal('error-message','ERROR!','Debe llenar el campo de Resolución');
		}else{
			$.ajax({
				type: 'post',
				dataType: "json",
				url: 'controller/actividadesback.php',
				data: { 
					'oper'	: 'guardarIncidente',
					'id'	: id,
					'data' 	: data
				},
				beforeSend: function() {
					$(".loader-maxia").show();
					$(".modal-container").addClass('swal2-in');
					cerrarDialogIncidente();
				},
				success: function (response) {	
					demo.showSwal('success-message','Buen trabajo!','Incidente almacenado satisfactoriamente');			
					// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
					tablaactivos.ajax.reload(null, false);
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

	//FUSIONAR INCIDENTES
	var dialogFusion;
	dialogFusion = $( "#dialog-form-fusion" ).dialog({					
		width: '50%', // overcomes width:'auto' and maxWidth bug
		maxWidth: 500,
		height: 'auto',
		modal: true,
		fluid: true, //new option
		resizable: false,
		autoOpen: false
	});

	function fusionarIncidentes() {	
		var idincidentes;
		var fusioninc 	= $('input[name=fusioninc]:checked').val();
		/*idincidentes = jQuery("#grid-mttoc").jqGrid('getGridParam','selarrrow');
		var pos = idincidentes.indexOf(fusioninc);
		idincidentes.splice(pos, 1);*/
		
		if($("#incidenteafusionar").select2("val") != ''){
			idincidentes = JSON.stringify($("#incidenteafusionar").select2("val"));
		}else{
			demo.showSwal('error-message','Alerta!','Seleccione el/los incidentes a Fusionar');
			return;
		}
		console.log(idincidentes);
		console.log(fusioninc);	
		$.ajax({
			type: 'post',
			url: 'controller/actividadesback.php',
			data: { 
				'oper'	: 'fusionarIncidentes',
				'idincidentes' : idincidentes,
				'fusioninc' : fusioninc
			},
			beforeSend: function() {
				$(".loader-maxia").show();
				$(".modal-container").addClass('swal2-in');
				cerrarDialogFusion();
			},
			success: function (response) {
				if(response){
					demo.showSwal('success-message','Buen trabajo!','Fusión realizada satisfactoriamente');
					// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
					tablapacientes.ajax.reload(null, false);
					//$(grid_selector).jqGrid('clearGridData');
					//$(grid_selector).trigger( 'reloadGrid' );
					incidenteselect = '';
				}else{
					demo.showSwal('error-message','','Ha ocurrido un error al realizar la Fusión, Asegúrese de seleccionar los Incidentes a Fusionar');
				}
				$(".loader-maxia").hide();
				$(".modal-container").removeClass('swal2-in');
			},
			error: function () {
				demo.showSwal('error-message','ERROR!','Ha ocurrido un error al realizar la Fusión, intente mas tarde');
			}
		});
		return;
	}

	function cerrarDialogFusion() {
		$('#incidenteafusionar').val('');
		$('#incidenteafusionar').val(null).trigger("change");
		dialogFusion.dialog('close');
	}

	function mergeIncidente(){
		var s;
		var radios = '';
		/*s = jQuery("#grid-mttoc").jqGrid('getGridParam','selarrrow');
		//alert(s[0]);
		$.each(s, function (ind, elem) {
			var xx     = jQuery("#grid-mttoc").getRowData(elem); 
			var y       = xx.incidente;		
			radios += '<div class="radio col-sm-6">';
			radios += '	<label>';
			radios += '		<input type="radio" name="fusioninc" value="'+elem+'"><span class="circle"></span><span class="check"></span> ';
			radios += '		'+y;
			radios += '	</label>';
			radios += '</div>';
		});
		*/
		if(incidenteselect == ''){
			demo.showSwal('error-message','Alerta!','Seleccione el Incidente a Prevalecer');
			return;
		}
		var xx     = jQuery("#grid-mttoc").getRowData(incidenteselect); 
		//var inc    = xx.incidente;
		var inc    = incidenteselect;
		var tit    = xx.titulo;
		radios += '<div class="radio col-sm-6">';
			radios += '	<label>';
			radios += '		<input type="radio" name="fusioninc" value="'+incidenteselect+'"  checked><span class="circle"></span><span class="check"></span> ';
			radios += '		'+inc+' - '+tit;
			radios += '	</label>';
			radios += '</div>';
		$(".radiosincidentes").html(radios);
		
		dialogFusion.dialog( "open" );
	}

	function revertirfusion() {
		var id 			= $('#incidente').val();
		var incidente	= $('#incidente').val();
		var fusionado 	= $("#fusionado").val();		
		console.log(id);
		console.log(fusionado);
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/actividadesback.php',
			data: { 
				'oper'	: 'revertirfusion',
				'id'	: id,
				'incidente'	: incidente,
				'fusionado'	: fusionado
			},
			beforeSend: function() {
				$(".loader-maxia").show();
				$(".modal-container").addClass('swal2-in');
				cerrarDialogIncidente();
			},
			success: function (response) {			
				demo.showSwal('success-message','Buen trabajo!','Fusión Revertida Exitosamente');			
				// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
				tablapacientes.ajax.reload(null, false);
				//$(grid_selector).jqGrid('clearGridData');
				//$(grid_selector).trigger( 'reloadGrid' );								
			},
			error: function () {
				demo.showSwal('error-message','ERROR!','Ha ocurrido un error al Revertida la Fusión, intente mas tarde');
			}
		});
		$(".loader-maxia").hide();
		$(".modal-container").removeClass('swal2-in');
	}

	var dialogfiltrosmasivos = $( "#dialog-filtrosmasivos" ).dialog({		
		width: '60%', 
		maxWidth: 600,
		height: 'auto',
		//modal: true,
		fluid: true,
		resizable: true,
		autoOpen: false
	});	
	
	function abrirFiltrosMasivos(){
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/actividadesback.php',
			data: { 
				'oper'	: 'abrirfiltros'
			},
			success: function (response) {	
				if (response.data!="") {
					var obj = JSON.parse(response.data);
					
					$("relleno").val(obj.relleno);
					$("filtrodesde").val(obj.filtrodesde);
					$("filtrohasta").val(obj.filtrohasta);
					if ('proyectof' in obj) $("#proyectof").select2().select2('val', obj.proyectof);
					if ('prioridadf' in obj) $("#prioridadf").select2().select2('val', obj.prioridadf);
					if ('filtrocat' in obj) $("#filtrocat").select2().select2('val', obj.filtrocat);
					if ('filtrosubcat' in obj) $("#filtrosubcat").select2().select2('val', obj.filtrosubcat);
					if ('filtromod' in obj) $("#filtromod").select2().select2('val', obj.filtromod);
					if ('filtromarca' in obj) $("#filtromarca").select2().select2('val', obj.filtromarca);
					if ('solicitantef' in obj) $("#solicitantef").select2().select2('val', obj.solicitantef);
					if ('estadof' in obj) $("#estadof").select2().select2('val', obj.estadof);
					if ('asignadoaf' in obj) $("#asignadoaf").select2().select2('val', obj.asignadoaf);
					if ('unidadejecutoraf' in obj)  select2().select2('val', obj.unidadejecutoraf);
					//$("#filtrosubcat").select2();
					$("#filtrocat").change(function(){
						/*/var idcategoria = $("#filtrocat option:selected").val();
						var idcategoria = JSON.stringify($("#filtrocat").select2("val"));
						$.get( "controller/combosback.php?oper=subcategorias&idcategoria="+idcategoria, { onlydata:"true" }, function(result){ 
							$("#filtrosubcat").html(result);
						});*/
					});
				}
				dialogfiltrosmasivos.dialog( "open" );						
			}
		});
	}
	
	function filtrosMasivos() {
		//console.log( $("#form_filtrosmasivos").serializeArray() );
		var dataserialize 	= $("#form_filtrosmasivos").serializeArray();
		var data 			= {};
		
		for (var i in dataserialize) {
			//COLOCAR EN EL IF LOS COMBOS SELECT2, PARA QUE PUEDA TOMAR TODOS LOS VALORES
			if( dataserialize[i].name == 'filtrocat' || dataserialize[i].name == 'filtrosubcat' || 
				dataserialize[i].name == 'proyectof' || dataserialize[i].name == 'filtromod'  || 
				dataserialize[i].name == 'prioridadf' || dataserialize[i].name == 'solicitantef'  || 
				dataserialize[i].name == 'estadof' || dataserialize[i].name == 'asignadoaf'  || 
				dataserialize[i].name == 'unidadejecutoraf'	|| dataserialize[i].name == 'filtromarca' ){
				data[dataserialize[i].name] = $("#"+dataserialize[i].name).select2("val");
			}else{
				data[dataserialize[i].name] = dataserialize[i].value;	
			}		
		}
		
		data = JSON.stringify(data);	
		//console.log(data);
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/actividadesback.php',
			data: { 
				'oper'	: 'guardarfiltros',
				'data'	: data
			},
			success: function (response) {			
				// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
				tablaactivos.ajax.reload(null, false);
				dialogfiltrosmasivos.dialog("close");						
			}
		});
	}
	function limpiarFiltrosMasivos(){	
		$.get( "controller/actividadesback.php?oper=limpiarFiltrosMasivos");
		var dataserialize = $("#form_filtrosmasivos").serializeArray();
		for (var i in dataserialize) {
			$("#"+dataserialize[i].name).val(null).trigger("change");
			tablaactivos.ajax.reload(null, false);
			dialogfiltrosmasivos.dialog("close");	
		}
	}
		
//});




