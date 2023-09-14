$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() { 
    /*-DECLARACION-VARIABLES--------------------------------------------------*/
    let idsubtipo=0;
    
    let cvisible = false;
	if(nivel != 7){
		cvisible = true;
	}
    /*-SELECT-----------------------------------------------------------------*/

    $.get( "controller/combosback.php?oper=clientes", { idempresas: "1" }, function(result){ 
        console.log(result)
		$("#idcliente").empty();
		$("#idcliente").append(result);
        console.log($("#idcliente"))

	});
    
     $.get( "controller/combosback.php?oper=tipos", function(result){ 
    	$("#tipo").empty();
    	$("#tipo").append(result);
    });
    
    $('#idcliente').on('select2:select',function(){
//    	let idcliente = $("#selectCliente option:selected").val();
		var idcliente = $("#idcliente option:selected").val();
    	//PROYECTOS
    	$.get( "controller/combosback.php?oper=proyectos", { idclientes: idcliente }, function(result){ 
    		$("#idproyecto").empty();
    		$("#idproyecto").append(result);
    	});
    });
    
    /*-FUNCION-SE-DISPARA-SI-EXISTE-UN-PARAMETRO-POR-LA-URL-------------------*/
	let getUrl=getQueryVariable('id');
	
	idsubtipo =getUrl!==false?getUrl:0; 
    console.log(idsubtipo,getUrl)
	if(getUrl!==false){
	    jQuery.ajax({
           url: "controller/subtiposback.php?oper=getSubtipo&idsubtipo="+idsubtipo,
           dataType: "json",
           success: function(item) {
                $('#id').val(idsubtipo);
                $('#tipo').val(item.idtipo).trigger("change");
                $('#nombre').val(item.nombre);
                $('#idcliente').val(item.idcliente).trigger("change");
                $.when( $('#idcliente').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//Proyecto
					$.get( "controller/combosback.php?oper=proyectos", { idcliente: item.idcliente }, function(result){ 
						$("#idproyecto").empty();
						$("#idproyecto").append(result);
						$('#idproyecto').val(item.idproyecto).trigger("change");
					}); 
				});   
           },
			/*complete: function(data,status){ 
				tablacampos.ajax.url( "controller/subtiposback.php?oper=cargarCampos&idsubtipo="+idsubtipo).load(null,false);
			}*/
        }); 
        $('.tipo').html('Editar subtipo');
	}else{
	    $('.tipo').html('Nuevo subtipo');
	}
	/*-Tabla------------------------------------------------------------------*/
	$('#tbsubtipo thead th').each(function() {
		let title = $(this).text();
		let id = $(this).attr('id');
		let ancho = $(this).width();
		
		if (title !== '' && title !== '-' && title !== 'Acción') {
			if (screen.width > 1024) {
				if(title == "Nombre"){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
    			}else if(title == "Tipo"){
    				$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
    			}else if(title == "Opciones"){
    				$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
    			}
			} else {
				$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
			}
		} else if (title == 'Acción') {
		    ancho = '100px';
		}
		$(this).width(ancho);
	});
	
	tablacampos = $("#tbsubtipo").DataTable({
	    scrollY: '100%',
		scrollX: true,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: true,
		autoWidth : true,
	    ajax: {
	        url:`controller/subtiposback-n.php?oper=cargarCampos&idsubtipo=${idsubtipo}`,
	    },
	    columns	: [
			{ 	"data": "id" },					//0
			{ 	"data": "acciones" },			//1
			{ 	"data": "nombre" },				//2
			{ 	"data": "tipo" }, 			    //3 
			{ 	"data": "opciones" }, 			//4 
		    ],
	    rowId: 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTOD id()
	    columnDefs: [ //OCULTAR LA COLUMNA Descripcion 
	        {
				targets : [0],
				visible: false
			},
			{
				targets: [1],
				visible: true,
	            className: 'text-center'
			},
			{
				targets: [2,4],
				visible: cvisible,
			},
			{
	            targets: [2, 3, 4],
	            className: 'text-left'
	        },
			{ targets	: 0, width	: '0%' },
			{ targets	: 1, width	: '80px' },
			{ targets	: 2, width	: '80px' },
			{ targets	: 3, width	: '80px' },
			{ targets	: 4, width	: '80px' },
	    ],
	    language: {
	        url: "js/Spanish.json",
	    },
	    lengthMenu: [[10,25, 50, 100], [10,25, 50, 100]],
	    initComplete: function() {		
			//APLICAR BUSQUEDA POR COLUMNAS
            let  height = $('#tbsubtipo').height();
            this.api().columns().every( function () {
                var that = this; 
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if (that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                } );
            });
			//OCULTAR LOADER
			$('#preloader').css('display','none');
	    },
		dom: '<"toolbarU toolbarDT">Blfrtip'
	});
	
	/*fin tabla*/
	$('#tbsubtipo').on( 'draw.dt', function () {		
		// Dar funcionalidad al botón desactivar
		//evaluarancho('tbsubtipo')
		ajustarTablas()
		
	    $('.boton-eliminar').each(function(){
			let id 	   = $(this).attr("data-id");
			let nomnbre = $("#tbsubtipo tr#"+id).find('td:nth-child(2)').html();
			
			$(this).on( 'click', function() {
				quitarCampo(id,nomnbre);
			});
		});
		// Tooltips
		$('[data-toggle="tooltip"]').tooltip();

	});
	
	/*-EVENTOS----------------------------------------------------------------*/
	
	//LIMPIAR COLUMNAS
	$('#limpiarCol').on( 'click', function() {
		$("#tbsitios").DataTable().search("").draw();
		$('#tbsitios_wrapper thead input').val('').change();
	});
	//REFRESCAR
	$("#refrescar").on('click', function() {
		tablacampos.ajax.reload();
	    ajustarTablas();
	});
	
	$("#anadir_campo").on("click",function(){
		 guardarCampo();
	});
	
	$("#btn-list-subtipos").on("click",function(){
	    location.href = "subtipos.php";
	})
	
	$("#guardar-subtipo").on("click",function(){		
		guardarSubTipo();
	});
	
	$("#tipo_campo").change(function(e,data){
		let tipo_campo = $("#tipo_campo").val();
		if(tipo_campo == 'Selector'){
			$(".selector").show();
			console.log("add")
			$('#box_anadir_campo').removeClass('col-sm-4 col-md-4').addClass('col-sm-12 col-md-12');
			$('#anadir_campo').removeClass('float-left').addClass('float-right');
			$('#anadir_campo').prop('style','margin-top:0px;height: 40px;');
		}else{
			$(".selector").hide();
			console.log("remove")
			$('#box_anadir_campo').removeClass('col-sm-12 col-md-12').addClass('col-sm-4 col-md-4');
			$('#anadir_campo').removeClass('float-right').addClass('float-left');
			$('#anadir_campo').prop('style','margin-top:28px;height: 40px;');

		} 


	});
	
	/*-FUNCIONES--------------------------------------------------------------*/
	function ajustarTablas(){
    	if (screen.width > 1024) {
    		//console.log('screen.width: '+screen.width);
    		$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    		$('.dataTables_scrollHead table').width('100%');
    		$('.dataTables_scrollBody table').width('100%');
    	}
    	
    	let height =$('#tbsubtipo').height();
        if (height>36 && height <120) {
    	    $('#tbsubtipo').height('80px');
        }
    }
	
	const existeCampo=(lblsubtipo)=>{
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
	
	function vaciarCampos(){
		$('#nombre_campo').val(""); 
		$('#tipo_campo').val(null).trigger("change");
		$('#opciones_campo').val(""); 
	}
	
	function validarSubtipo(idtipo,nombre,idcliente,idproyecto){ 
		var respuesta = 1; 
		if(idcliente == "" || idcliente == null || idcliente == undefined || idcliente == 0 || idcliente == "Sin Asignar"){
			notification('El campo Cliente es obligatorio!',"Advertencia!!","warning")
			respuesta = 0;
		}else if(idproyecto == "" || idproyecto == null || idproyecto == undefined || idproyecto == 0 || idproyecto == "Sin Asignar"){
			notification('El campo Proyecto es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		}else if(idtipo == "" || idtipo == null || idtipo == undefined || idtipo == 0 || idtipo == "Sin Asignar"){
			notification('El campo Tipo es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		}else if (nombre ==''){
		    notification('El campo Nombre del Subtipo es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		} 
		return respuesta;
	}
	
	const validarCampos=(nombre,tipo,opciones)=>{ //falta definir opciones de selector
		var respuesta = 1;

		if (nombre ==''){
			notification('El campo Nombre del campo es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		}else if (tipo =='' || tipo ==null || tipo ==undefined || tipo =='Seleccione' || tipo ==0){
			notification('El campo Tipo es obligatorio!',"Advertencia!","warning")
			respuesta = 0;
		} 
		if(tipo !='' || tipo !=null || tipo !=undefined || tipo !='Seleccione' || tipo !=0){
			if(tipo == 'Selector' && opciones == ""){
				notification('El campo Opciones es obligatorio!',"Advertencia!","warning")
				respuesta = 0;
			}
		}
		return respuesta;
	}
	/*-PETICIONES-------------------------------------------------------------*/
	const guardarCampo=()=>{
		
		var respuesta  =	 0;
		var idsubtipo  = $('#id').val();
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
					success: function (response) {					 
						if(response != 0){  
							vaciarCampos();
							notification('Campo agregado satisfactoriamente',"Buen trabajo!","success")
						    setTimeout(()=>{ 
						        tablacampos.ajax.reload(null, false);
						    }, 3000);
						}else{
							notification('Error al guardar!',"ERROR!!","error")
						}											
					},
					error: function () {
						notification('Ha ocurrido un error al grabar el Registro, intente mas tarde',"ERROR!!","error")
					}
				});
			}
		}else{
			notification('El nombre ya existe',"Error","error")
		} 
	}
	
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
		}).then((result)=>{
			if(result.value){
				console.log("eliminar")
				$.get( "controller/subtiposback.php?oper=eliminarCampo", 
    			{ 
    				onlydata : "true",
    				id 	     : id,
    				nombre   : nombre
    			}, function(result){
    				if(result == 1){
    					tablacampos.ajax.reload(null, false);
    					notification('Campo eliminado satisfactoriamente',"Buen trabajo!","success")
    				} else {
    					notification('Ha ocurrido un error al eliminar el Registro, intente más tarde',"ERROR!!","error")
    				}
    			});
			}else if(result.dismiss){
				console.log("no eliminar")
			}
		});
	}
	
	function vaciarSubTipos(){
		$('#id').val(null);
		$('#tipo').val(null).trigger("change");;
		$('#nombre').val(null);

		$('#idcliente').val(null).trigger("change");
		$('#idproyecto').val(null).trigger("change");

		$('#nombre_campo').val(""); 
		$('#tipo_campo').val(null).trigger("change");
		$('#opciones_campo').val(""); 

        idsubtipo = 0;
        tablacampos.ajax.reload(null, false);


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
		console.log(id)
		if(id != ""){
			oper = "actualizarSubtipo";
			msj  = "actualizado";
			console.log(msj)
		}else{
			oper = "guardarSubtipo";
			msj  = "creado";
			console.log(msj)
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
							success: function (response) {
								if(response != 0){  
									vaciarSubTipos();  
                                    if(oper=="guardarSubtipo"){
            
//            	    					notification('','Buen trabajo!','success');
                                        swal({		
                    								title: 'SubTipo '+msj+' satisfactoriamente',	
                    								text: "¿Desea registrar otro SubTipo?",
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
                            						 document.getElementById('nombre').focus();
                            						    
                    							}else{
        //        									location.href = "subtipos-n.php";
                									location.href = "subtipos.php";//JOSUE
                    							}
                    						});
                                    }else{
        									notification('SubTipo '+msj+' satisfactoriamente','Buen trabajo!',"success")
//        									location.href = "subtipos-n.php";
        									location.href = "subtipos.php";//JOSUE
                                    }


								}else{
								    notification('Error al guardar!','ERROR!',"error")
								}														 
							},
							error: function () {			
								notification('Ha ocurrido un error al grabar el Registro, intente mas tarde','ERROR!',"error")
							}
						});
				}else{
				    notification('Debe agregar los campos','ERROR!',"error")
				}
			});
		}
	}
});
$("select").select2();


