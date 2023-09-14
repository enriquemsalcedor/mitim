/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Subambientes");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1='Mientras que el módulo "ambiente" identifica la ubicación del equipo de manera general, el módulo "subambiente" permite focalizar su ubicación de manera específica, brindando así una información exacta y precisa sobre la localización de los equipos. Tomemos el siguiente ejemplo: Ambiente: Sala de emergencia del hospital central, Subambiente: área de observación femenina.'+$salto2;
	$ayuda2=""+$salto2;

	$ayudafin="</p></div>";
   	 /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


