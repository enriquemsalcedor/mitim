
function generarReporte(){
	 /*-UI-BOTONES-*/
    //let	viewReport=document.getElementById("viewReport")
    let viewReport=document.querySelector(".btnviewReport")
     //$(".btnviewReport").attr("t","Cabecera de la página");
    /*-UI-BOTONES-*/
	var idincidente 	 = $('#numero').val();
	var idreporte	 	 = $('#idreporte').val(); 
	var departamento 	 = $('#departamentoreporte').val();
	var tiposervicio 	 = $('#tiposervicio').val();
	var ubicacionactivo  = $('#ubicacionactivo').val();
	var estadoactivo	 = $('#estadoactivo').val();
	var fallareportada	 = $('#fallareportada').val();
	var trabajorealizado = $('#trabajorealizado').val();
	var observaciones	 = $('#observaciones').val();
	var fechafirmatecnico= $('#fechafirmatecnico').val();
	var nombrecliente1	 = $('#nombrecliente1').val();
	var nombrecliente2	 = $('#nombrecliente2').val();
	var fechafirmacliente= $('#fechafirmacliente').val();
	var canvaslimpio 	 = document.getElementById('canvas-limpio');
	var canvastecnico	 = document.getElementById('canvas-tecnico');
	var canvascliente1	 = document.getElementById('canvas-cliente1');
	var canvascliente2	 = document.getElementById('canvas-cliente2');
	var firmalimpia	     = canvaslimpio.toDataURL();
	var firmatecnico	 = canvastecnico.toDataURL();
	var firmacliente1	 = canvascliente1.toDataURL();
	var firmacliente2	 = canvascliente2.toDataURL();
	
	//Validaciones
	var sitio			 = $('#sitioreporte').val();
	var equipo	 		 = $('#equiporeporte').val();
	var ubicacion	 	 = $('#ubicacionactivo').val();
	var serie 		 	 = $('#seriereporte').val(); 
	var tecnico		 	 = $('#nombretecnico').val(); 
	var validarFechas 	 = "validarFechasDef";
	
	/* if(sitio == '' || sitio == undefined){ 
		demo.showSwal('error-message','ERROR','Debe llenar el campo Sitio');
	}else if(equipo == '' || equipo == undefined){ 
		demo.showSwal('error-message','ERROR','Debe llenar el campo Equipo');
	}else if(ubicacion == '' || ubicacion == undefined){ 
		demo.showSwal('error-message','ERROR','Debe llenar el campo Ubicación');
	}else  if(serie == '' || serie == undefined){ 
		demo.showSwal('error-message','ERROR','Debe llenar el campo Serie');
	}else */
	/* else if(departamento == '' || departamento == undefined){ 
		demo.showSwal('error-message','ERROR','Debe llenar el campo Departamento');
	} */
	/* else if(ubicacionactivo == '' || ubicacionactivo == undefined ){ 
		demo.showSwal('error-message','ERROR','Debe llenar el campo Ubicación del Activo');
	} */
	if(tecnico == '' || tecnico == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Nombre de Técnico','warning');
	}else if(tiposervicio == '' || tiposervicio == undefined || tiposervicio == 'sinasignar'){ 
		notification("Advertencia!",'Debe llenar el campo Tipo de Servicio','warning');
	}else if(estadoactivo == '' || estadoactivo == undefined || estadoactivo == 'sinasignar'){ 
		notification("Advertencia!",'Debe llenar el campo Estado Final del activo','warning');
	}else if(fallareportada == '' || fallareportada == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Falla o error reportado','warning');
	}else if(trabajorealizado == '' || trabajorealizado == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Trabajo realizado','warning');
	}else if(observaciones == '' || observaciones == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Observaciones','warning');
	}else if(fechafirmatecnico == '' || fechafirmatecnico == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Fecha de firma del reporte','warning');
	}else if(nombrecliente1 == '' || nombrecliente1 == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Nombre 1 del Cliente','warning');
	}else if(idreporte == "" && firmatecnico == firmalimpia){
		notification("Advertencia!",'Debe colocar la Firma del Técnico','warning');
	}else if(idreporte == "" && firmacliente1 == firmalimpia){
		notification("Advertencia!",'Debe colocar la Firma del Cliente 1','warning');
	}else if(idreporte != "" && $('#canvas-tecnico').css('display') == 'block' && firmatecnico == firmalimpia){
		notification("Advertencia!",'Debe colocar la Firma del Técnico','warning');
	}else if(idreporte != "" && $('#canvas-cliente1').css('display') == 'block' && firmacliente1 == firmalimpia){
		notification("Advertencia!",'Debe colocar la Firma del Cliente 1','warning');
	}else if(fechafirmacliente == '' || fechafirmacliente == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Fecha de firma del Cliente','warning');
	}else if(idreporte == ""){
		notification("Advertencia!",'Debe guardar los datos del Formulario','warning');
	}else{
		//Validar datos en la bd
		 $.ajax({
			url: 'controller/preventivos-reporteservicioback.php',
			type : 'POST',
			dataType: 'json',
			data: { 
				'oper' 		: 'validarGenerarReporte',
				'idreporte' : idreporte
			}, 
			success: function(response){
				if ( response == 1 ) { 
					//Validar fechas 
					$.ajax({
						type: 'post',
						dataType: "json",
						url: 'controller/preventivos-reporteservicioback.php',
						data: { 
							'oper' 		: validarFechas,
							'idreporte' : idreporte
						}, 
						success: function (response) { 
							if(response == 1){ 
								$('.btngenerarreporte').hide();
								$('.btnguardarreporte').hide();
								$('.icon-reporte').hide();
								$('.btnviewReport').show();
								//Generar reporte
								/*-CONSTRUCCION-DE-LA-URL*/		    
								href=`https://toolkit.maxialatam.com/soporte/reportes/incidentes-imagenreporteservicio.php?idincidente=${idincidente}&usuario=&idreporte=${idreporte}`
								/*-CONSTRUCCION-DE-LA-URL*/
								/*-ASIGNACION-DE-LA-URL-Y-HACER-VISIBLE-EL-BOTON-Y-OCULTAR-EL-GENERAR-REPORTE*/
								$('.btnviewReport').show();
								$(".btnviewReport").attr('href',href)
								//	window.open('reportes/incidentes-imagenreporteservicio.php?idincidente='+idincidente);
								tablaincidentes.ajax.reload(null, false);
								tablaFechasTemp.ajax.reload(null, false);
								tablaRepuestosTemp.ajax.reload(null, false);
								
						}else{
								notification("Debe agregar la(s) fecha(s) de atención.","Error",'error');
							}
						} 
					});
				}else if(response == 0){
					notification("Ocurrió un error al generar el reporte","Error",'error');
				}else{
					notification(''+response.msg+'',"Error",'error');
				}
			} 
		});  
	} 
}

