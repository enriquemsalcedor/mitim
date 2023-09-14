function abrirdialogImportar(){
	$("#form_importar_activos")[0].reset();
	$("#resultado").html('');
	$('#modalImportar').modal('show');
}

$('#descargarplantilla').click(function(){
    window.open('reportes/descargarplantillapostventas.php');
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
	$('#form_importar_activos').get(0).reset();
	$('#archivo').trigger('change'); 
});

$('#modalImportar').on('hidden.bs.modal', function (e) { 
    $('.fileinput-filename').html('');
	$('span.fileinput-new').show();
    $('span.fileinput-exists, a.fileinput-exists').hide();
})

$('#subir-archivo').click(function(){
    var formElement = document.getElementById("form_importar_activos");
    var formData = new FormData(formElement);
	
	var nombredocumento = $('.fileinput-filename').html();
	if(nombredocumento != ''){
		$.ajax({
			url : 'reportes/postventasimportar.php',
			type : 'POST',
			data : formData,
			dataType: 'html',
			processData: false,  // tell jQuery not to process the data
			contentType: false,  // tell jQuery not to set contentType
			beforeSend: function() {
				$(".loader-maxia").show();
			},
			success : function(data) {
				$(".loader-maxia").hide();
				$('#resultado').html(data);
				tablaincidentes.ajax.reload(null, false);
			}
		});
	}else{
		notification("¡Error!","Debe seleccionar un archivo","error");
	} 
});


