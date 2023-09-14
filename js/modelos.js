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

//AJUSTAR DATATABLES
$('.nav-control').on('click', function(e){
	ajustarTablas();
});

$("#nuevomodelo").click(function(){
	location.href = 'modelo.php';
});

    $("#tbmodelos tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'modelo.php?id='+id;

	});


 
    $('#tbmodelos thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre' || title == "Marca"){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}else if(title == 'Descripción'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 400px" /> ');
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
	tbmodelos = $("#tbmodelos").DataTable({
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
            $('th#cmarca input').val(columns[4]['search']['search']);
        },
	    ajax: {
	        url: "controller/modelosback.php?oper=cargar",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "nombre" },
			{ 	"data": "descripcion" },
			{ 	"data": "marca" },
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
	            targets: [2, 3, 4],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width	: '200px' },

			{ 
				targets	: 4,
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
                    }/* 
			          let  height = $('#tbmodelos').height();
			    	  if (height>36 && height <120) {
			    		    $('#tbmodelos').height('120px');
			    		} */
                } );
            });

			//OCULTAR LOADER
			$('#preloader').css('display','none');
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	/*fin tabla*/

	$('#tbmodelos').on( 'draw.dt', function () {		
		// Dar funcionalidad al botón desactivar
/*	    $('.boton-eliminar').each(function(){
			var id 	   = $(this).attr("data-id");
			var nomnbre = $("#tbmodelos tr#"+id).find('td:nth-child(2)').html();
			$(this).on( 'click', function() {
				eliminarmodelo(id,nomnbre);
			});
		});*/
		// Tooltips
		$('[data-toggle="tooltip"]').tooltip();

	});
    
    /*$('#tbmodelos').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })*/
    
    $(document).on('click','.boton-eliminar', function(e){
    
			var id 	   = $(this).attr("data-id");
			var nomnbre = $("#tbmodelos tr#"+id).find('td:nth-child(2)').html();
			eliminarmodelo(id,nomnbre);
     });
    
    

	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tbmodelos").DataTable().search("").draw();
		$('#tbmodelos_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tbmodelos.ajax.reload();
	    ajustarTablas();
	});



	function eliminarmodelo(idmodelos, nombre){
		$.get( "controller/modelosback.php?oper=hayRelacion", 
		{  
			id : idmodelos 
		}, function(result){//
			if(result == 1){
				notification('Hay registros asociados a este Modelo, no se puede eliminar.','Advertencia!','warning');
			}else{
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar el modelo "+nombre+"?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/modelosback.php?oper=deletemodelos", 
							{ 
								onlydata 	: "true",
								idmodelos  : idmodelos,
								nombre		: nombre
							}, function(result){
								if(result == 1){
									notification('Modelo eliminado satisfactoriamente','Buen trabajo!','success');
									// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
									tbmodelos.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar la modelo, intente más tarde','ERROR!','error');
								}
							});

						}
					}, function (isRechazo){ //verificar ok
						// NADA
					}
				);
			}//ELSE
		}); //RESULT RELACION


	}


