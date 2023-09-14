    $('#icono-filtrosmasivos').css('display','none');
    //LIMPIAR COLUMNAS
    $('#limpiarCol').on( 'click', function() {
    	$("#tablaclientes").DataTable().search("").draw();
    	$('#tablaclientes_wrapper thead input').val('').change();
    });
    //REFRESCAR
    $("#refrescar").on('click', function() {
    	tablaclientes.ajax.reload();
        ajustarTablas();
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
    
    $("#nuevo").click(function(){
    	location.href = 'cliente.php';
    });

    $("#tablaclientes tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'cliente.php?id='+id;
	});
 
	$('#tablaclientes thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre' || title == 'Provincia' || title == 'Distrito' || title == 'Corregimiento' || title == 'Referido' || title == 'Subreferido'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
				}else if(title == 'Dirección'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 350px" /> ');
				}else {
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}

			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (title == 'Acción') {
			var ancho = '100px';
		}
		$(this).width(ancho);
	});

	/*tabla*/
	tablaclientes = $("#tablaclientes").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		searching: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
		//serverSide: true,
        //serverMethod: 'post',
        stateLoadParams: function (settings, data) {			
			$('th#cnombre input').val(data['columns'][2]['search']['search']);
			$('th#capellidos input').val(data['columns'][3]['search']['search']);
			$('th#cdireccion input').val(data['columns'][4]['search']['search']);
			$('th#ctelefono input').val(data['columns'][5]['search']['search']);
			$('th#ccorreo input').val(data['columns'][6]['search']['search']);
			$('th#cmovil input').val(data['columns'][7]['search']['search']);
			/* $('th#cid_provincia input').val(data['columns'][8]['search']['search']);
			$('th#cid_distrito input').val(data['columns'][9]['search']['search']);
			$('th#cid_corregimiento input').val(data['columns'][10]['search']['search']);
			$('th#cid_referido input').val(data['columns'][11]['search']['search']);
			$('th#cid_subreferido input').val(data['columns'][12]['search']['search']); */
        },
        ajax: {
	        url: "controller/clientesback.php?oper=cargarclientes",
	    },
	    columns	: [
			{ 	"data": "id" },         	//0
			{ 	"data": "acciones" },   	//1
			{ 	"data": "nombre" },     	//2
			{ 	"data": "apellidos" },  	//3
			{ 	"data": "direccion" },  	//4
			{ 	"data": "telefono" },   	//5
			{ 	"data": "correo" },     	//6
            { 	"data": "movil" },       	//7
            { 	"data": "id_provincia" }, 	//8
            { 	"data": "id_distrito" }, 	//9
            { 	"data": "id_corregimiento"},//10
            { 	"data": "id_referido" },    //11
            { 	"data": "id_subreferido" }  //12
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

    $('#tablaclientes').on( 'draw.dt', function () {		
    	// Dar funcionalidad al botón desactivar
    		//evaluarancho('tablaclientes')
			ajustarDropdown();
    		ajustarTablas()
    
    /*
        $('.boton-eliminar').each(function(){
    		var id 	   = $(this).attr("data-id");
    		var marca = $("#tablaclientes tr#"+id).find('td:nth-child(2)').html();
    //		console.log("#tablaclientes tr#"+id)
    
    
    		$(this).on( 'click', function() {
    			eliminamarca(id,marca);
    		});
    	});*/
    	// Tooltips
    	$('[data-toggle="tooltip"]').tooltip();
    
    });
 
    /*$('#tablaclientes').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })*/
    
    $(document).on('click','.boton-eliminar', function(e){
        var id = $(this).attr("data-id");
        var nombre = $("#tablaclientes tr#"+id).find('td:nth-child(2)').html();
    	eliminarcliente(id,nombre);
    });
	
	function eliminarcliente(id,nombre){
		var idclientes = id;
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el cliente "+nombre+"?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm.value === true) {
				        $.get( "controller/clientesback.php?oper=existeincidentescli", 
        					{ 
        						onlydata   : "true",
        						idclientes : idclientes 
        					}, function(result){ 
        					    
        				        if(result.incidentes == 1 && result.proyectos == 1){ 
									notification('Error!','Hay Incidentes y Proyectos asociados a este cliente, no se puede eliminar.','error');
        						} else if(result.incidentes == 1 && result.proyectos == 0) { 
									notification('Error!','Hay Incidentes asociados a esta cliente, no se puede eliminar.','error');
        						} else if(result.incidentes == 0 && result.proyectos == 1) { 
									notification('Error!','Hay Proyectos asociados a esta cliente, no se puede eliminar.','error');								   
        						} else {
        						   $.get( "controller/clientesback.php?oper=deleteclientes", 
                    					{ 
                    						onlydata : "true",
                    						idclientes : idclientes //verificar ok
                    					}, function(result){
                    						if(result == 1){ 
												notification('Exito!','Cliente eliminado satisfactoriamente','success');
                    							tablaclientes.ajax.reload(null, false);
                    						} else { 
												notification('Error!','Ha ocurrido un error al eliminar el cliente, intente más tarde','error');
                    						}
                    				}); 
        						} 
					},'json'); 
				}
			}, function (isRechazo){ //verificar ok
				// NADA
			}
		);
	}


