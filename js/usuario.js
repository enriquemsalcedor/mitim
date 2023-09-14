$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar,#icono-notificaciones").css("display","none");
$(document).ready(function() {

	if(nivel==7){
//		$('#nivel').removeClass('col-md-4 col-xs-12 col-sm-4').addClass('col-md-6 col-xs-12 col-sm-6');
//		$('#estado').removeClass('col-md-12 col-xs-12 col-sm-12').addClass('col-md-6 col-xs-12 col-sm-6');
	}


	$('#showPassword').click(function(){
		let tipo_input  = document.getElementById('clavusuario').type
		let cambio = "password";
		let tool = "Mostrar";
		if(tipo_input== cambio){
			cambio = "text"
			$('.icon').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
			tool = "Ocultar";

		}else{
			$('.icon').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
		}
		document.getElementById('clavusuario').type = cambio
		document.getElementById('showPassword').title = tool+' contraseña'
	});



	cargarCombos();

	var id = getQueryVariable('id');
	if(id != "" && id != undefined && id != null){
		getUsuario();
		$('.tipo').html('Editar usuario');
	}else{
		recargarEmpresas();
		$('.tipo').html('Nuevo usuario');
	}



	$('#clieusuario').on('select2:select',function(){
		recargarClientes();
	});
	 
	$('#clieusuario').on("select2:unselect", function(e){
		var idclientes = $("#clieusuario").val().join();
		if(idclientes != ""){
			recargarClientes();
		}else{
			$("#proyusuario").empty();
			$("#ambienteusuario").empty(); 
		}        
    }); 



	
	$('#proyusuario').on('select2:select',function(){
		recargarProyectos();
	});
	 
	$('#proyusuario').on("select2:unselect", function(e){
		var idproyectos = $("#proyusuario").val().join();
		if(idproyectos != ""){
			recargarProyectos();
		}else{ 
			$("#ambienteusuario").empty(); 
		}        
    });


    $("#guardar-usuario").on("click",function(){
		guardarUsuario();
	});

    $("#salir-usuarios").on("click",function(){
    	console.log("salei")
		location.href = "usuarios.php";

	});



});


	//OBTENER COMBOS
	function cargarCombos(){
		$.get("controller/combosback.php?oper=niveles", { onlydata:"true" }, function(result){
			$("#niveusuario").empty();
//			$("#niveusuario").select2({placeholder:''}); 
			$("#niveusuario").append(result);

		});	

/*
		$.get("controller/combosback.php?oper=departamentos", { onlydata:"true" }, function(result){
//			$("#deparusuario").select2({placeholder:''});
			$("#deparusuario").empty();
			$("#deparusuario").append(result);	
		});
		$.get("controller/combosback.php?oper=grupos", { onlydata:"true" }, function(result){
//			$("#grupusuario").select2({placeholder:''});
			$("#grupusuario").empty();
			$("#grupusuario").append(result);	
		});*/
	 }


	function recargarEmpresas(){
		var idempresas 		= 1;
		var idclientes 		= $("#clieusuario").val().join();
		var idproyectos 	= $("#proyusuario").val().join(); 
		var ambienteusuario = $("#ambienteusuario").val().join();											   
		var idproveedor 	= $("#idproveedor").val().join();											   
		




		$.get("controller/combosback.php?oper=clientes", { idempresas: idempresas, tipo: 'sitios' }, function(result){ 
			$("#clieusuario").empty();
			$("#clieusuario").append(result);
//			$("#clieusuario").select2({placeholder:''});
			$("#clieusuario").val(idclientes.split(',')).trigger("change");
			 
			$.get("controller/combosback.php?oper=proyectosrel", { idclientes: idclientes, tipo: 'sitios' }, function(result){
				$("#proyusuario").empty();
				$("#proyusuario").append(result);
//				$("#proyusuario").select2({placeholder:''});
				$("#proyusuario").val(idproyectos.split(',')).trigger("change");	
				
				$.get("controller/combosback.php?oper=unidadesUsuarios", { idempresas: idempresas, idclientes: idclientes, idproyectos: idproyectos, tipo: 'sitios' }, function(result){
					$("#ambienteusuario").empty();
					$("#ambienteusuario").append(result);	
//					$("#ambienteusuario").select2({placeholder:''});
					$("#ambienteusuario").val(ambienteusuario.split(',')).trigger("change");
				});
			}); 
		});
   	}




   		//Recargar Clientes
	function recargarClientes(){
		var idempresas 		= 1;
		var idclientes 		= $("#clieusuario").val().join();
		var idproyectos 	= $("#proyusuario").val().join(); 
		var ambienteusuario = $("#ambienteusuario").val().join();	 
		var idproveedor		= $("#idproveedor").val().join();	
	
		$.get("controller/combosback.php?oper=proyectosrel", { idclientes: idclientes, tipo: 'sitios' }, function(result){
			$("#proyusuario").empty();
			$("#proyusuario").append(result);
//			$("#proyusuario").select2({placeholder:''});
			$("#proyusuario").val(idproyectos.split(',')).trigger("change");	
			
			$.get("controller/combosback.php?oper=unidadesUsuarios", { idempresas: idempresas, idclientes: idclientes, idproyectos: idproyectos, tipo: 'sitios' }, function(result){
				$("#ambienteusuario").empty();
				$("#ambienteusuario").append(result);	
//				$("#ambienteusuario").select2({placeholder:''});
				$("#ambienteusuario").val(ambienteusuario.split(',')).trigger("change");
			});
			//Proveedores
			$.get("controller/combosback.php?oper=proveedores", { idcliente: idclientes, idproyectos: idproyectos }, function(result){
				$("#idproveedor").empty();
				$("#idproveedor").append(result);
//				$("#idproveedor").select2({placeholder:''});
				$("#idproveedor").val(idproveedor.split(',')).trigger("change"); 
			});
		});  
	}
	


	//Recargar Proyectos
	function recargarProyectos(){
		var idempresas 		= 1;
		var idclientes 		= $("#clieusuario").val().join();
		var idproyectos 	= $("#proyusuario").val().join();  
		var ambienteusuario = $("#ambienteusuario").val().join();											  
		var idproveedor 	= $("#idproveedor").val().join();
		var idgrupos 	= $("#grupusuario").val().join();
		
		$.get("controller/combosback.php?oper=unidadesUsuarios", { idempresas: idempresas, idclientes: idclientes, idproyectos: idproyectos, tipo: 'sitios' }, function(result){
			$("#ambienteusuario").empty();
			$("#ambienteusuario").append(result);	
//			$("#ambienteusuario").select2({placeholder:''});
			$("#ambienteusuario").val(ambienteusuario.split(',')).trigger("change");
			
			//Proveedores
			$.get("controller/combosback.php?oper=proveedores", { idcliente: idclientes, idproyectos: idproyectos }, function(result){
				$("#idproveedor").empty();
				$("#idproveedor").append(result);
//				$("#idproveedor").select2({placeholder:''});
				$("#idproveedor").val(idproveedor.split(',')).trigger("change"); 
			});
										
		$.get("controller/combosback.php?oper=departamentos", { idproyectos: idproyectos }, function(result){
		//	$("#deparusuario").select2({placeholder:''});
			$("#deparusuario").empty();
			$("#deparusuario").append(result);	
		});
		$.get("controller/combosback.php?oper=grupos", { idproyectos: idproyectos }, function(result){
//			$("#grupusuario").select2({placeholder:''});
			$("#grupusuario").empty();
			$("#grupusuario").append(result);	
			$("#grupusuario").val(idgrupos.split(',')).trigger("change"); 
		});
		}); 
	}
	
	function validarusuarios(usuario,clave,nombre,correo,nivel_v,clientes,proyectos){
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

		var respuesta = 1;

		if (usuario == ""){
			notification('Debe introducir el campo Usuario','Advertencia!','warning');
			respuesta = 0;
		}else if(clave == ""){
			notification('Debe introducir el campo Clave','Advertencia!','warning');
			respuesta = 0;
		}else if(nombre == ""){
			notification('Debe introducir el campo Nombre','Advertencia!','warning');
			respuesta = 0;
		}else if(correo == ""){
			notification('Debe introducir el campo Correo','Advertencia!','warning');
			respuesta = 0;
		}else if(usuario.length<3){
				notification('El Usuario debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
				respuesta = 0;
			}else{
				if (clave.length < 3){
					notification('La Clave debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
					respuesta = 0;
				}else{
					if (nombre.length < 3){
						notification('El Nombre debe tener una longitud de al menos 3 caracteres','Advertencia!','warning');
						respuesta = 0;

					}else{
						if (!regex.test($('#corrusuario').val().trim())) {
							notification('La dirección de Correo no es válida','Advertencia!','warning');
							respuesta = 0;
						}else{ 
							if(clientes == "" || clientes == 0 ||clientes == '-' || clientes == null || clientes == undefined){
								notification('Debe introducir el campo Clientes','Advertencia!','warning');
								respuesta = 0
							}else{

								if(proyectos == "" || proyectos == 0 ||proyectos == '-' || proyectos == null || proyectos == undefined){
									notification('Debe introducir el campo Proyectos','Advertencia!','warning');
									respuesta = 0
								}else{
									if(nivel_v == "" || nivel_v == 0 ||nivel_v == '-' || nivel_v == null || nivel_v == undefined){
										notification('Debe introducir el campo Nivel','Advertencia','warning');
										respuesta = 0
									} 
								}
							} 
						}
					}
				}
			}
		//} 

		return respuesta;
	}  


	function guardarUsuario() { 
		var idusuarios		= 	$("#idusuario").val();
		var usuario 	    = 	$('#ususuario').val(); 
		var clave 	        = 	$('#clavusuario').val(); 
		var nombre 		    =   $('#nombusuario').val(); 
		var correo 		    =   $('#corrusuario').val(); 
		var telefono 		=   $('#telfusuario').val(); 
		var cargo 		    =   $('#cargusuario').val();
		var idambientes 	=   $('#ambienteusuario').val().join();
		var nivel_V		    =   $('#niveusuario').val(); 
		var estado 		    =   $('#edousuario').val(); 
		var idempresas 		=   1;
		var idclientes 		=   $('#clieusuario').val().join();
		var idproyectos 	=   $('#proyusuario').val().join();

		var iddepartamentos	=   null

		var  idgrupos = null
		if(nivel!=7){
			idgrupos	= $("#grupusuario").val().join();
			iddepartamentos	=   $('#deparusuario').val();

		}

		var idproveedor		=   $('#idproveedor').val().join();
		


		var vcorreo = 1;

		let accion = "creado";

		if(idusuarios==''){
			oper = 'guardarUsuario';
		}else{
			vcorreo = 2;
			oper = 'editarusuarios';
			accion = "actualizado";


		} 
		
		if(validarusuarios(usuario,clave,nombre,correo,nivel_V,idclientes,idproyectos)== 1){
			$.ajax({
				type: 'post', 
				url: 'controller/usuariosback.php?oper='+oper,
				data: {   
					'idusuarios'	  : idusuarios,
					'usuario'         : usuario,
					'clave'           : clave,
					'nombre'          : nombre,
					'correo'          : correo,
					'telefono'        : telefono,
					'cargo'           : cargo,
					'idambientes'     : idambientes,
					'nivel'           : nivel_V, 
					'estado'          : estado, 
					'idempresas' 	  : idempresas,
					'idclientes' 	  : idclientes,
					'idproyectos' 	  : idproyectos, 
					'iddepartamentos' : iddepartamentos,
					'idgrupos'		  : idgrupos, 
					'idproveedor' 	  : idproveedor,
				},
				beforeSend: function() { 
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response==1){
						$('#ususuario').val("");
						$('#clavusuario').val("");
						$('#nombusuario').val("");
						$('#corrusuario').val("");
						$('#telfusuario').val("");
						$('#cargusuario').val("");
						$('#ambienteusuario').val(null).trigger("change");	
						$('#niveusuario').val(null).trigger("change");	
						$('#edousuario').val(null).trigger("change");	
//						$('#emprusuario').val(null).trigger("change");
						$('#clieusuario').val(null).trigger("change");
						$('#proyusuario').val(null).trigger("change");
						if(nivel!=7){
							$('#deparusuario').val(null).trigger("change"); 
							$("#grupusuario").val(null).trigger('change');

						} 

						$('#idproveedor').val(null).trigger("change");  
                            if(oper=="guardarUsuario"){
    
    	    					notification('','Buen trabajo!','success');
                                swal({		
            								title: 'Usuario '+accion+' satisfactoriamente',	
            								text: "¿Desea registrar otra Usuario?",
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
    //                						 
                    						 document.getElementById('ususuario').focus();
                    						    
            							}else{
                    						location.href = "usuarios.php";
            							}
            						}); 
                                
                            }else{
        						notification('Usuario '+accion+' satisfactoriamente','Buen trabajo','success');	
        						location.href = "usuarios.php";
    
                            } 

					}else if(response==2){
						notification('El usuario ya existe!','Advertencia!','warning');
					}else if(response==3){
						notification('El correo ya existe!','Advertencia!','warning');
					}else if(response==4){
						notification('El usuario y el correo ya existen!','Advertencia!','warning');
					}else{
						notification('Error al guardar!','ERROR','error');
					}					
										
				},
				error: function () {
					$('#overlay').css('display','none');				
					notification('Ha ocurrido un error al grabar el Usuario, intente mas tarde','ERROR','error');
				}
			});		
		}
	}

	function getUsuario(){ 

		var idusuarios = getQueryVariable('id');
		jQuery.ajax({
		   url: "controller/usuariosback.php?oper=getusuarios&idusuarios="+idusuarios,
           dataType: "json",
           beforeSend: function(){
				$('#preloader').css('display','block');
           },success: function(item) {

				$('#preloader').css('display','none');
				$('#overlay').css('display','none');

				$("#idusuario").val(idusuarios);

				$("#ususuario").val(item.usuario);
				$("#clavusuario").val(item.clave);
				$("#nombusuario").val(item.nombre);
				$("#corrusuario").val(item.correo);
				$("#telfusuario").val(item.telefono);
				$("#cargusuario").val(item.cargo);  
				$("#niveusuario").val(item.nivel).trigger('change');
				$("#edousuario").val(item.estado).trigger('change'); 

				//CLIENTES
				$.get( "controller/combosback.php?oper=clientes", { idempresas: 1, tipo: 'sitios' }, function(result){ 
					$("#clieusuario").empty();
					$("#clieusuario").append(result);
					$("#clieusuario").val(item.idclientes.split(',')).trigger("change");


						$.get( "controller/combosback.php?oper=proyectosrel", { idclientes: item.idclientes, tipo: 'sitios' }, function(result){ 
							$("#proyusuario").empty();
							$("#proyusuario").append(result);
							$("#proyusuario").val(item.idproyectos.split(',')).trigger("change");

							if(item.idproyectos){
								$("#proyusuarioedit").val(item.idproyectos.split(',')).trigger('change');    
							}else{
								$("#proyusuarioedit").val("").trigger('change');
							}

							$.get("controller/combosback.php?oper=unidadesUsuarios", { idempresas: 1, idclientes: item.idclientes, idproyectos: item.idproyectos, tipo: 'sitios' }, function(result){
								$("#ambienteusuario").empty();
								$("#ambienteusuario").append(result);	
								if(item.idambientes){
									$("#ambienteusuario").val(item.idambientes.split(',')).trigger('change');  
								}else{
									$("#ambienteusuario").val("").trigger('change');    
								}
								var iniresp = $('#ambienteusuario').find('option:first').val();
								if(iniresp == ''){
									$('#ambienteusuario').find('option:first').remove().trigger('change');
								}

								$.get("controller/combosback.php?oper=proveedores", { idcliente: item.idclientes, idproyectos: item.idproyectos }, function(result){
									$("#idproveedor").empty();
									$("#idproveedor").append(result);	
									if(item.idproveedor){
										$("#idproveedor").val(item.idproveedor.split(',')).trigger('change');  
									}else{
										$("#idproveedor").val("").trigger('change');    
									}
									var iniresp = $('#idproveedor').find('option:first').val();
									if(iniresp == ''){
										$('#idproveedor').find('option:first').remove().trigger('change');
									}
								});  

							});
						});

					});

					if(nivel!=7){
					 $.get("controller/combosback.php?oper=departamentos", { idproyectos: item.idproyectos }, function(result){
						$("#deparusuario").empty();
						$("#deparusuario").append(result);
						if(item.iddepartamentos){
							$("#deparusuario").val(item.iddepartamentos.split(',')).trigger('change');
						}else{
							$("#deparusuario").val("").trigger('change');
						}
					 }); 
						$.get("controller/combosback.php?oper=grupos", { idproyectos: item.idproyectos }, function(result){
						$("#grupusuario").empty();
						$("#grupusuario").append(result);
						if(item.idgrupos){
							$("#grupusuario").val(item.idgrupos.split(',')).trigger('change');
						}else{
							$("#grupusuario").val("").trigger('change');
						}
						}); 


					}else{

					} 

           }
        });

	}
	
		$("select").select2();


