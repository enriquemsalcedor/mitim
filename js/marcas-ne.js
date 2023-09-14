$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
	var id = getQueryVariable('id');
	if(id != "" && id != undefined && id != null){
		getmarca();
		$('.tipo').html('Editar marca');
	}else{
	    $('.tipo').html('Nueva marca');
	}


	$("#listadomarca").on("click",function(){
		location.href = 'marcas.php';
	});
	$("#salir-marca").on("click",function(){
		location.href = 'marcas.php';
	});

    //Botones de Modales
    $("#guardar-marca").on("click",function(){
		guardar();
	});

	//VACIAR
	function vaciarGuardar(){
		$("#nombremarca").val("");
		$("#descripcionmarca").val("");
		
	}

	//VALIDAR GRABAR
	function validarmarca(nombre){
		var respuesta = 1;
		if (nombre != ""){
			if (nombre.length < 3){
				notification('El Nombre debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
				respuesta = 0;
			}
		} else {
			notification('Debe introducir el Nombre de la marca','Advertencia!','warning');
			respuesta = 0;
		}
		return respuesta;
	}


	//VACIAR
	function vaciaMarca(){

        $("#idmarca").val("");
		$("#nombremarca").val("");
		$("#descripcionmarca").val("");

	}	


	function guardar(){
		var idmarcas	= $("#idmarca").val();
		var nombre 		= $("#nombremarca").val();
		var descripcion	= $("#descripcionmarca").val();
		
		let accion = "creada";
		if(idmarcas==''){
			oper = 'createmarcas';
		}else{
			oper = 'updatemarcas';
			accion="actualizada"
		}

		if(validarmarca(nombre) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/marcasback.php',
				data: { 
					'oper'			: oper,
					'idmarcas'		: idmarcas,
					'nombre' 		: nombre,
					'descripcion' 	: descripcion
				},
				beforeSend: function() {
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response == 1){
						$("#modaladdmarcas").modal("hide");
						vaciaMarca();


                            if(oper=="createmarcas"){
    
//    	    					notification('','Buen trabajo!','success');
                                swal({		
            								title: 'Marca '+accion+' satisfactoriamente',	
            								text: "¿Desea registrar otra Marca?",
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
                    						 document.getElementById('nombremarca').focus();
                    						    
            							}else{
                    						location.href = "marcas.php";
            							}
            						});
        
    
    
                                
                            }else{
        						notification('Marca '+accion+' satisfactoriamente','Buen trabajo!','success');
        						location.href = "marcas.php";

    
                            }


					}else if(response == 2){ 
						notification('El nombre de la marca ya existe','Advertencia!','warning');
					}else if(response == 0){
						notification('Ha ocurrido un error al guardar la marca, intente más tarde','ERROR!','error');
					}else{
						notification('Ha ocurrido un error al guardar la marca, intente más tarde','ERROR!','error');

					}
										
				},
				error: function (error) {
					console.log(error)
					notification('Ha ocurrido un error al guardar la marca, intente más tarde','ERROR!',"error");

				}
			});
		}
	}



	function getmarca(){
		var idmarca = getQueryVariable('id');
		let tipo = getQueryVariable('type')
		jQuery.ajax({
           url: "controller/marcasback.php?oper=getmarcas&idmarcas="+idmarca,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {
           		if(item!=0){

					$('#preloader').css('display','none');
					$("#idmarca").val(idmarca);
					$("#nombremarca").val(item.nombre);
					$("#descripcionmarca").val(item.descripcion);
					if(tipo=="view"){
						$("#idmarca").prop("disabled",true);
						$("#nombremarca").prop("disabled",true);
						$("#descripcionmarca").prop("disabled",true);
					}

           		}else{
					notification('Ha ocurrido un error al buscar la marca '+idmarca+', intente más tarde','ERROR',"error");
					location.href = 'marcas.php';

           		}

           },error:function(err) {
				notification('Ha ocurrido un error al buscar la marca '+idmarca+', intente más tarde','ERROR',"error");
				location.href = 'marcas.php';
				console.log(err)
           }
        });


	}