/*-RECARGA-LA-TBLA-DE-REPORTE-DE-SERVICIO--------------------------------------------------------*/
  $('.btnviewReport').on("click", function() {
	console.log('clic tabla');
    setTimeout(function(){ 
	console.log('recargar tabla');
	tablaRporteSrv.ajax.reload(null, false);
	}, 9000);
  });
/*---------------------------------------------------------------*/
configurarCanvas('tecnico');
configurarCanvas('cliente1');
configurarCanvas('cliente2');



function abrirReporteServicio(id){ 
	$('#numero').val(id);
	$.ajax({
		dataType: "json",
		url: 'controller/preventivos-reporteservicioback.php',
		data: { 
			'oper'	: 'consultarReporte',
			'idincidente'	: id			
		}, 
		success: function (response) { 
			if(response!=0){
				$.map(response, function (item) {
					$('.tit_reporte').text("Editar ");
					$('.tit_numeracion').text("Reporte ");
					$('.cls_editar').css('display','block');
					$('.cls_crear').css('display','none');
					$('#modaladdreporte').modal('show');
					$('#idreporte').val(item.id);
					$('#codigoreporte').val(item.codigo);
					$('#fechacreacionreporte').val(item.fechacreacion);
					$('#marcareporte').val(item.marca);
					$('#modeloreporte').val(item.modelo);
					$('#seriereporte').val(item.serie); 
					$('#equiporeporte').val(item.equipo); 
					$('#sitioreporte').val(item.sitio);  
					$('#ubicacionactivo').val(item.ubicacionactivo);  
					$('#trabajorealizado').val(item.trabajorealizado);  
					$('#fallareportada').val(item.fallareportada);  
					$('#departamentoreporte').val(item.departamento);  
					$('#observaciones').val(item.observaciones);  
					$('#fechafirmatecnico').val(item.fechafirmatecnico);  
					$('#nombretecnico').val(item.nombretecnico);  
					$('#nombrecliente1').val(item.nombrecliente1);  
					$('#nombrecliente2').val(item.nombrecliente2);  
					$('#fechafirmacliente').val(item.fechafirmacliente);  
					$('#estadoactivo').val(item.estadoactivo).trigger('change'); 
					$('#tiposervicio').val(item.tiposervicio).trigger('change');
					if(item.estatus == 1){
						$('.btngenerarreporte').hide();  
						$('.btnguardarreporte').hide();  
						$('.icon-reporte').hide();  
					   // $('.btnviewReport').hide();
					}else{
					    /*-UI-BOTONES-*/
                        $('.btnviewReport').hide();
                        $(".btnSave").text('Grabar')
                    	/*-UI-BOTONES-*/
						$('.btngenerarreporte').show();  
						$('.btnguardarreporte').show();
						$('.icon-reporte').show();
					}
					
					if(item.firmatecnico != null && item.firmatecnico != ''){ 
						$('#mostrarfirmatecnico').attr('src',item.firmatecnico); 
						$('.limpiar-firmatecnico').hide(); 
						$('.boton-editar-firmatecnico').show();
					}else{ 
						$('#mostrarfirmatecnico').css('display','none');
						$('#canvas-tecnico').css('display','block');
						$('.limpiar-firmatecnico').show();
						$('.boton-editar-firmatecnico').hide();
					}
					if(item.firmacliente1 != null && item.firmacliente1 != ''){
						$('#mostrarfirmacliente1').attr('src',item.firmacliente1); 
						$('.limpiar-firmacliente1').hide(); 
						$('.boton-editar-firmacliente1').show();
					}else{
						$('#mostrarfirmacliente1').css('display','none');
						$('#canvas-cliente1').css('display','block');
						$('.limpiar-firmacliente1').show();
						$('.boton-editar-firmacliente1').hide();
					} 
					if(item.firmacliente2 != null && item.firmacliente2 != ''){
						$('#mostrarfirmacliente2').attr('src',item.firmacliente2); 
						$('.limpiar-firmacliente2').hide(); 
						$('.boton-editar-firmacliente2').show(); 
					}else{
						$('#mostrarfirmacliente2').css('display','none');
						$('#canvas-cliente2').css('display','block');
						$('.limpiar-firmacliente2').show();
						$('.boton-editar-firmacliente2').hide(); 
					}					
					
					abrirFechas(id,'def',item.id);
					abrirRepuestos(id,'def',item.id);
					//Consultar datos del reporte
                	//------------------
                	abrirRepotServcGn(id)
                	//------------------
				});
			}else{
			    /*-UI-BOTONES-*/
                $('.btnviewReport').hide();
                $(".btnSave").text('Nuevo reporte')

                /*-UI-BOTONES-*/
				//Consultar datos del correctivo
				$('.btngenerarreporte').hide();
				$('.btnguardarreporte').show();
				$('.icon-reporte').show();
				$.ajax({
					type: 'get',
					dataType: 'json',
					url: 'controller/preventivos-reporteservicioback.php',
					data: { 
						'oper'	: 'consultarCorrectivo',
						'id'	: id			
					},
					beforeSend: function() {
						$(".loader-maxia").show();
					},
					success: function (response) { 
						$('.tit_reporte').text("Crear ");
						$('.tit_numeracion').text("Correctivo ");
						$('.cls_editar').css('display','none');
						$('.cls_crear').css('display','block');
						$('.limpiar-firmatecnico').show();
						$('.limpiar-firmacliente1').show();
						$('.limpiar-firmacliente2').show();
						$('#modaladdreporte').modal('show');
						$.map(response, function (item) {
								$('#fechacreacionreporte').val(item.fechacreacion);
								$('#marcareporte').val(item.marca);
								$('#modeloreporte').val(item.modelo);
								$('#seriereporte').val(item.serie); 
								$('#equiporeporte').val(item.equipo); 
								$('#sitioreporte').val(item.sitio);
								$('#nombretecnico').val(item.nombretecnico);
								//Tipo de servicio
								$('.boton-editar-firmatecnico').css('display','none');
								$('.boton-editar-firmacliente1').css('display','none');
								$('.boton-editar-firmacliente2').css('display','none');
						});
					},
					complete: function(data,status){ 
						abrirFechas(id,'temp','');
						abrirRepuestos(id,'temp','');
    					//Consultar datos del reporte
                    	//------------------
                    	abrirRepotServcGn(id)
                    	//------------------
					}
				});
			}
		} 
	});  
}

