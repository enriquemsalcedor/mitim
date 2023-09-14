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
    	location.href = 'proyecto.php';
    });

    $("#tablaproyectos tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'proyecto.php?id='+id;
	});
 
	$('#tablaproyectos thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Nombre'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 400px" /> ');
				}else{ 
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
	tablaproyectos = $("#tablaproyectos").DataTable({
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
        },
	    ajax: {
	        url: "controller/proyectosback.php?oper=cargarproyectos",
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
			{ targets	: 1, width	: '50px' },
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



$('#tablaproyectos').on( 'draw.dt', function () {		
	// Dar funcionalidad al botón desactivar
		//evaluarancho('tablaproyectos')
		ajustarDropdown();
		ajustarTablas()

/*
    $('.boton-eliminar').each(function(){
		var id 	   = $(this).attr("data-id");
		var marca = $("#tablaproyectos tr#"+id).find('td:nth-child(2)').html();
//		console.log("#tablaproyectos tr#"+id)


		$(this).on( 'click', function() {
			eliminamarca(id,marca);
		});
	});*/
	// Tooltips
	$('[data-toggle="tooltip"]').tooltip();

});

$(document).on('click','.boton-eliminar', function(e){

    var id = $(this).attr("data-id");
    var nombre = $("#tablaproyectos tr#"+id).find('td:nth-child(2)').html();
	eliminarproyectos(id,nombre);
 });


//LIMPIAR COLUMNAS
$('#limpiarCol').on( 'click', function() {
	$("#tablaproyectos").DataTable().search("").draw();
	$('#tablaproyectos_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function() {
	tablaproyectos.ajax.reload();
    ajustarTablas();
});
	
	function eliminarproyectos(id){
		//var iddpto = id;
		
		//Verificar relaciones
		$.get( "controller/proyectosback.php?oper=hayRelacionPro", 
		{  
			id 	 : id  
		}, function(result){
			 
			var mensaje    	  = "";
			var modulos 	  = "";
			var existe  	  = 0; 
			var correctivos   = result.correctivos;
			var preventivos   = result.preventivos;
			var postventas 	  = result.postventas; 
			
			existe = (correctivos == 0 && preventivos == 0 && postventas == 0) ? 0 : 1;
			
			if(existe == 1){
				mensaje = `Hay registros asociados a este proyecto (`;
				
				if(correctivos == 1) modulos += "correctivos, ";
				if(preventivos == 1) modulos += "preventivos, ";
				if(postventas == 1) modulos += "postventas, "; 
				
				modulos = modulos.substring(0, modulos.length -2 );
				
				mensaje += ""+modulos+"), no se puede eliminar.";
				
				notification(mensaje,'Advertencia!','warning');
			}else{
				swal({
        			title: "Confirmar",
        			text: "¿Esta seguro de eliminar el Proyecto ?",
        			type: "warning",
        			showCancelButton: true,
        			cancelButtonColor: 'red',
        			confirmButtonColor: '#09b354',
        			confirmButtonText: 'Si',
        			cancelButtonText: "No"
        		}).then(
        			function(isConfirm){
        				if (isConfirm.value === true) {
        				     $.get( "controller/proyectosback.php?oper=deleteproyectos", 
            					{ 
            						onlydata : "true",
            						id : id  
            					}, function(result){
            						if(result == 1){ 
            							notification('Exito!','Proyecto eliminado satisfactoriamente','success');
            							tablaproyectos.ajax.reload(null, false);
            						} else { 
            							notification('Exito!','Ha ocurrido un error al eliminar el Proyecto, intente más tarde','success');
            						}
            					});
        				}
        			}, function (isRechazo){ 
        				
        			}
        		);  
			} 
		},'json');
		
	/*	$.get( "controller/proyectosback.php?oper=existeincidentesproy", 
			{ 
				onlydata : "true",
				id       : id 
			}, function(result){
			    
				if(result.incidentes == 1 && result.categorias == 1){ 
					notification('Error!','Hay Incidentes y Categorías asociadas a este proyecto, no se puede eliminar.','error');
				} else if(result.incidentes == 1 && result.categorias == 0) { 
					notification('Error!','Hay Incidentes asociados a este proyecto, no se puede eliminar.','error');
				} else if(result.incidentes == 0 && result.categorias == 1) { 
				   notification('Error!','Hay Categorías asociadas a este proyecto, no se puede eliminar.','error');
				} else { 
				    $.get( "controller/proyectosback.php?oper=deleteproyectos", 
					{ 
						onlydata : "true",
						id : id  
					}, function(result){
						if(result == 1){ 
							notification('Exito!','Proyecto eliminado satisfactoriamente','success');
							tablaclientes.ajax.reload(null, false);
						} else { 
							notification('Exito!','Ha ocurrido un error al eliminar el Proyecto, intente más tarde','success');
						}
					});
				} 
				
			},'json'); */
					
					
	/*	swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el Proyecto ?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm.value === true) {
				    
				}
			}, function (isRechazo){ 
				
			}
		);*/
	}


