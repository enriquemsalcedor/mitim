$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() {	




	$("select").select2({ language: "es" });


	//MARCAS 
	$.get("controller/combosback.php?oper=ambientes", { }, function(result){
		$("#idambientes").empty();
		$("#idambientes").append(result);
//		$("#idambientes").select2({placeholder: ""});


 		var id = getQueryVariable('id');
		if(id != "" && id != undefined && id != null){
			getSubambiente()
        $('.tipo').html('Editar área');
		}else{
		  $('.tipo').html('Nuevo área');  
		}
	});


	$("#listadosubambientes").on("click",function(){
		location.href = 'subambientes.php';
	});
	$("#salir-subambiente").on("click",function(){
		location.href = 'subambientes.php';
	});

    $("#guardar-subambiente").on("click",function(){
		guardar();
	});



});//FIN DOCUMENT

	function guardar(){
		var id	          = $("#idsubambientes").val();
		var nombre        = $('#nombre').val(); 
		var idambientes	  = $("#idambientes").val();
		
		let accion = "creada";
		if(id==''){
			oper = 'guardarSubambiente';
		}else{
			oper = 'actualizarSubambiente';
			accion="actualizada"
		}

		if(vsubambiente(nombre,idambientes) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/subambientesback.php',
				data: { 
					'oper'		    : oper, 
					'id'            : id,
					'nombre' 	    : nombre, 
					'idambientes'	: idambientes
				},
				beforeSend: function() {
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response == 1){
						vaciarSubambientes();








                            if(oper=="guardarSubambiente"){
    
    //	    					notification('','Buen trabajo!','success');
                                swal({		
            								title: 'Área '+accion+' satisfactoriamente',	
            								text: "¿Desea registrar otra Área?",
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
                    						location.href = "subambientes.php";
            							}
            						});
        
    
    
                                
                            }else{
        						notification('Área '+accion+' satisfactoriamente','Buen trabajo!','success');
        						location.href = "subambientes.php";

    
                            }



					}else{
						notification('Ha ocurrido un error al guardar el área, intente más tarde','ERROR!','error');

					}
										
				},
				error: function (error) {
					console.log(error)
					notification('Ha ocurrido un error al guardar el área, intente más tarde','ERROR!',"error");

				}
			});
		}
	}




	function vaciarSubambientes(){
		$("#idsubambientes").val("");
		$('#nombre').val(""); 
		$('#idambientes').val(null).trigger("change");
	}



	//VALIDAR GUARDAR AMBIENTES
	function vsubambiente(nombre,idambientes){
		var respuesta = 1;
			if(idambientes == "" || idambientes == 0 ||idambientes == '-' || idambientes == null || idambientes == undefined){
					notification('Debe seleccionar la Ubicación','Advertencia!','warning');
					respuesta = 0;
			}else{
    			if (nombre != ""){
    				if (nombre.length < 3){
    					notification('El Nombre debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
    					respuesta = 0;
    				}else{//TODO FINO CON EL NOMBRE
    
    
    				}
    			} else {
    				notification('Debe introducir el Nombre','Advertencia!','warning');
    				respuesta = 0; 
    			}

			    
			}


		return respuesta;
	}


		function getSubambiente(){
		var idsubambientes = getQueryVariable('id');
		let tipo = getQueryVariable('type')
		jQuery.ajax({
           url: "controller/subambientesback.php?oper=getSubambiente&idsubambientes="+idsubambientes,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {
           		if(item!=0){

					$('#preloader').css('display','none');
	                $("#idsubambientes").val(idsubambientes);
					$('#nombre').val(item.nombre); 
					$("#idambientes").val(item.idambientes).trigger('change');

					if(tipo=="view"){
						$("#idsubambientes").prop("disabled",true);
						$("#nombre").prop("disabled",true);
						$("#idambientes").prop("disabled",true);
					}

           		}else{
					notification('Ha ocurrido un error al buscar el área '+idsubambientes+', intente más tarde','ERROR',"error");
					location.href = 'subambientes.php';

           		}

           },error:function(err) {
				notification('Ha ocurrido un error al buscar el área '+idsubambientes+', intente más tarde','ERROR',"error");
				location.href = 'subambientes.php';
           }
        });


	}