function abrirFechas(id,tipo,idreporte){
	if(id!=undefined){
		if(tipo=='temp'){
			var oper = 'listarFechasTemp';
			var url = "controller/preventivos-reporteservicioback.php?oper="+oper+"&idincidente="+id;
		}else{
			var oper = 'listarFechasDef';
			var url = "controller/preventivos-reporteservicioback.php?oper="+oper+"&idincidente="+id+"&idreporte="+idreporte;
		}
		
		tablaFechasTemp = $("#tablafechastemp").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: url
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "acciones" },
				{ 	"data": "fecha" },
				{ 	"data": "tiempoviaje" },
				{ 	"data": "tiempolabor" },
				{ 	"data": "tiempoespera" },
				{ 	"data": "horainicio" },
				{ 	"data": "horafin" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					"targets"	: [ 0],
					"visible"	: false,
					"searchable": false
				},{
					targets		: [2,3,4],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}
		});
	}
}
		
function abrirRepuestos(id,tipo,idreporte){
	if(id!=undefined){
		if(tipo=='temp'){
			var oper = 'listarRepuestosTemp';
			var url = "controller/preventivos-reporteservicioback.php?oper="+oper+"&idincidente="+id;
		}else{
			var oper = 'listarRepuestosDef';
			var url = "controller/preventivos-reporteservicioback.php?oper="+oper+"&idincidente="+id+"&idreporte="+idreporte;
		}
		tablaRepuestosTemp = $("#tablarepuestostemp").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: url
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "acciones" },
				{ 	"data": "codigo" },
				{ 	"data": "cantidad" },
				{ 	"data": "descripcion" }
				],
			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					"targets"	: [ 0 ],
					"visible"	: false,
					"searchable": false
				},{
					targets		: [2,3,4],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}
		});
	}
}	 
/*-ABRIR-REPORTE-DE-SERVICIOS-GENERADOS------------------------------------------------------------*/
function abrirRepotServcGn(id){
    if(id!=undefined)
	{
		let oper = 'consultarReporteGn';
		let url = "controller/preventivos-reporteservicioback.php?oper="+oper+"&idincidente="+id;
	    console.log("ejecuto ")
	
		    tablaRporteSrv = $("#tablareporteservicio").DataTable({
			responsive: false,
			destroy: true,
			ordering: false,
			searching: false,
			"ajax"		: {
				"url"	: url
			},
			"columns"	: [
				{ 	"data": "id" },
				{ 	"data": "codigo" },
				{ 	"data": "fechacreacion" },
				{ 	"data": "nombretecnico" },
				{ 	"data": "enlace" },
				],

			"rowId": 'id', // CAMPO DE LA DATA QUE RETORNARÁ EL MÉTODO id()
			"columnDefs": [ //OCULTAR LA COLUMNA ID
				{
					"targets"	: [ 0],
					"visible"	: false,
					"searchable": false
				},{
					targets		: [2,3,4],
					className	: "dt-left"
				}
			],
			"language": {
				"url": "js/Spanish.json"
			}
		});
	}
}

