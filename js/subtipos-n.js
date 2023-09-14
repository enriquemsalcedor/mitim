$("#icono-filtrosmasivos").css("display","none");
$(document).ready(function() { 
    
    var cvisible = false;
	if(nivel != 7){
		cvisible = true;
	}
	
	console.log(nivel,cvisible)
	/*-TABLE------------------------------------------------------------------*/

    $("#tbsubtipo tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'subtipo.php?id='+id;

	});


    $('#tbsubtipo thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
	    console.log({id,title,ancho});
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				if(title == "Tipo"){
				    console.log(title);
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
    			}else if(title == "Subtipo"){
    			    console.log(title);
    				$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
    			}else if(title == "Cliente"){
    			    console.log(title);
    				$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
    			}else if(title == "Proyecto"){
    			    console.log(title);
    				$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
    			}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (title == 'Acción') {
			var ancho = '200px';
		}
		$(this).width(ancho);
	}); 
	/*tabla*/
	tbsubtipo = $("#tbsubtipo").DataTable({
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
            console.log(columns)
			$('th#ctipo input').val(columns[2]['search']['search']); 
            $('th#csubtipo input').val(columns[3]['search']['search']);
            $('th#ccliente input').val(columns[4]['search']['search']);
            $('th#cproyecto input').val(columns[5]['search']['search']);
        },
	    ajax: {
	        url: "controller/subtiposback-n.php?oper=subtipos",
	    },
	    columns	: [
			{ 	"data": "id" },					//0
			{ 	"data": "acciones" },			//1
			{ 	"data": "tipo" },				//2
			{ 	"data": "subtipo" }, 			//3 
			{ 	"data": "cliente" }, 			//4 
			{ 	"data": "proyecto" }			//5 
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
			{ targets	: 2, width	: '100px' },
			{ targets	: 3, width	: '100px' },
			{ targets	: 4, width	: '100px' },
			{ targets	: 5, width	: '100px' }
			
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
	    initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
    
           let  height = $('#tbsubtipo').height();

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
	$('#tbsubtipo').on( 'draw.dt', function () {		
		// Dar funcionalidad al botón desactivar
		evaluarancho('tbsubtipo')
		ajustarTablas()

/*	    $('.boton-eliminar').each(function(){
			let id 	   = $(this).attr("data-id");
			let nomnbre = $("#tbsubtipo tr#"+id).find('td:nth-child(2)').html();
			
			$(this).on( 'click', function() {
				eliminarsubtipo(id,nomnbre);
			});
		});*/
		// Tooltips
		$('[data-toggle="tooltip"]').tooltip();

	});
    $('#tbsubtipo').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })
    $(document).on('click','.boton-eliminar', function(e){
    
			let id 	   = $(this).attr("data-id");
			let nomnbre = $("#tbsubtipo tr#"+id).find('td:nth-child(2)').html();
			eliminarsubtipo(id,nomnbre);
     });




	/*-EVENTOS----------------------------------------------------------------*/
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tbsubtipo").DataTable().search("").draw();
		$('#tbsubtipo_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tbsubtipo.ajax.reload();
	    ajustarTablas();
	});
    
    $("#btn-nuevoSubtipo").on("click",function(){
	    location.href = "subtipo.php";
	})
    /*-FUNCIONES--------------------------------------------------------------*/
    function ajustarTablas(){
    	if (screen.width > 1024) {
    		//console.log('screen.width: '+screen.width);
    		$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    		$('.dataTables_scrollHead table').width('100%');
    		$('.dataTables_scrollBody table').width('100%');
    	}
    }
    /*-PETICIONE--------------------------------------------------------------*/
    const eliminarsubtipo=(id,nombre)=>{
        $.get( "controller/subtiposback.php?oper=hayRelacion", 
		{  
			id : id 
		}, function(result){
			if(result == 1){
				notification('Hay activos asociados a este Subtipo, no se puede eliminar',"Advertencia!","warning")
			}else{ 
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar el Subtipo "+nombre+"?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then((isConfirm)=>{

  				    if (isConfirm.value === true) {
//					if(result.value){
//					    console.log("eliminar")
//						if (isConfirm){
							$.get( "controller/subtiposback.php?oper=eliminarSubtipo", 
							{ 
								onlydata : "true",
								id 	     : id,
								nombre   : nombre
							}, function(result){
								if(result == 1){
									tbsubtipo.ajax.reload(null, false); 
							        notification('SubTipo eliminado satisfactoriamente',"Buen trabajo!","success")
								} else {
									notification('Error al eliminar!',"ERROR!!","error")
								}
							});
//						}
//					}else if(result.dismiss){
  				    }else{
					    console.log("no eliminar")
					}
				});
			}
		});
    }
});


