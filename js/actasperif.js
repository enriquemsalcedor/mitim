$(document).ready(function() {

	//Obtener Combos
	$.get("controller/combosback.php?oper=cuatrimestres", { onlydata:"true" }, function(result){
		$("#periodo").select2({placeholder:''});
		$("#periodo").append(result);	
	});

	//Mostrar Reporte
	$('#reporteMP').click(function(){
		var periodo	= $("#periodo option:selected").text();
		if(periodo == ''){
			demo.showSwal('error-message','ERROR!','Debe seleccionar un Periodo');
		}else{
			window.open('controller/actasperifback.php?periodo='+periodo);
		}				
	});


});