/*-------------------------------------------------------------------------------------------------*/
function guardarReporte(){
	
	var idreporte	 	 = $('#idreporte').val();
	var idincidente 	 = $('#numero').val();
	var departamento 	 = $('#departamentoreporte').val();
	var tiposervicio 	 = $('#tiposervicio').val();
	var ubicacionactivo  = $('#ubicacionactivo').val();
	var estadoactivo	 = $('#estadoactivo').val();
	var fallareportada	 = $('#fallareportada').val();
	var trabajorealizado = $('#trabajorealizado').val();
	var observaciones	 = $('#observaciones').val();
	var fechafirmatecnico= $('#fechafirmatecnico').val();
	var nombrecliente1	 = $('#nombrecliente1').val();
	var nombrecliente2	 = $('#nombrecliente2').val();
	var fechafirmacliente= $('#fechafirmacliente').val();
	var canvaslimpio 	 = document.getElementById('canvas-limpio');
	var canvastecnico	 = document.getElementById('canvas-tecnico');
	var canvascliente1	 = document.getElementById('canvas-cliente1');
	var canvascliente2	 = document.getElementById('canvas-cliente2');
	var firmalimpia	     = canvaslimpio.toDataURL();
	var firmatecnico	 = canvastecnico.toDataURL();
	var firmacliente1	 = canvascliente1.toDataURL();
	var firmacliente2	 = canvascliente2.toDataURL();
	
	if(idreporte!=""){
		oper = "editarReporte";
		validarFechas = "validarFechasDef";  
	}else{
		oper = "guardarReporte";
		validarFechas = "validarFechasTemp"; 
	}  
	
	//Permitir guardar firma vacía
	if($('#canvas-cliente1').css('display') == 'block' && firmacliente1 == firmalimpia){ 
		firmacliente1 = ""; 
	}
	if($('#canvas-cliente2').css('display') == 'block' && firmacliente2 == firmalimpia){ 
		firmacliente2 = ""; 
	} 
		
	//No permitir guardar lienzos si ya tiene firmas mostradas (imagenes)
	if(idreporte != "" && $('#canvas-tecnico').css('display') == 'none' && firmatecnico == firmalimpia){
		firmatecnico = 0;
	}
	if(idreporte != "" && $('#canvas-cliente1').css('display') == 'none' && firmacliente1 == firmalimpia){
		firmacliente1 = '-';
		console.log('cli1-1');
	}else{
		console.log('cli1-2');
	}
	if(idreporte != "" && $('#canvas-cliente2').css('display') == 'none' && firmacliente2 == firmalimpia){
		firmacliente2 = '-';
		console.log('cli2-1');
	}else{
		console.log('cli2-2');
	}
	
	/* if(departamento == '' || departamento == undefined){ 
		demo.showSwal('error-message','ERROR','Debe llenar el campo Departamento');
	}else  */if(tiposervicio == '' || tiposervicio == undefined || tiposervicio == 'sinasignar'){ 
		notification("Advertencia!",'Debe llenar el campo Tipo de Servicio','warning');
	}/* else if(ubicacionactivo == '' || ubicacionactivo == undefined ){ 
		demo.showSwal('error-message','ERROR','Debe llenar el campo Ubicación del Activo');
	} */else if(estadoactivo == '' || estadoactivo == undefined || estadoactivo == 'sinasignar'){ 
		notification("Advertencia!",'Debe llenar el campo Estado Final del activo','warning');
	}else if(fallareportada == '' || fallareportada == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Falla o error reportado','warning');
	}else if(trabajorealizado == '' || trabajorealizado == undefined){
		notification("Advertencia!",'Debe llenar el campo Trabajo realizado','warning'); 
	}else if(observaciones == '' || observaciones == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Observaciones','warning');
	}else if(fechafirmatecnico == '' || fechafirmatecnico == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Fecha de firma del reporte','warning');
	} else if(idreporte == "" && firmatecnico == firmalimpia){
		notification("Advertencia!",'Debe colocar la Firma del Técnico','warning');
	}else if(idreporte != "" && $('#canvas-tecnico').css('display') == 'block' && firmatecnico == firmalimpia){
		notification("Advertencia!",'Debe colocar la Firma del Técnico','warning');
	}else{
		
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/preventivos-reporteservicioback.php',
			data: { 
				'oper' 		: validarFechas,
				'idreporte' : idreporte,
				'idincidente':idincidente
			}, 
			success: function (response) { 
				if(response==true){
					$.ajax({
						type: 'post',
						dataType: "json",
						url: 'controller/preventivos-reporteservicioback.php',
						data: { 
							'oper'				: oper,
							'idincidente'		: idincidente,
							'idreporte'			: idreporte,
							'departamento'		: departamento,
							'tiposervicio'		: tiposervicio,
							'ubicacionactivo'	: ubicacionactivo,
							'estadoactivo'		: estadoactivo,
							'fallareportada'	: fallareportada,
							'trabajorealizado'	: trabajorealizado,
							'observaciones'		: observaciones,
							'fechafirmatecnico'	: fechafirmatecnico,
							'nombrecliente1'	: nombrecliente1,
							'nombrecliente2'	: nombrecliente2,
							'fechafirmacliente'	: fechafirmacliente,
							'firmatecnico'		: firmatecnico,
							'firmacliente1'		: firmacliente1,
							'firmacliente2'		: firmacliente2
						},
						beforeSend: function() {
							$('#overlay').css('display','block');
							$(".modal-container").addClass('swal2-in');
						},
						success: function (response) { 
							if(response==1){
								notification("Registro actualizado satisfactoriamente","¡Exito!",'success');
								$('#modaladdreporte').modal('hide');
							}else{
								notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
							}
						},
						complete: function(data,status){ 			
						}
					});
				}else{
					notification("Debe agregar la(s) fecha(s) de atención.","Error",'error');
				}
			} 
		}); 
	} 
}

