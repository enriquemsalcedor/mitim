ajustarDropdown();
$("#icono-filtrosmasivos").css("display","none");
	function ajustarTablas(){
		if (screen.width > 1024) {
			//console.log('screen.width: '+screen.width);
			$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
			$('.dataTables_scrollHead table').width('100%');
			$('.dataTables_scrollBody table').width('100%');
		}
	}

	$('.nav-control').on('click', function(e){
		ajustarTablas();
	});

	$("#nuevonivel").click(function(){
		location.href = 'nivel.php';
	});

	$('#tbniveles thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		console.log(title);
		if (title !== '' && title !== '-' && title !== 'Acción') {
			console.log(screen.width);
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');
				if( title == 'Nombre'){
					console.log("paso por aqui");
					$(this).html('<input type="text" placeholder="'+ title +'" id="' + id + '" style="width: 150px" /> ');
				}else if(title == 'Descripción'){
					$(this).html('<input type="text" placeholder="'+ title +'" id="' + id + '" style="width: 600px" /> ');
				}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (title == 'Acción') {
			var ancho = '50px';
		}
		$(this).width(ancho);
	});    
	
	tbniveles = $("#tbniveles").DataTable({
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
        },
	    ajax: {
	        url: "controller/nivelesback.php?oper=niveles",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "nombre" },
			{ 	"data": "descripcion" },
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
				className: "text-center"
			},
			{
	            targets: [2, 3],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width	: '200px' }
			
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
	         /*  let  height = $('#tbniveles').height();
	    	  if (height>36 && height <120) {
	    		    $('#tbniveles').height('120px');
	    		} */
			//OCULTAR LOADER
			//$('#preloader').css('display','none');
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});

	$('#tbniveles').on( 'draw.dt', function () {		
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();
    });
    
    /*$('#tbniveles').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })*/
    
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tbniveles").DataTable().search("").draw();
		$('#tbniveles_wrapper thead input').val('').change();
	});
	
	$("#refrescar").on('click', function() {
		tbniveles.ajax.reload();
        ajustarTablas();
    }); 								
 
    function eliminarnivel(id,nombre){
		var idactivos = id;
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el Nivel "+nombre+"?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
			    if (isConfirm.value === true){
					$.get( "controller/nivelesback.php?oper=deletenivel", 
					{ 
						onlydata : "true",
						id : id 
					}, function(result){
						if(result == 1){
                            notification("Nivel eliminado satisfactoriamente","¡Exito!",'success');
							tbniveles.ajax.reload(null, false);
						} else if(result == 0) {
				            notification("Ha ocurrido un error al eliminar el nivel","Error",'error');
						} else if(result == 2){
							notification('Hay registros asociados a este Nivel, no se puede eliminar.','Advertencia!','warning');
						}
					});

				}
			}, function (isRechazo){  
			}
		);
	}  
	
	$('#tbniveles').on( 'draw.dt', function () {		
		// Dar funcionalidad al botón desactivar
        $('.boton-eliminar').each(function(){
			var id 	   = $(this).attr("data-id");
			var nombre = $("#tbniveles tr#"+id).find('td:nth-child(2)').html();
			$(this).on( 'click', function() {
				eliminarnivel(id,nombre);
//				console.log(id,nombre); 
			});
		});
		// Tooltips
		$('[data-toggle="tooltip"]').tooltip();

    });


