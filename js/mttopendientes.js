$(document).ready(function() {

     // FUNCION DE CALENDARIO
	$('#calendarhidendesde').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD' }).on('change',function(){
	    var fechadesdeoculto = $('#calendarhidendesde').val();
	    $('#fecha-desde').val(fechadesdeoculto);
	});	
	$('#calendarhidenhasta').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick', format:'YYYY-MM-DD' }).on('change',function(){
	    var fechahastaoculto = $('#calendarhidenhasta').val();
	    $('#fecha-hasta').val(fechahastaoculto);
	});	
	$('.iconcalfdesde').on( 'click', function (e) { 
	    $('#calendarhidendesde').dblclick();
	});	
	$('.iconcalfhasta').on( 'click', function (e) { 
	    $('#calendarhidenhasta').dblclick();
	});
     
//OBTENER COMBO
	$.get("controller/combosback.php?oper=responsablesActas", { onlydata:"true" }, function(result){
		$("#casa").select2({placeholder:''});
		$("#casa").append(result);	
	});	
	$.get("controller/combosback.php?oper=unidades", { onlydata:"true" }, function(result){
		$("#unidadesjecutoras").select2({placeholder:''});
		$("#unidadesjecutoras").append(result);	
	});	

     // GUARDAR ACTAS
	$("#reporteMttoPend").on('click',function(){ 
		var desde 	= $("#fecha-desde").val();
		var hasta 	= $("#fecha-hasta").val();
		var casa	= $("#casa option:selected").val();
		var unidad	= $("#unidadesjecutoras option:selected").val();
		window.open('controller/mttopendientesback.php?desde='+desde+'&hasta='+hasta+'&casa='+casa+'&unidadejecutora='+unidad);				
   	});

	

});