function agregarFechas(){
	var idreporte	 	 = $('#idreporte').val(); 
	
	if(idreporte!=""){
		oper = "guardarFechasDef";
	}else{
		oper = "guardarFechasTemp";
	}
	var idincidente 	 = $('#numero').val();
	var fechaatencion 	 = $('#fechaatencion').val();
	var tiempoviaje 	 = $('#tiempoviaje').val();
	var tiempolabor 	 = $('#tiempolabor').val();
	var tiempoespera 	 = $('#tiempoespera').val();
	var horainicio		 = $('#horainicio').val();
	var horafin			 = $('#horafin').val(); 
	
	//validar
	if(fechaatencion == '' || fechaatencion == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Fecha de atención','warning');
	}else if(tiempoviaje == '' || tiempoviaje == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Tiempo de viaje','warning');
	}else if(horainicio == '' || horainicio == undefined ){ 
		notification("Advertencia!",'Debe llenar el campo Hora Inicio','warning');
	}else if(horafin == '' || horafin == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Hora final','warning');
	}else{
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/preventivos-reporteservicioback.php',
			data: { 
				'oper'				: oper,
				'idreporte'			: idreporte,
				'idincidente'		: idincidente,
				'fechaatencion'		: fechaatencion,
				'tiempoviaje'		: tiempoviaje,
				'tiempolabor'		: tiempolabor,
				'tiempoespera'		: tiempoespera,
				'horainicio'		: horainicio,
				'horafin'			: horafin 
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
				$(".modal-container").addClass('swal2-in');
			},
			success: function (response) { 
				if(response==1){
					notification("Registro actualizado satisfactoriamente","¡Exito!",'success');
					tablaFechasTemp.ajax.reload(null, false);
					limpiarFechas();
				}else{
					notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
				}
			},
			complete: function(data,status){ 			
			}
		});
	} 
} 

