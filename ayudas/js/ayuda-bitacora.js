/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Bitácora");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$lista="<i class='fa fa-check'></i>";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Una bitácora es una grabación secuencial en la base de datos del sistema de todos los acontecimientos, es decir, los eventos o las acciones que afectan a un proceso particular. Tales eventos pueden ser; cambio de los datos en un módulo, acceso a los diferentes módulos a que tiene derecho como usuario autorizado, entre otros. De esta forma constituye una evidencia del comportamiento del sistema y del usuario dentro del mismo."+$salto2;
	$ayuda2="Generalmente los acontecimientos vienen anotados con el momento exacto o data (fecha, hora, minuto, segundo) en el que ocurrió, lo que permite analizar paso a paso la actividad presentada permitiendo, de esta forma, realizar un seguimiento casi exacto de dicho acontecimiento, además, designar una o más categorizaciones del acontecimiento registrado. Es frecuente usar categorías distintas para distinguir la importancia del acontecimiento estableciendo distintos niveles de registro los cuales suelen ser: eliminación de datos, modificación, advertencia y error."+$salto2;
    $ayuda3="El uso de bitácoras puede servir para: "+$salto1+$lista+"- Análisis forense."+$salto1+$lista+"- Detección de intrusos."+$salto1+$lista+"- Depuración de errores."+$salto1+$lista+"- Motorización."+$salto1+$lista+"- Cumplir con las leyes establecidas."+$salto1+$lista+"- Auditoría.";
	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayuda3+$ayudafin);
	});

});


