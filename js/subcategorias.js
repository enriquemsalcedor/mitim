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

	$("#nuevo").click(function(){
		location.href = 'subcategoria.php';
	});

    $("#tablasubcategorias tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'subcategoria.php?id='+id;

	});


	$('#tablasubcategorias thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre' || title == 'Categorías'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 400px" /> ');
				}else {
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}

			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (title == 'Acción') {
			var ancho = '50';

		}

		$(this).width(ancho);
	});

	/*tabla*/
	tablasubcategorias = $("#tablasubcategorias").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
	    ajax: { 
	        url: "controller/subcategoriasback.php?oper=cargarsubcategorias",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "subcategoria" },
			{ 	"data": "categorias" }
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
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '50px'} 


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



$('#tablasubcategorias').on( 'draw.dt', function () {		
	// Dar funcionalidad al botón desactivar
		//evaluarancho('tablasubcategorias')
		ajustarDropdown();
		ajustarTablas()

/*
    $('.boton-eliminar').each(function(){
		var id 	   = $(this).attr("data-id");
		var marca = $("#tablasubcategorias tr#"+id).find('td:nth-child(2)').html();
//		console.log("#tablasubcategorias tr#"+id)


		$(this).on( 'click', function() {
			eliminamarca(id,marca);
		});
	});*/
	// Tooltips
	$('[data-toggle="tooltip"]').tooltip();

});

$(document).on('click','.boton-eliminar', function(e){

    var id = $(this).attr("data-id");
    var nombre = $("#tablasubcategorias tr#"+id).find('td:nth-child(2)').html();
	eliminar(id,nombre);
	
 });


//LIMPIAR COLUMNAS
$('#limpiarCol').on( 'click', function() {
	$("#tablasubcategorias").DataTable().search("").draw();
	$('#tablasubcategorias_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function() {
	tablasubcategorias.ajax.reload();
    ajustarTablas();
});

	function eliminar(id,nombre){
        console.log(id)
		 $.get( "controller/subcategoriasback.php?oper=hayRelacion", 
		{  
			id : id 
		}, function(result){
			
			
			var mensaje    	  = "";
			var modulos 	  = "";
			var existe  	  = 0; 
			var correctivos   = result.correctivos;
			var preventivos   = result.preventivos;
			var categorias 	  = result.categorias; 
			 
			existe = (correctivos == 0 && preventivos == 0 && categorias == 0) ? 0 : 1;
			
			if(existe == 1){
				mensaje = "Hay registros asociados a esta subcategoría (";
				if(correctivos == 1){
					modulos += "correctivos, ";
				}
				if(preventivos == 1){
					modulos += "preventivos, ";
				}
				if(categorias == 1){
					modulos += "categorias, ";
				} 
				modulos = modulos.substring(0, modulos.length -2 );
				
				mensaje += ""+modulos+"), no se puede eliminar.";
				
				notification(mensaje,'Advertencia!','warning');
			}else{
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar la subcategoría " +nombre+ "?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/subcategoriasback.php?oper=deletesubcategorias", 
							{  
								idsubcategorias : id 
							}, function(result){
								if(result == 1){
									notification('Subcategoría eliminada satisfactoriamente','Buen trabajo','success');		
									tablasubcategorias.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar la subcategoría, intente más tarde','ERROR','error');
								}
							});
						}
					}, function (isRechazo){  
					}
				);
			} 
			  
		},'json');  
	}