function limpiarFechas(){ 
	$('#fechaatencion').val("");
	$('#tiempoviaje').val("");
	$('#tiempolabor').val("");
	$('#tiempoespera').val("");
	$('#horainicio').val("");
	$('#horafin').val(""); 
}

function limpiarRepuestos(){ 
	$('#codigorep').val("");
	$('#cantidadrep').val("");
	$('#descripcionrep').val(""); 
}

function agregarRepuestos(){
	var idreporte	 = $('#idreporte').val();
	var idincidente  = $('#numero').val();
	var codigo 		 = $('#codigorep').val();
	var cantidad 	 = $('#cantidadrep').val();
	var descripcion	 = $('#descripcionrep').val(); 
	 
	if(idreporte!=""){
		oper = "guardarRepuestosDef";
	}else{
		oper = "guardarRepuestosTemp";
	}
	
	//validar
	if(codigo == '' || codigo == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Código');
	}else if(cantidad == '' || cantidad == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Cantidad','warning');
	}else if(descripcion == '' || descripcion == undefined){ 
		notification("Advertencia!",'Debe llenar el campo Descripción','warning');
	}else{
		$.ajax({
			type: 'post',
			dataType: "json",
			url: 'controller/preventivos-reporteservicioback.php',
			data: { 
				'oper'			: oper,
				'idreporte'		: idreporte,
				'idincidente'	: idincidente,
				'codigo'		: codigo,
				'cantidad'		: cantidad,
				'descripcion'	: descripcion 
			},
			beforeSend: function() {
				$('#overlay').css('display','block');
				$(".modal-container").addClass('swal2-in');
			},
			success: function (response) { 
				if(response==1){
					notification("Registro actualizado satisfactoriamente","¡Exito!",'success');
					tablaRepuestosTemp.ajax.reload(null, false);
					limpiarRepuestos();
				}else{
					notification("Ha ocurrido un error al grabar el Registro, intente mas tarde","Error",'error');
				}
			},
			complete: function(data,status){ 			
			}
		});
	} 
} 

$('#tablafechastemp').on( 'draw.dt', function () {
	$('.boton-eliminar-fecha').each(function(){
		var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().next().html();
		$(this).on( 'click', function() {
			var idreporte = $('#idreporte').val();
			if(idreporte!=""){
				var tipo = "def";
			}else{
				var tipo = "temp";
			}
			eliminarFechas(id,nombre,tipo);
		});
	});
 });
 
 $('#tablarepuestostemp').on( 'draw.dt', function () {
	$('.boton-eliminar-repuesto').each(function(){
		var id = $(this).attr("data-id");
		var nombre = $(this).parent().parent().next().html();
		$(this).on( 'click', function() {
			var idreporte = $('#idreporte').val();
			if(idreporte!=""){
				var tipo = "def";
			}else{
				var tipo = "temp";
			}
			eliminarRepuestos(id,nombre,tipo);
		});
	});
 });

	function eliminarFechas(id,nombre,tipo){
		console.log('ideli:'+id);
		if(tipo!='temp'){
			oper = "borrarFechasDef";
		}else{
			oper = "borrarFechasTemp";
		}
		var idincidente = id;
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar la fecha "+nombre+"?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm){
					$.get( "controller/preventivos-reporteservicioback.php?oper="+oper, 
					{ 
						onlydata : "true",
						id : idincidente
					}, function(result){
						if(result == 1){
							swal('Buen trabajo','Fecha eliminada satisfactoriamente','success');		
							// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
							tablaFechasTemp.ajax.reload(null, false);
						} else {
							swal('ERROR','Ha ocurrido un error al eliminar la fecha, intente más tarde','error');
						}
					});
				}
			}, function (isRechazo){
				// NADA
			}
		);
	}

	function eliminarRepuestos(id,nombre,tipo){
		if(tipo!='temp'){
			oper = "borrarRepuestosDef";
		}else{
			oper = "borrarRepuestosTemp";
		}
		var idincidente = id;
		swal({
			title: "Confirmar",
			text: "¿Esta seguro de eliminar el repuesto "+nombre+"?",
			type: "warning",
			showCancelButton: true,
			cancelButtonColor: 'red',
			confirmButtonColor: '#09b354',
			confirmButtonText: 'Si',
			cancelButtonText: "No"
		}).then(
			function(isConfirm){
				if (isConfirm){
					$.get( "controller/preventivos-reporteservicioback.php?oper="+oper, 
					{ 
						onlydata : "true",
						id : idincidente
					}, function(result){
						if(result == 1){
							swal('Buen trabajo','Repuesto eliminado satisfactoriamente','success');		
							// RECARGAR TABLA Y SEGUIR EN LA MISMA PAGINA (2do parametro)
							tablaRepuestosTemp.ajax.reload(null, false);
						} else {
							swal('ERROR','Ha ocurrido un error al eliminar el repuesto, intente más tarde','error');
						}
					});
				}
			}, function (isRechazo){
				// NADA
			}
		);
	}

