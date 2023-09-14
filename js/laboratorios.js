var incidenteselect = '';
var tablaincidentes;
var filasSeleccionadas = new Array();
var valoritems = 0;
$(document).ready(function() {
	$('.switch-sidebar-mini input').change();
	//Permite ver el nombre del campo
	$(".select2").removeClass("is-empty");
	
	//VALIDAR CAMPOS 
	$(".form-control").bind("keydown blur",function(e){validCampos(this);});
	
	$("#nuevo").click(function(){
		location.href = 'laboratorio.php';
	});
	
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tablaincidentes.ajax.reload();
        ajustarTablas();
		getNotificaciones();			  
    });
	
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tablaincidentes").DataTable().search("").draw();
		$('#tablaincidentes_wrapper thead input').val('').change();
	});

	function ajustarTablas(){
		if (screen.width > 1024) {
			//console.log('screen.width: '+screen.width);
			$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
			$('.dataTables_scrollHead table').width('100%');
			$('.dataTables_scrollBody table').width('100%');
		}
	}	
	//AJUSTAR DATATABLES
	$('.nav-control').on('click', function(e){
		ajustarTablas();
	});
	
	var height = screen.height - 200;
	$('#DZ_W_Filtros_Body').height(height);
	
    //HEADER
	$('#tablaincidentes thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');
				if(title == 'Estado'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Creación'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Cierre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 120px" /> ');
				}else if(title == 'Solicitante'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Dep. / Grupo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Nombre de Activo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 300px" /> ');
				}else if(title == 'Cliente' || title == 'Proyecto' || title == 'Categoría' || title == 'Ubicación' || title == 'Tipo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
				}else if(title == 'Asignado a' || title == 'Estado Equipo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
				}else{
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		}else if (title == 'Acción') {
			var ancho = '50px';
		}else if(title == '-'){
			$(this).html( '<input id="chkSelectAll" type="checkbox"  value="A11|" /> ' );
		}
		$(this).width(ancho);
	});
    
    var cvisible = false;
	
	if(nivel == 1 || nivel == 2 || nivel == 3){
		cvisible = true;
	}
	
	tablaincidentes = $("#tablaincidentes").DataTable({
		scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
        searching: true,
		//serverSide: true,
        //serverMethod: 'post',
        stateLoadParams: function (settings, data) {
            const{columns}=data
		    $('th#cid input').val(columns[2]['search']['search']);
		    $('th#cestado input').val(columns[3]['search']['search']);
		    $('th#ctitulo input').val(columns[4]['search']['search']);
		    $('th#csolicitante input').val(columns[5]['search']['search']);
		    $('th#ccreacion input').val(columns[6]['search']['search']);
		    $('th#cempresas input').val(columns[7]['search']['search']);
	        $('th#cdepartamentos input').val(columns[8]['search']['search']);
		    $('th#ccliente input').val(columns[9]['search']['search']);
		    $('th#cproyectos input').val(columns[10]['search']['search']);
		    $('th#casignadoa input').val(columns[11]['search']['search']);
			$('th#cserie input').val(columns[12]['search']['search']);
			$('th#cmarca input').val(columns[13]['search']['search']);
			$('th#cmodelo input').val(columns[14]['search']['search']);
			$('th#cprioridad input').val(columns[15]['search']['search']);
			$('th#cfechacierre input').val(columns[16]['search']['search']);
			$('th#cestadoant input').val(columns[17]['search']['search']);
			$('th#cestadoequipo input').val(columns[18]['search']['search']);
        },
		select: { style: 'multi' },
		ajax : { url	: "controller/laboratorioback.php?oper=incidentes" },
		columns	: [
			{ 	"data": "check"},			//0
			{ 	"data": "acciones"},		//1
			{ 	"data": "id"},				//2
			{ 	"data": "estado" },			//3
			{ 	"data": "titulo"},			//4
			{ 	"data": "solicitante"},		//5
			{ 	"data": "fechacreacion"},	//6 	
			{ 	"data": "idempresas"},		//7
			{ 	"data": "iddepartamentos"},	//8
			{ 	"data": "idclientes" },		//9
			{ 	"data": "idproyectos" },	//10 		
			{ 	"data": "asignadoa" },		//11 	
			{ 	"data": "serie" },			//12
			{ 	"data": "marca" },			//13
			{ 	"data": "modelo" },			//14
			{ 	"data": "idprioridad" },	//15
			{ 	"data": "fechacierre" },	//16
			{ 	"data": "estadoant" },		//17
			{ 	"data": "estadoequipo" }	//18
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
				width	: '200px'
			},
			{
				targets	: [ 5,6 ],
				width	: '80px'
			},
			{
				targets	: [ 9 ],
				width	: '120px'
			},
			{
				targets		: [ 7,17],
				visible		: false,
				searchable	: false
			},
			{
				targets		: [4, 5, 14,],
				className	: "dt-left"
			}
		],
		language: {
			url: "js/Spanish.json",
		},
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]]
		,/* rowCallback: function( row, data) {			
			 
		}, */initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
			this.api().columns().every( function () {
                var that = this; 
                $( 'input[type="text"]', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                } );
            });
			//OCULTAR LOADER
			$('#preloader').css('display','none');
			quitarSelecciones();
	    },
	    stateSaveParams: function(settings, data) {
            for (var i = 0, ien = data.columns.length; i < ien; i++) {
                delete data.columns[i].visible;
            }
        },
		dom: '<"toolbarU toolbarDT">Blfrtip'		
	});
	
	function generarSalidas(){
		var id = filasSeleccionadas;	
		if(id.length == 0){
			demo.showSwal('error-message','','Registros no Seleccionados');
			return;
		}
		if(!id[1]){
			id = id[0];
		}else{
			id = id;
		}
		$.ajax({
			type: 'post', 
			url: 'controller/laboratorioback.php',
			data: { 
				'oper'	: 'generarSalidas',
				'id'	: id,
				'data' 	: data
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
				cerrarDialogIncidenteMasivo();
			},
			success: function (response) {
				$('#overlay').css('display','none');
				demo.showSwal('success-message','Buen trabajo','Registros actualizados satisfactoriamente');			
				// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
				tablaincidentes.ajax.reload(null, false);
			},
			error: function () {
				$('#overlay').css('display','none');				
				demo.showSwal('error-message','ERROR!','Ha ocurrido un error al actualizar los Registro, intente mas tarde');
			}
		});
	}

	$('#limpiarFiltros').click(function(){
		tablaincidentes.state.clear();
		window.location.reload();
	});
	
	$('button.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        var column = tablaincidentes.column($(this).attr('data-column'));
        var ocultar = $(this).attr('data-column');
        column.visible(!column.visible());
        if (column.visible()) {
            $("#c" + $(this).attr('data-column')).css('background-color', '#36C95F');
            $.ajax({
                type: 'post',
                url: 'controller/laboratorioback.php',
                data: {
                    'oper': 'guardarcolumnaocultar',
                    'tipo': 'eliminar',
                    'columna': ocultar
                },
                beforeSend: function() {},
                success: function(response) {
                    console.log("lista de columnas actualizada guardada");
                    verificarbotonocultarcolumna();
                }
            });
        } else {
            $("#c" + $(this).attr('data-column')).css('background-color', 'rgb(156 218 173)');
            $.ajax({
                type: 'post',
                url: 'controller/laboratorioback.php',
                data: {
                    'oper': 'guardarcolumnaocultar',
                    'tipo': 'agregar',
                    'columna': ocultar
                },
                beforeSend: function() {},
                success: function(response) {
                    console.log("lista de columnas actualizada guardada");
                    verificarbotonocultarcolumna();
                }
            });
        }
        $('#tablaincidentes').width('100%');
        $('.dataTables_scrollHead table').width('100%');
    });
	
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
	
	$('#tablaincidentes tbody').on('click', 'tr', function () {
		$(this).closest('tr').toggleClass('selected');
		var json = tablaincidentes.rows('.selected').data();
		filasSeleccionadas = [];
		for (var i=0; i<json.length; i++)
			filasSeleccionadas.push(json[i].id);	
			valoritems = i++; 
	});
	
	$("#tablaincidentes tbody").on('dblclick','tr',function(){ 
		var id = $(this).attr("id");
		location.href="laboratorio.php?id="+id;
	})
	
	// AL CARGARSE LA TABLA
	$('#tablaincidentes').on( 'draw.dt', function () {	
		//evaluarancho('tablaincidentes')
		ajustarDropdown();
    	ajustarTablas()
			
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
		buscarcolumnasocultas();
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();	 
    });
	
	$('#tablaincidentes').on('processing.dt', function (e, settings, processing) {
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })
	
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
	
	function buscarcolumnasocultas(){
		 
        $.ajax({
            type: 'post',
            url: 'controller/laboratorioback.php',
            data: {
                'oper': 'consultarcolumnas',
            },
            beforeSend: function() {},
            success: function(response) { 
				if (response != '0') { 
                    var columnas = response.split(',');
                    for (var i = 0; i < columnas.length; i++) {
                        var column = tablaincidentes.column(columnas[i]);
                        column.visible(false);
                        $("#c" + columnas[i]).css('background-color', 'rgb(156 218 173)');
                        verificarbotonocultarcolumna();
                    }
                }
            }
        });
    }
    
    function verificarbotonocultarcolumna(){
		$('#preloader').css('display','block');
        $.ajax({
            type: 'post',
            url: 'controller/laboratorioback.php',
            data: {
                'oper': 'consultarcolumnas',
            },
            beforeSend: function() {},
            success: function(response) {
				  $('#preloader').css( 'display','none');
                if (response != '0') {
                    $("#botonocultarcolumnas").removeClass('btn-success');
                    $("#botonocultarcolumnas").addClass('btn-warning');
                }else{
                    $("#botonocultarcolumnas").addClass('btn-success');
                    $("#botonocultarcolumnas").removeClass('btn-warning');
                }
            }
        });
    }
		
	// ELIMINAR INCIDENTE
	function eliminarincidente(id,nombre){
		var idincidente = id;
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el registro "+nombre+"?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm.value === true){
					$.get( "controller/laboratorioback.php?oper=eliminarincidentes", 
					{ 
						onlydata : "true",
						idincidente : idincidente
					}, function(result){
						if(result == 1){
							swal('Buen trabajo','Registro eliminado satisfactoriamente','success');		
							// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
							tablaincidentes.ajax.reload(null, false);
							tablasalidas.ajax.reload(null, false);
						} else {
							swal('ERROR!','Ha ocurrido un error al eliminar el registro, intente más tarde','error');
						}
					});
				}
			}, function (isRechazo){
				// NADA
			}
		);
	} 

	//CALENDARIO
	$('#fechacierre, #fechacertificar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:false });
	$('#fecharesolucion, #fechaentrada_editar').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm:ss', switchOnClick:true, time:true });
	$('#fechareal, #fechaentrada').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false  });
	$('#horacreacion').bootstrapMaterialDatePicker({switchOnClick:true, date:false, format : 'HH:mm' });
	
	$('#desdef').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', clearText: 'Limpiar', clearButton: true  }).on('change',function(){
