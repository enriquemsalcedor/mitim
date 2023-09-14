$("#icono-filtrosmasivos,#icono-limpiar,#icono-refrescar").css("display","none");
$(document).ready(function() { 
	var id = getQueryVariable('id');
	$("#listado").click(function(){
	location.href = "activos.php";
    });

	function formatNumber(num) {
	  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
	}
	
	/* $("#ingresos").on('change',function(){
		console.log("PASÓ");
		var monto = $(this).val();
		//console.log(formatNumber(monto));
		$(this).val(formatNumber(monto));
	}); */
	
	function monthDiff(d1, d2) {
		var months;
		months = (d2.getFullYear() - d1.getFullYear()) * 12;
		months -= d1.getMonth() + 1;
		months += d2.getMonth();
		return months <= 0 ? 0 : months;
	} 
	$("#ingresos").on({
	  "focus": function(event) {
		$(event.target).select();
	  },
	  "keyup": function(event) {
		$(event.target).val(function(index, value) {
		  return value.replace(/\D/g, "")
			.replace(/([0-9])([0-9]{2})$/, '$1.$2')
			.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
		});
	  }
	});

	$('#fecinstactivos, #fectopactivos').bootstrapMaterialDatePicker({
		weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, lang : 'es', cancelText: 'Cancelar', switchOnClick:true, clearButton: true, clearText: 'Limpiar'
	});
	$("select").select2({placeholder: ""});
	//EMPRESAS
	/* $.get( "controller/combosback.php?oper=empresas", { onlydata:"true" }, function(result){ 
		$("#idempresas").empty();
		$("#idempresas").append(result);
		$("#idempresas").select2({placeholder: ""});
	}); */ 
	//EMPRESAS / CLIENTES
	/* $('#idempresas').on('select2:select',function(){
		//CLIENTES
		var idempresas = $("#idempresas option:selected").val();
		$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresas }, function(result){ 
			$("#idclientes").empty();
			$("#idclientes").append(result);
			$("#idclientes").select2({placeholder: ""});
		});
	}); */	
	
	//if(nivel == 7){
		$.get( "controller/combosback.php?oper=clientes", { idempresas: 1 }, function(result){ 
			$("#idclientes").empty();
			$("#idclientes").append(result); 
		});
	//}
	
	//CLIENTES
	$('#idclientes').on('select2:select',function(){
		var idempresas = $("#idempresasactivo option:selected").val();
		var idclientes = $("#idclientes option:selected").val();
		var idtipo 	   = $("#idtipo option:selected").val();	 
		
		//Proyectos
		$.get( "controller/combosback.php?oper=proyectos", { idempresas: idempresas, idclientes: idclientes }, function(result){ 
			$("#idproyectos").empty();
			$("#idproyectos").append(result); 
		});
		//Ambientes 
		$.get("controller/combosback.php?oper=ambientes", { idempresas: idempresas, idclientes: idclientes }, function(result){
			$("#idambientesactivo").empty();			
			$("#idambientesactivo").append(result); 			
		});
		
		//Marcas
		$.get("controller/combosback.php?oper=marcas", { tipo: "maestro" }, function(result){
			$("#idmarcasactivo").empty();
			$("#idmarcasactivo").append(result);	
		});
		
		//Responsables
		$.get("controller/combosback.php?oper=responsablesactivos", { idclientes: idclientes }, function(result){
			$("#idresponsablesactivo").empty();
			$("#idresponsablesactivo").append(result);	
		});
		
		if(idtipo != "" && idtipo != null && idtipo != undefined){
			//Tipos
			$.get( "controller/combosback.php?oper=subtipos", { idtipo: idtipo, idclientes: idclientes }, function(result){ 
				$("#idsubtipo").empty();
				$("#idsubtipo").append(result); 
			});
			$(".campossubtipos").empty();
		}
		
	});
	
	$('#idproyectos').on('select2:select',function(){
		var idempresas = $("#idempresasactivo option:selected").val();
		var idclientes = $("#idclientes option:selected").val();
		var idproyectos = $("#idproyectos option:selected").val();
		var idtipo 	   = $("#idtipo option:selected").val();
		
		//Ambientes 
		$.get("controller/combosback.php?oper=ambientes", { idempresas: idempresas, idclientes: idclientes, idproyectos: idproyectos }, function(result){
			$("#idambientesactivo").empty();			
			$("#idambientesactivo").append(result); 			
		});
		
		//Marcas
		$.get("controller/combosback.php?oper=marcas", { tipo: "maestro", idclientes: idclientes, idproyectos: idproyectos }, function(result){
			$("#idmarcasactivo").empty();
			$("#idmarcasactivo").append(result);	
		});
		
		//Responsables
		$.get("controller/combosback.php?oper=responsablesactivos", { idclientes: idclientes, idproyectos: idproyectos }, function(result){
			$("#idresponsablesactivo").empty();
			$("#idresponsablesactivo").append(result);	
		});
		
		if(idtipo != "" && idtipo != null && idtipo != undefined){
			//Tipos
			$.get( "controller/combosback.php?oper=subtipos", { idtipo: idtipo, idclientes: idclientes, idproyectos: idproyectos }, function(result){ 
				$("#idsubtipo").empty();
				$("#idsubtipo").append(result); 
			});
		}
		$(".campossubtipos").empty();
	});
	//AMBIENTES - SUBAMBIENTES 
	$('#idambientesactivo').on('select2:select',function(){
		var idambiente = $("#idambientesactivo option:selected").val();
		$.get( "controller/combosback.php?oper=subambientes", { id: idambiente }, function(result){ 
			$("#idsubambientesactivo").empty();
			$("#idsubambientesactivo").append(result); 
		});
	});
	//AMBIENTES - SUBAMBIENTES 
	$('#ambientenuevo').on('select2:select',function(){
		var idambiente = $("#ambientenuevo option:selected").val();
		$.get( "controller/combosback.php?oper=subambientes", { id: idambiente }, function(result){ 
			$("#subambientenuevo").empty();
			$("#subambientenuevo").append(result); 
		});
	});
	//MARCAS
	$.get("controller/combosback.php?oper=marcas", { tipo: "maestro" }, function(result){
		$("#idmarcasactivo").empty();
		$("#idmarcasactivo").append(result);	
	});
	//MARCAS NUEVO
	$('#idmarcasactivo').on('select2:select',function(){
		//MODELOS
		var idmarcas = $("#idmarcasactivo option:selected").val();
		$.get( "controller/combosback.php?oper=modelos", { idmarcas: idmarcas }, function(result){ 
			$("#idmodelosactivo").empty();
			$("#idmodelosactivo").append(result); 
		});
	});
	//RESPONSABLES
	$.get("controller/combosback.php?oper=responsablesactivos", { onlydata:"true" }, function(result){
		$("#idresponsablesactivo").empty();
		$("#idresponsablesactivo").append(result);	
	});
	
	//Tipos
	$.get( "controller/combosback.php?oper=tipos", function(result){ 
		$("#idtipo").empty();
		$("#idtipo").append(result); 
	});
	
	$('#idtipo').on('select2:select',function(){
		var idclientes  = $("#idclientes option:selected").val();
		var idproyectos = $("#idproyectos option:selected").val();		
		//Subtipos
		var idtipo = $("#idtipo option:selected").val();
		$.get( "controller/combosback.php?oper=subtipos", { idtipo: idtipo, idclientes: idclientes, idproyectos: idproyectos }, function(result){ 
			$("#idsubtipo").empty();
			$("#idsubtipo").append(result); 
		});
		$(".campossubtipos").empty();					   
	});		
	function validateDecimal(valor) {
		var pattern = /[^0-9\.{1}\,{1}]/g;
		return valor.replace(pattern, '');
	}
	$('#idsubtipo').on('select2:select',function(){
		 
		var idsubtipo = $("#idsubtipo option:selected").val();
		let campos = "";
		$.ajax({
			url: "controller/subtiposback.php",
			type:"POST",
			data: { oper:"getCampos", idsubtipo: idsubtipo },
			dataType:"json",
			success: function(response){
				campos += "<label class='text-label text-success font-w600'>Datos del subtipo</label><div class='form-row'>";
				$.map(response, function (item) {
					let id = item.id;
					let nombre = item.nombre;
					let tipo = item.tipo;
					let opciones = item.opciones;
					let arrayopc = opciones.split(",");
					let longitud = arrayopc.length; 
					let tipocampo = "";
					
					if(tipo == 'Texto'){
						tipocampo = '<input type="text" id="valor_'+id+'" class="form-control campovalor" autocomplete="off">';
					}
					if(tipo == 'Numérico Entero'){
						tipocampo = '<input type="number" id="valor_'+id+'" class="form-control campovalor" autocomplete="off">';
					}
					if(tipo == 'Numérico Decimal'){
						tipocampo = '<input type="text" id="valor_'+id+'" step="any" class="form-control campovalor campodecimal" autocomplete="off">';
					}
					if(tipo == 'Selector'){
						let i;
						let options = "";
						for (i = 0; i < arrayopc.length; i++) {
							options += '<option value="'+arrayopc[i]+'">'+arrayopc[i]+'</option>';
						}
						tipocampo += '<select id="valor_'+id+'" class="form-control selectdin campovalor" style="width:93%">';
						tipocampo += options;
						tipocampo += '</select>';
					} 
					campos += '<div class="col-xs-12 col-sm-6 col-md-4"><div class="form-group label-floating is-empty"><label class="control-label">'+nombre+'</label>'+tipocampo+'</div></div>';
				});
				campos += "</div>"; 
				let longcapa = $(".campossubtipos").length; 
				$(".campossubtipos").empty();  
				$(".campossubtipos").append(campos);  
				$(".selectdin").select2({placeholder:''}); 
				$(".campodecimal").keyup(function(){
					$(this).val(validateDecimal($(this).val()));	
				});
			}
		});
	});
	//LIMPIAR FILTROS 
	$('#limpiarFiltros').click(function(){
		tbactivos.state.clear();
		window.location.reload();
	});
	
    $('#idestadosactivo').select2({placeholder:''});
    
	var e = $.Event( "keypress", { which: 13 } );
	$('#select-table').on( 'change', function () {
        $('th#cestado input').val( $('th#cestado select').val()).trigger(e);
    });
	 
	if(id != "" && id != undefined && id != null){
		getActivo(id);
	$('.tipo').html('Editar activo');
	}else{
    $('.tipo').html('Nuevo activo');
	}
	$('a[href="#boxcom"]').click(function(){
		$.ajax({
			type: 'post',
			url: 'controller/activosback.php',
			data: { 
				'oper'		 : 'comentariosleidos', 
				'idactivos'  : idactivos,
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
			},
			success: function (response) {
				$('#overlay').css('display','none'); 
			},
			error: function () {
				$('#overlay').css('display','none');
			}
		});
	});
	//$("#tbactivos tbody").on('dblclick','tr',function(){
	function getActivo(idactivos){ 
		$("#idactivos").val(idactivos);
		
		$('.navcom').css('display','block'); 
		$('.navser').css('display','block'); 
		$('.navtra').css('display','block'); 
		$('.navfs').css('display','block'); 
		$('.navcorr').css('display','block'); 
		$('.navprev').css('display','block'); 
		$('.navadj').css('display','block'); 
		if(nivel == 5){
			$('#guardar-activo').hide();
		}
	
		jQuery.ajax({
           url: "controller/activosback.php?oper=getactivo&idactivos="+idactivos,
           dataType: "json",
           beforeSend: function(){
               $('#overlay').css('display','block');
           },success: function(item) {
				$('#overlay').css('display','none');
				/* if(idactivos == ''){
					$('.navact, .navcom, .navser, .navtra').css('display','none');
				}else{
					$('.navact, .navcom, .navser, .navtra').css('display','block');
				} */ 
				$("#seractivos").val(item.serie);							
				$('#nombreactivos').val(item.nombre);
				$('#actactivos').val(item.activo);
				$('#vidautil').val(item.vidautil);
				$('#ingresos').val(item.ingresos);
				/*$("#ingresos").on({
				  "change": function(event) {
					$(event.target).val(function(index, value) {
					  return value.replace(/\D/g, "")
						.replace(/([0-9])([0-9]{2})$/, '$1.$2')
						.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
					});
				  }
				}); */
				/* if(item.ingresos !=""){
					let ing = ingresos;
					let ingmoneda = ing.replace(/\D/g, "")
						.replace(/([0-9])([0-9]{2})$/, '$1.$2')
						.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
					$('#ingresos').val(ingmoneda);
				} */
				if(item.fechainst != "" && item.vidautil != ""){
					
					let hoy = new Date();
					let finstal = new Date(item.fechainst);
					let mesesuso = monthDiff(finstal,hoy);
					
					if(item.vidautil >= mesesuso){
						vidautilreal = item.vidautil - mesesuso;
					}else{
						vidautilreal = 0;
					}
					
					$('#vidautilreal').val(vidautilreal);
				}
				//TAB ACTIVO
				$("#idempresas").val(item.idempresas).trigger('change');
				//EMPRESAS / CLIENTES
				$.when( $('#idempresas').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//CLIENTES
					$.get( "controller/combosback.php?oper=clientes", { idempresas: item.idempresas }, function(result){ 
						$("#idclientes").empty();
						$("#idclientes").append(result); 
						$("#idclientes").val(item.idclientes).trigger("change");
						//CLIENTES / PROYECTOS
						$.when( $('#idclientes').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
							var idclientes = $("#idclientes option:selected").val();						
							//PROYECTOS
							$.get( "controller/combosback.php?oper=proyectos", { idclientes: item.idclientes }, function(result){ 
								$("#idproyectos").empty();
								$("#idproyectos").append(result); 
								$("#idproyectos").val(item.idproyectos).trigger("change"); 
							});
							//AMBIENTES - TRASLADO
							$.get("controller/combosback.php?oper=ambientes", { idempresas: item.idempresas, idclientes: item.idclientes }, function(result){
								$("#idambientesactivo, #ambienteactual, #ambientenuevo").empty();			
								$("#idambientesactivo, #ambienteactual, #ambientenuevo").append(result); 
								$("#idambientesactivo, #ambienteactual").val(item.idambientes).trigger('change');
								//SUBAMBIENTES 								
								$.when( $('#idambientesactivo').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
									var idambiente = $("#idambientesactivo option:selected").val();
									$.get( "controller/combosback.php?oper=subambientes", { id: item.idambientes }, function(result){ 
										$("#idsubambientesactivo, #subambienteactual").empty();
										$("#idsubambientesactivo, #subambienteactual").append(result); 
										$("#idsubambientesactivo, #subambienteactual").val(item.idsubambientes).trigger("change"); 
									});
								});
								//TRASLADAR
								$.when( $('#ambienteactual').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
									$.get("controller/combosback.php?oper=ambientesclientes", { idempresas: idempresas, idclientes: idclientes, ambienteactual: item.idambientes }, function(result){
										$("#ambientenuevo").empty(); 
										$("#ambientenuevo").append(result);	
									});
								});
							});
						});
					});
				});
				
				$("#idmarcasactivo").val(item.idmarcas).trigger('change');
				$.when( $('#idmarcasactivo').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//MODELOS
					$.get( "controller/combosback.php?oper=modelos", { idmarcas: item.idmarcas }, function(result){ 
						$("#idmodelosactivo").empty();
						$("#idmodelosactivo").append(result); 
						$("#idmodelosactivo").val(item.idmodelos).trigger('change');
					});
				});
	 
		       	$('#idresponsablesactivo').val(item.idresponsables).trigger('change');				
				$("#modactivos").val(item.modalidad);
				$("#edifactivos").val(item.edificio); 
				$("#faseactivos").val(item.fase); 
				$("#comactivos").val(item.comentarios); 
				$("#fectopactivos").val(item.fechatopemant); 
				$("#cotactivos").val(item.cotizacion);
				$("#mescotactivos").val(item.mesescotizar);
				$("#cotmenactivos").val(item.cotizacionmenos); 
				$("#fecinstactivos").val(item.fechainst); 
				$("#idestadosactivo").val(item.estado).trigger('change');
				
				$("#idtipo").val(item.idtipo).trigger('change'); 
				$.when( $('#idtipo').triggerHandler('change') ).then(function( data, textStatus, jqXHR ) {
					//Subtipos
					$.get( "controller/combosback.php?oper=subtipos", { idtipo: item.idtipo, idclientes: item.idclientes, idproyectos: item.idproyectos }, function(result){ 
						$("#idsubtipo").empty();
						$("#idsubtipo").append(result); 
						$("#idsubtipo").val(item.idsubtipo).trigger('change');
					});
				});
				
				if(item.campossubtipos != "" && item.idsubtipo != "" && item.idsubtipo != "0" && item.idsubtipo != "Sin Asignar"){
					let campos = "";
					$.ajax({
						url: "controller/subtiposback.php",
						type:"POST",
						data: { oper:"getCampos", idsubtipo: item.idsubtipo },
						dataType:"json",
						success: function(response){
							campos += "<label class='text-label text-success font-w600'>Datos del subtipo</label><div class='form-row'>";
							$.map(response, function (item) {
								let id = item.id;
								let nombre = item.nombre;
								let tipo = item.tipo;
								let opciones = item.opciones;
								let arrayopc = opciones.split(",");
								let longitud = arrayopc.length; 
								let tipocampo = "";
								console.log("TIPO ES:"+tipocampo);
								if(tipo == 'Texto'){
									tipocampo = '<input type="text" id="valor_'+id+'" class="form-control campovalor" autocomplete="off">';
								}
								if(tipo == 'Numérico Entero'){
									tipocampo = '<input type="number" id="valor_'+id+'" class="form-control campovalor" autocomplete="off">';
								}
								if(tipo == 'Numérico Decimal'){
									tipocampo = '<input type="text" id="valor_'+id+'" step="any" class="form-control campovalor campodecimal" autocomplete="off">';
								}
								if(tipo == 'Selector'){
									let i;
									let options = "";
									for (i = 0; i < arrayopc.length; i++) {
										options += '<option value="'+arrayopc[i]+'">'+arrayopc[i]+'</option>';
									}
									tipocampo += '<select id="valor_'+id+'" class="form-control selectdin campovalor" style="width:93%">';
									tipocampo += options;
									tipocampo += '</select>';
								} 
								campos += '<div class="col-xs-12 col-sm-6 col-md-4"><div class="form-group label-floating is-empty"><label class="control-label">'+nombre+'</label>'+tipocampo+'</div></div>';
							});
							campos += "</div>"; 
							let longcapa = $(".campossubtipos").length; 
							$(".campossubtipos").empty();  
							$(".campossubtipos").append(campos);  
							$(".selectdin").select2({placeholder:''});
							$(".campodecimal").keyup(function(){
								$(this).val(validateDecimal($(this).val()));	
							}); 
							$.map(JSON.parse(item.campossubtipos), function (itemcamp,index) { 
								let tipocampo = $("#"+index).attr("type");
								if(tipocampo != "text"){
									$("#"+index).val(itemcamp).trigger("change");
								}else{
									$("#"+index).val(itemcamp);
								}  
							});  
						}
					});
				}
				//TAB TRASLADAR
				$("#idactivotraslado").val(idactivos);
				abrirTraslados(idactivos);
				abrirComentarios(idactivos);
				abrirSeriales(idactivos);
				abrirFueraServicio(item.serie);
				abrirCorrectivos(idactivos);
				abrirPreventivos(idactivos);
           }
        }); 
	}
	//});
	
	$('#tablacomentario').on('processing.dt', function (e, settings, processing) {
		$('#preloader').css( 'display', processing ? 'block' : 'none' );
	})
	
	//COMENTARIOS
	function abrirComentarios(idactivos){ 
	
		/* $('#tablacomentario thead th').each(function() {
			let title = $(this).text();
			let id = $(this).attr('id');
			let ancho = $(this).width();
			
			if (title !== '' && title !== '-' && title !== 'Acción') {
				if (screen.width > 1024) {
					if(title == "Comentario" || title == "Usuario" || title == "Visibilidad" || title == "Fecha"){
						$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
					}else if(title == "Adjunto"){
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
					}
				} else {
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else if (title == 'Acción') {
				ancho = '100px';
			}
			$(this).width(ancho);
		}); */
		
		tablacomentario = $("#tablacomentario").DataTable({
		//scrollY: '100%',
		//scrollX: true,
		responsive: false,
		scrollCollapse: true,
		destroy: true,
		ordering: false,
		processing: false,
		autoWidth : false,
		searching: false,
			"ajax"		: {
				"url"	: "controller/activosback.php?oper=comentarios&id="+idactivos,
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "acciones" },
				{ 	"data": "comentario" },
				{ 	"data": "nombre" },
				{ 	"data": "visibilidad" },
				{ 	"data": "fecha" },
				{ 	"data": "adjuntos" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					"targets"	: [ 0 ],
					"visible"	: false,
					"searchable": false
				},
				{
					visible		: true,
					targets		: [4]
				} 
			],
		fixedColumns: true,
			"language": {
				"url": "js/Spanish.json"
			}/* ,
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
			},*/
		//	dom: '<"toolbarU toolbarDT">Blfrtip' 
		});
	}	
	
	//SERIALES
	function abrirSeriales(idactivos){
		//Seriales
		/* $('#tablaseriales thead th').each(function() {
			let title = $(this).text();
			let id = $(this).attr('id');
			let ancho = $(this).width();
			
			if (title !== '' && title !== '-' && title !== 'Acción') {
				if (screen.width > 1024) {
					if(title == "Serial anterior" || title == "Serial actual"){
						$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 350px" /> ');
					}else{
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
					}
				} else if( title == "Hasta" || title == "Incidente"){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else if (title == 'Acción') {
				ancho = '100px';
			}
			$(this).width(ancho);
		}); */
	 
		tablaseriales = $("#tablaseriales").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: "controller/activosback.php?oper=serialesbit&id="+idactivos,
			},
			"columns"	: [
				{ 	"data": "serialant" },
				{ 	"data": "serialact" },
				{ 	"data": "fecha" },
				{ 	"data": "dias" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					targets		: [0,1,2,3],
					className	: "text-left"
				}/* ,
				{ targets	: 0, width	: '80px' },
				{ targets	: 1, width	: '80px' },
				{ targets	: 2, width	: '80px' },
				{ targets	: 3, width	: '80px' }  */
			],
			"language": {
				"url": "js/Spanish.json"
			}/* ,
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
			dom: '<"toolbarU toolbarDT">Blfrtip' */
		});
	}
	
	//TRASLADOS
	function abrirTraslados(idactivos){
		
		/* $('#tbtraslados thead th').each(function() {
			let title = $(this).text();
			let id = $(this).attr('id');
			let ancho = $(this).width();
			
			if (title !== '' && title !== '-' && title !== 'Acción') {
				if (screen.width > 1024) {
					if(title == "Ubicación Anterior" || title == "Ubicación Nueva"){
						$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 350px" /> ');
					}else{
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 200px" /> ');
					}
				} else {
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else if (title == 'Acción') {
				ancho = '100px';
			}
			$(this).width(ancho);
		}); */
		
		var tbtraslados = $("#tbtraslados").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	:"controller/activosback.php?oper=traslados&idactivo="+idactivos,
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "idactivos" },
				{ 	"data": "ambienteanterior" },
				{ 	"data": "subambienteanterior" },
				{ 	"data": "ambientenuevo" },
				{ 	"data": "subambientenuevo" },
				{ 	"data": "usuario" },
				{ 	"data": "fecha" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					"targets"	: [ 0,1 ],
					"visible"	: false,
					"searchable": false
				},{
					targets		: [0],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}/* ,
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
			dom: '<"toolbarU toolbarDT">Blfrtip' */
		});
	}
	
	//FUERA DE SERVICIO
	function abrirFueraServicio(idactivos){
		/* $('#tablafueraservicio thead th').each(function() {
			let title = $(this).text();
			let id = $(this).attr('id');
			let ancho = $(this).width();
			
			if (title !== '' && title !== '-' && title !== 'Acción') {
				if (screen.width > 1024) {
					if(title == "Serial"){
						$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 350px" /> ');
					}else{
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 250px" /> ');
					}
				} else if( title == "Hasta" || title == "Incidente"){
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else if (title == 'Acción') {
				ancho = '100px';
			}
			$(this).width(ancho);
		}); */
		
		//FUERA DE SERVICIO
		tablafueraservicio = $("#tablafueraservicio").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: "controller/activosback.php?oper=fueraservicio&id="+idactivos,
			},
			"columns"	: [
				{ 	"data": "serial" },
				{ 	"data": "desde" },
				{ 	"data": "hasta" },
				{ 	"data": "incidente" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					targets		: [0,1,2,3],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}/* ,
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
			dom: '<"toolbarU toolbarDT">Blfrtip' */
		});
	}
	
	//Correctivos
	function abrirCorrectivos(idactivos){
		/* $('#tablacorrectivos thead th').each(function() {
			let title = $(this).text();
			let id = $(this).attr('id');
			let ancho = $(this).width();
			
			if (title !== '' && title !== '-' && title !== 'Acción') {
				if (screen.width > 1024) {
					if(title == "Título" || title == "Estado" || title == "Fecha Creación"){
						$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
					}else{
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
					}
				} else {
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else if (title == 'Acción') {
				ancho = '100px';
			}
			$(this).width(ancho);
		}); */
		
		tablacorrectivos = $("#tablacorrectivos").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: "controller/activosback.php?oper=correctivos&id="+idactivos,
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "titulo" },
				{ 	"data": "estado" },
				{ 	"data": "fechacreacion" },
				{ 	"data": "solicitante" },
				{ 	"data": "asignadoa" },
				{ 	"data": "accion" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					targets		: [0,1,2,3],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}/* ,
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
			dom: '<"toolbarU toolbarDT">Blfrtip' */
		});
		tablacorrectivos.on( 'draw.dt', function () {
			$('.boton-ir-correctivo').each(function(){
				var id = $(this).attr("data-id"); 
				$(this).on( 'click', function() {
					location.href="incidentes.php?id="+id;
				});
			});  
			
			// Tooltip
			$('[data-toggle="tooltip"]').tooltip();	 
			
		});
	}
	
	//Preventivos
	function abrirPreventivos(idactivos){
		
		/* $('#tablapreventivos thead th').each(function() {
			let title = $(this).text();
			let id = $(this).attr('id');
			let ancho = $(this).width();
			
			if (title !== '' && title !== '-' && title !== 'Acción') {
				if (screen.width > 1024) {
					if(title == "Título" || title == "Estado" || title == "Fecha Creación"){
						$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 200px" /> ');
					}else{
						$(this).html('<input type="text" placeholder="' + title + '" id="f' + id + '" style="width: 150px" /> ');
					}
				} else {
					$(this).html('<input type="text" placeholder="' + title + '" id="' + id + '" style="width: 100px" /> ');
				}
			} else if (title == 'Acción') {
				ancho = '100px';
			}
			$(this).width(ancho);
		}); */
		
		tablapreventivos = $("#tablapreventivos").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: "controller/activosback.php?oper=preventivos&id="+idactivos,
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "titulo" },
				{ 	"data": "estado" },
				{ 	"data": "fechacreacion" },
				{ 	"data": "solicitante" },
				{ 	"data": "asignadoa" },
				{ 	"data": "accion" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					targets		: [0,1,2,3],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}/* ,
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
			dom: '<"toolbarU toolbarDT">Blfrtip' */
		});
		tablapreventivos.on( 'draw.dt', function () {
			$('.boton-ir-preventivo').each(function(){
				var id = $(this).attr("data-id"); 
				$(this).on( 'click', function() {
					location.href="preventivos.php?id="+id;
				});
			});  
			
			// Tooltip
			$('[data-toggle="tooltip"]').tooltip();	 
			
		});
	}
	
	//VALIDAR GUARDAR ACTIVOS
	function vactivo(serie,equipo,marca,modelo,/*idsubambientes,*/estado,idempresas,idclientes,idproyectos){
		console.log("estado: "+estado);
		var respuesta = 1;
		if (serie != "" && equipo != ""/*  && marca != "" && modelo != "" */ /*&& idsubambientes != ""*/){
			if (serie.length < 3){
				notification("Error","El campo Serial 1 debe tener una longitud de al menos 3 caracteres",'info');
				respuesta = 0;
			}
			if (equipo.length < 2){
				notification("Error","El campo Nombre debe tener una longitud de al menos 3 caracteres",'info'); 
				respuesta = 0;
			}  
		} //else { 
		    /*if(idsubambientes==""){
		        demo.showSwal('error-message','ERROR','Debe introducir el subambiente');
			    respuesta = 0; 
		    }*/
			if(nivel != 7){
				idempresas = idempresas;
			}else{
				idempresas = 1;
			}
		   /*  if(idempresas=="" || idempresas == 0 || idempresas == null){
		        demo.showSwal('error-message','ERROR','Debe introducir la Empresa');
			    respuesta = 0;    
		    }else  */if(idclientes=="" || idclientes == 0 || idclientes == null){ 
				notification("Error","Debe introducir el Cliente",'warning'); 
			    respuesta = 0;    
		    }else if(estado=="" || estado == 0 || estado == null){
				notification("Error","Debe introducir el Estado",'warning');  
			    respuesta = 0;    
		    }else if(marca=="" || marca == 0 || marca == null){
				notification("Error","Debe introducir la Marca",'warning');  
			    respuesta = 0; 
		    }else if(modelo=="" || modelo == 0 || modelo == null){
				notification("Error","Debe introducir el Modelo",'warning');   
			    respuesta = 0; 
		    }else if(equipo==""){
				notification("Error","Debe introducir el Nombre",'warning');    
			    respuesta = 0; 
		    }else if(serie==""){
		        notification("Error","Debe introducir el Serial 1",'warning');
			    respuesta = 0;    
		    }  
		//}
		return respuesta;
	}
	
	const saveactivo = ()=> { 
		var id				=  $("#idactivos").val();
		var serie 		    =  $("#seractivos").val();
		var nombre		    =  $("#nombreactivos").val();
   		var idmarcas 		=  $("#idmarcasactivo").val();
       	var idmodelos 		=  $("#idmodelosactivo").val(); 
       	var activo  		=  $("#actactivos").val(); 
       	var idresponsables 	=  $("#idresponsablesactivo option:selected").val();
		var idambientes 	=  $("#idambientesactivo").val(); 
		var idambientesant 	=  $("#ambienteactual").val();
		var modalidad 		=  $("#modactivos").val();
		var idsubambientes 	=  $("#idsubambientesactivo").val();  
		var edificio  		=  $("#edifactivos").val(); 
		var fase 		    =  $("#faseactivos").val(); 
		var comentarios     =  $("#comactivos").val(); 
		var fechatopemant   =  $("#fectopactivos").val(); 
		var cotizacion 	    =  $("#cotactivos").val();
		var mesescotizar    =  $("#mescotactivos").val();
		var cotizacionmenos =  $("#cotmenactivos").val();
		var fechainst 		=  $("#fecinstactivos").val(); 
		var estado 		    =  $("#idestadosactivo").val();  
		var idempresas 	 	=  $("#idempresas").val();  
		var idclientes 		=  $("#idclientes").val();  
		var idproyectos     =  $("#idproyectos").val();  
		var idtipo     		=  $("#idtipo").val();
		var idsubtipo       =  $("#idsubtipo").val();
		var vidautil        =  $("#vidautil").val();
		var ingresos        =  $("#ingresos").val();
		let contenidolimpio = []; 
		let newObjeto		= {}			 
		let ingresosfinal = ingresos.replace(/,/g, '');				 
		//let ingresosfinal = Number.parseFloat(ingresos).toFixed(2);
		//Guardar campos del subtipo
		$(".campossubtipos .campovalor").each(function(){
			var idcampo  = $(this).attr('id');
			
			var valcampo = $("#"+idcampo).val();
			contenidolimpio.push({"id":idcampo,"valor":valcampo})
			function reducer(acc,cur){
                return {...acc,[cur.id]:"v"}
            }
            newObjeto=contenidolimpio.reduce(reducer,{})
            console.log(contenidolimpio)
            console.log(newObjeto)
             
		});
		if(id==''){
			oper = 'createactivo';
			var mensaje = 'Activo agregado satisfactoriamente';
		}else{
			oper = 'updateactivo';
			var mensaje = 'Activo actualizado satisfactoriamente';
		}
		if (vactivo(serie,nombre,idmarcas,idmodelos,estado,idempresas,idclientes,idproyectos) == 1){
			
			//Si cambia de ubicación verificar si tiene mantenimientos pendientes
			if(idambientes != idambientesant && idambientes != "" && idambientesant != "" && oper != 'createactivo'){
				$.get( "controller/activosback.php?oper=existeMttosFuturos", { idactivos: id }, function(result){
					if(result == 1){
						//Si tiene mantenimientos pendientes
						swal({
							title: "Confirmar",
							text: "¿El activo tiene mantenimientos próximos, desea cambiar la ubicación?",
							type: "warning",
							showCancelButton: true,
							cancelButtonColor: 'red',
							confirmButtonColor: '#09b354',
							confirmButtonText: 'Si',
							cancelButtonText: "No"
						}).then(
							function(isConfirm){
								if (isConfirm){
									//Si el usuario desea cambiar la ubicación
									 $.ajax({
										type: 'post',
										url: 'controller/activosback.php',
										data: { 
											'oper'		       	: oper, 
											'id'               	: id,
											'serie' 	   		: serie,
											'nombre'  	       	: nombre,
											'idmarcas'	        : idmarcas,
											'idmodelos'			: idmodelos,
											'activo'           	: activo,
											'idresponsables'	: idresponsables,
											'idambientes'		: idambientes,
											'modalidad'			: modalidad,
											'idsubambientes'	: idsubambientes, 
											'estado'			: estado,
											'edificio' 	       	: edificio,
											'fase'             	: fase, 
											'comentarios'      	: comentarios, 
											'fechatopemant'    	: fechatopemant, 
											'cotizacion'       	: cotizacion,
											'mesescotizar'     	: mesescotizar,
											'cotizacionmenos'  	: cotizacionmenos,
											'fechainst'        	: fechainst,
											'idempresas'       	: idempresas,
											'idclientes'      	: idclientes,
											'idproyectos'      	: idproyectos,
											'idtipo'      		: idtipo,
											'idsubtipo'      	: idsubtipo, 
											'vidautil'      	: vidautil, 
											'ingresos'      	: ingresosfinal, 
											'contenido'      	: JSON.stringify(contenidolimpio),
											'contenidolimpio'   : JSON.stringify(newObjeto) 
										},
										beforeSend: function() {
											$('#overlay').css('display','block');
										},
										success: function (response) { 
											$('#overlay').css('display','none');
											if(response==1){
												vaciaractivos();
												if(oper=="createactivo"){
													notification('','Buen trabajo!','success');
													swal({		
																title: mensaje,	
																text: "¿Desea registrar otro Activo?",
																type: "success",
																allowEscapeKey : false,
																allowOutsideClick: false,
																showCancelButton: true,
																cancelButtonColor: 'red',
																confirmButtonColor: '#09b354',
																confirmButtonText: 'Sí',
																cancelButtonText: "No"
														}).then(function(isConfirm) {
															if (isConfirm.value === true) {
																document.getElementById('idclientes').focus();
															}else{
																location.href = "activos.php";
															}
														});
												}else{
													notification("¡Exito!",mensaje,"success"); 
													location.href="activos.php";
												} 
											}else if(response==2){
												notification("Error","Ya existe un activo con este número de serie",'error');
											}else{
												notification("Error","Error al guardar",'error'); 
											}
										},
										error: function () {
											$('#overlay').css('display','none'); 
											notification("Error","Error al guardar",'error');
										}
									});
								}
							}, function (isRechazo){
								// NADA
							}
						);
					}else{
						//Si no tiene mantenimientos pendientes
						$.ajax({
							type: 'post',
							url: 'controller/activosback.php',
							data: { 
								'oper'		       	: oper, 
								'id'               	: id,
								'serie' 	   		: serie,
								'nombre'  	       	: nombre,
								'idmarcas'	        : idmarcas,
								'idmodelos'			: idmodelos,
								'activo'           	: activo,
								'idresponsables'	: idresponsables,
								'idambientes'		: idambientes,
								'modalidad'			: modalidad,
								'idsubambientes'	: idsubambientes, 
								'estado'			: estado,
								'edificio' 	       	: edificio,
								'fase'             	: fase, 
								'comentarios'      	: comentarios, 
								'fechatopemant'    	: fechatopemant, 
								'cotizacion'       	: cotizacion,
								'mesescotizar'     	: mesescotizar,
								'cotizacionmenos'  	: cotizacionmenos,
								'fechainst'        	: fechainst,
								'idempresas'       	: idempresas,
								'idclientes'      	: idclientes,
								'idproyectos'      	: idproyectos,
								'idtipo'      		: idtipo,
								'idsubtipo'      	: idsubtipo, 
								'vidautil'      	: vidautil, 
								'ingresos'      	: ingresosfinal, 
								'contenido'      	: JSON.stringify(contenidolimpio),
								'contenidolimpio'   : JSON.stringify(newObjeto) 
							},
							beforeSend: function() {
								$('#overlay').css('display','block');
							},
							success: function (response) { 
								$('#overlay').css('display','none');
								if(response==1){ 
									vaciaractivos();
									if(oper=="createactivo"){
										notification('','Buen trabajo!','success');
										swal({		
													title: mensaje,	
													text: "¿Desea registrar otro Activo?",
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
													document.getElementById('idclientes').focus();
												}else{
													location.href = "activos.php";
												}
											});
									}else{
										notification("¡Exito!",mensaje,"success"); 
										location.href="activos.php";
									}
								}else if(response==2){
									notification("Error","Ya existe un activo con este número de serie",'error');
								}else{
									notification("Error","Error al guardar",'error'); 
								}
							},
							error: function () {
								$('#overlay').css('display','none');
								notification("Error","Error al guardar",'error'); 
							}
						});
					}
			
				});
			}else{
				//Si no cambia de ubicación
				 $.ajax({
					type: 'post',
					url: 'controller/activosback.php',
					data: { 
						'oper'		       	: oper, 
						'id'               	: id,
						'serie' 	   		: serie,
						'nombre'  	       	: nombre,
						'idmarcas'	        : idmarcas,
						'idmodelos'			: idmodelos,
						'activo'           	: activo,
						'idresponsables'	: idresponsables,
						'idambientes'		: idambientes,
						'modalidad'			: modalidad,
						'idsubambientes'	: idsubambientes, 
						'estado'			: estado,
						'edificio' 	       	: edificio,
						'fase'             	: fase, 
						'comentarios'      	: comentarios, 
						'fechatopemant'    	: fechatopemant, 
						'cotizacion'       	: cotizacion,
						'mesescotizar'     	: mesescotizar,
						'cotizacionmenos'  	: cotizacionmenos,
						'fechainst'        	: fechainst,
						'idempresas'       	: idempresas,
						'idclientes'      	: idclientes,
						'idproyectos'      	: idproyectos,
						'idtipo'      		: idtipo,
						'idsubtipo'      	: idsubtipo, 
						'vidautil'      	: vidautil, 
						'ingresos'      	: ingresosfinal, 
						'contenido'      	: JSON.stringify(contenidolimpio),
						'contenidolimpio'   : JSON.stringify(newObjeto) 
					},
					beforeSend: function() {
						$('#overlay').css('display','block');
					},
					success: function (response) { 
						$('#overlay').css('display','none');
						if(response==1){ 
							vaciaractivos();
							if(oper=="createactivo"){
								notification('','Buen trabajo!','success');
								swal({		
											title: mensaje,	
											text: "¿Desea registrar otro Activo?",
											type: "success",
											allowEscapeKey : false,
											allowOutsideClick: false,
											showCancelButton: true,
											cancelButtonColor: 'red',
											confirmButtonColor: '#09b354',
											confirmButtonText: 'Sí',
											cancelButtonText: "No"
									}).then(function(isConfirm) {
										if (isConfirm.value === true) {
											document.getElementById('idclientes').focus();
										}else{
											location.href = "activos.php";
										}
									});
							}else{
								notification("¡Exito!",mensaje,"success"); 
								location.href="activos.php";
							}							
						}else if(response==2){
							notification("Error","Ya existe un activo con este número de serie",'error');
						}else{
							notification("Error","Error al guardar",'error'); 
						}
					},
					error: function () {
						$('#overlay').css('display','none');
						notification("Error","Error al guardar",'error'); 
					}
				});
			}
		} 
	} 
	  
	  
	  
    function vaciartrasladar(){
		$("#idactivotraslado").val("");    
		$("#ambienteactual").val("");
		$("#ambientenuevo").val("");
	} 
	
	function vaciaractivos(){
		$("#idactivos").val(""); 
		$("#seractivos").val("");
		$("#idambientesactivo").val(null).trigger("change");
		$("#modactivos").val("");		
		$("#nombreactivos").val("");
		$("#idmarcasactivo").val(null).trigger("change");
		$("#idmodelosactivo").val(null).trigger("change");
		$("#actactivos").val(""); 
		$("#idresponsablesactivo").val(null).trigger("change");  
		$("#idsubambientesactivo").val(null).trigger("change");  
		$("#edifactivos").val("");  
		$("#faseactivos").val("");
		$("#comactivos").val("");
		$("#fectopactivos").val(""); 
		$("#cotactivos").val("");
		$("#mescotactivos").val("");
		$("#cotmenactivos").val(""); 
		$("#fecinstactivos").val("");
		$("#idestadosactivo").val("ACTIVO").trigger("change");
		$("#idempresas").val(null).trigger("change"); 
		$("#idclientes").val(null).trigger("change"); 
		$("#idproyectos").val(null).trigger("change"); 
		$("#idtipo").val(null).trigger("change"); 
		$("#idsubtipo").val(null).trigger("change");
		$("#vidautil").val("");
		$("#vidautilreal").val("");
		$("#ingresos").val("");
		$(".campossubtipos").empty(); 		
	}
	
    //Botones de Modales
    $("#guardar-activo").on("click",function(){
		saveactivo();
	});
	
	$("#trasladar-activo").on("click",function(){
		trasladaractivo();
	});
	
	$(".navadj").on("click",function(){
		var id = $("#idactivos").val();
		$('#fevidenciasmodal').attr('src','filegator/activos.php#/?cd=activos/'+id); 
	});   
	
	$('#tablacomentario').on( 'draw.dt', function () {	
		// DAR FUNCIONALIDAD AL BOTON ELIMINAR COMENTARIOS
		$('.boton-eliminar-comentarios').each(function(){
			var id = $(this).attr("data-id"); 
			$(this).on( 'click', function() {
				eliminarcomentario(id);
			});
		});
		// DAR FUNCIONALIDAD AL BOTON EVIDENCIAS
		$('.boton-adjuntos-comentarios').each(function(){
			var id = $(this).attr("data-id");
			$(this).on( 'click', function() {
				adjuntosComentarios(id);
			});
		});
	});
	
	//Eliminar comentario
	function eliminarcomentario(id){
		var idactivos  = $("#idactivos").val();											 
		var idcomentario = id; 
		
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el comentario?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm.value === true) {
					$.get( "controller/activosback.php?oper=eliminarcomentarios", 
					{  
						idcomentario : idcomentario,
						idactivos  : idactivos 
					}, function(result){
						if(result == 1){
							notification("¡Exito!",'Comentario eliminado satisfactoriamente',"success");  
							tablacomentario.ajax.reload(null, false);
						} else {
							notification("¡Error!",'Ha ocurrido un error al eliminar el comentario, intente más tarde',"error");  
						}
					});
				}
			}, function (isRechazo){  
			}
		);
	}
	
	//***** ***** ***** ADJUNTO COMENTARIOS ***** ***** ***** // 
    $('#modalEvidenciasCom').on('hidden.bs.modal', function(){
        console.log('paso')
            tablacomentario.ajax.reload(null, false);
    });
    var dirxdefecto = 'activos'; 
