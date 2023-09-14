$(document).ready(function() {
	
	     // FUNCION DE CALENDARIO
	$('#calendarhidendesde').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick' }).on('change',function(){
	    var fechadesdeoculto = $('#calendarhidendesde').val();
	    $('#fecha-desde').val(fechadesdeoculto);
	});	
	$('#calendarhidenhasta').bootstrapMaterialDatePicker({weekStart:0, switchOnClick:false, time:false, triggerEvent: 'dblclick' }).on('change',function(){
	    var fechahastaoculto = $('#calendarhidenhasta').val();
	    $('#fecha-hasta').val(fechahastaoculto);
	});	
	$('.iconcalfdesde').on( 'click', function (e) { 
	    $('#calendarhidendesde').dblclick();
	});	
	$('.iconcalfhasta').on( 'click', function (e) { 
	    $('#calendarhidenhasta').dblclick();
	});
	
	//REGISTRAR PROMOTORES
	$('#reporteMPC').click(function(){
		//console.log('paso');
		var desde 	= $("#fecha-desde").val();
		var hasta 	= $("#fecha-hasta").val();
		
		window.open('controller/actasmpcback.php?desde='+desde+'&hasta='+hasta);				
	});
});

//MARCAR SIDEBAR
$("li").removeClass("active");
$('a').attr("aria-expanded","false");
$("div#mantenimientos").addClass("in");
$('div#mantenimientos').parent().addClass("active");
$('div#mantenimientos').siblings('a').attr("aria-expanded","true");
$("li#actasmpc").addClass("active");


