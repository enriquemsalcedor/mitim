$(document).ready(function() {  
	$("select").select2();  

    $('#tbpermisos thead th').each( function () {
        var title = $(this).text();
		var ancho1 = $(this).width();
		var ancho2 = ($(this).width() * 0.4).toFixed(0);
		if ( title !== '' && title !== '-' && title !== 'Acciones') 
			$(this).html( '<input type="text" placeholder="'+title+'" style="width: 100%" /> ' );
		else if (title=='-') 
			$(this).html( '<input id="chkSelectAll" class="fac fac-checkbox fac-white" type="checkbox" value="A11|" />' );
		
		$(this).width(ancho1);
    });
    
	var tbpermisos = $("#tbpermisos").DataTable({
		scrollX: true,
		destroy: true,
		scrollY: '54vh',
		scrollCollapse: true,
		searching: true,
		stateSave: true, //enable state saving (pagination,search per column,current page, search inputs....à
		stateLoadParams: function (settings, data) {			
			$('th#cnotificacion input').val(data['columns'][2]['search']['search']);
			$('th#cniveles input').val(data['columns'][3]['search']['search']);
			$('th#cexcepciones input').val(data['columns'][4]['search']['search']);
		},
		serverSide: true, 
		select: true,
		colReorder: true,
		fixedHeader: true,
		processing: true, 
		bAutoWidth: false,
		ordering: false,
		"ajax"		:"controller/permisosback.php?oper=permisos",
		"columns"	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "notificacion" },
			{ 	"data": "nivel" },
			{ 	"data": "excepcion" }
			],
		rowId: 'id',
		"columnDefs": [ 
			{
				"targets"	: [ 0 ],
				"visible"	: false,
				"searchable": false
			},
			{
				"targets"		: [2,3,4],
				"className"	: "dt-left"
			}
		],
		"language": {
			"url": "js/Spanish.json"
		}
	});
	cargarCombos();
	
	$('#limpiarFiltros').click(function(){
		tbpermisos.state.clear();
		window.location.reload();
	});
	
	tbpermisos.columns().every( function () {
		var that = this;
		$( 'input', this.header() ).keypress(function (event) {
			if (this.value!='A11|') {
				if ( event.which == 13 ) {
					if ( that.search() !== this.value ) {
						that
							.search( this.value )
							.draw();
					}
				}
			}
		});
	});

	// $("#tbpermisos tbody").on('dblclick','tr',function(){
	// 	var idniveles = $(this).attr("id"); 
	// 	jQuery.ajax({
	// 		url: "controller/nivelesback.php?oper=getnivel&idniveles="+idniveles,
	// 		dataType: "json",
	// 		beforeSend: function(){
	// 			$('#overlay').css('display','block');
	// 		},success: function(item) {
	// 			$('#overlay').css('display','none');
    //             $("#modalniveles").modal("show");
    //             $("#idniveles").val(idniveles);  
	// 			$("#nombre").val(item.nombre);							
	// 			$('#descripcion').val(item.descripcion);
    //        }
    //     }); 
	// }); 
	
	//VALIDAR GUARDAR niveles
	function vnivel(nivel,nombre,notificacion){
		var respuesta = 1;
		if(nivel == ""){
			demo.showSwal('error-message','ERROR','Debe seleccionar un nivel');
			respuesta = 0;
		}
		if(notificacion == ""){
			demo.showSwal('error-message','ERROR','Debe seleccionar una notificación');
			respuesta = 0;
		}
		if (nombre != ""){
			if (nombre.length < 3){
				demo.showSwal('error-message','ERROR','El nombre debe tener una longitud de al menos 2 caracteres');
				respuesta = 0;
			}
		}else{
			demo.showSwal('error-message','ERROR','Debe introducir un nombre');
			respuesta = 0;
		}
		return respuesta;
	}
	function vexcepcion(usuario,notificacion){
		var respuesta = 1;
		if(usuario == ""){
			demo.showSwal('error-message','ERROR','Debe seleccionar un usuario');
			respuesta = 0;
		}
		if(notificacion == ""){
			demo.showSwal('error-message','ERROR','Debe seleccionar una notificación');
			respuesta = 0;
		}
		return respuesta;
	}
	
	function savepermisos(){	
		// var id	          = $("#idniveles").val();
		// if(id==''){
		// 	oper = 'createnivel';
		// }else{
		// 	oper = 'updatenivel';
		// } 
		var tipopermiso	   		=   $('#tipopermiso').val(); 
		var nivelpermiso		=   $('#nivelpermiso').val(); 
		var nombrepermiso		=   $('#nombrepermiso').val(); 
		var notificacionnivel		=   $('#notificacionnivel').val(); 
		var usuariopermiso		=   $('#usuariopermiso').val(); 
		var notificacionpermiso	=   $('#notificacionpermiso').val();
		if(tipopermiso == 'nivel'){
			if (vnivel(nivelpermiso,nombrepermiso,notificacionnivel) == 1){
				$.ajax({
					type: 'post',
					url: 'controller/permisosback.php',
					data: { 
						'oper'		    	: 'createnivel', 
						'nivelpermiso' 	    : nivelpermiso,
						'nombrepermiso' 	: nombrepermiso,
						'notificacionnivel' : notificacionnivel
					},
					beforeSend: function() {
						$('#overlay').css('display','block');
					},
					success: function (response) {
						$('#overlay').css('display','none');
						vaciarniveles();
						demo.showSwal('success-message','Buen trabajo','Nivel para la notificación creado satisfactoriamente');
						tbpermisos.ajax.reload(null, false);
						$('#modalpermisos').modal('hide');
					},
					error: function () {
						$('#overlay').css('display','none');
						demo.showSwal('error-message','ERROR','Ha ocurrido un error al guardar el Nivel para la notificación, intente más tarde');
					}
				});	
			}
		}else if(tipopermiso == 'excepcion'){
			if (vexcepcion(usuariopermiso,notificacionpermiso) == 1){
				$.ajax({
					type: 'post',
					url: 'controller/permisosback.php',
					data: { 
						'oper'		    		: 'createexcepcion', 
						'usuariopermiso' 	    : usuariopermiso,
						'notificacionpermiso' 	: notificacionpermiso
					},
					beforeSend: function() {
						$('#overlay').css('display','block');
					},
					success: function (response) {
						$('#overlay').css('display','none');
						vaciarniveles();
						demo.showSwal('success-message','Buen trabajo','Excepción creada satisfactoriamente');
						tbpermisos.ajax.reload(null, false);
						$('#modalpermisos').modal('hide');
					},
					error: function () {
						$('#overlay').css('display','none');
						demo.showSwal('error-message','ERROR','Ha ocurrido un error al guardar la excepción, intente más tarde');
					}
				});	
			}
		}else{
			demo.showSwal('error-message','ERROR','Debe seleccionar un tipo de permiso');
		}
	} 

	function vaciarpermisos(){
		$("#tipopermiso").val("");
		$("#nivelpermiso").val("");							
		$('#nombrepermiso').val("");
		$("#notificacionnivel").val("");
		$("#usuariopermiso").val("");							
		$('#notificacionpermiso').val("");
	}  		
	
	function cargarCombos(){
		$.get("controller/combosback.php?oper=niveles", { onlydata:"true" }, function(result){
			$("#nivelpermiso").select2({placeholder:''});
			$("#nivelpermiso").append(result);	
		});	
		$.get("controller/combosback.php?oper=usuarios", { onlydata:"true" }, function(result){
			$("#usuariopermiso").select2({placeholder:''});
			$("#usuariopermiso").append(result);	
		});	 
		$.get("controller/combosback.php?oper=tiponotificaciones", { onlydata:"true" }, function(result){
			$("#notificacionnivel").select2({placeholder:''});
			$("#notificacionnivel").append(result);	
		});
		$.get("controller/combosback.php?oper=notificaciones", { onlydata:"true" }, function(result){
			$("#notificacionpermiso").select2({placeholder:''});
			$("#notificacionpermiso").append(result);	
		}); 
	}
 
    // function eliminarnivel(id,nombre){
	// 	var idactivos = id;
	// 	swal({
	// 		title: "Confirmar",
	// 		text: "¿Esta seguro de eliminar el Nivel "+nombre+"?",
	// 		type: "warning",
	// 		showCancelButton: true,
	// 		cancelButtonColor: 'red',
	// 		confirmButtonColor: '#09b354',
	// 		confirmButtonText: 'Si',
	// 		cancelButtonText: "No"
	// 	}).then(
	// 		function(isConfirm){
	// 			if (isConfirm){
	// 				$.get( "controller/nivelesback.php?oper=deletenivel", 
	// 				{ 
	// 					onlydata : "true",
	// 					id : id 
	// 				}, function(result){
	// 					if(result == 1){
	// 						swal('Buen trabajo','Nivel eliminado satisfactoriamente','success');		
	// 						tbpermisos.ajax.reload(null, false);
	// 					} else {
	// 						swal('ERROR','Ha ocurrido un error al eliminar el Nivel, intente más tarde','error');
	// 					}
	// 				});

	// 			}
	// 		}, function (isRechazo){  
	// 		}
	// 	);
	// }  
	 
	// tbpermisos.on( 'draw.dt', function () {	  
    //     $('.boton-eliminar').each(function(){
	// 		var id = $(this).attr("data-id");
	// 		var nombre = $(this).parent().parent().next().next().html();
	// 		$(this).on( 'click', function() {
	// 			eliminarnivel(id,nombre);
	// 		});
	// 	});
		
	// 	// TOOLTIPS
	// 	$('[data-toggle="tooltip"]').tooltip();	 
		
    // }); 
	
	$('#tipopermiso').on('select2:select',function(){
		if($('#tipopermiso').val() == 'nivel'){
			$(".div_excepcion").css('display','none');
			$(".div_nivel").css('display','block');
		}else if($('#tipopermiso').val() == 'excepcion'){
			$(".div_nivel").css('display','none');
			$(".div_excepcion").css('display','block');
		}else{
			$(".div_nivel").css('display','none');
			$(".div_excepcion").css('display','none');
		}
	});

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
	
});


