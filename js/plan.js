var incidenteselect = '';
var tablactividades;
var filasSeleccionadas = new Array();

ajustarDropdown();

$("#nuevo").on("click",function(){
	location.href = 'planactividad.php';
});
$("#listado").on("click",function(){
	location.href = 'plan.php';
});

$("#generarpreventivos").on("click",function(){
	$('#modalplangenerar').modal('show');
})
;
$("#filtrosmasivos").on("click",function(){
	$('#modalfiltrosmplan').modal('show');
});
	
function abrirGenerarOrdenes(){
	$("#filtro-desde, #filtro-hasta").val('');
	$('#idclientes, #idproyectos').val(null).trigger("change");
	$('#modalplangenerar').modal('show');
}
function limpiarGenerarOrd(){
	$('#filtro-desde, #filtro-hasta').val('');
	$('#idclientes, #idproyectos').val(null).trigger("change");
}
$('#modalplangenerar').on('hidden.bs.modal', function(){
limpiarGenerarOrd();
});


 
//$(document).ready(function() {

	//AJUSTAR DATATABLES
	function ajustarTablas(){
		if (screen.width > 1024) {
			//console.log('screen.width: '+screen.width);
			$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
			$('.dataTables_scrollHead table').width('100%');
			$('.dataTables_scrollBody table').width('100%');
		}
	}
	$('.nav-control').on('click', function(e){
		ajustarTablas();
	});
	
	//HEADER
    $('#tablactividades thead th').each( function () {
        var title = $(this).text();
		var id = $(this).attr('id');
		var ancho = $(this).width();
		if ( title !== '' && title !== '-' && title !== 'Acción'){
			if (screen.width > 1024) {
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100%" /> ' );
			}else{
				$(this).html( '<input type="text" placeholder="'+title+'" id="f'+id+'" style="width: 100px" /> ' );
			}
		}else if(title == 'Acción'){
			var ancho = '50px';
		}
		$(this).width(ancho);
    });
	var num = 0;
	//TABLA
	tablactividades = $("#tablactividades").DataTable({
		//scrollY: '100%',
		scrollX: false,
		scrollCollapse: false,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		responsive:true,
		serverside:true,
		ajax		: {
			url	: "controller/planactividadesback.php?oper=actividades"
		},		
		columns	: [ 
			{ 	"data": "check"},				//0 
			{ 	"data": "acciones"},            //1
			{ 	"data": "id"},					//2
			{ 	"data": "titulo" },				//3	
			{ 	"data": "descripcion"},			//4		 
			{ 	"data": "diainiciofrecuencia"},	//5
			{ 	"data": "tipo"},				//6	
			{ 	"data": "responsable"},			//7	
			{ 	"data": "frecuencia"},			//8
			{ 	"data": "cliente" },			//9	
			{ 	"data": "proyecto" }			//10
			],
		rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
			{
				targets	: [0,2],
				visible	: false
			},
			{ targets	: 0, width	: '5%' },
			{ targets	: 1, width	: '5%' },
			{ targets	: 2, width	: '20%' },
			{ targets	: 3, width	: '20%' }, 
			{ targets	: 4, width	: '15%' }, 
			{ targets	: 5, width	: '10%' }, 
			{ targets	: 6, width	: '5%' }, 
			{ targets	: 7, width	: '10%' }, 
			{ targets	: 8, width	: '10%' },  
			{ targets	: 6, width	: '5%', class : 'text-right' },
		],
		language: {
		    url: "js/Spanish.json",
		},
		lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
		initComplete: function () {
			ajustarTablas();
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
		dom: '<"toolbarC toolbarDT">Blfrtip'
	}); 
	 
	
	$('#limpiarFiltros').click(function(){
		tablactividades.state.clear();
		window.location.reload();
	});
	
	tablactividades.columns().every( function () {
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
	
	// AL CARGARSE LA TABLA
	$('#tablactividades').on( 'draw.dt', function () {		
		// DAR FUNCIONALIDAD AL BOTON ELIMINAR
        $('.eliminar-cliente').each(function(){
			var id = $(this).attr("data-id");
			var nombre = $("#tablactividades tr#"+id).find('td:nth-child(1)').html();
			$(this).on( 'click', function() {
				eliminarcliente(id,nombre);
			});
		});
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();
    });
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tablactividades").DataTable().search("").draw();
		$('#tablactividades_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tablactividades.ajax.reload();
        ajustarTablas();
    });
	$("#tablactividades tbody").on('dblclick','tr',function(){ 
		var id = $(this).attr("id");
		location.href="planactividad.php?id="+id;
	})
	
$('#tablactividades').on('processing.dt', function (e, settings, processing) {
        console.log("cargo proceso");
    $('#preloader').css( 'display', processing ? 'block' : 'none' );
});
	/*$("#tablactividades tbody").on('dblclick','tr',function(){
		var id = $(this).attr("id");
		abrirdialogEditarActividad(id);
	});
	
	$('#tablactividades tbody').on('click', 'tr', function () {
		$(this).closest('tr').toggleClass('selected');
		var json = tablactividades.rows('.selected').data();
		filasSeleccionadas = [];
		for (var i=0; i<json.length; i++)
			filasSeleccionadas.push(json[i].id);		
	});
	
	 $('#chkSelectAll').click(function(){
		if($("#chkSelectAll").is(':checked'))
			seleccionarTodas();
		else
			quitarSelecciones();
	});
	function seleccionarTodas() {
		$('#tablactividades > tbody  > tr').each(function() {
			$(this).addClass('selected');
		});
		$(this).closest('tr').toggleClass('selected');
		var json = tablactividades.rows('.selected').data();
		filasSeleccionadas = [];
		for (var i=0; i<json.length; i++)
			filasSeleccionadas.push(json[i].id);
	}
	
	function quitarSelecciones() {
		$('#tablactividades > tbody  > tr').each(function() {
			$(this).removeClass('selected');
		});
		filasSeleccionadas = [];
	} */
	 
	// AL CARGARSE LA TABLA
	$('#tablactividades').on( 'draw.dt', function () {
		// DAR FUNCIONALIDAD AL BOTON ELIMINAR
        $('.eliminar-actividad').each(function(){
			var id = $(this).attr("data-id");
			var nombre = $(this).parent().parent().next().next().next().html();
			$(this).on( 'click', function() {
				eliminaractividad(id);
			});
		});
		$('.abrir-evidencias').each(function(){
			var id = $(this).attr("data-id");
			$(this).on( 'click', function() {
				var reg = $(this).closest('tr');
				var row = tablactividades.row(reg).data();
				abrirevidencias(id);
			});
		});
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();
    });
		
	// ELIMINAR ACTIVIDAD
	function eliminaractividad(id){
		var id = id;
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el registro?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm){
					$.get( "controller/planactividadesback.php?oper=eliminaractividad", 
					{ 
						onlydata : "true",
						id : id
					}, function(result){
						if(result == 1){
						    notification("Registro eliminado satisfactoriamente","¡Exito!",'success');		
							tablactividades.ajax.reload(null, false);
						} else {
							notification("¡Error!","Ha ocurrido un error al eliminar el registro, intente más tarde","error"); 
						}
					});
				}
			}, function (isRechazo){
				// NADA
			}
		);
	} 
