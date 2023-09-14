$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
	var idusuario = getQueryVariable('id');
	$("select").select2({ language: "es" });
	
	
	// if (idusuario) {
	//     cargarusuario(idusuario);
	// }else{
	//     niveles(0);
	// }
    cargarusuario(idusuario);
	$("#listadousuarios").on("click",function(){
		location.href = 'permisos.php';
	});

	function guardar(){	
		var noti1 			= $('input[name=noti1]:checked').val();
		var noti2 			= $('input[name=noti2]:checked').val();
		var noti3 			= $('input[name=noti3]:checked').val();
		var noti4 			= $('input[name=noti4]:checked').val();
		var noti5 			= $('input[name=noti5]:checked').val();
		var noti6 			= $('input[name=noti6]:checked').val();
		var noti7 			= $('input[name=noti7]:checked').val();
		var noti8 			= $('input[name=noti8]:checked').val();
		var noti9 			= $('input[name=noti9]:checked').val();
		var noti10 			= $('input[name=noti10]:checked').val();
		var noti11 			= $('input[name=noti11]:checked').val();
		var noti12 			= $('input[name=noti12]:checked').val();
		var noti13 			= $('input[name=noti13]:checked').val();
		$.ajax({
			type: 'post',
			url: 'controller/permisosback.php',
			data: { 
				'oper'		    : 'updatepermiso', 
				'idusuario'		: idusuario,
				'noti1' 		: noti1,
				'noti2' 		: noti2,
				'noti3' 		: noti3,
				'noti4' 		: noti4,
				'noti5' 		: noti5,
				'noti6' 		: noti6,
				'noti7' 		: noti7,
				'noti8' 		: noti8,
				'noti9' 		: noti9,
				'noti10' 		: noti10,
				'noti11' 		: noti11,
				'noti12' 		: noti12,
				'noti13' 		: noti13
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			success: function (response) {
				$('#overlay').css('display','none');
				notification("Nivel para la notificación actualizado satisfactoriamente","¡Exito!",'success');
				location.href = 'permisos.php';
			},
			error: function () {
				$('#overlay').css('display','none');				
				notification("Ha ocurrido un error al guardar el nivel para la notificacion","Error",'error');
			}
		});	
	} 

	function cargarusuario(idusuario){
		jQuery.ajax({
			url: "controller/permisosback.php?oper=getnotificacion&idusuario="+idusuario,
			dataType: "json",
			beforeSend: function(){
				$('#overlay').css('display','block');
			},success: function(item) {
				$('#overlay').css('display','none'); 
				$("#nombreusuario").html("Usuario: "+item.nombre);
				$('input[name=noti1][value='+item.noti1+']').attr('checked', 'checked');
				$('input[name=noti2][value='+item.noti2+']').attr('checked', 'checked');
				$('input[name=noti3][value='+item.noti3+']').attr('checked', 'checked');
				$('input[name=noti4][value='+item.noti4+']').attr('checked', 'checked');
				$('input[name=noti5][value='+item.noti5+']').attr('checked', 'checked');
				$('input[name=noti6][value='+item.noti6+']').attr('checked', 'checked');
				$('input[name=noti7][value='+item.noti7+']').attr('checked', 'checked');
				$('input[name=noti8][value='+item.noti8+']').attr('checked', 'checked');
				$('input[name=noti9][value='+item.noti9+']').attr('checked', 'checked');
				$('input[name=noti10][value='+item.noti10+']').attr('checked', 'checked');
				$('input[name=noti11][value='+item.noti11+']').attr('checked', 'checked');
				$('input[name=noti12][value='+item.noti12+']').attr('checked', 'checked');
				$('input[name=noti13][value='+item.noti13+']').attr('checked', 'checked');
           }
        }); 
	}

    //Botones de Modales
    $("#guardar-permiso").on("click",function(){
		savepermisos();
	});
    //Mostrar modal nuevo 
	// $("#boton-nuevo").on('click',function(){
	// 	cargarCombos();
	// 	$('#modalpermisos').modal('show');
	// });
	
	$("#modalpermisos").on("hidden.bs.modal", function () {
        vaciarpermisos();
    });


