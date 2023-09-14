$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() {	





    $.get("controller/combosback.php?oper=usuarios", { onlydata:"true" }, function(result){
		$("#responsables").empty(); 
		$("#responsables").append(result);	

	 		var id = getQueryVariable('id');
			if(id != "" && id != undefined && id != null){
				getsitio();
				$('.tipo').html('Editar ubicación');
			}else{ 
				$('.tipo').html('Nueva ubicación');
			} 

	});  
 

	$("#salir-ambientes").on("click",function(){
		location.href = 'ambientes.php';
	});

    $("#guardar-ambiente").on("click",function(){
		guardar();
	});



});//FIN DOCUMENT

	function getsitio(){
		var idSitio = getQueryVariable('id');
		jQuery.ajax({
           url: "controller/ubicacionesback.php?oper=getsitio&idsitios="+idSitio,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {

				$('#preloader').css('display','none');
                $("#idsitios").val(idSitio);  							
				$('#unidad').val(item.unidad); 
		       	$('#responsables').val(item.responsables.split(',')).trigger('change');
           }
        });



	}


	function guardar(){

		var id	          = $("#idsitios").val();						
		var nombre        = $('#unidad').val(); 
		var responsables  = $('#responsables').val().join();
		var oper = "";
		var msj = "";						
		
		if(id==''){
			oper = 'createsitio';
			msj  = "Ubicación creada";
		}else{
			oper = 'updatesitio';
			msj  = "Ubicación actualizada";
		}

		let val = vambiente(nombre,responsables)
		if(val==1){

				$.ajax({
					type: 'post',
					url: 'controller/ubicacionesback.php',
					data: { 
						'oper'		    : oper, 
						'id'            : id,
						'unidad' 	    : nombre, 
						'responsables'  : responsables		  
					},
					beforeSend: function() {
						$('#preloader').css('display','block');
					},
					success: function (response) {
						$('#preloader').css('display','none');


						if(response==1){
							vaciarsitios();

                            if(oper=="createsitio"){
    
//    	    					notification('','Buen trabajo!','success');
                                swal({		
            								title: msj+' satisfactoriamente',	
            								text: "¿Desea registrar otra Ubicación?",
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
                    						 document.getElementById('unidad').focus();
                    						    
            							}else{
                							location.href="ambientes.php";
            							}
            						});
        
    
    
                                
                            }else{
        						notification(msj+' satisfactoriamente','Buen trabajo!','success');
    							location.href="ambientes.php";
    
    
                            }
    
						}else{
							$('#preloader').css('display','none');				
							notification('Ha ocurrido un error al grabar el Registro, intente mas tarde','ERROR!','error');
						}
					},
					error: function () {
						$('#preloader').css('display','none'); 
						notification("Ha ocurrido un error al guardar el estado, intente más tarde","Error!", "error");
					}
				});








		}



	}
	
	//Validaciones
	function vambiente(nombre,responsables){
		var respuesta = 1; 
		
        if(nombre==""){
		    notification('Debe introducir el campo Nombre','Advertencia!','warning');
			respuesta = 0; 
		}else if(nombre!=""){
			if(nombre.length<3){
				notification('El Nombre debe tener una longitud de al menos 3 caracteres','Advertencia!',"warning");
				respuesta = 0;
			}


		}
		 
		return respuesta;
	}
	

	function vaciarsitios(){
		$("#idsitios").val("");						
		$('#unidad').val(""); 
		$('#responsables').val(null).trigger("change");
		var iniresp = $('#responsables').find('option:first').val();
		if(iniresp == ''){ 
		    $('#responsables').find('option:first').remove().trigger('change');
		}				   
	}
	
		$("select").select2();


