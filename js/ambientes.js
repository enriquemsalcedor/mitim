$("#icono-filtrosmasivos").css("display","none");
$(document).ready(function() {     


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

$("#nuevoambiente").click(function(){
	location.href = 'ambiente.php';
});

    $("#tbsitios tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'ambiente.php?id='+id;

	});


	$('#tbsitios thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if(title == 'Nombre' || title=='Responsables' 	){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 300px" /> ');
				}else if(title == 'Clientes'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 250px" /> ');
				}else if(title == 'Proyectos'){
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

	var cvisible = false;
	if(nivel != 7){
		cvisible = true;
	}

	console.log(nivel,cvisible)

		/*tabla*/
	tbsitios = $("#tbsitios").DataTable({
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
            $('th#cresponsables input').val(columns[3]['search']['search']);
            $('th#cclientes input').val(columns[4]['search']['search']);
            $('th#cproyectos input').val(columns[5]['search']['search']);
        },
	    ajax: {
	        url: "controller/ubicacionesback.php?oper=sitios",
	    },
	    columns	: [
			{ 	"data": "id" },					//0
			{ 	"data": "acciones" },			//1
			{ 	"data": "unidad" }, 			//2  
			{ 	"data": "responsables" },		//3  
			{ 	"data": "clientes" }, 		//4
            { 	"data": "proyectos" } 		//5
		],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTOD id()
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
				targets: [2,4,5],
				visible: cvisible,
				
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
    
           let  height = $('#tbsitios').height();

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




	$('#tbsitios').on( 'draw.dt', function () {		
		// Dar funcionalidad al botón desactivar
		//evaluarancho('tbsitios')
		ajustarDropdown();
		ajustarTablas()


/*	    $('.boton-eliminar').each(function(){
			var id 	   = $(this).attr("data-id");
			var nomnbre = $("#tbsitios tr#"+id).find('td:nth-child(2)').html();
			$(this).on( 'click', function() {
				eliminarsitio(id,nomnbre);
			});
		});*/
		// Tooltips
		$('[data-toggle="tooltip"]').tooltip();

	});

    $(document).on('click','.boton-eliminar', function(e){
    
			var id 	   = $(this).attr("data-id");
			var nomnbre = $("#tbsitios tr#"+id).find('td:nth-child(2)').html();
			eliminarsitio(id,nomnbre);
     });
    
    $('#tbsitios').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })       
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tbsitios").DataTable().search("").draw();
		$('#tbsitios_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tbsitios.ajax.reload();
	    ajustarTablas();
	});



    function eliminarsitio(id,nombre){


		var idactivos = id;
		$.get( "controller/ubicacionesback.php?oper=hayRelacion", 
		{  
			id : id 
		}, function(result){//
			if(result == 1){
				notification('Hay registros asociados a esta Ubicación, no se puede eliminar.','Advertencia!','warning');
			}else{
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar la ubicación "+nombre+"?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/ubicacionesback.php?oper=deletesitio", 
							{ 
								onlydata : "true",
								id : id 
							}, function(result){
								if(result == 1){
									notification('Ubicación eliminada satisfactoriamente','Buen trabajo!','success');
									tbsitios.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar la ubicación, intente más tarde','ERROR!','error');
								}
							});

						}
					}, function (isRechazo){ //verificar ok
						// NADA
					}
				);//

			}//ELSE
		}); //RESULT RELACION

	}//FUNCION

});


