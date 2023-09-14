$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
	var id = getQueryVariable('id');
	if(id != "" && id != undefined && id != null){
		getpubicidad();
		$('.tipo').html('Editar publicidad');
	}else{
	    $('.tipo').html('Nueva publicidad');
	}


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


	function guardar(){
		var idpublicidad	= $("#idpublicidad").val();
		var titulo 			= $("#titulo").val();
		var descripcion		= $("#descripcion").val();
		var imagen 			= $("#imagenaux").val();

		let accion = "creada";
		if(idpublicidad==''){
			oper = 'createpublicidad';
		}else{
			oper = 'updatepublicidad';
			accion="actualizada"
		}
		var form	= $("#form_publicidad_ce").val();
		let frm = document.getElementById('form_publicidad_ce')

		var formData = new FormData(frm);		
		formData.append('oper',oper);

		if(validar(idpublicidad,imagen,titulo) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/publicidadesback.php',
				data: formData,
				contentType: false,
            	processData: false,
				beforeSend: function() {
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response == 1){
                            if(oper=="createpublicidad"){
    
                                swal({		
										title: 'Publicidad '+accion+' satisfactoriamente',	
										text: "¿Desea registrar otra Publicidad?",
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
                    						vaciarGuardar();
                    						document.getElementById('titulo').focus();
                    						    
            							}else{
                    						location.href = "publicidades.php";
            							}
            						});
        
                                
                            }else{
        						notification('Publicidad '+accion+' satisfactoriamente','Buen trabajo!','success');
        						location.href = "publicidades.php";

                            }

					}else if(response == 0){
						notification('Ha ocurrido un error al guardar la publicidad, intente más tarde','ERROR!','error');
					}else{
						notification('Ha ocurrido un error al guardar la publicidad, intente más tarde','ERROR!','error');

					}
										
				},
				error: function (error) {
					console.log(error)
					notification('Ha ocurrido un error al guardar la publicidad, intente más tarde','ERROR!',"error");

				}
			});
		}
	}



	function getpubicidad(){
		var idpublicidad = getQueryVariable('id');
		let tipo = getQueryVariable('type')
		jQuery.ajax({
           url: "controller/publicidadesback.php?oper=getpublicidades&idpublicidades="+idpublicidad,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {
           		if(item!=0){

					$('#preloader').css('display','none');
					$("#idpublicidad").val(idpublicidad);
					$("#titulo").val(item.titulo);
					$("#descripcion").val(item.descripcion);
					$('#imagenpreview').attr('src', item.imagen);

					if(tipo=="view"){
						$("#idpublicidad").prop("disabled",true);
						$("#titulo").prop("disabled",true);
						$("#descripcion").prop("disabled",true);
					}

           		}else{
					notification('Ha ocurrido un error al buscar la publicidad '+idpublicidad+', intente más tarde','ERROR',"error");
					location.href = 'publicidades.php';

           		}

           },error:function(err) {
				notification('Ha ocurrido un error al buscar la publicidad '+idpublicidad+', intente más tarde','ERROR',"error");
				location.href = 'publicidades.php';
				console.log(err)
           }
        });


	}

	function validar(idpublicidad,imagen,titulo){

		var respuesta = 1;
		console.log(idpublicidad,imagen,titulo);
		if (imagen == ""){
			if (idpublicidad == ""){
				notification('Debe seleccionar una imagen','Advertencia!','warning');
				respuesta = 0;
			}		
		}
		if(titulo == ""){
			notification('Debe introducir el campo Título','Advertencia!','warning');
			respuesta = 0;
		}
		return respuesta;

	}

	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#imagenpreview').attr('src', e.target.result);
				$('#imagenaux').val(e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	function validarImg(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#imagenpreview').attr('src', e.target.result);
				$('#imagenaux').val(e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	function CheckDimension(input) {

		var fileUpload = document.getElementById("imagen");
	 
			var reader = new FileReader();
			reader.readAsDataURL(fileUpload.files[0]);
			reader.onload = function (e) {

				
				var image = new Image();
	
				image.src = e.target.result;
						
				image.onload = function () {
					var height = this.height;
					var width = this.width;
					if (width == 321 && height == 133) {
						$('#imagenpreview').attr('src', e.target.result);
						$('#imagenaux').val(e.target.result);
						
						return;
					}
					notification('Debe cambiar la imagen por una de dimensiondes 600X126px','Advertencia!','warning');

					return;
				};
	
			}
			//reader.readAsDataURL(input.files[0]);
			
		
	}