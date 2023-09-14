$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() {



	$("#listadoestados").on("click",function(){
		location.href = 'estados.php';
	});
 //	$("#idempresas,#idclientes,#idproyectos").select2({placeholder: ""});
	//EMPRESAS
		var id = getQueryVariable('id');
		if(id != "" && id != undefined && id != null){
			getEstado();
			$('.tipo').html('Editar estado');
		} else{
		    
			$('.tipo').html('Nuevo estado');
		}
	
    $("#guardar-estado").on("click",function(){
		saveestado();
	}); 
});

	function getEstado(){ 
		var idEstado = getQueryVariable('id');
		jQuery.ajax({
           url: "controller/estadosback.php?oper=getestado&idestados="+idEstado,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) { 
				$('#preloader').css('display','none');
                $("#idestados").val(idEstado);  
				$("#nombre").val(item.nombre);						 
           }
        });
	}


	function vestado(nombre){
		var respuesta = 1;
	    if(nombre == ""){ 
			notification("Debe introducir el campo Nombre!", "Advertencia!","warning");
	    	respuesta = 0;  
	    }else if (nombre != ""){
			if (nombre.length < 3){ 
				notification("El campo Nombre debe tener una longitud de al menos 3 caracteres!", "Advertencia!","warning");
				respuesta = 0;
			}
		}
		return respuesta;
	}
	
	const saveestado=()=>{
		let respuesta  	 =	 0;		
		let id	         = $("#idestados").val();
		let nombre       = $("#nombre").val();		 
		
		if(id==''){
			oper = 'createestado';
		}else{
			oper = 'updateestado';
		} 
		
		$.get( "controller/estadosback.php?oper=hayRelacion", {  
			id : id 
		}, function(result){//
			if(result == 1){
				swal({
					title: "Confirmar",
					text: `Hay registros asociados a este Estado ¿Está seguro de cambiar el nombre al estado ${nombre}?`,
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm.value === true) {
							if (vestado(nombre) == 1 && respuesta == 0){
								$.ajax({
									type: 'post',
									url: 'controller/estadosback.php',
									data: { 
										'oper'		    : oper, 
										'id'            : id,
										'nombre' 	    : nombre 
									},
									beforeSend: function() {
										$('#preloader').css('display','block');
									},
									success: function (response) {
										$('#preloader').css('display','none');

										if(response==1){
											vaciarestados();  
											if(oper=="createestado"){  
												swal({		
															title: 'Estado creado satisfactoriamente',	
															text: "¿Desea registrar otro Estado?",
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
															location.href = "estados.php";
														}
													});  
											}else{
												notification("Estado actualizado satisfactoriamente", "Buen trabajo!","success");
												location.href="estados.php"; 
											} 
										}else if(response == 2){
											notification('El estado ya existe.','Advertencia!','warning');
										}else{
											$('#overlay').css('display','none');				
											notification('Ha ocurrido un error al grabar el Registro, intente mas tarde','ERROR!','error');
										}
									},
									error: function () {
										$('#overlay').css('display','none'); 
										notification( "Ha ocurrido un error al guardar el estado, intente más tarde", "Error!","error");
									}
								});			
							} 
						}
					}, function (isRechazo){  
					}
				); 
			}else{
				if (vestado(nombre) == 1 && respuesta == 0){
					$.ajax({
						type: 'post',
						url: 'controller/estadosback.php',
						data: { 
							'oper'		    : oper, 
							'id'            : id,
							'nombre' 	    : nombre 
						},
						beforeSend: function() {
							$('#preloader').css('display','block');
						},
						success: function (response) {
							$('#preloader').css('display','none');

							if(response==1){
								vaciarestados();  
								if(oper=="createestado"){  
									swal({		
												title: 'Estado creado satisfactoriamente',	
												text: "¿Desea registrar otro Estado?",
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
												location.href = "estados.php";
											}
										});  
								}else{
									notification("Estado actualizado satisfactoriamente", "Buen trabajo!","success");
									location.href="estados.php"; 
								} 
							}else if(response == 2){
								notification('El estado ya existe.','Advertencia!','warning');
							}else{
								$('#overlay').css('display','none');				
								notification('Ha ocurrido un error al grabar el Registro, intente mas tarde','ERROR!','error');
							}
						},
						error: function () {
							$('#overlay').css('display','none'); 
							notification( "Ha ocurrido un error al guardar el estado, intente más tarde", "Error!","error");
						}
					});			
				}
			}
		});
		
		 
	} 

	function vaciarestados(){
		$("#idestados").val("");
		$("#nombre").val("");		 
	}
	
	$("select").select2();


