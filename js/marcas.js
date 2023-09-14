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

$("#nuevamarca").click(function(){
	location.href = 'marca.php';
});

    $("#tbmarcas tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'marca.php?id='+id;

	});

 
 
 
	$('#tbmarcas thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
				}else if(title == 'Descripción'){
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
	tbmarcas = $("#tbmarcas").DataTable({
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
	        url: "controller/marcasback.php?oper=cargar",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "nombre" },
			{ 	"data": "descripcion" }
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
    $('#tbsla').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })
    

$('#tbmarcas').on( 'draw.dt', function () {		
	// Dar funcionalidad al botón desactivar
		//evaluarancho('tbmarcas')
		ajustarDropdown();
		ajustarTablas()

/*
    $('.boton-eliminar').each(function(){
		var id 	   = $(this).attr("data-id");
		var marca = $("#tbmarcas tr#"+id).find('td:nth-child(2)').html();
//		console.log("#tbmarcas tr#"+id)


		$(this).on( 'click', function() {
			eliminamarca(id,marca);
		});
	});*/
	// Tooltips
	$('[data-toggle="tooltip"]').tooltip();

});

$(document).on('click','.boton-eliminar', function(e){

    var id = $(this).attr("data-id");
    var marca = $("#tbmarcas tr#"+id).find('td:nth-child(2)').html();
	eliminamarca(id,marca);
 });


//LIMPIAR COLUMNAS
$('#limpiarCol').on( 'click', function() {
	$("#tbmarcas").DataTable().search("").draw();
	$('#tbmarcas_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function() {
	tbmarcas.ajax.reload();
    ajustarTablas();
});

    function eliminamarca(idmarcas,nombre){
        console.log(idmarcas)
		$.get( "controller/marcasback.php?oper=hayRelacion", 
		{  
    		onlydata : "true",
			id : idmarcas 
		}, function(result){//
			if(result == 1){
				notification('Hay registros asociados a esta Marca, no se puede eliminar.','Advertencia!','warning');
			}else{

				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar la Marca " +nombre+ "?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/marcasback.php?oper=deletemarcas", 
							{ 
								onlydata : "true",
								idmarcas : idmarcas,
								nombre		: nombre
							}, function(result){
								if(result == 1){
									notification('Marca eliminada satisfactoriamente','Buen trabajo','success');		
									tbmarcas.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar la marca, intente más tarde','ERROR','error');
								}
							});
						}
					}, function (isRechazo){  
					}
				);

			}//ELSE
		}); //RESULT RELACION

	}


