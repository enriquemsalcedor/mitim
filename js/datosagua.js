$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() {



	$("#listado").on("click",function(){
		location.href = 'datosaguas.php';
	});
		var id = getQueryVariable('id');
		if(id != "" && id != undefined && id != null){
			getform();
			$('.tipo').html('Editar Datos de agua');
		} else{
		    
			$('.tipo').html('Nuevo Datos de agua');
		}
	
    $("#guardar").on("click",function(){
		guardar();
	}); 
});

	function getform(){ 
		var idaguas= getQueryVariable('id');
		jQuery.ajax({
           url: "controller/datosaguaback.php?oper=getdatosagua&idaguas="+idaguas,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) { 
				$('#preloader').css('display','none');
				$("#fecha").val(item.fecha);
				$("#consumo").val(item.consumo);
			    $("#turbiedad").val(item.turbiedad);
			    $("#tanque1m1").val(item.tanque1m1);
				$("#tanque1m2").val(item.tanque1m2);
				$("#tanque2m1").val(item.tanque2m1);
				$("#tanque2m2").val(item.tanque2m2);
				$("#horasdisponible").val(item.disponibilidad);
				$("#potabilizado").val(item.potabilizado);
				$('#edotiempo').val(item.tiempo).trigger('change');
				$('#estadoplanta').val(item.estadoplanta).trigger('change');
				$("#notas").val(item.notas);
           }
        });
	}


	function validar(fecha){
		var respuesta = 1;
	    if(fecha == ""){ 
			notification("El campo Fecha es obligatorio!", "Advertencia!","warning");
	    	respuesta = 0;  
	    }
		return respuesta;
	}
	
	const guardar=()=>{	
		let id	         = $("#idaguas").val();
		var fecha		    = $("#fecha").val();
		var consumo		    = $("#consumo").val();
		var turbiedad	    = $("#turbiedad").val();
		var tanque1m1	    = $("#tanque1m1").val();
		var tanque1m2		= $("#tanque1m2").val();
		var tanque2m1		= $("#tanque2m1").val();
		var tanque2m2		= $("#tanque2m2").val();
		var disponibilidad	= $("#horasdisponible").val();
		var potabilizado	= $("#potabilizado").val();
		var tiempo	    	= $('#edotiempo').val();
		var estadoplanta	= $('#estadoplanta').val();
		var notas			= $("#notas").val();
		
		if(id==''){
			oper = 'createdatosagua';
		}else{
			oper = 'updatedatosagua';
		} 

		if (validar(fecha) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/datosaguaback.php',
				data: { 
					'oper'		    : oper, 
					'id'            : id,
					'fecha' 		 : fecha,
					'consumo' 	     : consumo,
					'turbiedad'      : turbiedad,
					'tanque1m1'      : tanque1m1,
					'tanque1m2'      : tanque1m2,
					'tanque2m1'      : tanque2m1,
					'tanque2m2'      : tanque2m2,
					'disponibilidad' : disponibilidad,
					'potabilizado'   : potabilizado,
					'tiempo'      	 : tiempo,
					'estadoplanta'   : estadoplanta,
					'notas'          : notas
				},
				beforeSend: function() {
					$('#preloader').css('display','block');
				},
				success: function (response) {
					$('#preloader').css('display','none');

					if(response==1){
						vaciar();  
						if(oper=="createdatosagua"){  
							swal({		
										title: 'Estado creado satisfactoriamente',	
										text: "¿Desea registrar otro Dato de agua?",
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
										 document.getElementById('fecha').focus();
											
									}else{
										location.href = "datosaguas.php";
									}
								});  
						}else{
							notification("Datos de agua actualizado satisfactoriamente", "Buen trabajo!","success");
							location.href="datosaguas.php"; 
						} 
					}else{
						$('#preloader').css('display','none');				
						notification('Ha ocurrido un error al grabar el Registro, intente mas tarde','ERROR!','error');
					}
				},
				error: function () {
					$('#preloader').css('display','none'); 
					notification( "Ha ocurrido un error al guardar el datos de agua, intente más tarde", "Error!","error");
				}
			});			
		} 
	} 

	function vaciar(){
		$("#idaguas").val("");
		$("#fecha").val();
		$("#consumo").val();
		$("#turbiedad").val();
		$("#tanque1m1").val();
		$("#tanque1m2").val();
		$("#tanque2m1").val();
		$("#tanque2m2").val();
		$("#horasdisponible").val();
		$("#potabilizado").val();
		$('#edotiempo').val();
		$('#estadoplanta').val();
		$("#notas").val();

	}
		$('#fecha').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD HH:mm', lang : 'es', cancelText: 'Cancelar', switchOnClick:true, time:false  });
		$("select").select2();


