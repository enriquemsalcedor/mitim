$("#icono-filtrosmasivos").css("display","none");
	//AJUSTAR DATATABLES
	$('.nav-control').on('click', function(e){
		ajustarTablas();
	});

	$("#nuevoproveedor").click(function(){
		location.href = 'proveedor.php';
	});

    $("#tablaproveedores tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'proveedor.php?id='+id;

	});


	$('#tablaproveedores thead th').each(function() {

		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');

					if (title == ''){
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 20px" /> ');
					}else if (title == 'Cliente' || title == 'Proyecto' || title == 'Proveedor'){
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
					}else if(title == 'Nombre del encargado o supervisor' || title == '¿Cuenta con contrato?' || title == 'Servicio contratado' || title == 'Número de teléfono' || title == 'Horario de atención contratada'){
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 300px" /> ');
					}else if(title == 'Fecha de inicio de contrato' ||  title == 'Correo' || title == '¿Incluye piezas?' || title == 'Fecha de finalización de contrato' ) {
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 250px" /> ');
					}else{
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
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
	tablaproveedores = $("#tablaproveedores").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		searching: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true, 
		serverSide: true,
        serverMethod: 'post',
		select: { style: 'multi' },
		stateLoadParams: function (settings, data) {
            const{columns}=data
			$('th#ccliente input').val(columns[2]['search']['search']); 
            $('th#cproyecto input').val(columns[3]['search']['search']);
            $('th#cproveedor input').val(columns[4]['search']['search']);
            $('th#cnombre input').val(columns[5]['search']['search']);
            $('th#cnumero input').val(columns[6]['search']['search']);
            $('th#ccorreo input').val(columns[7]['search']['search']);
            $('th#ccuenta input').val(columns[8]['search']['search']);
            $('th#cfechainicio input').val(columns[9]['search']['search']);
            $('th#cfechafinal input').val(columns[10]['search']['search']);
            $('th#cservicio input').val(columns[11]['search']['search']);
            $('th#cincluye input').val(columns[12]['search']['search']);
            $('th#chorariodeatención input').val(columns[13]['search']['search']);
            $('th#cutilizara input').val(columns[14]['search']['search']);
        }, 
	    ajax: {
	        url: "controller/proveedoresback.php?oper=proveedores",
	    },
	    columns	: [
			{ 	"data": "id" },					//0
			{ 	"data": "acciones" },			//1
			{ 	"data": "cliente" }, 			//2
			{ 	"data": "proyecto" }, 			//3
			{ 	"data": "nombre" }, 			//4
			{ 	"data": "encargado" },			//5
			{ 	"data": "telefono" },			//6
			{ 	"data": "correo" },				//7
			{ 	"data": "cuentacontrato" },		//8
			{ 	"data": "fechainiciocontrato" },//9
			{ 	"data": "fechafincontrato" },	//10
			{ 	"data": "serviciocontratado" },	//11
			{ 	"data": "incluyepiezas" },		//12
			{ 	"data": "horarioatencioncont" },//13
			{ 	"data": "utilizarasym" },		//14

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
	            targets: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '100px' },
			{ targets	: 2, width	: '200px' },
			{ targets	: 3, width	: '200px' },
			{ targets	: 4, width	: '200px' },
			{ targets	: 5, width	: '200px' },
			{ targets	: 6, width	: '200px' },
			{ targets	: 7, width	: '200px' },
			{ targets	: 8, width	: '200px' },
			{ targets	: 9, width	: '200px' },
			{ targets	: 10, width	: '200px' },
			{ targets	: 11, width	: '200px' },
			{ targets	: 12, width	: '200px' },
			{ targets	: 13, width	: '200px' },
			{ targets	: 14, width	: '200px' },
			
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
	    initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
            /* this.api().columns().every( function () {
                var that = this; 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                } );


            }); */
			//OCULTAR LOADER
			$('#preloader').css('display','none');
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	tablaproveedores.columns().every( function () {
        var that = this;
		$( 'input', this.header() ).keypress(function (event) {
			if (this.value!='A11|') {
				if ( event.which == 13 ) {
					if ( that.search() !== this.value ) {
						that
							.search( this.value )
							.draw();
					}
				}
			}
        });	 
    });
	/*fin tabla*/
    $("#tablaproveedores").on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
        $('#preloader').css( 'display', processing ? 'block' : 'none' );
    })


			// AL CARGARSE LA TABLA
	$('#tablaproveedores').on( 'draw.dt', function () {		
		// Dar funcionalidad al botón desactivar
		//evaluarancho('tablaproveedores')
		ajustarDropdown();
		ajustarTablas()

/*
	    $('.boton-eliminar').each(function(){
			var id 	   = $(this).attr("data-id");
			var nomnbre = $("#tablaproveedores tr#"+id).find('td:nth-child(2)').html();
			$(this).on( 'click', function() {
				eliminar(id,nomnbre);
			});
		});*/
		
		$('.boton-evidencias').each(function(){
			let id = $(this).attr("data-id");	
			let nombre = $("#tablaproveedores tr#"+id).find('td:nth-child(4)').html();			
			$(this).on( 'click', function() {
				abrirsolicitudes(id,nombre);
			});
		});
		
		// Tooltips
		$('[data-toggle="tooltip"]').tooltip();

	});
	
	let dirxdefecto = 'incidente';
	$('#fevidencias').attr('src','filegator/proveedores.php#/?cd=%2F'+dirxdefecto);
	
	const abrirsolicitudes = (id,nombre)=> {
	  let valid = true;
	  if ( valid ) {
		$.ajax({
			  type: 'post',
			  url: 'controller/proveedoresback.php',
			  data: { 
				'oper': 'abrirSolicitudes',
				'id': id		  
			  },
			  success: function (response) {
				$('#fevidencias').attr('src','filegator/proveedores.php#/?cd=proveedores/'+id);
				$('#modalEvidencias').modal('show');
				$('#modalEvidencias .modal-lg').css('width','1000px');
				$('#idsolicitudesevidencias').val(id);
				$('.titulo-evidencia').html('Proveedor: '+nombre+' - Evidencia'); 
			  },
			  error: function () { 
				notification("Ha ocurrido un error al agregar la evidencia, intente más tarde","Error",'error');
			  }
		   }); 
	  }
	  return valid;
	}
	
	$('#modalEvidencias').on('hidden.bs.modal', function(){
        tablaproveedores.ajax.reload(null, false);
    });
	
    $(document).on('click','.boton-eliminar', function(e){
    			let id 	   = $(this).attr("data-id");
    			let nombre = $("#tablaproveedores tr#"+id).find('td:nth-child(2)').html();
    				eliminar(id,nombre);
     });

	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tablaproveedores").DataTable().search("").draw();
		$('#tablaproveedores_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tablaproveedores.ajax.reload();
	    ajustarTablas();
	});

	function ajustarTablas(){
		if (screen.width > 1024) {
			//console.log('screen.width: '+screen.width);
			$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
			$('.dataTables_scrollHead table').width('100%');
			$('.dataTables_scrollBody table').width('100%');
		}
	}	


	function eliminar(id,nombre){
		$.get( "controller/proveedoresback.php?oper=hayRelacion", {  
			id : id 
		}, function(result){//
			if(result == 1){
				notification('Hay registros asociados a esta Proveedor, no se puede eliminar.','Advertencia!','warning');
			}else if(result==0){

					swal({
						title: "Confirmar",
						text: "¿Esta seguro de eliminar el proveedor "+nombre+"?",
						type: "warning",
						showCancelButton: true,
						cancelButtonColor: 'red',
						confirmButtonColor: '#09b354',
						confirmButtonText: 'Si',
						cancelButtonText: "No"
					}).then(
						function(isConfirm){
							if (isConfirm.value === true) {
								$.get( "controller/proveedoresback.php?oper=eliminarProveedor", 
								{ 
									onlydata : "true",
									id : id 
								}, function(result){
									if(result == 1){
										notification('Proveedor eliminado satisfactoriamente','Buen trabajo','success');		
										tablaproveedores.ajax.reload(null, false);
									} else {
										notification('Ha ocurrido un error al eliminar el proveedor, intente más tarde','ERROR','error');
									}
								});

							}
						}, function (isRechazo){ //verificar ok
							// NADA
						}
					);
				}
			});

	}


