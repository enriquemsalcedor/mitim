$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
	var id = getQueryVariable('id');
	var telefono = getQueryVariable('value');
	//var telefono = '+5079179155';
	var token = "";
	if(id != "" && id != undefined && id != null){
		gettoken();
		$('.tipo').html('Editar publicidad');
	}else{
	    $('.tipo').html('Nueva publicidad');
	}
	
	gettoken();

	$("#listadopublicidad").on("click",function(){
		location.href = 'publicidades.php';
	});
	$("#salir-publicidad").on("click",function(){
		location.href = 'publicidades.php';
	});

    //Botones de Modales
    $("#guardar-publicidad").on("click",function(){
		console.log('*****');
		guardar();
	});

	//VACIAR
	function vaciarGuardar(){
		$("#idpublicidad").val("");
		$("#titulo").val("");
		$("#descripcion").text("");
		$("#imagen").val("");
		$('#imagenpreview').attr('src', '');
		$('#imagenaux').val('');
		
	}	

	function gettoken(){
		jQuery.ajax({
			type: "POST",
			url: "http://34.130.54.49:3002/api/v1/user/login-uid-firebase",
			dataType: "json",
			data: {"uid_firebase" : "ZZZZZZZZZZZZZZZZZZZZZZZZZZZZ"},		
			beforeSend: function(){

			},success: function(respuesta) {
					if(respuesta!=null){
						token = respuesta.token;
						gethabilidadestemp(respuesta.token);

					}else{
						notification('Ha ocurrido un error, intente más tarde','ERROR',"error");
					}

			},error:function(err) {
					notification('Ha ocurrido un error, intente más tarde','ERROR',"error");
					//location.href = '/';
					console.log(err)
			}
        });


	}

	function gethabilidadestemp(token){
		jQuery.ajax({
			type: "GET",
			url: "http://34.130.54.49:3002/api/v1/users/habilidad-temp/"+telefono,
			dataType: "json",
			headers: {"Authorization": token},
			//data: 		
			beforeSend: function(){
				$('#preloader').css('display','block');
			},success: function(respuesta) {
					if(respuesta!=null){
						$('#preloader').css('display','none');
						var contenido = "";
						$.each(respuesta.data, function(index, value) {
							var icon = '';
							if (value.accion == 'ELIMINAR'){
								icon = 'fas fa-times';
							}else{
								icon = 'fas fa-check';
							}
							contenido += '<div class="col-xl-6" >' +
											'<div class="mb-2">' +
											'<button type="button" class="btn btn-primary btn-xs mr-2" style="width: 50px;" data-id="'+value.id_habilidad_temp+'"' +
											'id="accion-habilidad" data-accion="'+value.accion+'">'+
											'<i class="'+icon+' mr-2" title="'+value.accion+'"></i>'+ 
												
											'</button>'+
											value.nombre_categoria + ' | ' +
											value.nombre_servicio + 
											'<div></div>'+
											'</div>'+										
										'</div>';
						  });

						$('#habilidades').html(contenido);

					}else{
						notification('Ha ocurrido un error, intente más tarde','ERROR',"error");
					}

			},error:function(err) {
					notification('Ha ocurrido un error, intente más tarde','ERROR',"error");
					console.log(err)
			}
        });


	}


	function accionhabilidad(accion,id){
		console.log(id);
		var accionStg = '';
		if (accion == 'ELIMINAR'){
			accionStg = 'RECHAZADA';
		}else{
			accionStg = 'ACEPTADA';
		}
		jQuery.ajax({
			type: "PUT",
			url: "http://34.130.54.49:3002/api/v1/users/habilidad-temp/"+id+"/change-status",
			dataType: "json",
			headers: {"Authorization": token},
			beforeSend: function(){

			},success: function(respuesta) {
					if(respuesta.data!=null){
						notification('Habilidad ' + accionStg +' satisfactoriamente','Buen trabajo','success');	
						$('#accion-habilidad').attr("data-id").hide();

					}else{
						notification('Ha ocurrido un error, intente más tarde','ERROR',"error");
					}

			},error:function(err) {
					notification('Ha ocurrido un error, intente más tarde','ERROR',"error");
					console.log(err)
			}
        });


	}

	$(document).on('click','#accion-habilidad', function(e){

		var id = $(this).attr("data-id");
		var accion = $(this).attr("data-accion");
		accionhabilidad(accion,id);
	 });

	