//	    var fechadesdeoculto = $('#calendarhidendesde').val();
//	    $('#desdef').val(fechadesdeoculto);
	});	
	$('#hastaf').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', clearText: 'Limpiar', clearButton: true  }).on('change',function(){
//	    var fechahastaoculto = $('#calendarhidenhasta').val();
//	    $('#hastaf').val(fechahastaoculto);
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
});
 
//***** ***** ***** EXPORTAR ***** ***** ***** //
function exportar(tipo){
	var misCookies = document.cookie;
	var listaCookies = misCookies.split(";");
	var micookie = '';
	for (i in listaCookies) {
		busca = listaCookies[i].search("sistema");
		if (busca > -1) {
			micookie=listaCookies[i];
		}
	}
	igual = micookie.indexOf("=");
	sistema = micookie.substring(igual+1);
	var localS = localStorage.getItem('DataTables_tablaincidentes_/'+sistema+'/laboratorios.php');
	localS = jQuery.parseJSON(localS);
	//PARAMETROS
	param  = "bid=" + localS['columns'][2]['search']['search'];
	param += "&bestado=" + localS['columns'][3]['search']['search'];
	param += "&btitulo=" + localS['columns'][4]['search']['search'];
	param += "&bsolicitante=" + localS['columns'][5]['search']['search'];
	param += "&bcreacion=" + localS['columns'][6]['search']['search'];
	param += "&bempresa=" + localS['columns'][7]['search']['search'];
	param += "&bdepartamento=" + localS['columns'][8]['search']['search'];
	param += "&bcliente=" + localS['columns'][9]['search']['search'];
	param += "&bproyecto=" + localS['columns'][10]['search']['search'];
	param += "&basignadoa=" + localS['columns'][11]['search']['search'];
	param += "&bserie=" + localS['columns'][12]['search']['search'];	
	param += "&bmarca=" + localS['columns'][13]['search']['search'];
	param += "&bmodelo=" + localS['columns'][14]['search']['search'];
	param += "&bprioridad=" + localS['columns'][15]['search']['search'];
	param += "&bcierre=" + localS['columns'][16]['search']['search'];
	 
	window.open ("reportes/laboratorioexportar.php?" + param, "_blank"); 
}

