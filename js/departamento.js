






$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() { 

	$("select").select2({ language: "es" });  

	$("#listadodepartametos").on("click",function(){
		location.href = 'departamentos.php';
	}); 
    $("#guardar-departamento").on("click",function(){
		guardar();
	});

	var id = getQueryVariable('id');
	if(id != "" && id != undefined && id != null){
		getdepartamento();
		$('.tipo').html('Editar departamento / grupo');
	}else{
	    $('.tipo').html('Nuevo departamento / grupo');
	}



});//FIN DOCUMENT

	//Validar Guardar Departamento
	const validarGrabar = (nombre,descripcion,tipo)=>{
		var respuesta = 1;

		if(tipo == 0 || tipo == "" || tipo == undefined || tipo == null){
			notification('Debe seleccionar el Tipo del departamento','Advertencia!','warning');
			respuesta = 0;
		}else{

			if (nombre != ""){
				if (nombre.length < 3){


					notification('El Nombre debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
					respuesta = 0;
				}else{//TODO FINO CON EL NOMBRE


					if (descripcion != ""){
						if (descripcion.length < 3){
							notification('La Descripción debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
							respuesta = 0;
						}else{//TODO FINO CON EL descripcion
						}
					} else {
						notification('Debe introducir la Descripción del departamento','Advertencia!','warning');
						respuesta = 0;
					}
				}
			} else {
				notification('Debe introducir el Nombre del departamento','Advertencia!','warning')
				respuesta = 0;
			}

			
		}


		return respuesta;
	} 
		 
	const guardar = ()=>{
		let id			= $("#id").val();
		let nombre		= $("#nombre").val();
		let descripcion	= $("#descripcion").val(); 
		let tipo     	= $("#tipo option:selected").val();
		
		
		let accion = "creado";
		if(id==''){
			oper = 'createdepartamentos';
		}else{
			oper = 'updatedepartamentos';
			accion="actualizado"
		}

		if (validarGrabar(nombre,descripcion,tipo) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/departamentosback.php',
				data: { 
					'oper'			: oper,
					'id'			: id,
					'nombre' 		: nombre,
					'descripcion' 	: descripcion, 
					'tipo'   : tipo
				},
				beforeSend: function() {
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response == 1){
						vaciar(); 

                        if(oper=="createdepartamentos"){ 
                            swal({		
        								title: 'Departamento '+accion+' satisfactoriamente',	
        								text: "¿Desea registrar otro Departamento?",
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
//                						vaciarGuardar();
                						 document.getElementById('nombre').focus();
                						    
        							}else{
                						location.href = "departamentos.php";
        							}
        						}); 
                        }else{
							notification('Departamento '+accion+' satisfactoriamente','Buen trabajo!','success');
    						location.href = "departamentos.php";
                        } 

					}else if(response == 2){
						notification('El departamento ya existe!',"Advertencia!","warning")
					}else{
						notification('Error al guardar!',"ERROR!!","error")
					}
										
				},
				error: function (error) {
					console.log(error)
					notification('Ha ocurrido un error al guardar el departamento, intente más tarde','ERROR!',"error");

				}
			});
		}
	}
	
	const vaciar = ()=>{
        $("#id").val("");
		$("#nombre").val("");							
		$('#descripcion').val(""); 
		$('#tipo').val(null).trigger('change');
	} 

 	const getdepartamento = ()=>{ 
	
		let iddepartamentos = getQueryVariable('id');
		let tipo = getQueryVariable('type')
		
		jQuery.ajax({
           url: "controller/departamentosback.php?oper=getdepartamentos&iddepartamentos="+iddepartamentos,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {
           		if(item!=0){

					$('#preloader').css('display','none');
					$('#overlay').css('display','none');

	                $("#id").val(iddepartamentos);
					$("#nombre").val(item.nombre);							
					$('#descripcion').val(item.descripcion);
					$('#tipo').val(item.tipo).trigger('change');


					if(tipo=="view"){
						$("#id").prop("disabled",true);
						$("#nombre").prop("disabled",true);
						$("#descripcion").prop("disabled",true);
						$("#tipo").prop("disabled",true);
					}

           		}else{
					notification('Ha ocurrido un error al buscar el departamento '+iddepartamentos+', intente más tarde','ERROR',"error");
					location.href = 'departamentos.php';

           		}

           },error:function(err) {
				notification('Ha ocurrido un error al buscar el departamento '+iddepartamentos+', intente más tarde','ERROR',"error");
				location.href = 'departamentos.php';
           }
        });


	}


