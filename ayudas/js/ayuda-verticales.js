/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Verticales");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="La gestión vertical es un modelo que describe un estilo de propiedad y control. Las compañías integradas verticalmente están unidas por una jerarquía y comparten un mismo dueño. Generalmente, los miembros de esta jerarquía desarrollan tareas diferentes que se combinan a través de la sinergia para satisfacer una necesidad común, todo ello traducido en la búsqueda tanto de eficacia como de mayores utilidades. El ejemplo clásico de la gestión vertical es el de las empresas petroleras: una misma empresa puede reunir bajo su control tareas tan disímiles como la exploración, la perforación, producción, transporte, refinación, comercialización, distribución comercial y venta al detalle de los productos que procesa."+$salto2;
	$ayuda2='El módulo “verticales” muestra las áreas o campos de acción en los que la empresa Maxia desarrolla los diferentes proyectos para satisfacer las necesidades de sus clientes; por ejemplo: educación, aguas, salud, seguridad, control de tráfico, entre otras.'+$salto2
	;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


