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

$("#nuevapublicidad").click(function(){
	location.href = 'publicidad.php';
});

    $("#tbpublicidades tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'publicidad.php?id='+id;

	});

 
 
 
	$('#tbpublicidades thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

				if( title == 'Titulo'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
				}else if(title == 'Estatus'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 350px" /> ');
				}else if(title == 'Aplicación'){
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
	tbpublicidades = $("#tbpublicidades").DataTable({
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
			$('th#ctitulo input').val(columns[1]['search']['search']); 
			$('th#caplicacion input').val(columns[2]['search']['search']);
            $('th#cestatus input').val(columns[3]['search']['search']);
        },
	    ajax: {
	        url: "controller/publicidadesback.php?oper=cargar",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "titulo" },
			{ 	"data": "aplicacion" },
            { 	"data": "estatus" }
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
				visible: true,
	            className: 'text-left'
	        },
			{
	            targets: [3],
				visible: true,
	            className: 'text-left'
	        },
			{
	            targets: [4],
				visible: true,
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width 	: '200px' },
			{ targets	: 4, width 	: '200px'},


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
    

$('#tbpublicidades').on( 'draw.dt', function () {		
	// Dar funcionalidad al botón desactivar
		ajustarDropdown();
		ajustarTablas()

	$('[data-toggle="tooltip"]').tooltip();

});

$(document).on('click','.boton-eliminar', function(e){

    var id = $(this).attr("data-id");
    var publicidad = $("#tbpublicidades tr#"+id).find('td:nth-child(2)').html();
	eliminapublicidad(id,publicidad);
 });


//LIMPIAR COLUMNAS
$('#limpiarCol').on( 'click', function() {
	$("#tbpublicidades").DataTable().search("").draw();
	$('#tbpublicidades_wrapper thead input').val('').change();
});
//REFRESCAR
$("#refrescar").on('click', function() {
	tbpublicidades.ajax.reload();
    ajustarTablas();
});

function eliminapublicidad(idpublicidad,titulo){
	console.log(idpublicidad)

	swal({
		title: "Confirmar",
		text: "¿Esta seguro de eliminar la Publicidad " +titulo+ "?",
		type: "warning",
		showCancelButton: true,
		cancelButtonColor: 'red',
		confirmButtonColor: '#09b354',
		confirmButtonText: 'Si',
		cancelButtonText: "No"
	}).then(
		function(isConfirm){
			if (isConfirm.value === true) {
				$.get( "controller/publicidadesback.php?oper=deletepublicidad", 
				{ 
					onlydata : "true",
					idpublicidad : idpublicidad,
					titulo		 : titulo
				}, function(result){
					if(result == 1){
						notification('Publicidad eliminada satisfactoriamente','Buen trabajo','success');		
						tbpublicidades.ajax.reload(null, false);
					} else {
						notification('Ha ocurrido un error al eliminar la publicidad, intente más tarde','ERROR','error');
					}
				});
			}
		}, function (isRechazo){  
		}
	);
}