$('#fevidenciascom').attr('src','filegator/activoscom.php#/?cd=%2F'+dirxdefecto);
	//Adjuntos de comentarios
	function adjuntosComentarios(idactivos) {
		var arr = idactivos.split('-');
		var incidente = arr[0];
		var comentario = arr[1];		
		var valid = true;
		
		if ( valid ) {
			$.ajax({
				  type: 'post',
				  url: 'controller/activosback.php',
				  data: { 
					'oper'		: 'adjuntosComentarios',
					'idactivos' : idactivos
				  },
				  success: function (response) {
					$('#fevidenciascom').attr('src','filegator/activoscom.php#/?cd=activos/'+incidente+'/comentarios/'+comentario);
					$('#modalEvidenciasCom').modal('show');
					$('#modalEvidenciasCom .modal-lg').css('width','1000px');
					$('#idincidentesevidenciascom').val(incidente);
					$('#idcomentariosevidencias').val(comentario);
					$('.titulo-evidencia').html('Activo: '+id+' - Evidencia comentario');
					tablacomentario.ajax.reload(null, false); 
				  },
				  error: function () { 
					notification("¡Error!",response,"error");  
				  }
			   }); 
			}
		return valid;
	} 
	
	function vtrasladar(idubicacionanterior,idubicacionnueva){
		 
		var respuesta = 1; 
		if(idubicacionanterior=="" || idubicacionanterior == 0 || idubicacionanterior == null || idubicacionanterior == undefined){ 
			notification("Error","Debe seleccionar la ubicación actual",'warning'); 
			respuesta = 0;    
		}else if(idubicacionnueva=="" || idubicacionnueva == 0 || idubicacionnueva == null || idubicacionnueva == undefined){
			notification("Error","Debe seleccionar la ubicación nueva",'warning'); 
			respuesta = 0;    
		}   
		return respuesta;
	}
	
	function trasladaractivo(){
		var idactivos		= $("#idactivotraslado").val();    
		var ambienteactual  = $("#ambienteactual").val();
		var subambienteactual  = $("#subambienteactual").val();
		var ambientenuevo   = $("#ambientenuevo").val();
		var subambientenuevo   = $("#subambientenuevo").val();
		
		//Verifico si existen mantenimientos preventivos futuros
		$.get( "controller/activosback.php?oper=existeMttosFuturos", { idactivos: idactivos }, function(result){
			if(result == 1){
				swal({
					title: "Confirmar",
					text: "¿El activo tiene mantenimientos próximos, desea cambiar la ubicación?",
					type: "warning",
					showCancelButton: true,
					cancelButtonColor: 'red',
					confirmButtonColor: '#09b354',
					confirmButtonText: 'Si',
					cancelButtonText: "No"
				}).then(
					function(isConfirm){
						if (isConfirm){
							if (vtrasladar(ambienteactual,ambientenuevo) == 1){
								$.ajax({
									type: 'post',
									url: 'controller/activosback.php',
									data: { 
										'oper'           : 'trasladaractivo', 
										'idactivos'  	   : idactivos, 
										'ambienteanterior' : ambienteactual, 
										'subambienteanterior' : subambienteactual, 
										'ambientenuevo'    : ambientenuevo,
										'subambientenuevo'    : subambientenuevo
									},
									beforeSend: function() {
										$('#overlay').css('display','block');
									},
									success: function (response) {
										if(response == 1){
											$('#overlay').css('display','none');
											$("#idambientesactivo, #ambienteactual").val(ambientenuevo).trigger('change');
											//AMBIENTES - SUBAMBIENTES 
                                        	var idambiente = $("#ambientenuevo option:selected").val();
                                    		$.get( "controller/combosback.php?oper=subambientes", { id: idambiente }, function(result){ 
                                    			$("#idsubambientesactivo, #subambienteactual").empty();
                                    			$("#idsubambientesactivo, #subambienteactual").append(result); 
                                    			$("#idsubambientesactivo, #subambienteactual").val(subambientenuevo).trigger('change');
                                    		});
											$("#ambientenuevo, #subambientenuevo").val('0').trigger('change');
											notification("¡Exito!",'Activo actualizado satisfactoriamente',"success");  
											abrirTraslados(idactivos);
											//tbtraslados.ajax.reload(null, false);   
										}else{ 
											notification("Error","Ocurrió un error al actualizar el activo",'error'); 
										} 						
									},
									error: function () {
										$('#overlay').css('display','none'); 
										notification("Error","Ha ocurrido un error al actualizar el activo, intente más tarde",'error'); 
									}
								});
							} 
							return;
						}
					}, function (isRechazo){
						// NADA
					}
				);
			}else{
				if (vtrasladar(ambienteactual,ambientenuevo) == 1){
					$.ajax({
						type: 'post',
						url: 'controller/activosback.php',
						data: { 
							'oper'           : 'trasladaractivo', 
							'idactivos'  	   : idactivos, 
							'ambienteanterior' : ambienteactual, 
							'subambienteanterior' : subambienteactual, 
							'ambientenuevo'    : ambientenuevo,
							'subambientenuevo'    : subambientenuevo
						},
						beforeSend: function() {
							$('#overlay').css('display','block');
						},
						success: function (response) {
							if(response == 1){
								$('#overlay').css('display','none');
								$("#idambientesactivo, #ambienteactual").val(ambientenuevo).trigger('change');
								//AMBIENTES - SUBAMBIENTES 
                            	var idambiente = $("#ambientenuevo option:selected").val();
                        		$.get( "controller/combosback.php?oper=subambientes", { id: idambiente }, function(result){ 
                        			$("#idsubambientesactivo, #subambienteactual").empty();
                        			$("#idsubambientesactivo, #subambienteactual").append(result); 
                        			$("#idsubambientesactivo, #subambienteactual").val(subambientenuevo).trigger('change');
                        		});
								$("#ambientenuevo, #subambientenuevo").val('0').trigger('change');
								notification("¡Exito!",'Activo actualizado satisfactoriamente',"success");
								abrirTraslados(idactivos);
								//tbtraslados.ajax.reload(null, false);   
							}else{ 
								notification("Error","Ocurrió un error al actualizar el activo",'error');
							} 						
						},
						error: function () {
							$('#overlay').css('display','none'); 
							notification("Error","Ha ocurrido un error al actualizar el activo, intente más tarde",'error');
						}
					});
				}
			}
			
		});	 
	}
	//Calculo de vida útil
	$( "#vidautil" ).keyup(function() {
		let vidautil = $(this).val();
		let fechinst = $("#fecinstactivos").val();
		
		if(fechinst != "" && vidautil != ""){
			let hoy = new Date();
			let finstal = new Date(fechinst);
			let mesesuso = monthDiff(finstal,hoy);
			
			if(vidautil >= mesesuso){
				vidautilreal = vidautil - mesesuso;
			}else{
				vidautilreal = 0;
			} 
			$('#vidautilreal').val(vidautilreal);
		}
	});
	
	$('#fecinstactivos').on('change', function(e){ 
		let fechinst = $(this).val();
		let vidautil = $("#vidautil").val();
		
		if(fechinst != "" && vidautil != ""){
			let hoy = new Date();
			let finstal = new Date(fechinst);
			let mesesuso = monthDiff(finstal,hoy);
			
			if(vidautil >= mesesuso){
				vidautilreal = vidautil - mesesuso;
			}else{
				vidautilreal = 0;
			} 
			$('#vidautilreal').val(vidautilreal);
		}
	});
});
	function limpiarComentario(){
		$('#comentario').val('');
	}
	//Agregar comentario
	var form,
		comentario = $( "#comentario" ),
		allFields = $( [] ).add( comentario ),
		tips = $( ".validateTips" );
	function agregarComentario() {
	    console.log("funciono jesus");
		var coment  = $('#comentario').val();		
		var visibilidad  = $('input[name=visibilidad]:checked').val();
		
		/* if(coment==''){
			$('#comentario').addClass('form-valide-error-bottom');
			return;
		} */
		if(visibilidad == undefined ){
			visibilidad  = 'Público';		
		}else if(visibilidad == ''){
			$('input[name=visibilidad]').addClass('form-valide-error-bottom');
			return;
		}
		var idactivoselect = $("#idactivos").val();
		if (coment != '') {
			$.ajax({
				type: 'post',
				url: 'controller/activosback.php',
				data: { 
					'oper'	: 'agregarComentario',
					'id' : idactivoselect,
					'coment' : coment,
					'visibilidad' : visibilidad
				},
				beforeSend: function() {
					$('#overlay').css('display','block');
					$('#dialog-form-coment').hide();
				},
				success: function (response) {
					$('#overlay').css('display','none');
					if(response){					
						$('#comentario').val("");					
						if ( $('.boton-coment-'+idactivoselect+'').length > 0 ) {
							$('.boton-coment-'+idactivoselect+'').removeClass("blue");
							$('.boton-coment-'+idactivoselect+'').addClass('green');
						}else{
							$('.msj-'+idactivoselect+'').append('<span class="icon-col green fa fa-comment boton-coment-'+idactivoselect+'" data-id="" data-toggle="tooltip" data-original-title="Comentarios" data-placement="right"></span>');
						}
						notification("¡Exito!",'Comentario Almacenado Satisfactoriamente',"success");  
						tablacomentario.ajax.reload(null, false);
					}else{
						notification("Error","Ha ocurrido un error al grabar el Comentario, intente mas tarde",'error'); 
					}
				},
				error: function () {
					$('#overlay').css('display','none');
					notification("Error","Ha ocurrido un error al grabar el Comentario, intente mas tarde",'error');  
				}
			});
		}else{ 
			notification("Advertencia!","Debe llenar el campo Nuevo Comentario",'warning'); 
		}
		return;
	}