//});
		//CLIENTES
		$.get( "controller/combosback.php?oper=clientes", { idempresas: 1 }, function(result){ 
			$("#idclientes").empty();
			$("#idclientes").append(result);
		});
		
	//CLIENTES / PROYECTOS - SITIOS
	$('#idclientes').on('select2:select',function(){
		var idclientes = $("#idclientes option:selected").val();
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
			$("#idproyectos").empty();
			$("#idproyectos").append(result);
		});				
	});	
		function validarform(desde,hasta,idclientes,idproyectos){
		var respuesta = 1;
		
		if(desde == ""){ 
			notification("Error","El campo Desde es obligatorio",'warning'); 
			respuesta = 0;
		}else if (hasta == ""){ 
			notification("Error","El campo Hasta es obligatorio",'warning'); 
			respuesta = 0;
		}else if (idclientes != '-' && (idclientes == "" || idclientes == 0 || idclientes == undefined) ){ 
			notification("Error","El campo Cliente es obligatorio",'warning');
			respuesta = 0;
		}else if (idproyectos != '-' && (idproyectos == "" || idproyectos == 0 || idproyectos == undefined) ){ 
			notification("Error","El campo Proyecto es obligatorio",'warning');
			respuesta = 0;
		}
		return respuesta;
	} 
	function generarOrdenes() {
		//	VALIDA //	
		/*
		var valid = false;
		if($('#filtro-desde').val()!='') valid = true;
		else{$('#filtro-desde').focus(); setTimeout(function(){$(".fixed-plugin .show-dropdown").addClass('open');},100);return;}
		if($('#filtro-hasta').val()!='') valid = true;
		else{$('#filtro-hasta').focus(); setTimeout(function(){$(".fixed-plugin .show-dropdown").addClass('open');},100);return;}*/
		var desde = $("#filtro-desde").val();
		var hasta = $("#filtro-hasta").val();
		var idclientes  = $("#idclientes").val();
		var idproyectos = $("#idproyectos").val();

	  if(validarform(desde,hasta,idclientes,idproyectos)== 1) {
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: 'controller/planactividadesback.php',
			data: { 
				'oper': 'generarOrdenes',
				'desde': desde,
				'hasta': hasta,
				'idclientes': idclientes,
				'idproyectos': idproyectos
			},
			beforeSend: function() {
				$('#preloader').css('display','block');
			},
			success: function (response) {
				$('#preloader').css('display','none');
				if(response.success) {
				    swal("Buen trabajo!", "Se generaron " + response.ordenes + " órdenes de trabajo para el periodo indicado.", "success");
				}else{
				    notification("Error","No hay ordenes para generar.",'warning');
				}  
				$('#modalplangenerar').modal('hide');
			},
			error: function () {
				$('#preloader').css('display','none'); 
				notification("Error","Ha ocurrido un error al generar las órdenes.",'warning');
			}
		 }); 
	  }
	} 

//var dirxdefecto = 'correctivo';
//$('#fevidencias').attr('src','filegator/plan.php#/?cd=%2F'+dirxdefecto); 
$('#filtro-desde,  #filtro-hasta').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, lang : 'es'  });
	//incializa los select2
$("select").select2();


