$(document).ready(function() {
    $('.switch-sidebar-mini input').change();
    //Permite ver el nombre del campo
 //   $(".select2").removeClass("is-empty");
	$('#desdef').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
//	    var fechadesdeoculto = $('#calendarhidendesde').val();
//	    $('#desdef').val(fechadesdeoculto);
	});	
	$('#hastaf').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
//	    var fechahastaoculto = $('#calendarhidenhasta').val();
//	    $('#hastaf').val(fechahastaoculto);
	});	
	/*
	$('.iconcalfdesde').on( 'click', function (e) { 
	    $('#calendarhidendesde').dblclick();
	});	
	$('.iconcalfhasta').on( 'click', function (e) { 
	    $('#calendarhidenhasta').dblclick();
	});*/

	$("#nuevo").click(function(){
		location.href = 'postventa.php';
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
				if(title == 'Estado'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Creación'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Hora creación'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 120px" /> ');
				}else if(title == 'Cierre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 120px" /> ');
				}else if(title == 'Solicitante'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Dep. / Grupo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Dep. / Grupo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Titulo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 300px" /> ');
				}else if(title == 'Cliente' || title == 'Proyecto' || title == 'Categoría' || title == 'Ubicación' || title == 'Tipo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
				}else if(title == 'Serial 1' || title == 'Marca' || title == 'Modelo' || title == 'Prioridad'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Id'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 60px" /> ');
				}else if(title == 'Subcategoría' || title == 'Asignado a' || title == 'Estado Ant.' ){
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
			 $('th#choracreacion input').val(columns[7]['search']['search']);
			 $('th#cfechar input').val(columns[8]['search']['search']);
			 $('th#cempresa input').val(columns[9]['search']['search']);
			 $('th#cdepartamento input').val(columns[10]['search']['search']);
			 $('th#ccliente input').val(columns[11]['search']['search']);
			 $('th#cproyecto input').val(columns[12]['search']['search']);
			 $('th#ccategoria input').val(columns[13]['search']['search']);
			 $('th#csubcategoria input').val(columns[14]['search']['search']);
			 $('th#casignadoa input').val(columns[15]['search']['search']);
			 $('th#cambiente input').val(columns[16]['search']['search']);
			 $('th#cprioridad input').val(columns[17]['search']['search']);
			 $('th#cresolucion input').val(columns[18]['search']['search']);
        },
		select: { style: 'multi' },
	    ajax: {
	        url: "controller/postventasback.php?oper=incidentes&user_id="+temp,
	    },
	    columns	: [
			{ 	"data": "check"},			//0
			{ 	"data": "acciones"},		//1
			{ 	"data": "id"},				//2
			{ 	"data": "estado" },			//3
			{ 	"data": "titulo"},			//4
			{ 	"data": "solicitante"},		//5
			{ 	"data": "fechacreacion"},	//6
			{ 	"data": "horacreacion" },	//7
			{ 	"data": "fechareal" },		//8
			{ 	"data": "empresa" },		//9
			{ 	"data": "departamento" },	//10
			{ 	"data": "cliente" },		//11
			{ 	"data": "proyecto" },		//12			
			{ 	"data": "categoria" },		//13
			{ 	"data": "subcategoria" },	//14		
			{ 	"data": "asignadoa" },		//15
			{ 	"data": "sitio" },			//16 
			{ 	"data": "prioridad" },		//17
			{ 	"data": "fecharesolucion" }	//18
		],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
	    columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
	        {
				orderable	: false,
				className	: 'select-checkbox text-center',
				searchable	: false,
				visible     : true,
				targets		: 0
			},
			{
	            targets: [2, 3, 4],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px', className: 'text-center' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width	: '200px' },
			{ targets	: 4, width	: '200px' },
			{ targets	: 5, width	: '200px' },
			{ targets	: 6, width	: '200px' },
			{ targets	: 7, width	: '200px' },
			{ targets	: 8, width	: '200px' },
			{ targets	: 9, visible: false, searchable: false },
			{ targets	: 10, width	: '200px' },
			{ targets	: 11, width	: '200px' },
			{ targets	: 12, width	: '200px' },
			{ targets	: 13, width	: '200px' },
			{ targets	: 14, width	: '200px' },
			{ targets	: 15, width	: '200px' },
			{ targets	: 16, width	: '200px' },
			{ targets	: 17, width	: '200px' },
			{ targets	: 18, width	: '200px' } 
			
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
	/*fin tabla*/
	
	$('#limpiarFiltros').click(function(){
		tablaincidentes.state.clear();
		window.location.reload();
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
		location.href="postventa.php?id="+id;
	})
	
	$('#tablaincidentes').on( 'draw.dt', function () {	
		ajustarDropdown();
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
		var localS = localStorage.getItem('DataTables_tablaincidentes_/'+sistema+'/postventas.php');
		localS = jQuery.parseJSON(localS);
		//PARAMETROS
		param  = "bid=" + localS['columns'][2]['search']['search'];
		param += "&bestado=" + localS['columns'][3]['search']['search'];
		param += "&btitulo=" + localS['columns'][4]['search']['search'];
		param += "&bsolicitante=" + localS['columns'][5]['search']['search'];
		param += "&bcreacion=" + localS['columns'][6]['search']['search'];
		param += "&bhorac=" + localS['columns'][7]['search']['search'];
		param += "&bfechar=" + localS['columns'][8]['search']['search'];	
		param += "&bempresa=" + localS['columns'][9]['search']['search'];
		param += "&bdepartamento=" + localS['columns'][10]['search']['search'];
		param += "&bcliente=" + localS['columns'][11]['search']['search'];
		param += "&bproyecto=" + localS['columns'][12]['search']['search'];
		param += "&bcategoria=" + localS['columns'][13]['search']['search'];	
		param += "&bsubcategoria=" + localS['columns'][14]['search']['search'];
		param += "&basignadoa=" + localS['columns'][15]['search']['search'];
		param += "&bambiente=" + localS['columns'][16]['search']['search']; 
		param += "&bprioridad=" + localS['columns'][17]['search']['search'];
		param += "&bcierre=" + localS['columns'][18]['search']['search'];
		
		if(tipo == 1){
			window.open ("reportes/postventasexportar.php?" + param, "_blank");
		}else{
			window.open ("reportes/postventasexportarcom.php?" + param, "_blank");
		}	
    });
	$("#guardar-evidencia").on('click', function() {
		swal("Buen trabajo", "archivos adjuntados satisfactoriamente", "success");
		$('#modalEvidencias').modal('hide');
	});
	
	$('#chkSelectAll').click(function(){
		if($("#chkSelectAll").is(':checked')){
			console.log("paso por aqui");
			seleccionarTodas();
		}else{
			console.log("paso por else");
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
	
	var dirxdefecto = 'incidente';
	$('#fevidencias').attr('src','filegator/postventas.php#/?cd=%2F'+dirxdefecto);
	
	function abrirsolicitudes(id) {
		  var valid = true;
		  if ( valid ) {
			$.ajax({
				  type: 'post',
				  url: 'controller/incidentesback.php',
				  data: { 
					'oper': 'abrirSolicitudes',
					'id': id		  
				  },
				  success: function (response) {
					$('#fevidencias').attr('src','filegator/postventas.php#/?cd=postventas/'+id);
					$('#modalEvidencias').modal('show');
					$('#modalEvidencias .modal-lg').css('width','1000px');
					$('#idsolicitudesevidencias').val(id);
					$('.titulo-evidencia').html('Postventa: '+id+' - Evidencia');
					console.log('id'+id);
				  },
				  error: function () {
				sweetAlert("Oops...", "Ha ocurrido un error al eliminar la solicitud, intente más tarde", "error");
				  }
			   }); 
		  }
		  return valid;
		}
		
	$('#modalEvidencias').on('hidden.bs.modal', function(){
    console.log('paso')
         tablaincidentes.ajax.reload(null, false);
    });

	function eliminarincidente(idincidente,nombre){
		swal({
				title: "Confirmar",
				text: "¿Esta seguro de eliminar la visita " +nombre+ "?",
				type: "warning",
				showCancelButton: true,
				cancelButtonColor: 'red',
				confirmButtonColor: '#09b354',
				confirmButtonText: 'Si',
				cancelButtonText: "No"
			}).then(
				function(isConfirm){
					if (isConfirm.value === true) {
						$.get( "controller/postventasback.php?oper=eliminarincidentes", 
						{  
							idincidente : idincidente 
						}, function(result){
							if(result == 1){
								notification('Visita eliminada satisfactoriamente','Buen trabajo','success');		
								tablaincidentes.ajax.reload(null, false);
							} else {
								notification('Ha ocurrido un error al eliminar la visita, intente más tarde','Error','error');
							}
						});
					}
				}, function (isRechazo){  
				}
			);
	} 

	$('button.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
        var column = tablaincidentes.column($(this).attr('data-column'));
        var ocultar = $(this).attr('data-column');
        column.visible(!column.visible());
        if (column.visible()) {
            $("#c" + $(this).attr('data-column')).css('background-color', '#36C95F');
            $.ajax({
                type: 'post',
                url: 'controller/postventasback.php',
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
                url: 'controller/postventasback.php',
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
	
	function buscarcolumnasocultas(){
        $.ajax({
            type: 'post',
            url: 'controller/postventasback.php',
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
            url: 'controller/postventasback.php',
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

});


