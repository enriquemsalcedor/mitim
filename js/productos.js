$(document).ready(function() {
    $('.switch-sidebar-mini input').change();
    //Permite ver el nombre del campo
  //  $(".select2").removeClass("is-empty");
	$('#desdef').bootstrapMaterialDatePicker({
		weekStart:0, switchOnClick:false, time:false,  format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
	});	
	$('#hastaf').bootstrapMaterialDatePicker({
		weekStart:0, switchOnClick:false, time:false, format:'YYYY-MM-DD', lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar', clearButton: true }).on('change',function(){
	});	
	
	//REFRESCAR
	$("#refrescar").on('click', function() {
	    tablaincidentes.ajax.reload();
        ajustarTablas(); 
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
				if(title == 'Nombre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Precio'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
				}else if(title == 'Existencia'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 120px" /> ');
				}else if(title == 'Cart'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 120px" /> ');
				}else if(title == 'Ubicación'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Imagen'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Compañia'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 300px" /> ');
				}else if(title == 'Fecha'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
				}else{
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		}else if (title == 'Acción') {
			ancho = '50px';
		}else if(title == '-'){
			$(this).html( '<input id="chkSelectAll" type="checkbox"  value="A11|" /> ' );
		}
		$(this).width(ancho);
	});

	var cvisible  = false;	
    var cvisibled = false;
	var cvisible__et = false;

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
			$('th#cid input').val(columns[2]['search']['search']);
			$('th#cnombre input').val(columns[3]['search']['search']); 
			$('th#cprecio input').val(columns[4]['search']['search']); 
			$('th#cexistencia input').val(columns[5]['search']['search']); 
			$('th#ccart input').val(columns[6]['search']['search']); 
			$('th#cubicacion input').val(columns[7]['search']['search']); 
			$('th#cfecha input').val(columns[8]['search']['search']);
			$('th#cimagen input').val(columns[9]['search']['search']); 
			$('th#ccompania input').val(columns[10]['search']['search']); 
			 
        },
	    ajax: {
	        url: "controller/productosback.php?oper=productos&user_id="+temp,
	    },
	    columns	: [
			{ 	"data": "check" },
			{ 	"data": "acciones" },
			{ 	"data": "id" },
			{ 	"data": "name" },
			{ 	"data": "price" },
			{ 	"data": "existence" },
			{ 	"data": "cart" },
			{ 	"data": "ubication" }, 
			{ 	"data": "created_date" },
			{ 	"data": "image" },
			{ 	"data": "company" }
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
				className	: 'select-checkbox',
				searchable	: false,
				targets		: 0
			},
			{
	            targets: [2, 5, 6],
	            visible: false
			},
			{
	            targets: [4],
	            className: 'text-right'
			},
			{
	            targets: [2, 3],
	            className: 'text-left'
			},
			{
	            targets: [9],
	            className: 'text-right'
			},
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '30px' },
			{ targets	: 3, width	: '200px' },
			{ targets	: 4, width	: '200px' },
			{ targets	: 7, width	: '500px' },
			{ targets	: 8, width	: '100px' },
			{ targets	: 9, width	: '250px' },
			{ targets	: 10, width	: '200px' } 
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],rowCallback: function( row, data) {
			let nro_c = 0;
			cvisible == true ? nro_c = 2 : nro_c = 1;	
			
		},
		initComplete: function() {
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
		location.href = "correctivo.php?id="+id;
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
                url: 'controller/productosback.php',
                data: {
                    'oper': 'guardarcolumnaocultar',
                    'tipo': 'eliminar',
                    'columna': ocultar
                },
                beforeSend: function() {},
                success: function(response) {
                    verificarbotonocultarcolumna();
                }
            });
        } else {
            $("#c" + $(this).attr('data-column')).css('background-color', 'rgb(156 218 173)');
            $.ajax({
                type: 'post',
                url: 'controller/productosback.php',
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
		evaluarancho('tablaincidentes');
		ajustarTablas();
    	buscarcolumnasocultas();
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();
    });
    $('#tablaincidentes').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
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
		var localS = localStorage.getItem('DataTables_tablaincidentes_/'+sistema+'/productos.php');
		//console.log(localS);
		localS = jQuery.parseJSON(localS);

		//PARAMETROS
		param  = "bid=" + localS['columns'][2]['search']['search'];
		param += "&bnombre=" + localS['columns'][3]['search']['search'];
		param += "&bprecio=" + localS['columns'][4]['search']['search'];
		param += "&bexistencia=" + localS['columns'][5]['search']['search'];
		param += "&bcart=" + localS['columns'][6]['search']['search'];
		param += "&bubicacion=" + localS['columns'][7]['search']['search'];	
		param += "&bimagen=" + localS['columns'][8]['search']['search'];
		param += "&bcompania=" + localS['columns'][9]['search']['search'];
		
		
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
            url: 'controller/productosback.php',
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
            url: 'controller/productosback.php',
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


