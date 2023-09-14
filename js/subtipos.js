//$(document).ready(function() {   
ajustarDropdown();
$("#icono-filtrosmasivos").css("display","none");
$("#tipo_campo").select2({placeholder: ""});
	
	$.get( "controller/combosback.php?oper=clientes", { idempresas: 1 }, function(result){ 
		$("#idcliente").empty();
		$("#idcliente").append(result);
		$("#idcliente").select2({placeholder: ""});
	});
	
	$.get( "controller/combosback.php?oper=tipos", function(result){ 
		$("#tipo").empty();
		$("#tipo").append(result);
		$("#tipo").select2({placeholder: ""});
	});
	
	$('#idcliente').on('select2:select',function(){
		var idcliente = $("#idcliente option:selected").val();
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idcliente }, function(result){ 
			$("#idproyecto").empty();
			$("#idproyecto").append(result);
			$("#idproyecto").select2({placeholder: ""});
		});
	});	

	$('#tablasubtipos thead th').each( function () {
        var title = $(this).text();
		var ancho1 = $(this).width();
		var ancho2 = ($(this).width() * 0.4).toFixed(0); 
		if (title != ''){
			$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 100px" /> ');
		} 
		$(this).width(ancho1);
    });
    
	var tablasubtipos = $("#tablasubtipos").DataTable({
		scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
		stateSave: true,
        searching: true,
		//serverSide: true,
        //serverMethod: 'post',
        stateLoadParams: function (settings, data) {
            const{columns}=data
			$('th#ctipo input').val(columns[2]['search']['search']); 
            $('th#csubtipo input').val(columns[3]['search']['search']);
            $('th#ccliente input').val(columns[4]['search']['search']);
            $('th#cproyecto input').val(columns[5]['search']['search']);
        },
		ajax		:"controller/subtiposback.php?oper=subtipos",
		columns	: [
			{ 	"data": "id" },					//0
			{ 	"data": "acciones" },			//1
			{ 	"data": "tipo" },				//2
			{ 	"data": "subtipo" }, 			//3 
			{ 	"data": "cliente" }, 			//4 
			{ 	"data": "proyecto" }			//5 
			],
		rowId: 'id',
		columnDefs: [ 
			{
				"targets"	: [ 0 ],
				"visible"	: false,
				"searchable": false
			},
			{
				"targets"		: [2],
				"className"	: "dt-left"
			}
		],
		language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
	    initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
            this.api().columns().every( function () {
                var that = this; 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                } );
            });
			//OCULTAR LOADER
			$('#preloader').css('display','none');
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	
	tablasubtipos.columns().every( function () {
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

	$("#tablasubtipos tbody").on('dblclick','tr',function(){
		let idsubtipo = $(this).attr("id"); 
		jQuery.ajax({
           url: "controller/subtiposback.php?oper=getSubtipo&idsubtipo="+idsubtipo,
           dataType: "json",
           beforeSend: function(){
               $('#overlay').css('display','block');
           },success: function(item) {
				$('#overlay').css('display','none');
                $("#modalsubtipos").modal("show");
                $('#id').val(idsubtipo);
				console.log("TIPO:"+item.idtipo);
                $('#tipo').val(item.idtipo).trigger("change");
                $('#nombre').val(item.nombre);
                $('#idcliente').val(item.idcliente).trigger("change");
				$.when( $('#idcliente').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//Proyecto
					$.get( "controller/combosback.php?oper=proyectos", { idcliente: item.idcliente }, function(result){ 
						$("#idproyecto").empty();
						$("#idproyecto").append(result);
						$("#idproyecto").select2({placeholder: ""});
						$('#idproyecto').val(item.idproyecto).trigger("change");
					}); 
				});   
           },
			complete: function(data,status){ 
				tablacampos.ajax.url( "controller/subtiposback.php?oper=cargarCampos&idsubtipo="+idsubtipo).load(null,false);
			}
        });  
	}); 
	     
	tablasubtipos.on( 'draw.dt', function () {	  
        $('.boton-eliminar-subtipo').each(function(){
			var id = $(this).attr("data-id");
			var nombre = $(this).parent().parent().next().next().html();
			$(this).on( 'click', function() {
				eliminar(id,nombre);
			});
		});		
		// TOOLTIPS
		$('[data-toggle="tooltip"]').tooltip();	  
    }); 
 
    //Botones de Modales
    $("#guardar-subtipo").on("click",function(){		
		guardarSubTipo();
	});
	
	$("#boton-nuevo").on("click",function(){		
		$("#modalsubtipos").modal("show");
	}); 
	
	$("#modalsubtipos").on("hidden.bs.modal", function () {
		vaciarSubtipo();
		let id = $("#id").val();
		eliminarCamposTemp();
		tablacampos.ajax.url( "controller/subtiposback.php?oper=cargarCampos&idsubtipo=0").load(null,false); 
    }); 
	
	/* $('#modalsubtipos').on('shown.bs.modal', function () {
		if(id == ""){ 
			tablacampos.ajax.url( "controller/subtiposback.php?oper=cargarCampos&idsubtipo=0").load(null,false); 
		}else{
			tablacampos.ajax.url( "controller/subtiposback.php?oper=cargarCampos&idsubtipo=0").load(null,false); 
		}
	}) */
	function eliminarCamposTemp(){
		$.get( "controller/subtiposback.php?oper=eliminarCamposTemp", 
		function(result){
			/* if(result == 1){ 
				tablacampos.ajax.reload(null, false); 
			} else {
				swal('ERROR','Ha ocurrido un error al eliminar el Registro, intente más tarde','error');
			} */
		});
	}
	
	function vaciarSubtipo(){
		$('#id').val("");  
		$('#tipo').val(null).trigger("change");  
		$('#nombre').val(""); 
		$('#idcliente').val(null).trigger("change");  
		$('#idproyecto').val(null).trigger("change");  
	} 
//});
	function validarSubtipo(idtipo,nombre,idcliente,idproyecto){ 
		var respuesta = 1; 
		if(idcliente == "" || idcliente == null || idcliente == undefined || idcliente == 0 || idcliente == "Sin Asignar"){
			demo.showSwal('error-message','ERROR!','El campo Cliente es obligatorio!');
			respuesta = 0;
		}else if(idproyecto == "" || idproyecto == null || idproyecto == undefined || idproyecto == 0 || idproyecto == "Sin Asignar"){
			demo.showSwal('error-message','ERROR!','El campo Proyecto es obligatorio!');
			respuesta = 0;
		}else if(idtipo == "" || idtipo == null || idtipo == undefined || idtipo == 0 || idtipo == "Sin Asignar"){
			demo.showSwal('error-message','ERROR!','El campo Tipo es obligatorio!');
			respuesta = 0;
		}else if (nombre ==''){
			demo.showSwal('error-message','ERROR!','El campo Nombre del Subtipo es obligatorio!');
			respuesta = 0;
		} 
		return respuesta;
	}
	
	function guardarSubTipo(){
		
		var respuesta =	 0;
		var id     	   = $('#id').val();
		var idtipo 	   = $('#tipo').val();
		var nombre 	   = $('#nombre').val();
		var idcliente  = $('#idcliente').val();
		var idproyecto = $('#idproyecto').val();
		
		let oper   = "";
		let msj    = "";
		
		if(id != ""){
			oper = "actualizarSubtipo";
			msj  = "actualizado";
		}else{
			oper = "guardarSubtipo";
			msj  = "creado";
		} 
		if(validarSubtipo(idtipo,nombre,idcliente,idproyecto)== 1&& respuesta == 0){
			//Verificar si tiene campos agregados
			$.get( "controller/subtiposback.php?oper=existeCampos&idsubtipo="+id, function(result){		
				if(result == 1){
					
						$.ajax({
							type: 'post', 
							url: 'controller/subtiposback.php?oper='+oper,
							data: {   
								'id'	 	 : id,
								'idtipo' 	 : idtipo,
								'nombre' 	 : nombre, 
								'idcliente'  : idcliente, 
								'idproyecto' : idproyecto 
							},
							beforeSend: function() {  
								//$('#overlay').css('display','block');		  
							},
							success: function (response) {
								if(response != 0){  
									vaciarSubtipo(); 
									$("#modalsubtipos").modal("hide");
									demo.showSwal('success-message','Buen trabajo!','SubTipo '+msj+' satisfactoriamente'); 
									tablasubtipos.ajax.reload(null, false); 
								}else{
									demo.showSwal('error-message','ERROR!','Error al guardar!');
								}											
								//$('#overlay').css('display','none');					 
							},
							error: function () {
								//$('#overlay').css('display','none');			
								demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Registro, intente mas tarde');
							}
						});
					
				}else{
					demo.showSwal('error-message','ERROR!','Debe agregar los campos');
				}
			});
		} 
	}
	
	function eliminar(id,nombre){
		 
		$.get( "controller/subtiposback.php?oper=hayRelacion", 
		{  
			id : id 
		}, function(result){
			if(result == 1){
				swal('ERROR','Hay activos asociados a este Subtipo, no se puede eliminar','error');
			}else{ 
				swal({
					title: "Confirmar",
					text: "¿Esta seguro de eliminar el Subtipo "+nombre+"?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm){
							$.get( "controller/subtiposback.php?oper=eliminarSubtipo", 
							{ 
								onlydata : "true",
								id 	     : id,
								nombre   : nombre
							}, function(result){
								if(result == 1){
									swal('Buen trabajo','SubTipo eliminado satisfactoriamente','success');		
									tablasubtipos.ajax.reload(null, false); 
								} else {
									swal('ERROR','Ha ocurrido un error al eliminar el Registro, intente más tarde','error');
								}
							});
						}
					}, function (isRechazo){
						
					}
				);
			}
		});	 
	}
	
	/********************************CAMPOS DEL SUBTIPO*******************************************************/
	$('[data-toggle="tooltip"]').tooltip();
	
	function validarCampos(nombre,tipo,opciones){ //falta definir opciones de selector
		var respuesta = 1;

		if (nombre ==''){
			demo.showSwal('error-message','ERROR!','El campo Nombre del campo es obligatorio!');
			respuesta = 0;
		}else if (tipo =='' || tipo ==null || tipo ==undefined || tipo =='Seleccione' || tipo ==0){
			demo.showSwal('error-message','ERROR!','El campo Tipo es obligatorio!');
			respuesta = 0;
		} 
		if(tipo !='' || tipo !=null || tipo !=undefined || tipo !='Seleccione' || tipo !=0){
			if(tipo == 'Selector' && opciones == ""){
				demo.showSwal('error-message','ERROR!','El campo Opciones es obligatorio!');
				respuesta = 0;
			}
		}
		return respuesta;
	}
	
	function vaciarCampos(){
		$('#nombre_campo').val(""); 
		$('#tipo_campo').val(null).trigger("change");
		$('#opciones_campo').val(""); 
	}

	tablacampos = $("#tablacampos").DataTable({
		responsive: false,
		destroy: true,
		ordering: false,
		searching: false,
		"ajax"		: {
			"url"	: "controller/subtiposback.php?oper=cargarCampos&idsubtipo=0", 
		}, 
		"columns"	: [
			{ 	"data": "id" },
			{ 	"data": "acciones" },
			{ 	"data": "nombre" },
			{ 	"data": "tipo" }, 
			{ 	"data": "opciones" }
			],
		"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
		"columnDefs": [ //OCULTAR LA COLUMNA ID
			{
				"targets"	: [ 0 ],
				"visible"	: false,
				"searchable": false
			},{
				"targets"	: [ 1 ],
				"width"		: "5px"
			},{
				targets		: [2],
				className	: "dt-left"
			} 
		],
		"language": {
			"url": "js/Spanish.json"
		} 
	});
	
	$("#anadir_campo").on("click",function(){
		 guardarCampo();
	});
	 
	$("#tipo_campo").change(function(e,data){
		let tipo_campo = $("#tipo_campo").val();
		if(tipo_campo == 'Selector'){
			$(".selector").show();
		}else{
			$(".selector").hide();
		} 
	});
	
	function existeCampo(lblsubtipo){
		var lbls = []; 
		tablacampos.rows().data().each(function (value) {
			lbls.push(value.nombre); 
		}); 
		var resultado = lbls.indexOf(lblsubtipo); 
		if(resultado == -1){
			//No existe el campo
			return 1;
		}else{
			//Sí existe el campo 
			return 0;
		}
	}

	function guardarCampo(){
		
		var respuesta  =	 0;
		var idsubtipo  = $('#idsubtipo').val();
		var nombre	   = $('#nombre_campo').val();
		var tipo 	   = $('#tipo_campo').val(); 
		var opciones   = $('#opciones_campo').val(); 
		let oper 	   = "guardarCampo";
		
		var existe = existeCampo(nombre);
		
		if(existe == 1){
			if(validarCampos(nombre,tipo,opciones)== 1&& respuesta == 0){
				$.ajax({
					type: 'post', 
					url: 'controller/subtiposback.php?oper='+oper,
					data: {   
						'idsubtipo': idsubtipo,
						'nombre'   : nombre,
						'tipo'     : tipo,  
						'opciones' : opciones
					},
					beforeSend: function() {  
						$('#overlay').css('display','block');					  
					},
					success: function (response) {
						
						$('#overlay').css('display','none');					 
						if(response != 0){  
							vaciarCampos();
							demo.showSwal('success-message','Buen trabajo!','Campo agregado satisfactoriamente'); 
							tablacampos.ajax.reload(null, false); 
						}else{
							demo.showSwal('error-message','ERROR!','Error al guardar!');
						}											
					},
					error: function () {
						$('#overlay').css('display','none');			
						demo.showSwal('error-message','ERROR!','Ha ocurrido un error al grabar el Registro, intente mas tarde');
					}
				});
			}
		}else{
			demo.showSwal('error-message','ERROR!','El nombre ya existe');
		} 
	}
	
	$('#tablacampos').on( 'draw.dt', function () {	
		$('.boton-eliminar').each(function(){
			var id = $(this).attr("data-id"); 
			$(this).on( 'click', function() {
				var nombre = $(this).parent().parent().next().html();
				quitarCampo(id,nombre);
			});
		}); 
	});
	
	function quitarCampo(id,nombre){
		var id = id;
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el campo "+nombre+"?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm){
					$.get( "controller/subtiposback.php?oper=eliminarCampo", 
					{ 
						onlydata : "true",
						id 	     : id,
						nombre   : nombre
					}, function(result){
						if(result == 1){
							swal('Buen trabajo','Campo eliminado satisfactoriamente','success');		
							tablacampos.ajax.reload(null, false); 
						} else {
							swal('ERROR','Ha ocurrido un error al eliminar el Registro, intente más tarde','error');
						}
					});
				}
			}, function (isRechazo){
				
			}
		);
	}


