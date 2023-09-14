
$(document).ready(function() {
$('.tipo').html('Asociar Proveedor');   
$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");

	$("#listado").on("click",function(){
		location.href = 'proveedores.php';
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
	idproveedor =getUrl!==false?getUrl:0;  

	tablacp = $("#tablacp").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		ajax: {
			url:`controller/proveedoresback.php?oper=cargarproveedoresclientes&idproveedor=${idproveedor}`,
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
		let id 		 	= idproveedor
		let idcliente	= $('#idcliente').val();   
		let idproyecto	= $('#idproyecto').val();  
		
		if(vasociar(idcliente,idproyecto)== 1&& respuesta == 0){
			$.ajax({
				type: 'post', 
				url: `controller/proveedoresback.php?oper=asociarproveedoresclientes`,
				data: {   
					'id'   	 	: id, 
					'idcliente' : idcliente,
					'idproyecto': idproyecto 
				},
				success: function (response) {	
					if(response == 1){ 
						vaciarCampos();
						swal({		
									title: 'Estado asociado satisfactoriamente',	
									text: "¿Desea asociar otro proveedor?",
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
									location.href = "proveedores.php";
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
           url: "controller/proveedoresback.php?oper=getproveedor&idproveedores="+idproveedor,
           dataType: "json",
           success: function(item) {
				let tipo = "Proveedor";
                $("#nombreproveedor").text(`${tipo}: ${item.nombre}`);   
           }, 
        }); 
	}
	
	$(document).on('click','.boton-eliminar', function(e){
	 
		var id 		       = $(this).attr("data-id"); 
		var idcliente      = $(this).attr("data-idcliente"); 
		var idproyecto     = $(this).attr("data-idproyecto"); 
		var idproveedor    = $(this).attr("data-idproveedor"); 
		
		eliminar(id,idcliente,idproyecto,idproveedor);
		
	});
	
	//Quitar asociación
	const eliminar = (id,idcliente,idproyecto,idproveedor)=>{
		 $.get( "controller/proveedoresback.php?oper=hayRelacionPc", 
		{  
			id 		       : id,
			idcliente      : idcliente,
			idproyecto     : idproyecto,
			idproveedor    : idproveedor
		}, function(result){
			
			if(result == 1){
				notification('El proveedor tiene registros asociados, no se puede eliminar.','Advertencia','warning');
			}else{
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar la asociación al proveedor ?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/proveedoresback.php?oper=eliminarproveedoresclientes", 
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


