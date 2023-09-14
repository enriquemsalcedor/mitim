/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Flujos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="La definición de Flujos se expresa mediante anotaciones y procedimientos de aquellos procesos asociados a la gestión de cualquier unidad funcional en la organización. En Mantenimiento, estos flujos identifican y definen responsabilidades, actividades, roles y funciones de acuerdo a normas internacionales y propias del cliente. Esto permite identificar los problemas y oportunidades de mejora, eliminando procesos redundantes que no generan valor y así, finalmente, estandarizar las actividades en el mantenimiento."+$salto2;
	$ayuda2="El módulo de flujos del proceso tiene que ver con la manera como se atacan los diferentes tipos de solicitudes, ya sean éstas para trabajos de emergencias, rutinas, inspecciones, etc. y en el cual estarán involucrados los tipos de documentos ya sean solicitudes de trabajos, que posteriormente se convertirán en órdenes de trabajos por mantenimiento correctivo o preventivo. Es decir, flujo es una guía que señala la ruta a seguir de cualquier solicitud o documento dentro del sistema."+$salto2
	;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


