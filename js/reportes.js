	
	//$(document).ready(function() {
		
		
		
		//CALENDARIO  
		$('#fecha-desdec, #fecha-hastac, #fecha-desder, #fecha-hastar, #fecha-desdecss, #fecha-hastacss').bootstrapMaterialDatePicker({weekStart:0, format:'YYYY-MM-DD', switchOnClick:true, time:false, clearButton: true, lang : 'es', cancelText: 'Cancelar', clearText: 'Limpiar'  }); 
		
	//});	
	
	$("#icono-filtrosmasivos,#icono-refrescar").css("display","none");
	
	var pathname = window.location.pathname;
	var pagina = pathname.split('/'); 
	var tiporeporte = pagina[2];
	 
	//CLIENTES
	let idempresas = 1; 
	
	//Cliente CSS
	$("#idclientescss").attr('disabled', true);
	$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
		$("#idclientescss").empty();
		$("#idclientescss").append(result); 
		$('#idclientescss').val(1).trigger("change");
	});  
	
	//Otros clientes
	$.get( "controller/combosback.php?oper=clientes&idempresas="+idempresas, { onlydata:"true" }, function(result){ 
		$("#idclientes").empty();
		$("#idclientes").append(result); 
		$("#idclientes option[value='1']").remove();
	});  
	
	//Cliente CSS	
	$("#idclientescss").change(function(e,data){
		let idempresas = $("#idempresas option:selected").val();
		let idclientes = $("#idclientescss option:selected").val();		
		
		//Proyectos
		$.get( "controller/combosback.php?oper=proyectos&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idproyectoscss").empty();
			$("#idproyectoscss").append(result); 
		});		 
		
		//Ubicaciones
		$.get( "controller/combosback.php?oper=sitiosclientes&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idambientes").empty();
			$("#idambientes").append(result); 
		});	 
		var incidentes = "'incidentes','preventivos'";
		var separador = incidentes.split(','); 
		$('#tipo').val(separador).trigger("change");  
	});
	
	//Otros clientes
	$("#idclientes").change(function(e,data){
		var idempresas = $("#idempresas option:selected").val();
		var idclientes = $("#idclientes option:selected").val();		
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos&idclientes="+idclientes, { onlydata:"true" }, function(result){ 
			$("#idproyectos").empty();
			$("#idproyectos").append(result); 
		});		 
	});		
	
	/* $("#idproyectos").change(function(e,data){
		var idproyectos = $("#idproyectos option:selected").val();
		var idempresas = $("#idempresas option:selected").val();
		var idclientes = $("#idclientes option:selected").val(); 
	});	 */  

$('#exportar').on( 'click', function (e) { 
	exportar('reporte.php');
});
$('#exportarCSS').on( 'click', function (e) { 
	exportar('reportescss.php');
});	
$('#icono-limpiar').on( 'click', function (e) { 
	$('#idclientes').val(null).trigger("change"); 
	$('#idproyectos').val(null).trigger("change"); 
	$('#idproyectoscss').val(null).trigger("change"); 
	$('#tipo').val(null).trigger("change"); 
	$('#idambientes').val(null).trigger("change"); 
	$('#fecha-desdec').val("");
	$('#fecha-hastac').val("");
	$('#fecha-desder').val("");
	$('#fecha-hastar').val("");
	$('#fecha-desdecss').val("");
	$('#fecha-hastacss').val("");
});	


