








$(document).ready(function() {
	
	$("#listado").click(function(){
		location.href = 'categorias.php';
	}); 
	
	let getUrl=getQueryVariable('id'); 
	idcategoria =getUrl!==false?getUrl:0;  
		 
	if(getUrl!==false){
	    jQuery.ajax({
           url: "controller/categoriasback.php?oper=getcategorias&idcategorias="+idcategoria,
           dataType: "json",
           success: function(item) {
                $("#id").val(item.id);      
                $("#nombre").val(item.nombre);  
           }, 
        }); 
	}   
	
	$("#guardar").on("click",function(){
		getUrl != '' ? editar() : guardar(); 
	});   
	
	const vaciarCampos = ()=>{ 
		$('#idcliente').val(null).trigger("change");
		$('#idproyecto').val(null).trigger("change");
		$('#tipo').val(null).trigger("change");
		$('#idcategorias').val("");
		$('#nombre').val(""); 
	}
	
	const vcategoria = nombre =>{ 
		var respuesta = 1;  
		if (nombre ==''){
			notification('El campo Nombre es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		} 
		return respuesta;
	}
	  
	const guardar = ()=>{
		let respuesta  	=	 0;
		let id 		 	= $('#id').val(); 
		let nombre		= $('#nombre').val();   
		let oper		= "createcategorias"; 
		  
		if(vcategoria(nombre)== 1&& respuesta == 0){
			$.ajax({
				type: 'post', 
				url: `controller/categoriasback.php?oper=${oper}`,
				data: {   
					'id'   	 : id, 
					'nombre' : nombre
				},
				success: function (response) {	
					if(response == 1){ 
						vaciarCampos();
						swal({		
								title: 'Categoría registrada satisfactoriamente',	
								text: "¿Desea registrar otra categoría?",
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
								 document.getElementById('nombre').focus();
									
							}else{
								notification('Exito!',"Categoría actualizada satisfactoriamente!","success")
								location.href = "categorias.php";
							}
						}); 
					}else if(response == 2){
						notification('La categoría ya existe!',"Advertencia!","warning")
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
	
	const editar = ()=>{
		let respuesta  	=	 0;
		let id 		 	= $('#id').val(); 
		let nombre		= $('#nombre').val();
		let oper 		= "editarcategoria"; 
		
		 $.get( "controller/categoriasback.php?oper=hayRelacion", 
		{  
			id : id 
		}, function(result){
			
			
			var mensaje    	  = "";
			var modulos 	  = "";
			var existe  	  = 0; 
			var correctivos   = result.correctivos;
			var preventivos   = result.preventivos;
			var proyectos 	  = result.proyectos;
			var subcategorias = result.subcategorias; 
			
			if(correctivos == 0 && preventivos == 0 && proyectos == 0 && subcategorias == 0){
				existe = 0;
			}else{
				existe = 1;
			}
			
			if(existe == 1){
				mensaje = "Hay registros asociados a esta categoría (";
				if(proyectos == 1){
					modulos += "Proyectos, ";
				}
				if(correctivos == 1){
					modulos += "Correctivos, ";
				}
				if(preventivos == 1){
					modulos += "Preventivos, ";
				} 
				if(subcategorias == 1){
					modulos += "Subcategorias, ";
				} 
				modulos = modulos.substring(0, modulos.length -2 );
				
				mensaje += ""+modulos+")";
				
				swal({
					title: "Confirmar",
					text: `${mensaje} ¿Está seguro de cambiar el nombre de la categoría a ${nombre}?`,
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							 if(vcategoria(nombre)== 1&& respuesta == 0){
								$.ajax({
									type: 'post', 
									url: `controller/categoriasback.php?oper=${oper}`,
									data: {   
										'id'   	 : id, 
										'nombre' : nombre
									},
									success: function (response) {	
										if(response == 1){ 
											location.href = "categorias.php"; 
										}else if(response == 2){
											notification('La categoría ya existe!',"Advertencia!","warning")
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
					}, function (isRechazo){  
					}
				);
				 
			}else{
				$.ajax({
					type: 'post', 
					url: `controller/categoriasback.php?oper=${oper}`,
					data: {   
						'id'   	 : id, 
						'nombre' : nombre
					},
					success: function (response) {	
						if(response == 1){ 
							location.href = "categorias.php"; 
						}else if(response == 2){
							notification('La categoría ya existe!',"Advertencia!","warning")
						}else{
							notification('Error al guardar!',"ERROR!!","error")
						} 										
					},
					error: function () {
						notification('Ha ocurrido un error al grabar el Registro, intente mas tarde',"ERROR!!","error")
					}
				});
			}  
		},'json');  
	}
});

	


