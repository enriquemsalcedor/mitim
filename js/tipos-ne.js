$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
	var id = getQueryVariable('id');
	if(id != "" && id != undefined && id != null){
		gettipo();
		$('.tipo').html('Editar tipo');
	}else{
	    $('.tipo').html('Nuevo tipo');
	}

	$("#salir-tipos").on("click",function(){
		location.href = 'tipos.php';
	});

    $("#guardar-tipo").on("click",function(){
		guardarTipo();
	});




	function vtipo(nombre){
		var respuesta = 1; 

		if(nombre==""){
		    notification('Debe introducir el campo Nombre','Advertencia!','warning');
			respuesta = 0; 
		}else{
            if(nombre.length < 3){
    			notification('El campo Nombre debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
    			respuesta = 0;
                
            }

		}
		return respuesta;
	}

	function vaciar(){
		$("#id").val("");
		$('#nombre').val(""); 
	}  								
 	
	function gettipo(){
		var idtipo = getQueryVariable('id');
		let tipo = getQueryVariable('type')

		jQuery.ajax({
           url: "controller/tiposback.php?oper=getTipo&idtipo="+idtipo,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {
				$('#preloader').css('display','none');
				$('#overlay').css('display','none');
           		if(item!=0){
	                $("#modalsitios").modal("show");
	                $("#id").val(idtipo);  
					$("#nombre").val(item.nombre);
					if(tipo=="view"){
						$("#id").prop("disabled",true);
						$("#nombre").prop("disabled",true);
					}
				}else{
					notification('Ha ocurrido un error al buscar el tipo '+idtipo+', intente más tarde','ERROR',"error");
					location.href = 'tipos.php';

				}
           },error:function(err) {
				notification('Ha ocurrido un error al buscar el tipo '+idtipo+', intente más tarde','ERROR',"error");
				location.href = 'tipos.php';
           }
        });



	}


	function guardarTipo(){	
		var id	          		= $("#id").val();
		var nombre        		= $('#nombre').val();  
		var msj 				= "";
		
		if(id==''){
			oper = 'guardarTipo';
			msj  = 'agregado';
		}else{
			oper = 'actualizarTipo';
			msj  = 'actualizado';
		}
		if (vtipo(nombre) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/tiposback.php',
				data: { 
					'oper'		          : oper, 
					'id'            	  : id,
					'nombre' 	    	  : nombre 
				},
				beforeSend: function() {
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response==1){
						vaciar();
                            if(oper=="guardarTipo"){
    
//    	    					notification('','Buen trabajo!','success');
                                swal({		
            								title: 'Tipo '+msj+' satisfactoriamente',	
            								text: "¿Desea registrar otro Tipo?",
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
                    						location.href = 'tipos.php';
            							}
            						});
        
    
    
                                
                            }else{
        						notification('Tipo '+msj+' satisfactoriamente','Buen trabajo','success');
        						location.href = 'tipos.php';

    
                            }








					}else if(response == 2){
					    notification('El nombre del Tipo ya existe','Advertencia!','warning');
					}else if(response == 0){
						notification('Error al guardar el tipo','ERROR','error');
					}
				},
				error: function () {
					$('#overlay').css('display','none');
					notification('Ha ocurrido un error al guardar el tipo, intente más tarde','ERROR','error');
				}
			});
		} 
	}


