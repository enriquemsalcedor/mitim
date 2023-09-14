$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() {	




	$("select").select2({ language: "es" });


	//MARCAS 
	$.get("controller/combosback.php?oper=marcas", { tipo : 'maestro' }, function(result){
		$("#idmarcas").empty();
		$("#idmarcas").append(result);
//		$("#idmarcas").select2(null);
 		var id = getQueryVariable('id');
		if(id != "" && id != undefined && id != null){
			getmodelo();
			$('.tipo').html('Editar modelo');
		}else{
		    $('.tipo').html('Nuevo modelo');
		} 



	});
	

	$("#listadomodelos").on("click",function(){
		location.href = 'modelos.php';
	});
	$("#salir-modelo").on("click",function(){
		location.href = 'modelos.php';
	});

    //Botones de Modales
    $("#guardar-modelo").on("click",function(){
		guardar();
	});




});//FIN DOCUMENT


 	


	//VACIAR
	function vaciarGuardar(){
		$("#idmodelos").val("");
		$("#nombremodelo").val("");
		$("#descripcionmodelo").val("");
		$("#idmarcas").val(null).trigger("change");
	}


	//VALIDAR GRABAR
	function vmodelo(nombre,idmarcas){
		var respuesta = 1;
		/* if (nombre != ""){
			if (nombre.length < 2){
				demo.showSwal('error-message','ERROR!','El Nombre debe tener una longitud de al menos 3 caracteres');
				respuesta = 0;
			}
		}else { */

			if (nombre != ""){
				if (nombre.length < 3){
					notification('El Nombre debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
					respuesta = 0;
				}else{//TODO FINO CON EL NOMBRE

					if(idmarcas == 0 || idmarcas == "" || idmarcas == undefined || idmarcas == null){
				        notification('Debe seleccionar la marca','Advertencia!','warning');
				    	respuesta = 0;  
				    }

				}
			} else {
				notification('Debe introducir el Nombre del modelo','Advertencia!','warning');
				respuesta = 0;
			}





		//}
		return respuesta;
	}





	function guardar(){
		var idmodelos	= $("#idmodelos").val();
		var nombre 		=  $("#nombremodelo").val();
		var descripcion =  $("#descripcionmodelo").val();
		var idmarcas 	=  $("#idmarcas").val();
		
		let accion = "creado";
		if(idmodelos==''){
			oper = 'createmodelos';
		}else{
			oper = 'updatemodelos';
			accion="actualizado"
		}

		if(vmodelo(nombre,idmarcas) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/modelosback.php',
				data: { 
					'oper'			: oper,
					'idmodelos'		: idmodelos,
					'nombre' 		: nombre,
					'descripcion' 	: descripcion,
					'idmarcas'    	: idmarcas 
				},
				beforeSend: function() {
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response == 1){
						vaciarGuardar();
//						notification('Modelo '+accion+' satisfactoriamente','Buen trabajo!','success');
//						location.href = "modelos.php";

                        if(oper=="createmodelos"){

//	    					notification('','Buen trabajo!','success');
                            swal({		
        								title: 'Modelo '+accion+' satisfactoriamente',	
        								text: "¿Desea registrar otro Modelo?",
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
                						 document.getElementById('nombremodelo').focus();
                						    
        							}else{
                						location.href = "modelos.php";
        							}
        						});
    


                            
                        }else{
	    					notification('Modelo '+accion+' satisfactoriamente','Buen trabajo!','success');
    						location.href = "modelos.php";
                        }

					}else if(response == 2){ 
						notification('El nombre del modelo ya existe','Advertencia!','warning');
					}else if(response == 0){
						notification('Ha ocurrido un error al guardar el modelo, intente más tarde','ERROR!','error');
					}else{
						notification('Ha ocurrido un error al guardar el modelo, intente más tarde','ERROR!','error');

					}
										
				},
				error: function (error) {
					console.log(error)
					notification('Ha ocurrido un error al guardar el modelo, intente más tarde','ERROR!',"error");

				}
			});
		}
	}



	function getmodelo(){
		var idmodelos = getQueryVariable('id');
		let tipo = getQueryVariable('type')
		jQuery.ajax({
           url: "controller/modelosback.php?oper=getmodelos&idmodelos="+idmodelos,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {
           		if(item!=0){

					$('#preloader').css('display','none');

	                $("#idmodelos").val(idmodelos);
					$("#nombremodelo").val(item.nombre); 
					$("#descripcionmodelo").val(item.descripcion);
					console.log(item.idmarcas)
//					$("#idmarcas").val(item.idmarcas.split(',')).trigger("change");
					$("#idmarcas").val(item.idmarcas).trigger("change"); //NO LOCAL


					if(tipo=="view"){
						$("#idmodelos").prop("disabled",true);
						$("#nombremodelo").prop("disabled",true);
						$("#descripcionmodelo").prop("disabled",true);
						$("#idmarcas").prop("disabled",true);
					}

           		}else{
					notification('Ha ocurrido un error al buscar el modelo '+idmodelos+', intente más tarde','ERROR',"error");
					location.href = 'modelos.php';

           		}

           },error:function(err) {
				notification('Ha ocurrido un error al buscar el modelo '+idmodelos+', intente más tarde','ERROR',"error");
				location.href = 'modelos.php';
           }
        });


	}


