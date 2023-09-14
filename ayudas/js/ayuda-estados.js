/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Estados");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Un estado puede definirse como la situación o modo de estar de una persona cosa o proceso, destacando que su situación puede ser temporal pues su condición está sujeta a cambios. Puede considerarse incluso como los diferentes índices para monitorear el progreso alcanzado, a través de pruebas y comparaciones, en un lapso de tiempo determinado, constatando, sin subjetivismo, si se ha mejorado o no respecto a una situación inicial."+$salto2;
	$ayuda2="Este módulo contiene el registro de los diferentes estados por los que puede pasar un proceso, pasando por estados iniciales, medios y finales. Entre ellos: correcciones, prueba, pendiente, documentación, finalizado, entre otros. Permite además, crear nuevos estados que vayan surgiendo de acuerdo a la dinámica de los procesos, así como desactivar e incluso eliminar aquellos que ya no sean necesarios."+$salto2;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


