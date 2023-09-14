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
	location.href = 'categoria.php';
}); 

$("#tablacategorias tbody").on('dblclick','tr',function(){
	var id = $(this).attr("id");
	window.location.href = 'categoria.php?id='+id;

});

 
 
 
/* 	$('#tablacategorias thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
				}else if(title == 'Cliente' || title == 'Proyecto'){
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
 */

	$('#tablacategorias thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 400px" /> ');
				}else if(title == 'Clientes'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 400px" /> ');
				}else if(title == 'Proyectos'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 400px" /> ');
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
	tablacategorias = $("#tablacategorias").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
	    ajax: {
	        //url: "controller/categoriasback.php?oper=cargarproyectoscategorias",
	        url: "controller/categoriasback.php?oper=cargarcategorias",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "nombre" } 
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
	            targets: [2],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '50px'},
			{ targets	: 2, width	: '200px' }


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



$('#tablacategorias').on( 'draw.dt', function () {		
	// Dar funcionalidad al botón desactivar
		//evaluarancho('tablacategorias')
		ajustarDropdown();
		ajustarTablas()

/*
    $('.boton-eliminar').each(function(){
		var id 	   = $(this).attr("data-id");
		var marca = $("#tablacategorias tr#"+id).find('td:nth-child(2)').html();
//		console.log("#tablacategorias tr#"+id)


		$(this).on( 'click', function() {
			eliminamarca(id,marca);
		});
	});*/
	// Tooltips
	$('[data-toggle="tooltip"]').tooltip();

});

$(document).on('click','.boton-eliminar', function(e){

    var id = $(this).attr("data-id");
    var nombre = $("#tablacategorias tr#"+id).find('td:nth-child(2)').html();
	eliminar(id,nombre);
 });
 
 //Asociar / Quitar asociación
 $(document).on('click','.boton-asociar', function(e){
	 
    var id = $(this).attr("data-id");
	window.location.href = 'categoriarel.php?id='+id;
	
 });


//LIMPIAR COLUMNAS
$('#limpiarCol').on( 'click', function() {
	$("#tablacategorias").DataTable().search("").draw();
	$('#tablacategorias_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function() {
	tablacategorias.ajax.reload();
    ajustarTablas();
});

    function eliminar(id,nombre){
        console.log(id)
		 $.get( "controller/categoriasback.php?oper=hayRelacion", 
		{  
			id : id 
		}, function(result){
			
			
			var mensaje    	  = "";
			var modulos 	  = "";
			var existe  	  = 0; 
			var correctivos   = result.correctivos;
			var preventivos   = result.preventivos;
			var subcategorias = result.subcategorias; 
			
			existe = (correctivos == 0 && preventivos == 0 && subcategorias == 0) ? 0 : 1;
			
			if(existe == 1){
				mensaje = "Hay registros asociados a esta categoría (";
				if(correctivos == 1){
					modulos += "correctivos, ";
				}
				if(preventivos == 1){
					modulos += "preventivos, ";
				} 
				if(subcategorias == 1){
					modulos += "subcategorias, ";
				}
				modulos = modulos.substring(0, modulos.length -2 );
				
				mensaje += ""+modulos+"), no se puede eliminar.";
				
				notification(mensaje,'Advertencia!','warning');
			}else{
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar la categoría " +nombre+ "?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/categoriasback.php?oper=deletecategorias", 
							{  
								idcategorias : id 
							}, function(result){
								if(result == 1){
									notification('Categoría eliminada satisfactoriamente','Buen trabajo','success');		
									tablacategorias.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar la categoría, intente más tarde','ERROR','error');
								}
							});
						}
					}, function (isRechazo){  
					}
				);
			} 
			  
		},'json');  
	}


