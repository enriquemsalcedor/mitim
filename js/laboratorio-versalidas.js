	/* function verCierres(){
		$('#modalversalidas').modal('show');
	} */
	
	// Setup - add a text input to each header cell
    $('#tablasalidas thead th').each( function () {
        var title = $(this).text();
		var ancho1 = $(this).width();
		var ancho2 = ($(this).width() * 0.4).toFixed(0);
		if ( title !== '' && title !== '-' && title !== 'Acciones') 
			$(this).html( '<input type="text" placeholder="'+title+'" style="width: 100%" /> ' );
		else if (title=='-') 
			$(this).html( '<input id="chkSelectAll" class="fac fac-checkbox fac-white" type="checkbox" value="A11|" />' );
		
		$(this).width(ancho1);
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
	
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tablasalidas.ajax.reload();
        ajustarTablas();
    });
	
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tablasalidas").DataTable().search("").draw();
		$('#tablasalidas_wrapper thead input').val('').change();
	});
	
	$("#listado").click(function(){
		location.href = "laboratorios.php";
	});
	
	tablasalidas = $("#tablasalidas").DataTable({ 
		scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true, 
		ajax : { url	: "controller/laboratorioback.php?oper=verSalidas" },
		columns	: [ 
			{ 	"data": "acciones"},//0
			{ 	"data": "orden"},	//1
			{ 	"data": "fecha"},	//2
			{ 	"data": "usuario" } //3
			],
		rowId: 'orden', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		columnDefs: [ //OCULTAR LA COLUMNA Descripcion  
			{ 
				targets		: [1,2,3],
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
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'		
	});
	
	/* tablasalidas.columns().every( function () {
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
    }); */
	
	// AL CARGARSE LA TABLA
	$('#tablasalidas').on( 'draw.dt', function () {		
		// DAR FUNCIONALIDAD AL BOTON VER SALIDAS
        $('.boton-ver-salidas').each(function(){
			var id = $(this).attr("data-id"); 
			$(this).on( 'click', function() {
				window.open('reportes/laboratorioexportarcierres.php?ids='+id+'&tipo=listar');
			});
		}); 
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();		
		 
    });


