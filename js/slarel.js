
$(document).ready(function() {
$('.tipo').html('Asociar Prioridad');   
$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");

	$("#listado").on("click",function(){
		location.href = 'prioridades.php';
	});
	
	$.get( "controller/combosback.php?oper=clientes", { idempresas: "1" }, function(result){  
		$("#idcliente").empty();
		$("#idcliente").append(result);  
	});

	$('#idcliente').on('select2:select',function(){
		
		let idclientes = $("#idcliente").val();
		
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){  
			$("#idproyecto").empty();
			$("#idproyecto").append(result);  
		});
	});  
 
	var getUrl=getQueryVariable('id'); 
	idprioridad =getUrl!==false?getUrl:0;  

	tablacp = $("#tablacp").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		ajax: {
			url:`controller/slaback.php?oper=cargarprioridadesclientes&idprioridad=${idprioridad}`,
		},
		columns	: [
			{ 	"data": "id" },			//0
			{ 	"data": "acciones" },	//1 
			{ 	"data": "cliente" }, 	//2  
			{ 	"data": "proyecto" } 	//3
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
				targets: [2, 3],
				className: 'text-left'
			},
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '80px' },
			{ targets	: 2, width	: '80px' },
			{ targets	: 3, width	: '80px' } 
		],
		language: {
			url: "js/Spanish.json",
		},
		lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
		initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
			let  height = $('#tabla').height();
			this.api().columns().every( function () {
				var that = this; 
				$( 'input', this.header() ).on( 'keyup change clear', function () {
					if (that.search() !== this.value ) {
						that.search( this.value ).draw();
					}
				} );
			});
			//OCULTAR LOADER
			$('#preloader').css('display','none');
		},
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	
	const vasociar=(idcliente,idproyecto)=>{ 
		var respuesta = 1; 
			if (idcliente =='' || idcliente ==null || idcliente ==undefined || idcliente =='Seleccione' || idcliente ==0){
				 notification('El campo Cliente es obligatorio!',"Advertencia!","warning")
				respuesta = 0;
			}if (idproyecto =='' || idproyecto ==null || idproyecto ==undefined || idproyecto =='Seleccione' || idproyecto ==0){
				 notification('El campo Proyecto es obligatorio!',"Advertencia!","warning")
				respuesta = 0;
			} 
		  
		return respuesta;
	}
	
	const vaciarCampos = ()=>{ 
		$('#idcliente').val(null).trigger("change");
		$('#idproyecto').val(null).trigger("change"); 
	}
	
	const guardar = ()=>{
		let respuesta  	=	 0;
		let id 		 	= idprioridad
		let idcliente	= $('#idcliente').val();   
		let idproyecto	= $('#idproyecto').val();  
		
		if(vasociar(idcliente,idproyecto)== 1&& respuesta == 0){
			$.ajax({
				type: 'post', 
				url: `controller/slaback.php?oper=asociarprioridadesclientes`,
				data: {   
					'id'   	 	: id, 
					'idcliente' : idcliente,
					'idproyecto': idproyecto 
				},
				success: function (response) {	
					if(response == 1){ 
						vaciarCampos();
						swal({		
									title: 'Prioridad asociada satisfactoriamente',	
									text: "¿Desea asociar otra prioridad?",
									type: "success",
									allowEscapeKey : false,
									allowOutsideClick: false,
									showCancelButton: true,
									cancelButtonColor: 'red',
									confirmButtonColor: '#09b354',
									confirmButtonText: 'Sí',
									cancelButtonText: "No"
							}).then(function(isConfirm) {
								console.log(isConfirm)
								if (isConfirm.value === true) {
									tablacp.ajax.reload(null, false);
									document.getElementById('idcliente').focus();
								}else{
									location.href = "prioridades.php";
								}
							}); 
					}else if(response == 2){
						notification('La asociación ya existe!',"Advertencia!","warning")
					}else{
						notification('Error al guardar!',"ERROR!!","error")
					} 										
				},
				error: function () {
					notification('Ha ocurrido un error al grabar el Registro, intente mas tarde',"ERROR!!","error")
				}
			});
		}  
	}
	
	$("#guardar").on("click",function(){		
		guardar(); 
	});
	
	if(getUrl!==false){
	    jQuery.ajax({
           url: "controller/slaback.php?oper=getsla&idsla="+idprioridad,
           dataType: "json",
           success: function(item) {
				let tipo = "Prioridad";
                $("#nombreprioridad").text(`${tipo}: ${item.prioridad}`);   
           }, 
        }); 
	}
	
	$(document).on('click','.boton-eliminar', function(e){
	 
		var id 		       = $(this).attr("data-id"); 
		var idcliente      = $(this).attr("data-idcliente"); 
		var idproyecto     = $(this).attr("data-idproyecto"); 
		var idprioridad    = $(this).attr("data-idprioridad"); 
		
		eliminar(id,idcliente,idproyecto,idprioridad);
		
	});
	
	//Quitar asociación
	const eliminar = (id,idcliente,idproyecto,idprioridad)=>{
		 $.get( "controller/slaback.php?oper=hayRelacionPc", 
		{  
			id 		       : id,
			idcliente      : idcliente,
			idproyecto     : idproyecto,
			idprioridad    : idprioridad
		}, function(result){
			
			if(result == 1){
				notification('El prioridad tiene registros asociados, no se puede eliminar.','Advertencia','warning');
			}else{
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar la asociación a la prioridad ?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/slaback.php?oper=eliminarprioridadesclientes", 
							{  
								id : id
								
							}, function(result){
								if(result == 1){
									notification('Asociación eliminada satisfactoriamente','Buen trabajo','success');		
									tablacp.ajax.reload(null, false);
								} else {
									notification('Ha ocurrido un error al eliminar la asociación, intente más tarde','ERROR','error');
								}
							});
						}
					}, function (isRechazo){  
					}
				);
			}

		},'json'); 
	} 
});

	$("select").select2();


