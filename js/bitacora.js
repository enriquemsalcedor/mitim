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
	
	$('#tbbitacora thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		console.log(title);
		console.log(ancho);
		console.log(id);
		if (title !== '' && title !== '-' && id !== 'accion') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');
				if(title == 'Usuario' || title == "Módulo"){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 90px" /> ');
				}else if(title == 'Fecha'  ){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
				}else if(title == 'Acción'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 350px" /> ');
				}else if(title == 'Identificador'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 130px" /> ');
				}else if(title == 'Sentencia'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 600x" /> ');
				}else{
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (id == 'accion') {
			var ancho = '50px';
		}
		$(this).width(ancho);
	});


    $("#tbbitacora tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'bitacora-ne.php?id='+id;

	});




		/*tabla*/
	tbbitacora = $("#tbbitacora").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
		serverSide: true,
        serverMethod: 'post',
	    ajax: {
	        url: "controller/bitacoraback.php?oper=listbitacora",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "usuario" },
			{ 	"data": "fecha" },
			{ 	"data": "modulo" },
			{ 	"data": "accion" },/*
			{ 	"data": "identificador" },
			{ 	"data": "sentencia" },*/
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
	            targets: [2, 3, 4, 5/*, 6, 7*/],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width	: '200px' },
			{ targets	: 4, width	: '200px' },
			{ targets	: 5, width	: '200px' },/*
			{ targets	: 6, width	: '200px' },
			{ targets	: 7, width	: '200px' },*/
			
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

                    /*
                    if (this.value != 'A11|') {
                        if (event.which == 13) {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        }
                    }*/

                } );

            });

			//OCULTAR LOADER
			$('#preloader').css('display','none');
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	/*fin tabla*/

			// AL CARGARSE LA TABLA
	$('#tbbitacora').on( 'draw.dt', function () {		
		// TOOLTIPS
		//evaluarancho('tbbitacora')
		ajustarDropdown();
		ajustarTablas()


		$('[data-toggle="tooltip"]').tooltip();
    });
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tbbitacora").DataTable().search("").draw();
		$('#tbbitacora_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tbbitacora.ajax.reload();
        ajustarTablas();
    });