//***** ***** ***** EXPORTAR ***** ***** ***** //
const exportar = (tiporeporte)=> {
	
	if(tiporeporte == 'reportescss.php'){
		var bcliente 	 = $('#idclientescss').val();
		var bproyecto 	 = $('#idproyectoscss').val(); 
		var btipo 	 	 = $('#tipo').val();
		var bubicacion 	 = $('#idambientes').val(); 
		var bfechadesdec = $('#fecha-desdecss').val();
		var bfechahastac = $('#fecha-hastacss').val(); 
		
		//PARAMETROS
		param  = "idclientes=" + bcliente;
		param += "&idproyectos=" + bproyecto; 
		param += "&tipo=" + btipo;
		param += "&idambientes=" + bubicacion;
		param += "&fechadesdec=" + bfechadesdec;
		param += "&fechahastac=" + bfechahastac; 	 
		
		 if(bcliente != "" && bcliente != 0 && bcliente != undefined && 
			bproyecto != "" && bproyecto != 0 && bproyecto != undefined	&&  
			btipo != "" && btipo != 0 && btipo != undefined && 
			bubicacion != "" && bubicacion != 0 && bubicacion != undefined && 
			bfechadesdec != "" &&  bfechadesdec != undefined &&
			bfechahastac != "" && bfechahastac != undefined ){  
				window.open ("reportes/reporteclientecss.php?" + param, "_blank"); 
		 }else{ 
			
			if (bcliente == "" || bcliente == '0' || bcliente == undefined ){
				notification("Debe seleccionar el cliente","Advertencia!",'warning');
			}else if(bproyecto == "" || bproyecto == '0' || bproyecto == undefined ){ 
				notification("Debe seleccionar el proyecto","Advertencia!",'warning');
			}else if(btipo == "" || btipo == '0' || btipo == undefined ){
				notification("Debe seleccionar el tipo","Advertencia!",'warning'); 
			}else if(bubicacion == "" || bubicacion == '0' || bubicacion == undefined ){
				notification("Debe seleccionar la ubicación","Advertencia!",'warning'); 
			}else if(bfechadesdec == "" || bfechadesdec == undefined ){
				notification("Debe seleccionar la fecha Desde","Advertencia!",'warning'); 
			}else if(bfechahastac == "" || bfechahastac == undefined ){
				notification("Debe seleccionar la fecha Hasta","Advertencia!",'warning'); 
			} 
			
		 }
	}else{
		console.log(`PASÓ R`)
		var bcliente 	 = $('#idclientes').val();
		var bproyecto 	 = $('#idproyectos').val();  
		var bfechadesdec = $('#fecha-desdec').val();
		var bfechahastac = $('#fecha-hastac').val();
		var bfechadesder = $('#fecha-desder').val();
		var bfechahastar = $('#fecha-hastar').val();
		
		//PARAMETROS
		param  = "idclientes=" + bcliente;
		param += "&idproyectos=" + bproyecto; 
		param += "&tipo=" + btipo;
		param += "&fechadesdec=" + bfechadesdec;
		param += "&fechahastac=" + bfechahastac;
		param += "&fechadesder=" + bfechadesder;
		param += "&fechahastar=" + bfechahastar;	 
		
		 if(bcliente != "" && bcliente != 0 && bcliente != undefined && 
			bproyecto != "" && bproyecto != 0 && bproyecto != undefined	&& 
			bfechadesdec != "" &&  bfechadesdec != undefined &&
			bfechahastac != "" && bfechahastac != undefined	&&
			bfechadesder != "" && bfechadesder != undefined	&&
			bfechahastar != "" && bfechahastar != undefined){ 
			//if(bcliente != 1){
				//window.open ("reportes/reporteclientes.php?" + param, "_blank");
				window.open ("librerias/phpword/reportecliente.php?" + param, "_blank");				
			/* }else{
				window.open ("reportes/reporteclientecss.php?" + param, "_blank");
			}  */
		 }else{ 
			console.log(`PASÓ S`)
			if (bcliente == "" || bcliente == '0' || bcliente == undefined ){ 
				notification("Debe seleccionar el cliente","Advertencia!",'warning'); 
			}else if(bproyecto == "" || bproyecto == '0' || bproyecto == undefined ){
				notification("Debe seleccionar el proyecto","Advertencia!",'warning'); 
			}/* else if(btipo == "" || btipo == '0' || btipo == undefined ){
				notification("Debe seleccionar el tipo","Advertencia!",'warning');  
			} */else if(bfechadesdec == "" || bfechadesdec == undefined ){
				notification("Debe seleccionar la fecha de creación Desde","Advertencia!",'warning'); 
			}else if(bfechahastac == "" || bfechahastac == undefined ){
				notification("Debe seleccionar la fecha de creación Hasta","Advertencia!",'warning'); 
			}else if(bfechadesder == "" || bfechadesder == undefined ){
				notification("Debe seleccionar la fecha de resolución Desde","Advertencia!",'warning'); 
			}else if(bfechahastar == "" || bfechahastar == undefined ){
				notification("Debe seleccionar la fecha de resolución Hasta","Advertencia!",'warning'); 
			}
			
		 } 
	}  
}

$("select").select2(); 