//***** ***** ***** SOLICITUDES DE SERVICIO ***** ***** ***** //
	//var dialogSol;
	/* var options = {
		url  : 'elFinder/php/connector.minimal.laboratorio.php?idincidente=0',
		lang : 'es',
		rememberLastDir: false,
		reloadClearHistory: true,
		useBrowserHistory: true
	} */	
	// var elfInstance = $('#elfinder').elfinder(options).elfinder('instance');
	// elfInstance.bind('upload', function(event) {
		// var url 		= event.data.added[0].url;
		// var arrUrl 		= url.split('/');
		// var nincidente 	= arrUrl[7];
		// var nimagen 	= arrUrl[8];
		// var idcoment 	= arrUrl[9];	//Taller no tiene
		/*		
		$.ajax({
			  type: 'post',
			  url: 'controller/laboratorioback.php',
			  beforeSend: function() {
					$('#overlay').css('display','block');
				},
			  data: { 
				'oper'	   : 'notificacionAdjunto',
				'incidente': nincidente,
				'imagen'   : nimagen,
				'idcoment' : idcoment		 
			  },
			  success: function (response) {
				$('#overlay').css('display','none');				
				demo.showSwal('success-message','Archivo Adjunto','Notificación enviada');
			  },
			  error: function () {
				$('#overlay').css('display','none');
				demo.showSwal('error-message','ERROR!',response);
			  }
		});
		*/
		//tablaincidentes.ajax.reload(null, false);
	//});
	/* elfInstance.bind('remove', function(event) {
		tablaincidentes.ajax.reload(null, false);
	}); */
	
