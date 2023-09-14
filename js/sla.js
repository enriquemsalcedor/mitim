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
    
    $("#nuevaprioridad").click(function(){
    	location.href = 'prioridad.php';
    });

    $("#tbsla tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'prioridad.php?id='+id;

	});
	//HEADER
	$('#tbsla thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Prioridad'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}else if(title == 'Descripción'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 350px" /> ');
				}else if(title == 'Días' || title == "Horas" || title == "Tipo"){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 90px" /> ');
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

	$('#tbsla').on( 'draw.dt', function () {		
		// Dar funcionalidad al botón desactivar

		//evaluarancho('tbsla')
		ajustarDropdown();
		ajustarTablas()
/*
        $('.boton-eliminar').each(function(){
			var id 	   = $(this).attr("data-id");
			var prioridad = $("#tbsla tr#"+id).find('td:nth-child(2)').html();
			$(this).on( 'click', function() {
				eliminaprioridad(id,prioridad);
			});
		});*/
		// Tooltips
		$('[data-toggle="tooltip"]').tooltip();

    });
    
    $(document).on('click','.boton-eliminar', function(e){
        var id 	   = $(this).attr("data-id");
		var prioridad = $("#tbsla tr#"+id).find('td:nth-child(2)').html();
		eliminaprioridad(id,prioridad);
	});
	/*tabla*/
	tbsla = $("#tbsla").DataTable({
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
            $('th#cprioridad input').val(columns[2]['search']['search']);
			$('th#cdescripcion input').val(columns[3]['search']['search']);
			$('th#cdias input').val(columns[4]['search']['search']); 
        },
	    ajax: {
	        url: "controller/slaback.php?oper=sla",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "prioridad" },
			{ 	"data": "descripcion" },
			{ 	"data": "clientes" },
			{ 	"data": "proyectos" }/*,
			{ 	"data": "dias" },
			{ 	"data": "horas" },
			{ 	"data": "tipo" },*/
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
	            targets: [2, 3, 4, 5],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width	: '200px' },
			{ targets	: 4, width	: '200px' },
			{ targets	: 5, width	: '200px' } 
			
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
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
			//$('#preloader').css('display','none');
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	
	/*fin tabla*/
	$('#tbsla').on( 'keyup change', function (e, settings, processing) {
        console.log("cargo proceso");
    })
    
    /*$('#tbsla').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })*/
    //LIMPIAR COLUMNAS
    $('#limpiarCol').on( 'click', function() {
    	$("#tbsla").DataTable().search("").draw();
    	$('#tbsla_wrapper thead input').val('').change();
    });
    //REFRESCAR
    $("#refrescar").on('click', function() {
    	tbsla.ajax.reload();
        ajustarTablas();
    });

    function eliminaprioridad(id,prioridad){
		var idactivos = id;
		$.get( "controller/slaback.php?oper=hayRelacion", {  
			id : id 
		}, function(result){//
			if(result == 1){
				notification('Hay registros asociados a esta Prioridad, no se puede eliminar.','Advertencia!','warning');
			}else{



				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar la Prioridad "+prioridad+"?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
		//				if (isConfirm){
							$.get( "controller/slaback.php?oper=deletesla", 
							{ 
								onlydata : "true",
								id : id 
							}, function(result){
								if(result == 1){
									notification('Prioridad eliminada satisfactoriamente','Buen trabajo','success');		
									tbsla.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar la prioridad, intente más tarde','ERROR','error');
								}
							});
						}
					}, function (isRechazo){  
					}
				);
			}//ELSE
		}); //RESULT RELACION


	}


