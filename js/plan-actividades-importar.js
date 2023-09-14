//	FORMULARIO INCIDENTE NUEVO //
var dialogImportarActividades = $( "#dialog-form-importar-actividades" ).dialog({
	width: '72%', 
	maxWidth: 600,
	height: 'auto',
	modal: true,
	fluid: true,
	resizable: false,
	autoOpen: false
});

function abrirdialogImportar(){
	$("#form_importar_actividades")[0].reset();
	$("#resultado").html('');
	dialogImportarActividades.dialog( "open" );
}

$('#descargarplantilla').click(function(){
    window.open('controller/descargarplantillaplan.php');
});

$('#subir-archivo').click(function(){
    var formElement = document.getElementById("form_importar_actividades");
    var formData = new FormData(formElement);

    $.ajax({
		url : 'controller/planactividadesback.php?oper=importaractividades',
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
			tablactividades.ajax.reload(null, false);
		}
});

});


