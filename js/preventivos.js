$(document).ready(function() {
    $('.switch-sidebar-mini input').change();
    //Permite ver el nombre del campo
 //   $(".select2").removeClass("is-empty");
	$('#desdef').bootstrapMaterialDatePicker({
		weekStart:0, switchOnClick:false, time:false,  format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
	});	
	$('#hastaf').bootstrapMaterialDatePicker({
		weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
	});	
	
	$("#nuevopermiso").click(function(){
		location.href = 'preventivo.php';
	});
	
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tablaincidentes.ajax.reload();
        ajustarTablas();
		getNotificaciones();			  
    });
    
    //LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		//$("#tablaincidentes").DataTable().search("").draw();
		//$('#tablaincidentes_wrapper thead input').val('').change();
		tablaincidentes.state.clear();
		window.location.reload();
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
				}else if(title == 'Sub Categoría' || title == 'Asignado a' || title == 'Estado Ant.' ){
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
		searching: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
		serverSide: true,
        serverMethod: 'post',
		select: { style: 'multi' },
		stateLoadParams: function (settings, data) {
            const{columns}=data
            console.log(columns)
		    $('th#cid input').val(columns[2]['search']['search']);
            $('th#cestado input').val(columns[3]['search']['search']);
            $('th#ctitulo input').val(columns[4]['search']['search']);
            $('th#csolicitante input').val(columns[5]['search']['search']);
            $('th#ccreacion input').val(columns[6]['search']['search']);
            $('th#chorac input').val(columns[7]['search']['search']);
            $('th#cfechar input').val(columns[8]['search']['search']);
            $('th#cempresa input').val(columns[9]['search']['search']);
            $('th#cdepartamento input').val(columns[10]['search']['search']);
            $('th#ccliente input').val(columns[11]['search']['search']);
            $('th#cproyecto input').val(columns[12]['search']['search']);
            $('th#ccategoria input').val(columns[13]['search']['search']);								
            $('th#csubcategoria	input').val(columns[14]['search']['search']);							
            $('th#casignadoa input').val(columns[15]['search']['search']);
            $('th#csitio input').val(columns[16]['search']['search']);
            $('th#cmodalidad input').val(columns[17]['search']['search']);
            $('th#cserie input').val(columns[18]['search']['search']);
            $('th#cmarca input').val(columns[19]['search']['search']);
            $('th#cmodelo input').val(columns[20]['search']['search']);
            $('th#cprioridad input').val(columns[21]['search']['search']);
            $('th#cresolucion input').val(columns[22]['search']['search']);
        },
	    ajax: {
	        url: "controller/preventivosback.php?oper=incidentes",
	    },
	    columns	: [
			{ 	"data": "check" },			//0
			{ 	"data": "acciones" },		//2
			{ 	"data": "id" },				//1
			{ 	"data": "estado" },			//3
			{ 	"data": "titulo" },			//4
			{ 	"data": "solicitante" },	//5
			{ 	"data": "fechacreacion" },	//6
			{ 	"data": "horacreacion" },	//7
			{ 	"data": "fechareal" },		//8
			{ 	"data": "idempresas" },		//9
			{ 	"data": "iddepartamentos" },//10
			{ 	"data": "idclientes" },		//11
			{ 	"data": "idproyectos" },	//12
			{ 	"data": "idcategoria" },	//13
			{ 	"data": "idsubcategoria" },	//14
			{ 	"data": "asignadoa" },		//15
			{ 	"data": "sitio" },			//16
			{ 	"data": "modalidad" },		//17
			{ 	"data": "serie" },			//18
			{ 	"data": "marca" },			//19
			{ 	"data": "modelo" },			//20
			{ 	"data": "idprioridad" },	//21
			{ 	"data": "fecharesolucion" },//22
		],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
	    columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
	        {
				targets : [0],
				visible: true
			},
			{
				orderable	: false,
				className	: 'select-checkbox',
				searchable	: false,
				targets		: 0
			},
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
			{ targets	: 8, width	: '200px' },
			{ targets	: 9, visible: false },
			{ targets	: 10, width	: '200px' },
			{ targets	: 11, width	: '200px' },
			{ targets	: 12, width	: '200px' },
			{ targets	: 13, width	: '200px' },
			{ targets	: 14, width	: '200px' },
			{ targets	: 15, width	: '200px' },
			{ targets	: 16, width	: '200px' },
			{ targets	: 17, width	: '200px' },
			{ targets	: 18, width	: '200px' },
			{ targets	: 19, width	: '200px' },
			{ targets	: 20, width	: '200px' },
			{ targets	: 21, width	: '200px' },
			{ targets	: 22, width	: '200px' },
			
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],rowCallback: function( row, data) {
			// if(data['estadoant'] == 1){
			// 	$('td', row).css('background-color', '#e0fbed');
			// }
			// if(data['estado'] == "Asignado" && data['horasasignado'] >= 24){
			// 	let mensaje = "El correctivo tiene más de 24 horas Asignado";
			// 	$('td', row).eq(1).css('color', 'red');
			// 	$('td', row).eq(1).html("<span data-toggle='tooltip' data-placement='right' data-original-title='"+mensaje+"'>"+data['id']+"</span>");
			// }
			// if(data['estado'] == "A la Espera del Cliente" && data['horasalaespera'] >= 72){
			// 	let mensaje = "El correctivo tiene más de 72 horas A la Espera del Cliente";
			// 	$('td', row).eq(1).css('color', 'red');
			// 	$('td', row).eq(1).html("<span data-toggle='tooltip' data-placement='right' data-original-title='"+mensaje+"'>"+data['id']+"</span>");
			// }
			// if(data['estado'] == "Reporte Pendiente" && data['horasreportepen'] >= 120){
			// 	let mensaje = "El correctivo tiene más de 5 días en Reporte Pendiente";
			// 	$('td', row).eq(1).css('color', 'red');
			// 	$('td', row).eq(1).html("<span data-toggle='tooltip' data-placement='right' data-original-title='"+mensaje+"'>"+data['id']+"</span>");
			// }
			// if(data['estado'] == "Nuevo" && data['asignadoa'] == null && data['validacionusuario'] == 1){
			// 	let mensaje = "El correctivo es nuevo y no ha sido asignado";
			// 	$('td', row).eq(1).css('color', 'red');
			// 	$('td', row).eq(1).html("<span data-toggle='tooltip' data-placement='right' data-original-title='"+mensaje+"'>"+data['id']+"</span>");
			// }
		},
		initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
			/* this.api().columns().every( function () {
                var that = this; 
                $( 'input[type="text"]', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                } );
            }); */
			var t = 0;
			this.api().columns().every( function () {
                var column = this;
            });
			//OCULTAR LOADER
			$('#preloader').css('display','none');
			quitarSelecciones();
			tablaincidentes.columns.adjust();
	    },
	    stateSaveParams: function(settings, data) {
            for (var i = 0, ien = data.columns.length; i < ien; i++) {
                delete data.columns[i].visible;
            }
        },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	/*fin tabla*/
	
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
		location.href = "preventivo.php?id="+id;
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
                url: 'controller/preventivosback.php',
                data: {
                    'oper': 'guardarcolumnaocultar',
                    'tipo': 'eliminar',
                    'columna': ocultar
                },
                beforeSend: function() {},
                success: function(response) {
                    verificarbotonocultarcolumna();
                    console.log("lista de columnas actualizada guardada");
                }
            });
        } else {
            $("#c" + $(this).attr('data-column')).css('background-color', 'rgb(156 218 173)');
            $.ajax({
                type: 'post',
                url: 'controller/preventivosback.php',
                data: {
                    'oper': 'guardarcolumnaocultar',
                    'tipo': 'agregar',
                    'columna': ocultar
                },
                beforeSend: function() {},
                success: function(response) {
                    verificarbotonocultarcolumna();
                    console.log("lista de columnas actualizada guardada");
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
		$('.boton-reporte').each(function(){
			var id = $(this).attr("data-id");			
			$(this).on( 'click', function() {
				abrirReporteServicio(id);
			});
		});
		buscarcolumnasocultas();
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();
    });
    $('#tablaincidentes').on('processing.dt', function (e, settings, processing) {
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
})
	const peticionExcel = (archivo) =>{ 
		$.ajax({
			type:'POST',
			url:`reportes/${archivo}`,
			data: {},
			dataType:'json',
			beforeSend: function() {
				$('#preloader').css('display', 'block');
			},
		}).done(function(data){
			
			var $a = $("<a>");
			$a.attr("href",data.file);
			$("body").append($a);
			$a.attr("download",data.name);
			$a[0].click();
			$a.remove(); 
			$('#preloader').css('display', 'none');
		});
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
		var localS = localStorage.getItem('DataTables_tablaincidentes_/'+sistema+'/preventivos.php');
		//console.log(localS);
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
		param += "&bsitio=" + localS['columns'][16]['search']['search'];
		param += "&bmodalidad=" + localS['columns'][17]['search']['search'];
		param += "&bserie=" + localS['columns'][18]['search']['search'];	
		param += "&bmarca=" + localS['columns'][19]['search']['search'];
		param += "&bmodelo=" + localS['columns'][20]['search']['search'];
		param += "&bprioridad=" + localS['columns'][21]['search']['search'];
		param += "&bcierre=" + localS['columns'][22]['search']['search'];
		
		if(tipo == 1){
			if(nivel != 7){
				//window.open ("reportes/preventivosexportar.php?" + param, "_blank");
				peticionExcel(`preventivosexportar.php?${param}`);
			}else{
				//window.open ("reportes/preventivosexportarsym.php?" + param, "_blank");
				peticionExcel(`preventivosexportarsym.php?${param}`);
			} 
		}else{
			//window.open ("reportes/preventivosexportarcom.php?" + param, "_blank");
			peticionExcel(`preventivosexportarcom.php?${param}`);
		}	
    });

	/* $("#guardar-evidencia").on('click', function() {
		swal("Buen trabajo", "archivos adjuntados satisfactoriamente", "success");
		$('#modalEvidencias').modal('hide');
	}); */
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
            url: 'controller/preventivosback.php',
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
                        verificarbotonocultarcolumna()
                    }
                }
            }
        });
    }
	
	function verificarbotonocultarcolumna(){
		$('#preloader').css('display','block');
        $.ajax({
            type: 'post',
            url: 'controller/preventivosback.php',
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

	var dirxdefecto = 'incidente';
	$('#fevidencias').attr('src','filegator/preventivos.php#/?cd=%2F'+dirxdefecto);
	function abrirsolicitudes(id) {
		  var valid = true;
		  if ( valid ) {
			$.ajax({
				  type: 'post',
				  url: 'controller/preventivosback.php',
				  data: { 
					'oper': 'abrirSolicitudes',
					'id': id		  
				  },
				  success: function (response) {
					$('#fevidencias').attr('src','filegator/preventivos.php#/?cd=incidentes/'+id);
					$('#modalEvidencias').modal('show');
					$('#modalEvidencias .modal-lg').css('width','1000px');
					$('#idsolicitudesevidencias').val(id);
					$('.titulo-evidencia').html('Preventivo: '+id+' - Evidencia');
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
			text: "¿Esta seguro de eliminar el preventivo " +nombre+ "?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm.value === true) {
					$.get( "controller/preventivosback.php?oper=eliminarincidentes", 
					{ 
						onlydata : "true",
						idincidente : idincidente
					}, function(result){
						if(result == 1){
							swal('Buen trabajo','Preventivo eliminado satisfactoriamente','success');		
							tablaincidentes.ajax.reload(null, false);
						} else {
							swal('ERROR','Ha ocurrido un error al eliminar el preventivo, intente más tarde','error');
						}
					});
				}else{
					swal.close();
				} 
			}, function (isRechazo){  
			}
		);
	} 
});


