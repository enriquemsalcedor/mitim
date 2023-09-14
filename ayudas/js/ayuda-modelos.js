/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Modelos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1;
	$ayuda1='La palabra modelo posee diferentes significados, todo depende del contexto en donde se encuentre. Para el caso que nos ocupa se refiere a un artefacto o dispositivo que se fabrica según un patrón de diseño específico y que pertenece a una marca también específica. Por ejemplo: "el auto que me gusta de la MARCA Chevrolet es el MODELO Optra."'+$salto2;
	$ayuda2="El presente módulo ofrece información detallada y valiosa sobre las características de los equipos relacionadas al modelo, dicha información permite encaminar las tareas de reparación, mantenimiento y sustitución de piezas de manera mucho más eficiente."+$salto2;

	$ayudafin="</p></div>";
   	 /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


