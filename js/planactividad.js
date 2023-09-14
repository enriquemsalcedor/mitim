$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
	function cargarCombos() {
		//CLIENTES
		$.get( "controller/combosback.php?oper=clientes", { idempresas: 1 }, function(result){ 
			$("#idclientes").empty();
			$("#idclientes").append(result);
		});
		
	//CLIENTES / PROYECTOS - SITIOS
	$('#idclientes').on('select2:select',function(){
		var idclientes = $("#idclientes option:selected").val();
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
			$("#idproyectos").empty();
			$("#idproyectos").append(result);
		});				
		//SITIOS
		$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: idclientes }, function(result){ 
			$("#idambientes").empty();
			$("#idambientes").append(result);
		});

	});	
	//PROYECTOS / CATEGORIAS
	$('#idproyectos').on('select2:select',function(){
		var idproyectos = $("#idproyectos option:selected").val();
		var idclientes = $("#idclientes option:selected").val();
		//AMBIENTES
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes+"&idproyectos="+idproyectos, {  }, function(result){ 
			$("#idambientes").empty();
			$("#idambientes").append(result);
		});
		$.get( "controller/combosback.php?oper=categorias", { tipo: "preventivo", idproyectos: idproyectos }, function(result){ 
			$("#idcategorias").empty();
			$("#idcategorias").append(result);
		});
		$.get( "controller/combosback.php?oper=departamentosgrupos", { idproyectos: idproyectos }, function(result){ 
			$("#iddepartamentos").empty();
			$("#iddepartamentos").append(result);
		});
			//PRIORIDAD
	$.get( "controller/combosback.php?oper=prioridades", { idclientes: idclientes,idproyectos: idproyectos }, function(result){ 
		$("#idprioridades").empty();
		$("#idprioridades").append(result);
	});
	});
	//CATEGORIAS - SUBCATEGORIAS
	$('#idcategorias').on('select2:select',function(){
		//MÓDULOS CATEGORÍAS //
		
		var idproyectos = $("#idproyectos option:selected").val();
		var idcategorias = $("#idcategorias option:selected").val();
		
		$.get( "controller/combosback.php?oper=subcategorias", { idproyectos: idproyectos, idcategoria: idcategorias }, function(result){ 
			$("#idsubcategorias").empty();
			$("#idsubcategorias").append(result);
		});
		
	});
	//SITIOS / SERIE
	$('#idambientes').on('select2:select',function(){
		var idambientes = $("#idambientes option:selected").val();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idambientes }, function(result){ 
			$("#idactivos").empty();
			$("#idactivos").append(result);
		});
		$.get( "controller/combosback.php?oper=areas", { idubicacion: idambientes }, function(result){ 
			$("#idsubambientes").empty();
			$("#idsubambientes").append(result);
		});
	});
	//AREA / SERIE
	$('#idsubambientes').on('select2:select',function(){
		var idambientes = $("#idambientes option:selected").val();
		var idsubambientes = $("#idsubambientes option:selected").val();
		//SERIE
		$.get( "controller/combosback.php?oper=serie", { idsitio: idambientes, idarea: idsubambientes }, function(result){ 
			$("#idactivos").empty();
			$("#idactivos").append(result);
		});
	});
	/*
	//SERIE / MARCA - MODELO
	$('#serie').on('select2:select',function(){
		var idserie = $("#serie option:selected").val();
		//SERIE
		$.ajax({
			url: "controller/combosback.php",
			type:"POST",
			data: { oper:"seriesel", idserie: idserie },
			dataType:"json",
			success: function(response){
				$.map(response, function (item) {
					$('#marca').val(item.marca);
					$('#modelo').val(item.modelo);
					$('#areact').val(item.areact);
				});
			}
		});
	});*/
	//DEPARTAMENTOS / ASIGNADO A
	$('#iddepartamentos').on('select2:select',function(){
		var iddepartamentos = $("#iddepartamentos option:selected").val();
		var idproyectos 	= $("#idproyectos option:selected").val();
		$.get( "controller/combosback.php?oper=usuariosDep", { idproyectos: idproyectos, iddepartamentos: iddepartamentos }, function(result){ 
			$("#responsable").empty();
			$("#responsable").append(result);
		});
	});
	}
	
	$("#listado").on("click",function(){
		location.href = 'plan.php';
	});
	
	var id = getQueryVariable('id');
	cargarCombos();
	
	if(id != ''){
		getPlan();
	}
	function getPlan(){  
		var id = getQueryVariable('id');
		$('#id').val(id);
		
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: 'controller/planactividadesback.php',
			data: { 
				'oper'	: 'cargarActividad',
				'id'	: id			
			},
			beforeSend: function() {
				$('#preloader').css('display','block');
			},
			success: function (response) {
				$('#preloader').css('display','none');				
				$.map(response, function (item) {
				    var mensajeinfo = "Actividad: "+item.id;
				    $("#nombreusuario").html(mensajeinfo);
		    	    $('#divnombrecedula').show();
					$('#titulo').val(item.titulo);
					$('#descripcion').val(item.descripcion);
					$('#diainiciofrecuencia').val(item.diainiciofrecuencia);
					//CLIENTES
					$.get( "controller/combosback.php?oper=clientes", { idempresas: 1 }, function(result){ 
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
						//PROYECTOS / CATEGORIAS
						$.when( $('#idproyectos').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
							$.get( "controller/combosback.php?oper=categorias", { tipo: "preventivo", idproyectos: item.idproyectos }, function(result){ 
								$("#idcategorias").empty();
								$("#idcategorias").append(result);
								$("#idcategorias").val(item.idcategorias).trigger("change");
							});
						//DEPARTAMENTOS
					$.get( "controller/combosback.php?oper=departamentosgrupos", { idproyectos: item.idproyectos }, function(result){ 
						$("#iddepartamentos").empty();
						$("#iddepartamentos").append(result);
						$("#iddepartamentos").val(item.iddepartamentos).trigger("change");
					});
					 $.get( "controller/combosback.php?oper=prioridades", { idclientes: item.idclientes,idproyectos: item.idproyectos  }, function(result){ 
		            	$("#idprioridades").empty();
			            $("#idprioridades").append(result);
			            $("#idprioridades").val(item.idprioridades).trigger("change");
		            });
						});
					});
					//SITIOS
					$.get( "controller/combosback.php?oper=sitiosclientes", { idclientes: item.idclientes }, function(result){ 
						$("#idambientes").empty();
						$("#idambientes").append(result);
						$("#idambientes").val(item.idambientes).trigger("change"); 
					});
					$.get( "controller/combosback.php?oper=areas", { idubicacion: item.idambientes }, function(result){ 
		            	$("#idsubambientes").empty();
			            $("#idsubambientes").append(result);
			            $("#idsubambientes").val(item.idsubambientes.split(',')).trigger("change"); 
		            });
				});
				//CATEGORIAS - SUBCATEGORIAS
				$.when( $('#idcategorias').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					
					//MÓDULOS CATEGORÍAS//
					
					
					$.get( "controller/combosback.php?oper=subcategorias", { idproyectos: item.idproyectos, idcategoria: item.idcategorias, tipo: 'preventivos' }, function(result){ 
						$("#idsubcategorias").empty();
						$("#idsubcategorias").append(result);
						$("#idsubcategorias").val(item.idsubcategorias).trigger("change");
						
					});
				});
					//SITIOS / SERIE
				$.when( $('#unidadejecutora').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//SERIE
					$.get( "controller/combosback.php?oper=serie", { idsitio: item.idambientes, idarea: item.idsubambientes }, function(result){ 
						$("#idactivos").empty();
						$("#idactivos").append(result);
						$("#idactivos").val(item.idactivos).trigger("change");	
					});
				});
				//DEPARTAMENTOS / ASIGNADO A
				$.when( $('#iddepartamentos').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					var iddepartamentos = $("#iddepartamentos option:selected").val();
					$.get( "controller/combosback.php?oper=usuariosDep", { idproyectos: item.idproyectos, iddepartamentos: item.iddepartamentos }, function(result){ 
						$("#responsable").empty();
						$("#responsable").append(result);
						$("#responsable").val(item.responsable).trigger("change");
					});
				});
					$("#tipoplan").val(item.tipoplan).trigger("change");
					if(item.frecuencia != ''){	
						$('input[name=frecuencia][id=f'+item.frecuencia+']').attr('checked', 'checked');
						if(item.frecuencia == 'diaria'){
							var arr_arreglo = item.diasfrecuencia.split("|");
							for(i = 0; i < arr_arreglo.length; i++){
								console.log(i+'-'+arr_arreglo[i]);
								$('input[name=frecuenciaDias][id=fd'+arr_arreglo[i]+']').attr('checked', 'checked');
							}
							$(".dias-frecuencia").show();
						}else{
							$(".dias-frecuencia").hide();
						}
						if(item.frecuencia == 'quincenal'){
							$('input[name=frecuenciaQuincenal][id=fq'+item.diasfrecuencia+']').attr('checked', 'checked');
							$(".dias-frecuencia-quincenal").show();
						}else{
							$(".dias-frecuencia-quincenal").hide();
						}
						if(item.frecuencia != 'diaria' && item.frecuencia != 'quincenal'){
							$('input[name=iniciofrecuencia]').removeAttr('checked');
							$('input[name=iniciofrecuencia][id=if'+item.diasfrecuencia+']').attr('checked', 'checked');
							$(".mes-frecuencia").show();
						}else{
							$(".mes-frecuencia").hide();
						}
					}
					
					var checkboxHabilidades = checkboxArrValue('frecuenciaDias', checkboxHabilidades);
					checkboxCargarValue(item.diasfrecuencia, 'frecuenciaDias', checkboxHabilidades);			
					$('#observacion').val(item.observacion);
					$("#tipoplan").val(item.tipoplan).trigger("change");
				});				
			},
			error: function () {
				$('#preloader').css('display','none');
			}
		}); 
		
	}
	
	function guardar() {  
		var iniciofrecuencia = '';
		var frecuencia = $('input:radio[name=frecuencia]:checked').attr('id');
		if(frecuencia != undefined ){
		frecuencia = frecuencia.substr(1);
			if(frecuencia == 'diaria'){
				if(!$('input:checkbox[name="frecuenciaDias"]').is(':checked')){
					$('input:checkbox[name="frecuenciaDias"]').parent().addClass('form-valide-error-bottom');
					return;
				}
				var diasfrecuencia = [];
				$("input:checkbox[name=frecuenciaDias]:checked").each(function (){
					var fd = $(this).attr('id');
					diasfrecuencia.push(fd.substr(2));
				});
				diasfrecuencia = diasfrecuencia.join(",");
			}else if(frecuencia == 'quincenal'){			
				var diasfrecuencia = [];
				$("input:radio[name=frecuenciaQuincenal]:checked").each(function (){
					var fq = $(this).attr('id');
					diasfrecuencia.push(fq.substr(2));
				});
				diasfrecuencia = diasfrecuencia.join(",");
			}else{
				var diasfrecuencia = '';
				//INICIO DE FRECUENCIA
				if(!$('input:radio[name="iniciofrecuencia"]').is(':checked')){
					$('input:radio[name="iniciofrecuencia"]').parent().addClass('form-valide-error-bottom');
					return;
				}
				var iniciofrecuencia = [];
				$("input:radio[name=iniciofrecuencia]:checked").each(function (){
					var ifr = $(this).attr('id');
					iniciofrecuencia.push(ifr.substr(2));
				});
				iniciofrecuencia = iniciofrecuencia.join(",");
			}
		}else{ 
			frecuencia = '';
		} 
		var id 				= $('#id').val();
		var titulo 			= $('#titulo').val();
		var descripcion 	= $('#descripcion').val();
		var idclientes 		= $('#idclientes').val(); 
		var idproyectos 	= $('#idproyectos').val(); 
		var idcategorias 	= $('#idcategorias').val(); 
		var idsubcategorias = $('#idsubcategorias').val(); 
		var idambientes 	= $('#idambientes').val(); 
		var idsubambientes 	= $('#idsubambientes').val().join();
		var idactivos 	    = $('#idactivos').val();
		var idprioridades 	= $('#idprioridades').val();
		var iddepartamentos = $('#iddepartamentos').val(); 
		var tipoplan 		= $('#tipoplan').val();	
		var responsable 	= $('#responsable').val();
		var observacion 	= $('#observacion').val();
		var diainiciofrecuencia	= $('#diainiciofrecuencia').val();
		console.log("A");
		if(validarform(titulo,descripcion,diainiciofrecuencia,idclientes,idproyectos,idcategorias,idambientes,idsubambientes,idprioridades,frecuencia,tipoplan,iddepartamentos,responsable,'-') == 1){
		console.log("B");
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: 'controller/planactividadesback.php',
				data: { 
					'oper'				:'guardarActividad',
					'id' 				: id,
					'titulo' 			: titulo,
					'descripcion'		: descripcion,
					'idclientes' 		: idclientes,
					'idproyectos' 		: idproyectos, 
					'idcategorias' 		: idcategorias,
					'idsubcategorias' 	: idsubcategorias, 
					'idambientes' 		: idambientes,
					'idsubambientes' 	: idsubambientes, 
					'idactivos' 	    : idactivos,
					'idprioridades' 	: idprioridades,
					'iddepartamentos' 	: iddepartamentos, 
					'tipoplan' 			: tipoplan,
					'frecuencia' 		: frecuencia,
					'observacion' 		: observacion, 
					'responsable' 		: responsable,
					'diasfrecuencia'	: diasfrecuencia,
					'iniciofrecuencia'	: iniciofrecuencia,
					'diainiciofrecuencia' : diainiciofrecuencia
				},
				beforeSend: function() {
					$('#preloader').css('display','block');
				},
				success: function (response) {
					$('#preloader').css('display','none'); 
					notification("Actividad registrada satisfactoriamente","¡Exito!",'success'); 
					location.href="plan.php";
				},
				error: function () {
					$('#preloader').css('display','none');							 
					notification("¡Error!","Ha ocurrido un error al registrar el pago, intente más tarde","error"); 
				}
			});
		}
		
	}
	
	function validarform(titulo,descripcion,diainiciofrecuencia,idclientes,idproyectos,idcategorias,idambientes,idsubambientes,idprioridades,frecuencia,tipoplan,iddepartamentos,responsable){
		var respuesta = 1;
		if (titulo != '-' && titulo != ""){
			if (titulo.length < 3){
				notification("Error","El campo Titulo debe tener una longitud de al menos 3 caracteres",'warning'); 
				respuesta = 0;
			}
		} 
		if(titulo == ""){ 
			notification("Error","El campo Titulo es obligatorio",'warning'); 
			respuesta = 0;
		}else if (descripcion == ""){ 
			notification("Error","El campo Descripción es obligatorio",'warning'); 
			respuesta = 0;
		}else if (idclientes != '-' && (idclientes == "" || idclientes == 0 || idclientes == undefined) ){ 
			notification("Error","El campo Cliente es obligatorio",'warning');
			respuesta = 0;
		}else if (idproyectos != '-' && (idproyectos == "" || idproyectos == 0 || idproyectos == undefined) ){ 
			notification("Error","El campo Proyecto es obligatorio",'warning');
			respuesta = 0;
		}else if (idcategorias != '-' && (idcategorias == "" || idcategorias == 0 || idcategorias == undefined) ){ 
			notification("Error","El campo Categoriía es obligatorio",'warning');
			respuesta = 0;
		}else if (idambientes != '-' && (idambientes == "" || idambientes == 0 || idambientes == undefined) ){ 
			notification("Error","El campo Ubicación es obligatorio",'warning');
			respuesta = 0;
		}else if (idsubambientes != '-' && (idsubambientes == "" || idsubambientes == 0 || idsubambientes == undefined) ){ 
			notification("Error","El campo Áreas es obligatorio",'warning');
			respuesta = 0;
		}else if (idprioridades != '-' && (idprioridades == "" || idprioridades == 0 || idprioridades == undefined) ){ 
			notification("Error","El campo Prioridad es obligatorio",'warning');
			respuesta = 0;
		}else if (frecuencia == "" && tipoplan != 'M'){ 
			notification("Error","El campo Frecuencia es obligatorio",'warning');
			respuesta = 0;
		}else if (diainiciofrecuencia == ''){ 
			notification("Error","El campo Día de inicio de frecuencia  es obligatorio",'warning');
			respuesta = 0;
		}else if (iddepartamentos != '-' && (iddepartamentos == "" || iddepartamentos == 0 || iddepartamentos == undefined) ){
			notification("Error","El campo Departamento es obligatorio",'warning');
			respuesta = 0;
		}else if (responsable != '-' && (responsable == "" || responsable == 0 || responsable == undefined) ){
			notification("Error","El campo  Responsable",'warning');
			respuesta = 0;
		}
		return respuesta;
	} 
	
	//VALIDA CAMPOS 
	$("#form_plan_actividad .form-control").bind("keydown blur change",function(e){campos(this);});

	$('input[name="frecuencia"]').click(function(e){
		if($(this).is(':checked'))
			$('label[for="frecuencia"]').removeClass('form-valide-error-bottom');
	});
	$('input[name="frecuenciaDias"]').click(function(e){
		if($(this).is(':checked'))
			$('input[name="frecuenciaDias"]').parent().removeClass('form-valide-error-bottom');
	});		
	function campos(t) {
		setTimeout(function(){
			if($(t).val()=='' || $(t).val()==0){
				$(t).addClass('form-valide-error-bottom');
				if(!$('input[name="frecuencia"]').is(':checked')){
					$('label[for="frecuencia"]').addClass('form-valide-error-bottom');
				}
			}else{$(t).removeClass('form-valide-error-bottom');}	
		},500);
	}
	
	function checkboxCargarValue(arreglo, valor, checkboxAll){
		if(arreglo != undefined){
			if(arreglo.indexOf("|")){
				var arr_arreglo = arreglo.split("|");
				for(i = 0; i <= (arr_arreglo.length)-1; i++){	
					var value = $.trim(arr_arreglo[i]);
					if (jQuery.inArray(value, checkboxAll) !== -1 ){
						$('input[name="'+valor+'"][value="'+value+'"]').attr('checked', 'checked');
					}
				}
			}else{
				$('input[name="'+valor+'"][value="'+value+'"]').attr('checked', 'checked');
			}
		}
	}
	function checkboxArrValue(valor, arr){
		var checkboxValor = [];
		$(":checkbox[name="+valor+"]").each(function(index){
			checkboxValor.push($(this).val());
		});
		return checkboxValor;
	}
	
	//FRECUENCIA
$(".dias-frecuencia, .dias-frecuencia-quincenal, .mes-frecuencia").hide();
$('input[type=radio][name=frecuencia]').change(function () {
	console.log("PASÓ ESTADO");
	var frecuencia = $('input[type=radio][name=frecuencia]:checked').attr('id');
	frecuencia = frecuencia.substr(1);
	$(".dias-frecuencia, .dias-frecuencia-quincenal, .mes-frecuencia").hide();
	if(frecuencia == 'diaria'){
		$(".dias-frecuencia").show();
	}else{
		$(".dias-frecuencia").hide();
	}
	if(frecuencia == 'quincenal'){
		$(".dias-frecuencia-quincenal").show();
	}else{
		$(".dias-frecuencia-quincenal").hide();
	}
	if(frecuencia != 'diaria' && frecuencia != 'quincenal'){
		$(".mes-frecuencia").show();
	}else{
		$(".mes-frecuencia").hide();
	}
});
	//incializa los select2
	$("select").select2();


