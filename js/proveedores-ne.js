$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() {


	$("select").select2({ language: "es" });
//	$("#cuentacontrato, #incluyepiezas, #utilizarasym").select2({placeholder: ""});

	$('#fechainiciocontrato, #fechafincontrato').bootstrapMaterialDatePicker({
		weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, lang : 'es', cancelText: 'Cancelar', switchOnClick:true, clearButton: true, clearText: 'Limpiar'
	});
	  
	var idempresas = 1;
	//EMPRESAS
	$.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresas").empty();
		$("#idempresas").append(result);
	});
	//CLIENTES	
	$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresas }, function(result){ 
		$("#idclientes").empty();
		$("#idclientes").append(result);    
	});
	//CLIENTES / PROYECTOS - SITIOS
	$('#idclientes').on('select2:select',function(){
		let idclientes = $("#idclientes option:selected").val();		
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
			$("#idproyectos").empty();
			$("#idproyectos").append(result);					 
		});	
	});	


	$("#listadoproveedores").on("click",function(){
		location.href = 'proveedores.php';
	});

		let id = getQueryVariable('id');
		if(id != "" && id != undefined && id != null){
			getproveedor();
			$('.tipo').html('Editar proveedor');
		}else{
		  $('.tipo').html('Nuevo proveedor');  
		}


