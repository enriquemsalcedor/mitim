$("select").select2({placeholder: ""});

function abrirdialogImportar(){
	var idempresas = 1;
	$.get( "controller/combosback.php?oper=clientes", { idempresas: idempresas }, function(result){ 
		$("#idclientesimp").empty();
		$("#idclientesimp").append(result);
	});
	//PROYECTOS
	$('#idclientesimp').on('select2:select',function(){
		var idclientes = $("#idclientesimp option:selected").val();		
		//PROYECTOS
		$.get( "controller/combosback.php?oper=proyectos", { idclientes: idclientes }, function(result){ 
			$("#idproyectosimp").empty();
			$("#idproyectosimp").append(result);
		});	
	});
	$("#form_importar_actividades")[0].reset();
	$("#resultado").html('');
	$('#modalImportar').modal('show');
}

$('#descargarplantilla').click(function(){
    var idclientes  = $('#idclientesimp').val();
	var idproyectos = $('#idproyectosimp').val();
	
	if(idclientes != "0" && idproyectos != "0" && idclientes != null && idproyectos != null){
		window.open('reportes/descargarplantillapreventivo.php?idclientes='+idclientes+'&idproyectos='+idproyectos);
	}else{ 
		notification("¡Advertencia!","Debe llenar los campos Clientes y Proyectos","warning");  
	}
});

$('#archivo').on('change', function() {
    // If a file is uploaded - hide "Select Image" and show "Change - Remove"
	var filename = $(this).val().split('\\').pop();
	$('.fileinput-filename').html(filename);
    if($(this).val().length) {
      $('span.fileinput-new').hide();
      $('span.fileinput-exists, a.fileinput-exists').show();
    // If a file is not uploaded - show "Select Image" and hide "Change - Remove"
    } else {
      $('span.fileinput-new').show();
      $('span.fileinput-exists, a.fileinput-exists').hide();
    }
});

$('a.fileinput-exists').on('click', function() {
	$('form').get(0).reset();
	$('#archivo').trigger('change'); 
});

$('#modalImportar').on('hidden.bs.modal', function (e) {
	$("#idclientesimp").val(null).trigger('change');
	$("#idproyectosimp").val(null).trigger('change');
    $('.fileinput-filename').html('');
	$('span.fileinput-new').show();
    $('span.fileinput-exists, a.fileinput-exists').hide();
})

$('#subir-archivo').click(function(){
    var formElement = document.getElementById("form_importar_actividades");
    var formData = new FormData(formElement);
    var nombredocumento = $('.fileinput-filename').html();
	console.log($('.fileinput-filename').html());
	if(nombredocumento != ''){
        $.ajax({
    		url : 'reportes/preventivosimportar.php',
    		type : 'POST',
    		data : formData,
    		dataType: 'html',
    		processData: false,  // tell jQuery not to process the data
    		contentType: false,  // tell jQuery not to set contentType
    		beforeSend: function() {
    			$('#overlay').css('display','block');
    		},
    		success : function(data) {
    			$('#overlay').css('display','none');
    			$('#resultado').html(data);
    			tablaincidentes.ajax.reload(null, false);
    		}
        });
    }else{
		notification("¡Advertencia!","Debe seleccionar un archivo","warning");  
	}

});


