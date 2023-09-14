$(document).ready(function() {
    $('.switch-sidebar-mini input').change();
	$('#desdef').bootstrapMaterialDatePicker({
		weekStart:0, switchOnClick:false, time:false,  format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
	});	
	$('#hastaf,#fechadevolucionf').bootstrapMaterialDatePicker({
		weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
	});	
	
	$("#nuevopermiso").click(function(){
		location.href = 'flota.php';
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
				}else if(title == 'Fecha solicitud desde'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 180px" /> ');
				}else if(title == 'Fecha solicitud hasta'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 180px" /> ');
				}else if(title == 'Cierre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 120px" /> ');
				}else if(title == 'Solicitante'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Dep. / Grupo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Dep. / Grupo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Motivo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 300px" /> ');
				}else if(title == 'Cliente' || title == 'Proyecto' || title == 'Categoría' || title == 'Ubicación' || title == 'Tipo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
				}else if(title == 'Serial 1' || title == 'Marca' || title == 'Modelo' || title == 'Prioridad'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Id'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 60px" /> ');
				}else if(title == 'Sub Categoría' || title == 'Asignado a' || title == 'Estado Ant.' ){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
				}else if(title == 'Fecha de devolución'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 180px" /> ');
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

	var cvisible  = false;	
    var cvisibled = false;
	
	if(nivel == 1 || nivel == 2 || nivel == 3){
		cvisible = true;
	}
	if(nivel != 7){
		cvisibled = true;
	}
	$('#preloader').css('display','block');
	/*tabla*/
	tablaincidentes = $("#tablaincidentes").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
		serverSide: true,
        serverMethod: 'post',
	    ajax: {
	        url: "controller/flotasback.php?oper=incidentes",
	    },
	    columns	: [
			{ 	"data": "acciones" },
			{ 	"data": "id" },
			{ 	"data": "estado" },
			{ 	"data": "titulo" },
			{ 	"data": "solicitante" },
			{ 	"data": "fechasolicituddesde" },
			{ 	"data": "fechasolicitudhasta" },
			{ 	"data": "asignadoa" },
			{ 	"data": "sitio" },
			{ 	"data": "serie" },
			{ 	"data": "marca" },
			{ 	"data": "modelo" },
			{ 	"data": "fecharesolucion" }
		],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
	    columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
			{
	            targets: [2, 3, 4],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width	: '200px' },
			{ targets	: 4, width	: '200px' },
			{ targets	: 5, width	: '200px' },
			{ targets	: 6, width	: '200px' },
			{ targets	: 7, width	: '200px' },
			{ targets	: 9, width	: '200px' },
			{ targets	: 10, width	: '200px' },
			{ targets	: 11, width	: '200px' },
			{ targets	: 12, width	: '200px' },
			
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],rowCallback: function( row, data) {			
	        
		},
		initComplete: function() {		
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
	/*fin tabla*/
	// AL CARGARSE LA TABLA
	$('#tablaincidentes tbody').on('click', 'tr', function () {
		$(this).closest('tr').toggleClass('selected');
		var json = tablaincidentes.rows('.selected').data();
		filasSeleccionadas = [];
		for (var i=0; i<json.length; i++)
			filasSeleccionadas.push(json[i].id);	
	});

	$("#tablaincidentes tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		location.href = "flota.php?id="+id;
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
                url: 'controller/flotasback.php',
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
                url: 'controller/flotasback.php',
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

	$('#tablaincidentes').on( 'draw.dt', function () {	
		//evaluarancho('tablaincidentes');
		ajustarDropdown();
		ajustarTablas();

		$('.boton-eliminar').each(function(){
			var id 	   = $(this).attr("data-id");
			var incidente = $("#tablaincidentes tr#"+id).find('td:nth-child(1)').html();
			$(this).on( 'click', function() {
				eliminarincidente(id,incidente);
			});
		});	
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
        console.log("cargo proceso");
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})

	$("#guardar-evidencia").on('click',function(){
		var idsolicitudesevidencias = $('#idsolicitudesevidencias').val();
		$.ajax({
			type: 'post',
			url: 'controller/flotasback.php',
			beforeSend: function() {
				$('#preloader').css('display','block');
			},
			data: { 
				'oper'	   : 'notificacionAdjunto',
				'incidente': idsolicitudesevidencias//,
				//'imagen'   : nimagen,
				//'idcoment' : idcoment		 
			},
			success: function (response) {
				$('#preloader').css('display','none');
				tablaincidentes.ajax.reload(null, false);
				//demo.showSwal('success-message','Archivo Adjunto','Notificación enviada');
				notification("Notificación enviada","Archivo Adjunto",'success');
			},
			error: function () {
				$('#preloader').css('display','none');
				notification("Ha ocurrido un error","Error",'error');
			}
		});
		//demo.showSwal('success-message','Archivos Adjuntos','Satisfactoriamente');
		tablaincidentes.ajax.reload(null, false);
		$('#modalEvidencias').modal('hide');
		//$(".progress").css("display","none");
	});
	
	$('#chkSelectAll').click(function(){
		if($("#chkSelectAll").is(':checked')){
			//console.log("paso por aqui");
			seleccionarTodas();
		}else{
			//console.log("paso por else");
			quitarSelecciones();
		}
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
            url: 'controller/flotasback.php',
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
                        console.log(column.visible(false))
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
            url: 'controller/flotasback.php',
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

	var dirxdefecto = 'flota';
	$('#fevidencias').attr('src','filegator/flotas.php#/?cd=%2F'+dirxdefecto);
	function abrirsolicitudes(id) {
		  var valid = true;
		  if ( valid ) {
			$.ajax({
				  type: 'post',
				  url: 'controller/flotasback.php',
				  data: { 
					'oper': 'abrirSolicitudes',
					'id': id		  
				  },
				  success: function (response) {
					$('#fevidencias').attr('src','filegator/flotas.php#/?cd=flotas/'+id);
					$('#modalEvidencias').modal('show');
					$('#modalEvidencias .modal-lg').css('width','1000px');
					$('#idsolicitudesevidencias').val(id);
					$('.titulo-evidencia').html('Solicitud de Flota: '+id+' - Evidencia');
					console.log('id'+id);
				  },
				  error: function () {
				sweetAlert("Oops...", "Ha ocurrido un error al agregar la evidencia, intente más tarde", "error");
				  }
			   }); 
		  }
		  return valid;
		}

	$('#modalEvidencias').on('hidden.bs.modal', function(){
        //console.log('paso')
        tablaincidentes.ajax.reload(null, false);
    });
    
	function eliminarincidente(idincidente,nombre){
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el correctivo " +nombre+ "?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
					$.get( "controller/flotasback.php?oper=eliminarincidentes", 
					{ 
						onlydata : "true",
						idincidente : idincidente
					}, function(result){
						if(result == 1){
							swal('Buen trabajo','Incidente eliminado satisfactoriamente','success');		
							tablaincidentes.ajax.reload(null, false);
						} else {
							swal('ERROR','Ha ocurrido un error al eliminar el incidente, intente más tarde','error');
						}
					});
			}, function (isRechazo){  
			}
		);
	} 
		$("#reportes").on('click', function() {
		var tipo = 1;
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
		//console.log('sistema:'+sistema);
		var localS = localStorage.getItem('DataTables_tablaincidentes_/'+sistema+'/flotas.php');
		//console.log(localS);
		localS = jQuery.parseJSON(localS);

		//PARAMETROS
		param  = "bid=" + localS['columns'][1]['search']['search'];
		param += "&bestado=" + localS['columns'][2]['search']['search'];
		param += "&btitulo=" + localS['columns'][3]['search']['search'];
		param += "&bsolicitante=" + localS['columns'][4]['search']['search'];
		param += "&bfechasolicituddesde=" + localS['columns'][5]['search']['search'];
		param += "&bfechasolicitudhasta=" + localS['columns'][6]['search']['search'];	
		param += "&basignadoa=" + localS['columns'][7]['search']['search'];
		param += "&bsitio=" + localS['columns'][8]['search']['search'];
		param += "&bserie=" + localS['columns'][9]['search']['search'];	
		param += "&bmarca=" + localS['columns'][10]['search']['search'];
		param += "&bmodelo=" + localS['columns'][11]['search']['search'];
		param += "&bfecharesolucion=" + localS['columns'][12]['search']['search'];
		
		
		if(tipo == 1){
			window.open ("reportes/solicitudesflotas.php?" + param, "_blank");
		}
    });
});


