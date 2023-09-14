var incidenteselect = '';
var tablaincidentes;
var filasSeleccionadas = new Array();

//$(document).ready(function() {
	$('.switch-sidebar-mini input').change();
	//Permite ver el nombre del campo
	$(".select2").removeClass("is-empty");
	
	//VALIDAR CAMPOS 
	$(".form-control").bind("keydown blur",function(e){validCampos(this);});
	
	// Setup - add a text input to each header cell
    $('#tablaincidentes thead th').each( function () {
        var title = $(this).text();
		var ancho1 = $(this).width();
		var ancho2 = ($(this).width() * 0.4).toFixed(0);
		if ((title!=='') && (title!='-')) 
			$(this).html( '<input type="text" placeholder="'+title+'" size=8 /> ' );
		else if (title=='-') 
			$(this).html( '<input id="chkSelectAll" class="fac fac-checkbox fac-white" type="checkbox" value="A11|" />' );
		if ((title=='Titulo') && (title!='-')) 
			$(this).html( '<input type="text" placeholder="'+title+'" size=32 /> ' );
		
		$(this).width(ancho1);
    });
	
	if(nivel == 1 || nivel == 2){
		var cvisible = true;
	}else{
		var cvisible = false;
	}
	
	tablaincidentes = $("#tablaincidentes").DataTable({
		scrollX: true,
		destroy: true,
		scrollY: '54vh',
		scrollCollapse: true,
		searching: true,
		stateSave: false,
		serverSide: true, //Busca en el servidor los registros de 10 en 10
		select: { style: 'multi' },
		colReorder: true,
		fixedHeader: true,
		processing: true, 
		bAutoWidth: false,
		ordering: false,
		ajax : { url	: "controller/incidentesback.php?oper=incidentes" },
		columns	: [
			{ 	"data": "check"},			//0
			{ 	"data": "acciones"},		//1
			{ 	"data": "id"},				//2
			{ 	"data": "estado" },			//3
			{ 	"data": "titulo"},			//4
			{ 	"data": "solicitante"},		//5
			{ 	"data": "fechacreacion"},	//6
			{ 	"data": "horacreacion" },	//7		
			{ 	"data": "idempresas"},		//8
			{ 	"data": "iddepartamentos"},	//9
			{ 	"data": "idclientes" },		//10
			{ 	"data": "idproyectos" },	//11		
			{ 	"data": "idcategoria" },	//12
			{ 	"data": "idsubcategoria"},	//13		
			{ 	"data": "asignadoa" },		//14
			{ 	"data": "sitio" },			//15
			{ 	"data": "modalidad" },		//16
			{ 	"data": "serie" },			//17
			{ 	"data": "marca" },			//18
			{ 	"data": "modelo" },			//19
			{ 	"data": "idprioridad" },	//20
			{ 	"data": "fechacierre" }		//21
			],
		rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		columnDefs: [ //OCULTAR LA COLUMNA Descripcion
			{
				orderable	: false,
				className	: 'select-checkbox',
				searchable	: false,
				visible		: cvisible,
				targets		: 0
			},
			{
				orderable	: false,
				targets		: 1,
				searchable	: false,
			},
			{
				targets	: 2,
				width	: '80px' 
			},
			{
				targets	: 3,
				width	: '140px' 
			},
			{
				targets	: [ 4 ],
				width	: '80px'
			},
			{
				targets	: [ 5,6 ],
				width	: '80px'
			},
			{
				targets		: [ 8 ],
				visible		: false,
				searchable	: false
			},
			{
				targets		: [ 9 ],
				width	: '140px'
			},
			{
				targets		: [4, 5, 12, 13, 14, 15, 16],
				className	: "dt-left"
			}
		],		
		fixedColumns: true,
		language: {
			url: "js/Spanish.json",
		},
		lengthMenu: [[25, 50, 100], [25, 50, 100]],
		initComplete: function () {
			var t = 0;
			this.api().columns().every( function () {
                var column = this;
				/*
				var titles = ["Estado","Solicitante","Categoría","SubCategoría","Proyecto","Asignado","Sitio","Modalidad","Prioridad"];
				if (column[0]==3 || column[0]==5 || column[0]==8 || column[0]==9 || column[0]==10 || column[0]==11 || column[0]==12 || column[0]==13 || column[0]==17) {
					var select = $('<select id="cmb' + titles[t] + '"><option value="" selected>' + titles[t] + '</option></select>')
						.appendTo( $(column.header()).empty() )
						.on( 'change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);	 
							column
								.search( val ? val : '', true, false )
								.draw();
						} );
					column.data().unique().sort().each( function ( d, j ) {
						select.append( '<option value="'+d+'">'+d+'</option>' )
					} );
					$("#cmb" + titles[t]).select2({
							placeholder: titles[t],
							allowClear: true
						});
					t++;
				}
				*/
            } );
			quitarSelecciones();
			//tablaincidentes.columns.adjust();			
        }		
	});
	
	$('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
		var column = tablaincidentes.column( $(this).attr('data-column') );
		column.visible( ! column.visible() );
		if (column.visible()){
			$("#c"+$(this).attr('data-column')).css('background-color', '#267DBD');
		} else {
			$("#c"+$(this).attr('data-column')).css('background-color', 'red');
		}
    } );
	
	tablaincidentes.columns().every( function () {
        var that = this;
		$( 'input', this.header() ).keypress(function (event) {
			if (this.value!='A11|') {
				if ( event.which == 13 ) {
					if ( that.search() !== this.value ) {
						that
							.search( this.value )
							.draw();
					}
				}
			}
        });	
    });
	
	$("#tablaincidentes tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		abrirdialogIncidenteEditar(id);		
	});
	
	$('#tablaincidentes tbody').on('click', 'tr', function () {
		$(this).closest('tr').toggleClass('selected');
		var json = tablaincidentes.rows('.selected').data();
		filasSeleccionadas = [];
		for (var i=0; i<json.length; i++)
			filasSeleccionadas.push(json[i].id);		
	});
	
	// AL CARGARSE LA TABLA
	$('#tablaincidentes').on( 'draw.dt', function () {		
		// DAR FUNCIONALIDAD AL BOTON ELIMINAR
        $('.boton-eliminar').each(function(){
			var id = $(this).attr("data-id");
			var nombre = $(this).parent().parent().next().next().next().html();
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
		for (var i=6; i<=18; i++) {
			var column = tablaincidentes.column( i );
			if (column.visible()){
				$("#c"+i).css('background-color', '#267DBD');
			} else {
				$("#c"+i).css('background-color', 'red');
			}
		}
    });
	
	$('#chkSelectAll').click(function(){
		if($("#chkSelectAll").is(':checked'))
			seleccionarTodas();
		else
			quitarSelecciones();
	});
	
	function seleccionarTodas() {
		$('#tablaincidentes > tbody  > tr').each(function() {
			$(this).addClass('selected');
		});
		$(this).closest('tr').toggleClass('selected');
		var json = tablaincidentes.rows('.selected').data();
		filasSeleccionadas = [];
		for (var i=0; i<json.length; i++)
			filasSeleccionadas.push(json[i].id);
	}
	
	function quitarSelecciones() {
		$('#tablaincidentes > tbody  > tr').each(function() {
			$(this).removeClass('selected');
		});
		filasSeleccionadas = [];
	}
		
	// ELIMINAR INCIDENTE
	function eliminarincidente(id,nombre){
		var idincidente = id;
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
					$.get( "controller/incidentesback.php?oper=eliminarincidentes", 
					{ 
						onlydata : "true",
						idincidente : idincidente
					}, function(result){
						if(result == 1){
							swal('Buen trabajo!','Incidente eliminado satisfactoriamente','success');		
							// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
							tablaincidentes.ajax.reload(null, false);
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
	
	// ELIMINAR COMENTARIOS
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
				if (isConfirm){
					$.get( "controller/incidentesback.php?oper=eliminarcomentarios", 
					{ 
						onlydata : "true",
						idcomentario : idcomentario
					}, function(result){
						if(result == 1){
							swal('Buen trabajo!','Comentario eliminado satisfactoriamente','success'); 
							tablacomentario.ajax.reload(null, false);
						} else if(result == 2){
						    swal('ERROR!','No tiene permisos para eliminar este comentario','error');
						} else {
							swal('ERROR!','Ha ocurrido un error al eliminar el comentario, intente más tarde','error');
						}
					});
				}
			}, function (isRechazo){
				// NADA
			}
		);
	}
	
	//***** ***** ***** SOLICITUDES DE SERVICIO ***** ***** ***** //
	var dialogSol;
	var options = {
		url  : 'elFinder/php/connector.minimal.incidentes.php',
		lang : 'es',
		rememberLastDir: false
	}	
	var elfInstance = $('#elfinder').elfinder(options).elfinder('instance');
	elfInstance.bind('upload', function(event) {
		var url 		= event.data.added[0].url;
		var arrUrl 		= url.split('/');
		var nincidente 	= arrUrl[7];
		var nimagen 	= arrUrl[8];
		$.ajax({
			  type: 'post',
			  url: 'controller/incidentesback.php',
			  data: { 
				'oper': 	 'notificacionAdjunto',
				'incidente': nincidente,
				'imagen': 	 nimagen,
			  },
			  success: function (response) {
				demo.showSwal('success-message','Archivo Adjunto','Notificación enviada');
			  },
			  error: function () {
				demo.showSwal('error-message','ERROR!',response);
			  }
		   });
	});

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
		//modal: true,
		fluid: true,
		resizable: false,
		autoOpen: false
	});

	function cerrarDialogSol() {
		dialogSol.dialog('close');
		$('#dialog-form-sol').hide();
		tablaincidentes.ajax.reload(null, false);
		if(tablacomentario){
			tablacomentario.ajax.reload(null, false);
		}		
	}

	//CALENDARIO
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false });
	$('#fecharesolucion').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true });
	$('#fechacreacion, #fechareal').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false  });
	$('#horacreacion').bootstrapMaterialDatePicker({switchOnClick:true, date:false, format : 'HH:mm' });
	
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
//});