function cerrarReporte(){
	$('#modaladdreporte').modal('hide');
}

$('#modaladdreporte').on('hidden.bs.modal', function (e) {
  	$('#idreporte').val("");
	$('#numero').val("");
	$('#departamentoreporte').val("");
	$('#tiposervicio').val("");
	$('#ubicacionactivo').val(null).trigger("change");
	$('#estadoactivo').val(null).trigger("change");
	$('#fallareportada').val("");
	$('#trabajorealizado').val("");
	$('#observaciones').val("");
	$('#fechafirmatecnico').val("");
	$('#nombrecliente1').val("");
	$('#nombrecliente2').val("");
	$('#fechafirmacliente').val("");
	$('#nombretecnico').val("");
	borrarFirma('tecnico');
	borrarFirma('cliente1');
	borrarFirma('cliente2'); 
	$('.cancelareditar-firmatecnico').css('display','none');
	$('.cancelareditar-firmacliente1').css('display','none');
	$('.cancelareditar-firmacliente2').css('display','none');
	
	//Limpiar datos temporales del usuario
	/* $.ajax({
		url: 'controller/preventivos-reporteservicioback.php',
		type : 'POST',
		dataType: 'json',
		data: { 
			'oper' : limpiarTemporales 
		}, 
		success: function(response){
			//
		}
	}); */
})