/* 	function abrirVentanaEvidencias(response,incidente) {
		var elfInstance = $('#elfinder').elfinder(options).elfinder('instance');
		elfInstance.bind('load change', function(event) { 
			console.log('event: '+event);
			elfInstance.exec('open', response);
		});
		$("#modalEvidencias").on('show.bs.modal', function(){
			elfInstance.exec('reload');
		});
		$('.elfinder-path-dir').attr('title',incidente);
		$('.elfinder-path-dir').html('Registro #'+incidente);
		$('.titulo-modal').text('Reportes de Servicios y Evidencias');
		$('#modalEvidencias').modal('show');
	} */
	$('#modalEvidencias').on('hidden.bs.modal', function(){
    console.log('paso')
         tablaincidentes.ajax.reload(null, false);
    });
	function abrirsolicitudes(incidente) {	
		var valid = true;
		if ( valid ) {
			$.ajax({
				  type: 'post',
				  url: 'controller/laboratorioback.php',
				  data: { 
					'oper': 	 'abrirSolicitudes',
					'incidente': incidente
				  },
				  success: function (response) {
					$('#fevidencias').attr('src','filegator/laboratorio.php#/?cd=laboratorio/'+incidente);
					$('#modalEvidencias').modal('show');
					$('#modalEvidencias .modal-lg').css('width','1000px');
					$('#idincidentesevidencias').val(incidente); 
					//abrirVentanaEvidencias(response,incidente);
					$('.titulo-evidencia').html('Laboratorio: '+incidente+' - Evidencia');
				  },
				  error: function () {
					demo.showSwal('error-message','ERROR!',response);
				  }
			   }); 
			}
		return valid;
	}
	
	var dirxdefecto = 'incidente';
	$('#fevidencias').attr('src','filegator/laboratorio.php#/?cd=%2F'+dirxdefecto); 
	
	/* dialogSol = $( "#dialog-form-sol" ).dialog({
		width: '50%', 
		maxWidth: 600,
		height: 'auto',
		//modal: true,
		fluid: true,
		resizable: false,
		autoOpen: false
	}); */

	/* function cerrarDialogSol() {
		//dialogSol.dialog('close');
		$('#dialog-form-sol').hide();
		tablaincidentes.ajax.reload(null, false);
		if(tablacomentario){
			tablacomentario.ajax.reload(null, false);
		}		
	} */
	
	function verCierres(){
		location.href="laboratoriocierres.php";
	}


