$(document).ready(function() {
	/* FOCUS */
	/*$(document).on('focus', '.select2', function() {
		$(this).siblings('select').select2('open');
	});*/ 
	
	//OBTENER COMBO DE EMPRESAS
	$.get("controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){
		$("#empdepto").select2({placeholder:'Empresas'});
		$("#empdepto").append(result);	
	});	
	
	//VALIDAR GRABAR
	function validarGrabar(nombre,descripcion){
		var respuesta = 1;
		if (nombre != "" && descripcion != ""){
			if (nombre.length < 3){
				demo.showSwal('error-message','ERROR','El Nombre debe tener una longitud de al menos 3 caracteres');
				respuesta = 0;
			}
			if (descripcion.length < 3){
				demo.showSwal('error-message','ERROR','La Descripción debe tener una longitud de al menos 3 caracteres');
				respuesta = 0;
			} 
		} else {
			demo.showSwal('error-message','ERROR','Debe introducir todos los datos');
			respuesta = 0;
		}
		return respuesta;
	} 
	
	function guardar(){
		var nombre 			= $("#nombredpto").val();
		var descripcion 	= $("#descdpto").val(); 
		var idempresas 		= $("#empdepto").val(); 
		
		/*$.get("controller/contactosback.php?oper=existe", {onlydata:"true", 'cedula': cedula })
		.done(function(response){
			if (response > 0) {
				demo.showSwal('error-message','ERROR','Ya existe un contacto con esta cédula');
			} else */
				
			if(validarGrabar(nombre,descripcion) == 1){
				$.ajax({
					type: 'post',
					url: 'controller/departamentosback.php',
					data: { 
						'oper'			: 'createdepartamentos', 
						'nombre' 		: nombre,
						'descripcion' 	: descripcion, 
						'idempresas' 	: idempresas 
					},
					beforeSend: function() {
						$('#overlay').css('display','block');
					},
					success: function (response) {
						$('#overlay').css('display','none');
						demo.showSwal('success-message','Buen trabajo','Departamento agregado satisfactoriamente');
							setTimeout(function(){
								location.href = 'departamentos.php';
							},3000);
					},
					error: function () {
						$('#overlay').css('display','none');
						demo.showSwal('error-message','ERROR','Ha ocurrido un error al guardar el Departamento, intente más tarde');
					}
				});			
			}
		//});
	}
	
	$("#boton-guardar, #boton-guardar-salir").on("click",function(){
		guardar();
	});	
	
	$("#boton-guardar").on("click",function(){
		$("#salir").val("0");
	});
	
	$("#boton-guardar-salir").on("click",function(){
		$("#salir").val("1");
	});
	
	$("#boton-cancelar").on("click",function(){
		location.href = 'departamentos.php';
	});
	
	// ELIMINAR ESPACIOS EN BLANCO
	$("#nombredpto").on("blur", function(){
		var valor = $(this).val().trim();
		$(this).val( valor );
	});	
	$("#descdpto").on("blur", function(){
		var valor = $(this).val().trim();
		$(this).val( valor );
	});	  
	
});


