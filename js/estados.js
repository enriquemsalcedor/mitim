$("#icono-filtrosmasivos").css("display","none");
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

	$("#nuevoestado").click(function(){
		location.href = 'estado.php';
	});

    $("#tbestados tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'estado.php?id='+id;

	});


	//HEADER
	$('#tbestados thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 150px" /> ');
				}else if(title == 'Descripción'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 500px" /> ');
				}else if(title == 'Tipo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Proyectos' || title == 'Clientes'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 	350px" /> ');
				}else {
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}

			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (title == 'Acción') {
			var ancho = '50px';
		}
		$(this).width(ancho);
	});

	$('#tbestados').on( 'draw.dt', function () {		
		// Dar funcionalidad al botón desactivar
		//evaluarancho('tbestados')
		ajustarTablas()

/*        $('.boton-eliminar').each(function(){
			var id 	   = $(this).attr("data-id");
			var nombre = $("#tbestados tr#"+id).find('td:nth-child(2)').html();
			$(this).on( 'click', function() {
				eliminarestado(id,nombre);
//				console.log(id,nombre); 
			});
		});*/
		// Tooltips
		$('[data-toggle="tooltip"]').tooltip();

    });
    
    $(document).on('click','.boton-eliminar', function(e){
    	var id 	   = $(this).attr("data-id");
		var nombre = $("#tbestados tr#"+id).find('td:nth-child(2)').html();
		eliminarestado(id,nombre);
     });


		/*tabla*/
	tbestados = $("#tbestados").DataTable({
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
			$('th#cnombre input').val(columns[2]['search']['search']); 
            $('th#cdescripcion input').val(columns[3]['search']['search']);
            $('th#ctipo input').val(columns[4]['search']['search']); 
        },
	    ajax: {
	        url: "controller/estadosback.php?oper=estados",
	    },
	    columns	: [
			{ 	"data": "id" },				//0
			{ 	"data": "acciones" },		//1
			{ 	"data": "nombre" },			//2	 
			{ 	"data": "idclientes" },		//5
			{ 	"data": "idproyectos" }		//6
		],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
	    columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
	        {
				targets : [0],
				visible: false
			},
			{
				targets: [1],
				visible: true,
				
			},
			{
	            targets: [2, 3, 4],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '50px' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width	: '200px' },
			{ targets	: 4, width	: '200px' } 
			
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
	    initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
            this.api().columns().every( function () {
                var that = this; 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
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
	/*fin tabla*/
    // AL CARGARSE LA TABLA
	$('#tbestados').on( 'draw.dt', function () {		
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();
		$('[data-toggle="popover"]').popover(); 
    });
    
    /*$('#tbestados').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })*/
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tbestados").DataTable().search("").draw();
		$('#tbestados_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tbestados.ajax.reload();
        ajustarTablas();
    });


    function eliminarestado(id,nombre){
		$.get( "controller/estadosback.php?oper=hayRelacion", {  
			id : id 
		}, function(result){//
			if(result == 1){
				notification('Hay registros asociados a este Estado, no se puede eliminar.','Advertencia!','warning');
			}else{

				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar el Estado "+nombre+"?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){

						if (isConfirm.value === true) {
							$.get( "controller/estadosback.php?oper=deleteestado", 
							{ 
								onlydata : "true",
								id : id 
							}, function(result){
								if(result == 1){
									notification('Estado eliminado satisfactoriamente','Buen trabajo','success');		
									tbestados.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar el estado, intente más tarde','ERROR','error');
								}
							});

						}
					}, function (isRechazo){  
					}
				);
			}//ELSE
		}); //RESULT RELACION

	}