$('#fechaatencion, #fechafirmatecnico, #fechafirmacliente').bootstrapMaterialDatePicker(
	{ weekStart : 0, format:'YYYY-MM-DD', switchOnClick:true, clearButton: false, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar' }
);
$('#tiempoviaje, #tiempolabor, #tiempoespera, #horainicio, #horafin').bootstrapMaterialDatePicker(
	{ date: false, format:'HH:mm', switchOnClick:true, time:true, clearButton: false, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar' }
);

function configurarCanvas(tipo) {
	if(tipo=='tecnico'){
		var idcanvas = 'canvas-tecnico';
	}else if(tipo=='cliente1'){
		var idcanvas = 'canvas-cliente1';
	}else if(tipo=='cliente2'){
		var idcanvas = 'canvas-cliente2';
	}
	// Set up the canvas
	var canvas = document.getElementById(idcanvas);
	var ctx = canvas.getContext("2d");
	ctx.strokeStyle = "#222222";
	ctx.lineWith = 2;

	// Set up mouse events for drawing
	var drawing = false;
	var mousePos = { x:0, y:0 };
	var lastPos = mousePos;
	canvas.addEventListener("mousedown", function (e) {
			drawing = true;
	  lastPos = getMousePos(canvas, e);
	}, false);
	canvas.addEventListener("mouseup", function (e) {
	  drawing = false;
	}, false);
	canvas.addEventListener("mousemove", function (e) {
	  mousePos = getMousePos(canvas, e);
	}, false);

	// Get the position of the mouse relative to the canvas
	function getMousePos(canvasDom, mouseEvent) {
	  var rect = canvasDom.getBoundingClientRect();
	  return {
		x: mouseEvent.clientX - rect.left,
		y: mouseEvent.clientY - rect.top
	  };
	}

	// Get a regular interval for drawing to the screen
	window.requestAnimFrame = (function (callback) {
			return window.requestAnimationFrame || 
			   window.webkitRequestAnimationFrame ||
			   window.mozRequestAnimationFrame ||
			   window.oRequestAnimationFrame ||
			   window.msRequestAnimaitonFrame ||
			   function (callback) {
			window.setTimeout(callback, 1000/60);
			   };
	})();

	// Draw to the canvas
	function renderCanvas() {
	  if (drawing) {
		ctx.moveTo(lastPos.x, lastPos.y);
		ctx.lineTo(mousePos.x, mousePos.y);
		ctx.stroke();
		lastPos = mousePos;
	  }
	}

	// Allow for animation
	(function drawLoop () {
	  requestAnimFrame(drawLoop);
	  renderCanvas();
	})();

	// Set up touch events for mobile, etc
	canvas.addEventListener("touchstart", function (e) {
			mousePos = getTouchPos(canvas, e);
	  var touch = e.touches[0];
	  var mouseEvent = new MouseEvent("mousedown", {
		clientX: touch.clientX,
		clientY: touch.clientY
	  });
	  canvas.dispatchEvent(mouseEvent);
	}, false);
	canvas.addEventListener("touchend", function (e) {
	  var mouseEvent = new MouseEvent("mouseup", {});
	  canvas.dispatchEvent(mouseEvent);
	}, false);
	canvas.addEventListener("touchmove", function (e) {
	  var touch = e.touches[0];
	  var mouseEvent = new MouseEvent("mousemove", {
		clientX: touch.clientX,
		clientY: touch.clientY
	  });
	  canvas.dispatchEvent(mouseEvent);
	}, false);

	// Get the position of a touch relative to the canvas
	function getTouchPos(canvasDom, touchEvent) {
	  var rect = canvasDom.getBoundingClientRect();
	  return {
		x: touchEvent.touches[0].clientX - rect.left,
		y: touchEvent.touches[0].clientY - rect.top
	  };
	}

	// Prevent scrolling when touching the canvas
	document.body.addEventListener("touchstart", function (e) {
	  if (e.target == canvas) {
		e.preventDefault();
	  }
	}, false);
	document.body.addEventListener("touchend", function (e) {
	  if (e.target == canvas) {
		e.preventDefault();
	  }
	}, false);
	document.body.addEventListener("touchmove", function (e) {
	  if (e.target == canvas) {
		e.preventDefault();
	  }
	}, false);
	
	$("#"+idcanvas)[0].addEventListener('touchmove', function(e) {
	  e.preventDefault();
	  //brushMove();
	},false);
}

function borrarFirma(tipo) {
	if(tipo=='tecnico'){
		var idcanvas = 'canvas-tecnico';
	}else if(tipo=='cliente1'){
		var idcanvas = 'canvas-cliente1';
	}else if(tipo=='cliente2'){
		var idcanvas = 'canvas-cliente2';
	}
	var canvas = document.getElementById(idcanvas);
	var ctx = canvas.getContext("2d");
	ctx.beginPath();
	ctx.clearRect(0, 0, canvas.width, canvas.height);
}

$('.boton-editar-firmatecnico').on( 'click', function() {
	$('.boton-editar-firmatecnico').hide();
	$('#canvas-tecnico').css('display','block');
	$('#mostrarfirmatecnico').css('display','none');
	$('.limpiar-firmatecnico').show();
	$('.cancelareditar-firmatecnico').show();
});

$('.boton-editar-firmacliente1').on( 'click', function() {
	$('.boton-editar-firmacliente1').hide();
	$('#canvas-cliente1').css('display','block');
	$('#mostrarfirmacliente1').css('display','none');
	$('.limpiar-firmacliente1').show();
	$('.cancelareditar-firmacliente1').show();
});

$('.boton-editar-firmacliente2').on( 'click', function() {
	$('.boton-editar-firmacliente2').hide();
	$('#canvas-cliente2').css('display','block');
	$('#mostrarfirmacliente2').css('display','none');
	$('.limpiar-firmacliente2').show();
	$('.cancelareditar-firmacliente2').show();
});

$('.cancelareditar-firmatecnico').on( 'click', function() {  
	$('.boton-editar-firmatecnico').show();
	$('#canvas-tecnico').css('display','none');
	$('#mostrarfirmatecnico').css('display','block');
	$('.limpiar-firmatecnico').css('display','none');
	$('.cancelareditar-firmatecnico').css('display','none');
	borrarFirma('tecnico');
});

$('.cancelareditar-firmacliente1').on( 'click', function() {  
	$('.boton-editar-firmacliente1').show();
	$('#canvas-cliente1').css('display','none');
	$('#mostrarfirmacliente1').css('display','block');
	$('.limpiar-firmacliente1').css('display','none');
	$('.cancelareditar-firmacliente1').css('display','none');
	borrarFirma('cliente1');
});

$('.cancelareditar-firmacliente2').on( 'click', function() { 
	$('.boton-editar-firmacliente2').show();
	$('#canvas-cliente2').css('display','none');
	$('#mostrarfirmacliente2').css('display','block');
	$('.limpiar-firmacliente2').hide();
	$('.cancelareditar-firmacliente2').css('display','none');
	borrarFirma('cliente2');
});

$("select").select2();


