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
	
	$("#nuevopermiso").click(function(){
		location.href = 'permiso.php';
	});
	
	//HEADER
    // $('#tbpermisos thead th').each( function () {
    //     var title = $(this).text();
	// 	var ancho1 = $(this).width();
	// 	var ancho2 = ($(this).width() * 0.4).toFixed(0);
	// 	if ( title !== '' && title !== '-' && title !== 'Acciones') 
	// 		$(this).html( '<input type="text" placeholder="'+title+'" style="width: 100%" /> ' );
	// 	else if (title=='-') 
	// 		$(this).html( '<input id="chkSelectAll" class="fac fac-checkbox fac-white" type="checkbox" value="A11|" />' );
	// 	$(this).width(ancho1);
    // });

	//HEADER
	$('#tbpermisos thead th').each(function() {
		var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		console.log(title);
		console.log(ancho);
		console.log(id);
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				//$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100%" /> ');
				if(title == 'Creación de corretivo' || title == 'Creación de preventivo' || title == 'Cambio de estado Asignado' || title == 'Comentarios públicos' || title == 'Comentarios privados' || title == 'Cambio de solicitante' || title == 'Cambio de estado Reporte pendiente'){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 250px" /> ');
				}else if(title == 'Usuario' || title == 'Adjuntos' ){
					$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 90px" /> ');
				}else if(title == 'Cambio de estado En espera de cliente' || title == 'Cambio de estado En espera de respuesto' || title == 'Programación de preventivos (domingos)' || title == 'Notificación de vida útil de activo' || title == 'Cambio de estado Resuelto'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 350px" /> ');
				}else if(title == 'Creación'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 150px" /> ');
				}else if(title == 'Hora creación'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 150px" /> ');
				}else if(title == 'Unidad Ejecutora'){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 150px" /> ');
				}else{
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (title == 'Acción') {
			var ancho = '50px';
		}
		$(this).width(ancho);
	});
    
    $("#tbpermisos tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		window.location.href = 'permiso.php?id='+id;

	});
	/*tabla*/
	tbpermisos = $("#tbpermisos").DataTable({
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
			$('th#cusuario input').val(columns[2]['search']['search']); 
            $('th#cnoti1 input').val(columns[3]['search']['search']);
            $('th#cnoti2 input').val(columns[4]['search']['search']);
            $('th#cnoti3 input').val(columns[5]['search']['search']);
            $('th#cnoti4 input').val(columns[6]['search']['search']);
            $('th#cnoti5 input').val(columns[7]['search']['search']);
            $('th#cnoti6 input').val(columns[8]['search']['search']);
            $('th#cnoti7 input').val(columns[9]['search']['search']);
            $('th#cnoti8 input').val(columns[10]['search']['search']);
            $('th#cnoti9 input').val(columns[11]['search']['search']);
            $('th#cnoti10 input').val(columns[12]['search']['search']);
            $('th#cnoti11 input').val(columns[13]['search']['search']);
            $('th#cnoti12 input').val(columns[14]['search']['search']);
            $('th#cnoti13 input').val(columns[15]['search']['search']);
        },
	    ajax: {
	        url: "controller/permisosback.php?oper=permisos",
	    },
	    columns	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "usuario" },
			{ 	"data": "noti1" },
			{ 	"data": "noti2" },
			{ 	"data": "noti3" },
			{ 	"data": "noti4" },
			{ 	"data": "noti5" },
			{ 	"data": "noti6" },
			{ 	"data": "noti7" },
			{ 	"data": "noti8" },
			{ 	"data": "noti9" },
			{ 	"data": "noti10" },
			{ 	"data": "noti11" },
			{ 	"data": "noti12" },
			{ 	"data": "noti13" },
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
				
			},
			{
	            targets: [2, 3, 4],
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
			{ targets	: 15, width	: '200px' },
			
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
	// AL CARGARSE LA TABLA
	$('#tbpermisos').on( 'draw.dt', function () {		
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();
    });
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tbpermisos").DataTable().search("").draw();
		$('#tbpermisos_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tbpermisos.ajax.reload();
        ajustarTablas();
    });
	// function eliminarusuariofisica(id,nombre) {
	//     var id = id;
	//     swal({
	//         title: "Confirmar",
	//         text: "¿Esta seguro de eliminar el usuario "+nombre+" ?",
	//         type: "warning",
	//         showCancelButton: true,
	//         cancelButtonColor: 'red',
	//         confirmButtonColor: '#09b354',
	//         confirmButtonText: 'Si',
	//         cancelButtonText: "No"
	//     }).then(
	//         function(isConfirm) {
	//             console.log(isConfirm);
	            
	//             if (isConfirm.value==true) {
	//                 $('#preloader').css('display', 'block');
	//                 $.get("controller/usuariosback.php?oper=eliminarusuariosfisica", {
	//                     onlydata: "true",
	//                     id: id
	//                 }, function(result) {
	//                     if (result == 1) {
	//                         $('#preloader').css('display', 'none');
    //     	               toastr.success("Usuario eliminado satisfactoriamente", "¡Exito!", {
    //                             timeOut: 500000000,
    //                             closeButton: !0,
    //                             debug: !1,
    //                             newestOnTop: !0,
    //                             progressBar: !0,
    //                             positionClass: "toast-top-right",
    //                             preventDuplicates: !0,
    //                             onclick: null,
    //                             showDuration: "300",
    //                             hideDuration: "1000",
    //                             extendedTimeOut: "1000",
    //                             showEasing: "swing",
    //                             hideEasing: "linear",
    //                             showMethod: "fadeIn",
    //                             hideMethod: "fadeOut",
    //                             tapToDismiss: !1
    //                         });
	//                         // RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
	//                         tbpermisos.ajax.reload(null, false);
	//                         tbpermisos.columns.adjust();
	//                     } else {
	//                         $('#preloader').css('display', 'none');
	//                         toastr.error("Error general", "¡Error!", {
    //                             positionClass: "toast-top-right",
    //                             timeOut: 5e3,
    //                             closeButton: !0,
    //                             debug: !1,
    //                             newestOnTop: !0,
    //                             progressBar: !0,
    //                             preventDuplicates: !0,
    //                             onclick: null,
    //                             showDuration: "300",
    //                             hideDuration: "1000",
    //                             extendedTimeOut: "1000",
    //                             showEasing: "swing",
    //                             hideEasing: "linear",
    //                             showMethod: "fadeIn",
    //                             hideMethod: "fadeOut",
    //                             tapToDismiss: !1
    //                         });
	//                     }
	//                 });
	//             }
	//         },
	//         function(isRechazo) {
	//             console.log(isRechazo);
	//             // NADA
	//         }
	//     );
	// }


