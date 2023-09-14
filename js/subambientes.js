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
    
    $("#nuevosubambiente").click(function(){
    	location.href = 'subambiente.php';
    });

    $("#tbsubambientes tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'subambiente.php?id='+id;

	});
	 
	//HEADER
	$('#tbsubambientes thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
				}else if(title == 'Ubicación'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 400px" /> ');
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

    $('#tbsubambientes').on( 'draw.dt', function () {		
    	// Dar funcionalidad al botón desactivar
    
    	//evaluarancho('tbsubambientes')
		ajustarDropdown();
    	ajustarTablas()
    /*
        $('.boton-eliminar').each(function(){
    		var id 	   = $(this).attr("data-id");
    		var ubicacion = $("#tbsubambientes tr#"+id).find('td:nth-child(2)').html();
    		$(this).on( 'click', function() {
    			eliminarSubambientes(id,ubicacion);
    		});
    	});*/
    	// Tooltips
    	$('[data-toggle="tooltip"]').tooltip();
    
    });


    $(document).on('click','.boton-eliminar', function(e){
    
		var id 	   = $(this).attr("data-id");
		var area = $("#tbsubambientes tr#"+id).find('td:nth-child(2)').html();
		eliminarSubambientes(id,area);
     });
    
	/*tabla*/
	tbsubambientes = $("#tbsubambientes").DataTable({
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
        /*
        stateLoadParams: function (settings, data) {
            const{columns}=data
			$('th#cnombre input').val(columns[2]['search']['search']); 
            $('th#cubicacion input').val(columns[3]['search']['search']);
            $('th#cclientes input').val(columns[4]['search']['search']);
            $('th#cproyectos input').val(columns[5]['search']['search']);
        },*/
	    ajax: {
	        url: "controller/subambientesback.php?oper=subambientes",
	    },
	    columns	: [
			{ 	"data": "id" },				//0
			{ 	"data": "acciones" },		//1
			{ 	"data": "nombre" }, 		//2
			{ 	"data": "ambientes" },	//3
			{ 	"data": "clientes" },	//4
			{ 	"data": "proyectos" },	//5
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
	            className: 'text-center'
				
			},
			{
	            targets: [2, 3],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ 
				targets	: 3,
				width 	: '200px',
			},
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
    /*$('#tbsubambientes').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })*/

    //LIMPIAR COLUMNAS
    $('#limpiarCol').on( 'click', function() {
    	$("#tbsubambientes").DataTable().search("").draw();
    	$('#tbsubambientes_wrapper thead input').val('').change();
    });
    //REFRESCAR
    $("#refrescar").on('click', function() {
    	tbsubambientes.ajax.reload();
        ajustarTablas();
    });

    function eliminarSubambientes(id,ubicacion){

		var idactivos = id;
		$.get( "controller/subambientesback.php?oper=hayRelacion", 
		{  
			id : id 
		}, function(result){//
			if(result == 1){
				notification('Hay registros asociados a esta Área, no se puede eliminar.','Advertencia!','warning');
			}else{


					var idactivos = id;
					swal({
						title: "Confirmar",
						text: "¿Esta seguro de eliminar el Área "+ubicacion+"?",
						type: "warning",
						showCancelButton: true,
						cancelButtonColor: 'red',
						confirmButtonColor: '#09b354',
						confirmButtonText: 'Si',
						cancelButtonText: "No"
					}).then(
						function(isConfirm){
							if (isConfirm.value === true) {
								$.get( "controller/subambientesback.php?oper=eliminarSubambientes", 
								{ 
									onlydata : "true",
									id : id 
								}, function(result){
									if(result == 1){
										notification('Área eliminada satisfactoriamente','Buen trabajo','success');
										tbsubambientes.ajax.reload(null, false);
									} else {
										notification('Ha ocurrido un error al eliminar el Área, intente más tarde','ERROR','error');
									}
								});

							}
						}, function (isRechazo){  
						}
					);
			}//ELSE
		}); //RESULT RELACION

	}//FUNCION


