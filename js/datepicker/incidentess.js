$(document).ready(function() {
	
	var tablapacienteimg = $("#tablapacienteimg").DataTable({
		responsive: true,
		"scrollY": "500px",
		"ajax"		: {
			"url"	: "controller/incidentessback.php?oper=incidentes"
		},
		"columns"	: [
			{ 	"data": "acciones" },
			{ 	"data": "id" },
			{ 	"data": "estado" },
			{ 	"data": "titulo" },
			{ 	"data": "descripcion" },
			{ 	"data": "solicitante" },
			{ 	"data": "fechacreacion" },
			{ 	"data": "horacreacion" },
			{ 	"data": "proyecto" },
			{ 	"data": "idcategoria" },
			{ 	"data": "asignadoa" },
			{ 	"data": "sitio" },
			{ 	"data": "idprioridad" },
			{ 	"data": "fechacierre" },
			{ 	"data": "tieneEvidencias" }
			],
		"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		"columnDefs": [ //OCULTAR LA COLUMNA ID
			{
				"targets"	: [ 4, 14 ],
				"visible"	: false,
				"searchable": false
			},{
				"targets"	: [ 0,1,2 ],
				"width"		:  "70px"
			}
		],
		"language": {
			"url": "js/Spanish.json",
			"info": "Mostrando página _PAGE_ de _PAGES_"
		}
	});
	
	// ELIMINAR INCIDENTE
	function eliminarincidente(id,nombre){
		var idpaciente = id;
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el incidente "+nombre+"?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm){
					$.get( "controller/incidentessback.php?oper=eliminarincidentes", 
					{ 
						onlydata : "true",
						idpaciente : idpaciente
					}, function(result){
						if(result == 1){
							swal('Buen trabajo!','Incidente eliminado satisfactoriamente','success');		
							// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
							tablapacientes.ajax.reload(null, false);
						} else {
							swal('ERROR!','Ha ocurrido un error al eliminar el incidente, intente más tarde','error');
						}
					});

				}
			}, function (isRechazo){
				// NADA
			}
		);
	}
	
	// AL CARGARSE LA TABLA
	$('#tablapacienteimg').on( 'draw.dt', function () {		
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
				  url: 'controller/incidentesback.php',
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
	//COMENTARIO GRID
	var dialoGridComent = $( "#dialog-grid-coment" ).dialog({		
		width: '80%', 
		maxWidth: 600,
		height: 'auto',
		modal: true,
		fluid: true,
		resizable: true,
		autoOpen: false,
		//title: 'Lista de Materiales del Inventario',
		open: function(event, ui) { 
			initGridComent(grid_coment,pager_coment);
			urlMa = 'controller/incidentesback.php?oper=comentarios&id='+incidenteselect;
			$(grid_coment).jqGrid('setGridParam', { url: urlMa });
			$(grid_coment).jqGrid('clearGridData');
			$(grid_coment).trigger("reloadGrid");
		}
	});	

	function abrirGridComent(id) {
		incidenteselect = id;
		if (incidenteselect!=''){
			dialoGridComent.data('actId', incidenteselect);
			dialoGridComent.dialog( "open" );
		} else {
			demo.showSwal('error-message','ERROR!','Debe seleccionar un incidente');	
		}
	}
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

	function cerrarGridComent() {
		dialoGridComent.dialog('close');
	}
	/*
	function cerrarGridAdjunto() {
		dialoGridAdjunto.dialog('close');
	}*/

	function initGridComent(grid_coment,pager_coment) {
		jQuery(grid_coment).jqGrid({
			url: 'controller/incidentesback.php?oper=comentarios&id='+incidenteselect,
			datatype: "json",
			height: "auto",
			colNames:[' ', '#','Id','Comentario','Usuario','Visibilidad','Fecha','Adjunto'],
			colModel:[
				{name:'myac',index:'', width:10, fixed:true, sortable:false, resize:false, editable: false,
					formatter:'actions', search: false,
					formatoptions:{ 
						keys:true,
						//delbutton: false,//disable delete button							
						delOptions:{recreateForm: true, beforeShowForm:beforeDeleteCallback},
						editbutton:false, editOptions:{recreateForm: true, beforeShowForm:beforeEditCallback},
						afterSave: function (rowid, response, postdata, options) {
							jQuery(grid_coment).trigger('reloadGrid');
							jQuery(grid_coment).trigger('reloadGrid');
						}
					},
					reloadAfterSubmit: true
				},
				{name:'id',index:'id',width:40, search: false },
				{name:'idcomentario',index:'idcomentario',width:50,editable:true,search: false, hidden: true },
				{name:'comentario',index:'comentario',width:300,editable:true,edittype: "textarea",	searchoptions: {sopt: ["cn"]}},
				{name:'nombre',index:'nombre',width:150, searchoptions: {sopt: ["cn"]}},
				{name:'visibilidad',index:'visibilidad',width:80, searchoptions: {sopt: ["cn"]}},
				{name:'fecha',index:'fecha',width:80,formatter: "date", formatoptions: {  newformat: "Y-m-d" }},
				{name:'adjuntos',index:'adjuntos',width:150, search: false }
				
			], 
			autowidth: true,
			shrinkToFit: false,
			viewrecords : true,
			rowNum:10,
			rowList:[10,20,30],
			pager: pager_coment,
			altRows: true,
			gridview:true,
			height: "100%",
			multiboxonly: true,
			editurl: "controller/incidentesback.php?idincidente="+incidenteselect+"&tipo=delcomment",//nothing is saved
			/*ondblClickRow: function (rowid, iRow,iCol) {
				var row_id = $(grid_coment).getGridParam('selrow');
				jQuery(grid_coment).editRow(row_id, true);
			},*/
			loadComplete : function() {
				//VALIDAR USUARIO			
				if(nivel > 2){
					$('#grid-coment .ui-pg-div.ui-inline-del').hide();
					//$('#grid-mttoc .ui-pg-div.ui-inline-del').css('display','none');
				}
				var grid = $(this),
				iCol = getColumnIndexByName(grid,'myac'); // 'act' - name of the actions column
				grid.children("tbody")
					.children("tr.jqgrow")
					.children("td:nth-child("+(iCol+1)+")")
					.each(function(data) {					
						$("<div>",
							{
								title: "Adjuntos",
								mouseover: function() {
									$(this).addClass('ui-state-hover');
								},
								mouseout: function() {
									$(this).removeClass('ui-state-hover');
								},
								click: function(e) {
									var cliente = jQuery(grid_coment).jqGrid('getRowData', $(e.target).closest("tr.jqgrow").attr("id"));
									abrirGridAdjunto(incidenteselect,cliente.idcomentario);
								}
							}
						  ).css({"margin-left": "0px", float:"left"})
						   .addClass("ui-pg-div ui-inline-custom")
						   .append("<span class='icon-col blue fa fa-bullhorn'></span>")
						   //.append('<a class="glyphicon glyphicon-camera center blue" aria-hidden="true"></a>')
						   .appendTo($(this).children("div"));
					});
					
				var table = this;
				setTimeout(function(){
					//styleCheckbox(table);						
					updateActionIcons(table);
					updatePagerIcons(table);
					enableTooltips(table);
				}, 0);
			},
		});
		
		//navButtons
		jQuery(grid_coment).jqGrid('navGrid',pager_coment,
			{ 	//navbar options
				edit: false,
				editicon : 'edit',
				add: false,
				addicon : 'add',
				del: false,
				delicon : 'del',
				search: false,
				searchicon : 'search',
				refresh: true,
				refreshicon : 'refresh',
				view: false,
				viewicon : 'view',
			}
		);
				
		//enable search/filter toolbar	
		/*$(grid_coment).jqGrid('filterToolbar',{
			stringResult: true, searchOnEnter: false, defaultSearch: 'cn', ignoreCase: true, enableClear: false 
		});*/
		//if(grid_coment=="#grid-coment"){
			jQuery(grid_coment).jqGrid('navGrid',pager_coment,{edit:false,add:false,del:false,search: false,refresh: true}
			/*).navSeparatorAdd(pager_coment,{
				sepclass : "ui-separator",sepcontent: ""}
			).navButtonAdd(pager_coment,
				{
					caption:"Nuevo Comentario",
					title: "Click para Agregar Nuevo Comentario",
					buttonicon: "icon-actions blue fa fa-plus",
					onClickButton: function(){
						dialogComent.dialog( "open" );			
					},
					position:"last"
				}*/
			);
		//}
	};
	//COMENTARIO AGREGAR
	var dialogComent, form,
		comentario = $( "#comentario" ),
		allFields = $( [] ).add( comentario ),
		tips = $( ".validateTips" );

	dialogComent = $( "#dialog-form-coment" ).dialog({			
		width: '50%', // overcomes width:'auto' and maxWidth bug
		maxWidth: 500,
		height: 'auto',
		modal: true,
		fluid: true, //new option
		resizable: false,
		autoOpen: false
	});

	form = dialogComent.find( "form" ).on( "submit", function( event ) {
		event.preventDefault();
		agregarComentario();
	});
	function abrirdialogComent() {	
		dialogComent.dialog( "open" );
	}
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
				url: 'controller/incidentesback.php',
				data: { 
					'oper'	: 'agregarComentario',
					'id' : incidenteselect,
					'comentario' : coment,
					'visibilidad' : visibilidad
				},
				beforeSend: function() {
					$(".loader-maxia").show();
					$(".modal-container").addClass('swal2-in');
					dialogComent.dialog( "close" );
				},
				success: function (response) {
					if(response){					
						$('#comentario').val("");					
						demo.showSwal('success-message','Buen trabajo!','Comentario Almacenado Satisfactoriamente');			
						$(grid_coment).jqGrid('clearGridData');
						$(grid_coment).trigger( 'reloadGrid' );
						$(grid_coment_form).jqGrid('clearGridData');
						$(grid_coment_form).trigger( 'reloadGrid' );
						
					}else{
						demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Comentario, intente mas tarde');
					}
					$(".loader-maxia").hide();
					$(".modal-container").removeClass('swal2-in');				
				},
				error: function () {
					demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Comentario, intente mas tarde');
				}
			});
		}
		return;
	}

	function cerrarDialogComent() {
		dialogComent.dialog('close');
	}

	//***** ***** ***** BITACORA ***** ***** ***** //
	function initGridBitacora(grid_bitacora,pager_bitacora) {	
		jQuery(grid_bitacora).jqGrid({
			url: 'controller/incidentesback.php?oper=historial&id='+incidenteselect,
			datatype: "json",
			height: "auto",
			colNames:[' ', '#','Usuario','Fecha','Acción'],
			colModel:[
				{name:'myac',index:'', width:10, fixed:true, sortable:false, resize:false, editable: false,
					formatter:'actions', search: false,
					formatoptions:{ 
						keys:true,
						delbutton: false//disable delete button	
					}
				},
				{name:'id',index:'id',width:70, hidden: true},
				{name:'usuario',index:'usuario',width:120},
				{name:'fecha',index:'fecha',width:150 /*,formatter: "date",
					formatoptions: { newformat: "Y-m-d" }*/},
				{name:'accion',index:'accion',width:450}				
			], 
			viewrecords: true,
			rowNum:10,
			autowidth: true,
			rowList:[10,20,30],
			pager: pager_bitacora,
			altRows: true,
			gridview:true,
			height: "100%",
			autowidth: true,
			shrinkToFit: false		
		});			
		jQuery(grid_bitacora).jqGrid('navGrid',pager_coment,{edit:false,add:false,del:false,search: false,refresh: true});
	};

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
			jQuery(grid_selector)[0].triggerToolbar();
			e.preventDefault();
			e.stopPropagation();
		});
	}

	//	FORMULARIO INCIDENTE  //
	var dialogIncidente = $( "#dialog-form-incidentes" ).dialog({
		width: '67%', 
		maxWidth: 600,
		height: 'auto',
		modal: false,
		fluid: true,
		resizable: false,
		autoOpen: false,
		open: function(event, ui) {
			if(incidenteselect != ''){
				$('.gridComent').show();
				//COMENTARIOS
				initGridComent(grid_coment_form,pager_coment_form);
				urlMa = 'controller/incidentesback.php?oper=comentarios&id='+incidenteselect;
				$(grid_coment_form).jqGrid('setGridParam', { url: urlMa });
				$(grid_coment_form).jqGrid('clearGridData');
				$(grid_coment_form).trigger("reloadGrid");
				$('#form_incidentes .bnuevocoment').show();
				//HISTORIAL
				if(nivel == 1 || nivel == 2 || nivel == 3){
					$('.gridBit').show();
					initGridBitacora(grid_bitacora_form,pager_bitacora_form);
					urlBi = 'controller/incidentesback.php?oper=historial&id='+incidenteselect;
					$(grid_bitacora_form).jqGrid('setGridParam', { url: urlBi });
					$(grid_bitacora_form).jqGrid('clearGridData');
					$(grid_bitacora_form).trigger("reloadGrid");
				}
			}else{
				$('.gridComent, .gridBit').hide();
				$('#form_incidentes .bnuevocoment').hide();
			}
			
			var newWidth = $(grid_coment_form).closest(".ui-jqgrid").parent().width();
			$(grid_coment_form).jqGrid("setGridWidth", newWidth, true);
			var newWidth = $(grid_bitacora_form).closest(".ui-jqgrid").parent().width();
			$(grid_bitacora_form).jqGrid("setGridWidth", newWidth, true);
			
			$("#titulo").focus();
		}
	});

	function editarMasiva(){
		var id = jQuery("#grid-mttoc").jqGrid('getGridParam','selarrrow');	
		//console.log(id);
		//console.log(id.length);
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
			/*
			//COMENTARIOS
			initGridComent(grid_coment_form,pager_coment_form);
			urlMa = 'controller/incidentesback.php?oper=comentarios&id='+incidenteselect;
			$(grid_coment_form).jqGrid('setGridParam', { url: urlMa });
			$(grid_coment_form).jqGrid('clearGridData');
			$(grid_coment_form).trigger("reloadGrid");
			$('.bnuevocoment').show();
			//HISTORIAL
			initGridBitacora(grid_bitacora_form,pager_bitacora_form);
			urlBi = 'controller/incidentesback.php?oper=historial&id='+incidenteselect;
			$(grid_bitacora_form).jqGrid('setGridParam', { url: urlBi });
			$(grid_bitacora_form).jqGrid('clearGridData');
			$(grid_bitacora_form).trigger("reloadGrid");
			*/
		}else{
		//if(id!=''){
			//$('#tituloincidente').html("Incidente N° "+id);
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
				url: 'controller/incidentesback.php',
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
					//console.log(response);
					demo.showSwal('success-message','Buen trabajo!','Incidente almacenado satisfactoriamente');			
					$(grid_selector).jqGrid('clearGridData');
					$(grid_selector).trigger( 'reloadGrid' );
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
			url: 'controller/incidentesback.php',
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
					$(grid_selector).jqGrid('clearGridData');
					$(grid_selector).trigger( 'reloadGrid' );
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
			url: 'controller/incidentesback.php',
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
				$(grid_selector).jqGrid('clearGridData');
				$(grid_selector).trigger( 'reloadGrid' );								
			},
			error: function () {
				demo.showSwal('error-message','ERROR!','Ha ocurrido un error al Revertida la Fusión, intente mas tarde');
			}
		});
		$(".loader-maxia").hide();
		$(".modal-container").removeClass('swal2-in');
	}

	function vermas(){
		$("div.vermas").toggle();
		$("#btn-vermas span").addClass(function(index, currentClass){
			if (currentClass== "glyphicon glyphicon-plus") {
				$("#btn-vermas span").removeClass("glyphicon glyphicon-plus");
				$.post( "controller/incidentesback.php?oper=comentariovisto", { id:incidenteselect });
			}else{
				$("#btn-vermas span").addClass("glyphicon glyphicon-plus");
			}
		});
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
			url: 'controller/incidentesback.php',
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
					if (obj.chkDescripcion == 'on') 
						$("#chkDescripcion").prop('checked', true);
					else
						$("#chkDescripcion").prop('checked', false);
					
					if (obj.chkSolicitante == 'on') 
						$("#chkSolicitante").prop('checked', true);
					else
						$("#chkSolicitante").prop('checked', false);
					
					if (obj.chkFechaCreacion == 'on') 
						$("#chkFechaCreacion").prop('checked', true);
					else
						$("#chkFechaCreacion").prop('checked', false);
					
					if (obj.chkHoraInicio == 'on') 
						$("#chkHoraInicio").prop('checked', true);
					else
						$("#chkHoraInicio").prop('checked', false);
					
					if (obj.chkProyecto == 'on') 
						$("#chkProyecto").prop('checked', true);
					else
						$("#chkProyecto").prop('checked', false);
					
					if (obj.chkCategoria == 'on') 
						$("#chkCategoria").prop('checked', true);
					else
						$("#chkCategoria").prop('checked', false);
					
					if (obj.chkAsignadoA == 'on') 
						$("#chkAsignadoA").prop('checked', true);
					else
						$("#chkAsignadoA").prop('checked', false);
					
					if (obj.chkSitio == 'on') 
						$("#chkSitio").prop('checked', true);
					else
						$("#chkSitio").prop('checked', false);
					
					if (obj.chkSerie == 'on') 
						$("#chkSerie").prop('checked', true);
					else
						$("#chkSerie").prop('checked', false);
					
					if (obj.chkMarca == 'on') 
						$("#chkMarca").prop('checked', true);
					else
						$("#chkMarca").prop('checked', false);
					
					if (obj.chkModalidad == 'on') 
						$("#chkModalidad").prop('checked', true);
					else
						$("#chkModalidad").prop('checked', false);
					
					if (obj.chkPrioridad == 'on') 
						$("#chkPrioridad").prop('checked', true);
					else
						$("#chkPrioridad").prop('checked', false);
					
					if (obj.chkFechaCierre == 'on') 
						$("#chkFechaCierre").prop('checked', true);
					else
						$("#chkFechaCierre").prop('checked', false);
					
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
			url: 'controller/incidentesback.php',
			data: { 
				'oper'	: 'guardarfiltros',
				'data'	: data
			},
			success: function (response) {			
				$(grid_selector).jqGrid('setGridParam',{url:'controller/incidentesback.php?oper=incidentes&data='+data});
				$(grid_selector).jqGrid('clearGridData');
				$(grid_selector).trigger("reloadGrid");
				verificarColumnas();
				dialogfiltrosmasivos.dialog("close");						
			}
		});
	}
	function limpiarFiltrosMasivos(){	
		$.get( "controller/incidentesback.php?oper=limpiarFiltrosMasivos");
		var dataserialize = $("#form_filtrosmasivos").serializeArray();
		for (var i in dataserialize) {
			$("#"+dataserialize[i].name).val(null).trigger("change");
			$(grid_selector).jqGrid('setGridParam',{url:'controller/incidentesback.php?oper=incidentes'});
			$(grid_selector).jqGrid('clearGridData');
			$(grid_selector).trigger("reloadGrid");
		}
		//limpiarFormulario("#form_filtrosmasivos");
		//$("#form_filtrosmasivos").trigger("reset");
		//dialogfiltrosmasivos.dialog("close");
	}
		
});

