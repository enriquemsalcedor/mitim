/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre tipos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1='La palabra "tipo" es un término que hace referencia a una clasificación, discriminación o diferenciación de diversos aspectos que forman parte de un todo. La utilización más popular de la palabra es para referir a un modelo o ejemplar.'+$salto2;
	$ayuda2='El módulo "Tipo" es empleado para identificar la clase o variedad de equipos incluidos en el sistema a los que se les aplicarán los diferentes planes de mantenimiento. Se usa para hablar por ejemplo, de tipo tecnología, tipo médico, tipo limpieza, tipo construcción, entre otros. Guarda una estrecha relación con los módulos "equipos" y "subtipos".'+$salto2;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


