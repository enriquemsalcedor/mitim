









$(document).ready(function() {
	let getUrl=getQueryVariable('id'); 
	idsubambiente =getUrl!==false?getUrl:0;
	
	$("#listado").click(function(){
		location.href = 'subambientes.php';
	});
	
	$.get( "controller/combosback.php?oper=clientes", { idempresas: "1" }, function(result){  
		$("#idclientes").empty();
		$("#idclientes").append(result); 
	}); 
    
    $('#idclientes').on('select2:select',function(){ 
		var idclientes = $("#idclientes option:selected").val();
    	//Proyectos
    	$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
    		$("#idproyectos").empty();
    		$("#idproyectos").append(result);
    	});
    });
	
	$('#idproyectos').on('select2:select',function(){
    	let idclientes = $("#idclientes option:selected").val();
		let idproyectos = $("#idproyectos option:selected").val();
    	//Categorías de proyectos
    	$.get( "controller/combosback.php?oper=ambientes", { idproyectos: idproyectos }, function(result){ 
    		$("#idambientes").empty();
    		$("#idambientes").append(result);
    	});
    }); 

	if(getUrl!==false){
	    jQuery.ajax({
           url: "controller/subambientesback.php?oper=getsubambientes&idsubambientes="+idsubambiente,
           dataType: "json",
           success: function(item) { 
                $("#nombresubambiente").text(`Subambiente: ${item.nombre}`);   
           }, 
        }); 
	}
	
	$(document).on('click','.boton-eliminar', function(e){
	 
		var id = $(this).attr("data-id"); 
		var idclientes   = $(this).attr("data-idclientes"); 
		var idproyectos  = $(this).attr("data-idproyectos"); 
		var idambientes = $(this).attr("data-idambientes"); 
		var idsubambientes = $(this).attr("data-idsubambientes");  
		
		eliminar(id,idclientes,idproyectos,idambientes,idsubambientes);
		
	});
	
	//Quitar asociación
	function eliminar(id,idclientes,idproyectos,idambientes,idsubambientes){
		
		 $.get( "controller/subambientesback.php?oper=hayRelacionPs", 
		{  
			idclientes 	 	: idclientes, 
			idproyectos  	: idproyectos, 
			idambientes	    : idambientes,
			idsubambientes  : idsubambientes
		}, function(result){
			
			
			var mensaje    	  = "";
			var modulos 	  = "";
			var existe  	  = 0; 
			var correctivos   = result.correctivos;
			var preventivos   = result.preventivos;
			var postventas 	  = result.postventas;  
			
			if(correctivos == 0 && preventivos == 0 && postventas == 0 ){
				existe = 0;
			}else{
				existe = 1;
			}
			
			if(existe == 1){
				mensaje = "Hay registros relacionados a esta asociación (";
				if(correctivos == 1){
					modulos += "correctivos, ";
				}
				if(preventivos == 1){
					modulos += "preventivos, ";
				}
				if(postventas == 1){
					modulos += "postventas, ";
				} 
				modulos = modulos.substring(0, modulos.length -2 );
				
				mensaje += ""+modulos+"), no se puede eliminar.";
				
				notification(mensaje,'Advertencia!','warning');
			}else{
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar la asociación al area ?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							$.get( "controller/subambientesback.php?oper=eliminarsubambientespuente", 
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
	
	$("#guardar").on("click",function(){		
		guardar(); 
	});
	
	const validar=(idclientes,idproyectos,idambientes)=>{ 
		let respuesta = 1;
		
		if (idclientes =='' || idclientes ==null || idclientes ==undefined || idclientes =='Seleccione' || idclientes ==0){
			 notification('El campo Cliente es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		}if (idproyectos =='' || idproyectos ==null || idproyectos ==undefined || idproyectos =='Seleccione' || idproyectos ==0){
			 notification('El campo Proyecto es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		}else if (idambientes =='' || idambientes ==null || idambientes ==undefined || idambientes =='Seleccione' || idambientes ==0){
			notification('El campo Área es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		} 
		  
		return respuesta;
	}
	
	const vaciarCampos = ()=>{ 
		$('#idclientes').val(null).trigger("change");
		$('#idproyectos').val(null).trigger("change");
		$('#idcategorias').val(null).trigger("change");
	}
	
	const guardar = ()=>{
		
		let respuesta  	=	 0;
		let id 		 	= idsubambiente; 
		let idclientes	= $('#idclientes').val();   
		let idproyectos	= $('#idproyectos').val();   
		let idambientes	= $('#idambientes').val();     
		
		if(validar(idclientes,idproyectos,idambientes)== 1&& respuesta == 0){
			$.ajax({
				type: 'post', 
				url: `controller/subambientesback.php?oper=asociarsubambientespuente`,
				data: {   
					'id'   	 	  : id, 
					'idclientes'   : idclientes,
					'idproyectos'  : idproyectos,
					'idambientes' : idambientes 
				},
				success: function (response) {	
					if(response == 1){ 
						vaciarCampos();
						swal({		
									title: 'Área registrada satisfactoriamente',	
									text: "¿Desea registrar otra área?",
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
									document.getElementById('idclientes').focus(); 
								}else{
									notification('Exito!',"Área agregada satisfactoriamente!","success")
									location.href = "subambientes.php";
								}
							}); 
					}else if(response == 2){
						notification('El Área ya existe!',"Advertencia!","warning")
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
	
	tablacp = $("#tablacp").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		ajax: {
			url:`controller/subambientesback.php?oper=cargarsubambientespuente&idsubambiente=${idsubambiente}`,
		},
		columns	: [
			{ 	"data": "id" },			//0
			{ 	"data": "acciones" },	//1 
			{ 	"data": "cliente" }, 	//2
			{ 	"data": "proyecto" }, 	//3
			{ 	"data": "ambiente" } 	//4  
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
});

$("select").select2();


