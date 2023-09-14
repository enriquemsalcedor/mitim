$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
	var id = getQueryVariable('id');
	if(id != "" && id != undefined && id != null){
		getsla();
		$('.tipo').html('Editar prioridad');
	}else{
	    $('.tipo').html('Nueva prioridad');
	}
 
	$("select").select2({ language: "es" });
	$("#tipo").select2(null);


	$("#listadosla").on("click",function(){
		location.href = 'prioridades.php';
	});

    //Botones de Modales
    $("#guardar-sla").on("click",function(){
		savesla();
	});



    function savesla(){	
		var id	        = $("#idsla").val();
		var prioridad   = $("#prioridad").val();							
		var descripcion = $('#descripcion').val();
		var dias   		= $('#dias').val();
		var horas   	= $('#horas').val();
		var tipo   		= $('#tipo').val();		
		
		let accion = "creada";
		if(id==''){
			oper = 'createsla';
		}else{
			oper = 'updatesla';
			accion="actualizada"
		}
		if (vsla(prioridad) == 1){
				$.ajax({
					type: 'post',
					url: 'controller/slaback.php',
					data: { 
						'oper'		    : oper, 
						'id'            : id,
						'prioridad' 	: prioridad,
						'descripcion' 	: descripcion,
						'dias' 			: dias,
						'horas' 		: horas,
						'tipo' 			: tipo
					},
					beforeSend: function() {
						$('#overlay').css('display','block');
					},
					success: function (response) {

						$('#overlay').css('display','none');
						if(response==1){
							vaciarsla();

                        if(oper=="createsla"){
    
//    	    					notification('','Buen trabajo!','success');
                                swal({		
            								title: 'Prioridad '+accion+' satisfactoriamente',	
            								text: "¿Desea registrar otra Prioridad?",
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
                    						 document.getElementById('prioridad').focus();
                    						    
            							}else{
                    						location.href = "prioridades.php";
            							}
            						});
        
    
    
                                
                            }else{
    							notification("Prioridad "+accion+" satisfactoriamente", "Buen trabajo!","success");
    							location.href = "prioridades.php";
    
                            }


						}else{
							notification('Ha ocurrido un error al guardar la prioridad, intente más tarde','ERROR',"error");

						}
					},
					error: function () {
						$('#overlay').css('display','none');
		//				showSwal('error-message','ERROR','Ha ocurrido un error al guardar la prioridad, intente más tarde');
						notification('Ha ocurrido un error al guardar la prioridad, intente más tarde','ERROR',"error");
					}
				});			
		} 
	} 




	//VALIDAR GUARDAR SLA
	function vsla(prioridad){
		var respuesta = 1;
	    if(prioridad == ""){ 
			notification( "Debe introducir el campo Prioridad!","Advertencia!","warning");
	    	respuesta = 0;  
	    }else {
			if (prioridad.length < 3){
				notification('La Prioridad debe tener una longitud de al menos 3 caracteres','Advertencia!',"warning");
				respuesta = 0;
			}

	    }
		return respuesta;
	}


	function vaciarsla(){
		$("#idsla").val("");
		$("#prioridad").val("");
		$('#descripcion').val("");
		$('#dias').val("");
		$('#horas').val("");
//		$('#tipo').val("");
		$("#tipo").val(null).trigger("change");


	}
 
	function getsla(){
		var idsla = getQueryVariable('id');
		jQuery.ajax({
           url: "controller/slaback.php?oper=getsla&idsla="+idsla,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {
           		if(item!=0){
					$('#preloader').css('display','none');
					$("#idsla").val(idsla);
					$("#prioridad").val(item.prioridad);
					$('#descripcion').val(item.descripcion);
					$('#dias').val(item.dias);
					$('#horas').val(item.horas);
//					$('#tipo').val(item.tipo);

						$("#tipo").val(item.tipo).trigger("change");
						let tipo_bd = item.tipo.normalize('NFD').replace(/[\u0300-\u036f]/g,"");
						if (String(tipo_bd.toLowerCase()) === ("habiles").toLowerCase()){
							console.log("BD: "+item.tipo, "Sin Acentos: "+tipo_bd);
							$("#tipo").val("Habiles").trigger("change");
						}


           		}else{
					notification('Ha ocurrido un error al buscar la prioridad '+idsla+', intente más tarde','ERROR',"error");
           		}

           }
        });


	}


