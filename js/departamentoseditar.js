$(document).ready(function() {
    
    //TOMAR PARAMETROS DE LA URL
	function getParamsUrl(k){
	var p={};
	location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){p[k]=v})
	return k?p[k]:p;
	}
    
	var iddep = getParamsUrl('id');
	$('.form-group.label-floating').removeClass('is-empty');
	
	/* FOCUS */
	$(document).on('focus', '.select2', function() {
		$(this).siblings('select').select2('open');
	}); 
	
	//OBTENER COMBO DE EMPRESAS
	$.get("controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){
		$("#empdepto").select2({placeholder:'Empresas'});
		$("#empdepto").append(result);	
	});	
	
	// OBTENER DATOS DEL DEPARTAMENTO A EDITAR
	$.get("controller/departamentosback.php?oper=getdepartamentos", {'id': iddep } ,function(item){
		if (item != "0") {
			$("#nombredpto").val(item.nombre);
			$("#descdpto").val(item.descripcion);  
			$('#empdepto').select2().val(item.idempresas).trigger('change');
			
		} else {
			demo.showSwal('error-message','ERROR','El departamento a editar no existe');
			setTimeout(function(){
				location.href = 'departamentos.php'
			},3000);
		}	
	}, 'json');

	
	//VALIDAR GRABAR
	function validarGrabar(nombre,descripcion){
		var respuesta = 1;
		if ( nombre != "" && descripcion != "" ){
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
	
	
	function actualizar(){
		var id			= iddep;
		var nombre		= $("#nombredpto").val();
		var descripcion	= $("#descdpto").val(); 
		var idempresas	= $("#empdepto option:selected").val(); 

		
		if (validarGrabar(nombre,descripcion) == 1){
			$.ajax({
				type: 'post',
				url: 'controller/departamentosback.php',
				data: { 
					'oper'			: 'updatedepartamentos',
					'id'			: id,
					'nombre' 		: nombre,
					'descripcion' 	: descripcion, 
					'idempresas' 	: idempresas
					
				},
				beforeSend: function() {
					$('#overlay').css('display','block');
				},
				success: function (response) {
					$('#overlay').css('display','none');
					demo.showSwal('success-message','Buen trabajo','Departamentos actualizado satisfactoriamente');
					setTimeout(function(){
						location.href = 'departamentos.php'
					},3000);
				},
				error: function () {
					$('#overlay').css('display','none');
					demo.showSwal('error-message','ERROR','Ha ocurrido un error al guardar el Departamento, intente más tarde');
				}
			});			
		}
	}
	
	$("#boton-guardar").on("click",function(){
		actualizar();
	});
	
	$("#boton-cancelar").on("click",function(){
		location.href = 'departamentos.php';
	});	
	
	// ELIMINAR ESPACIOS EN BLANCO	
	$("#nombredpto, #descdpto").on("blur", function(){
		var valor = $(this).val().trim();
		$(this).val( valor );
	});	 
	
});