//VALIDAR GUARDAR ESTADOS
    $("#guardar-proveedor").on("click",function(){
		guardarProveedor();
	});

});


	function vproveedor(nombre){
		var respuesta = 1; 
		
		if(nombre != "" && nombre.length < 3){
			notification('El campo Proveedor debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
			respuesta = 0;
		}
		return respuesta;
	}


	function vaciar(){
		$("#id").val("");
		$('#nombre').val("");
		$('#encargado').val("");
		$("#telefono").val("");
		$("#correo").val("");
		$("#idclientes").val(null).trigger("change");
		$("#idproyectos").val(null).trigger("change");
		$("#cuentacontrato").val(null).trigger("change");
		$("#fechainiciocontrato").val("");
		$("#fechafincontrato").val("");
		$("#serviciocontratado").val("");
		$("#incluyepiezas").val(null).trigger("change");
		$("#horarioatencioncont").val("");
		$("#utilizarasym").val(null).trigger("change");
	}  	

	function guardarProveedor(){
		var id	          		= $("#id").val();
		var nombre        		= $('#nombre').val();
		var idclientes        	= $('#idclientes').val();
		var idproyectos        	= $('#idproyectos').val();		
		var encargado        	= $('#encargado').val(); 
		var telefono	  		= $("#telefono").val(); 
		var correo	  			= $("#correo").val();
		var cuentacontrato	    = $("#cuentacontrato").val(); 
		var fechainiciocontrato	= $("#fechainiciocontrato").val(); 
		var fechafincontrato	= $("#fechafincontrato").val(); 
		var serviciocontratado	= $("#serviciocontratado").val(); 
		var incluyepiezas	  	= $("#incluyepiezas").val(); 
		var horarioatencioncont	= $("#horarioatencioncont").val(); 
		var utilizarasym	  	= $("#utilizarasym").val(); 
		var msj 				= "";
		
		if(id==''){
			oper = 'guardarProveedor';
			msj  = 'agregado';
		}else{
			oper = 'actualizarProveedor';
			msj  = 'actualizado';
		}
		if (vproveedor(nombre) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/proveedoresback.php',
				data: { 
					'oper'		          : oper, 
					'id'            	  : id,
					'nombre' 	    	  : nombre, 
					'idclientes' 	      : idclientes, 
					'idproyectos' 	      : idproyectos, 
					'encargado' 	      : encargado, 
					'telefono'			  : telefono,
					'correo'			  : correo,
					'cuentacontrato'	  : cuentacontrato,
					'fechainiciocontrato' : fechainiciocontrato,
					'fechafincontrato'	  : fechafincontrato,
					'serviciocontratado'  : serviciocontratado,
					'incluyepiezas'		  : incluyepiezas,
					'horarioatencioncont' : horarioatencioncont,
					'utilizarasym'		  : utilizarasym
				},
				beforeSend: function() {
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response == 1){
						vaciar();



                        if(oper=="guardarProveedor"){

//	    					notification('Proveedor '+msj+' satisfactoriamente','Buen trabajo!','success');
                            swal({		
        								title: 'Proveedor '+msj+' satisfactoriamente',	
        								text: "¿Desea registrar otro Proveedor?",
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
                						location.href = "proveedores.php";
        							}
        						});
    


                            
                        }else{
    						notification('Proveedor '+msj+' satisfactoriamente','Buen trabajo!','success');
    						location.href = "proveedores.php";


                        }


					}else if(response == 0){
						notification('Ha ocurrido un error al guardar el proveedor, intente más tarde','ERROR!','error');

					}else{
						notification('Ha ocurrido un error al guardar el proveedor, intente más tarde','ERROR!','error');

					}
										
				},
				error: function (error) {
					console.log(error)
					notification('Ha ocurrido un error al guardar el proveedor, intente más tarde','ERROR!',"error");

				}
			});
		}
	}


		function getproveedor(){
		var idproveedor = getQueryVariable('id');
		let tipo = getQueryVariable('type')
		jQuery.ajax({
           url: "controller/proveedoresback.php?oper=getProveedor&idproveedor="+idproveedor,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {
           		if(item!=0){

					$('#preloader').css('display','none');

	                $('#id').val(idproveedor);
//					$("#idmarcas").val(item.idmarcas.split(',')).trigger("change");
					$("#idmarcas").val(item.idmarcas).trigger("change");



	                $('#nombre').val(item.nombre);
					
					//CLIENTES
					$.get( "controller/combosback.php?oper=clientes", { idempresas: item.idempresas }, function(result){ 
						$("#idclientes").empty();
						$("#idclientes").append(result);
						$("#idclientes").val(item.idclientes).trigger("change");
					});
					//CLIENTES / PROYECTOS - DEPARTAMENTOS
				$.when( $('#idclientes').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//PROYECTOS
					$.get( "controller/combosback.php?oper=proyectos", { idclientes: item.idclientes }, function(result){ 
						$("#idproyectos").empty();
						$("#idproyectos").append(result);
						$("#idproyectos").val(item.idproyectos).trigger("change");	
					});
				});
					
					
	                $('#encargado').val(item.encargado);
					$("#telefono").val(item.telefono);
					$("#correo").val(item.correo);
					$("#cuentacontrato").val(item.cuentacontrato).trigger("change");
					$("#fechainiciocontrato").val(item.fechainiciocontrato);
					$("#fechafincontrato").val(item.fechafincontrato);
					$("#serviciocontratado").val(item.serviciocontratado);
					$("#incluyepiezas").val(item.incluyepiezas).trigger("change");
					$("#horarioatencioncont").val(item.horarioatencioncont);
					$("#utilizarasym").val(item.utilizarasym).trigger("change");

					if(tipo=="view"){
//						$("#idmodelos").prop("disabled",true);

	    	            $('#id').prop("disabled",true)
						$("#idmarcas").prop("disabled",true);




		                $('#nombre').prop('disabled',true);
		                $('#encargado').prop('disabled',true);
						$("#telefono").prop('disabled',true);
						$("#correo").prop('disabled',true);
						$("#cuentacontrato").prop('disabled',true);
						$("#fechainiciocontrato").prop('disabled',true);
						$("#fechafincontrato").prop('disabled',true);
						$("#serviciocontratado").prop('disabled',true);
						$("#incluyepiezas").prop('disabled',true);
						$("#horarioatencioncont").prop('disabled',true);
						$("#utilizarasym").prop('disabled',true);



					}

           		}else{
					notification('Ha ocurrido un error al buscar el Proveedor '+idproveedor+', intente más tarde','ERROR',"error");
					location.href = 'proveedores.php';

           		}

           },error:function(err) {
				notification('Ha ocurrido un error al buscar el Proveedor '+idproveedor+', intente más tarde','ERROR',"error");
				location.href = 'proveedores.php';
           }
        });


	}


