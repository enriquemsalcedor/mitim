/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Entregables");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="El término entregable es utilizado en la gestión de proyectos para describir un objeto, tangible o intangible, como resultado final de un proyecto, destinado a satisfacer las necesidades de un cliente, ya sea interno o externo a la organización. El entregable es el resultado en sí, pudiendo ser un reporte, un documento, un paquete de trabajo, la actualización de un servidor o cualquier otra obra que sea el resultado del cumplimiento de un proyecto ejecutado en su totalidad."+$salto2;
	$ayuda2="El módulo muestra entonces todos los trabajos padres o productos finales por los cuales se recibirá un pago; por Ejemplo, El entregable es: mantenimiento de las paredes del hospital, y las actividades o tareas que se deben realizar para lograr ese entregable son: trasladar diariamente al personal, comprar la pintura, reparar las fisuras en las paredes, armar los andamios, pintar, entre otras. El número de actividades para alcanzar un entregable será variable y dependerá de la magnitud del trabajo final."+$salto2;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


