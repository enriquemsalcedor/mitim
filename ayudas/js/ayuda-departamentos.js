/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre el maestro Departamentos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Los departamentos de una empresa son las partes en que se encuentra dividida la empresa. Por ejemplo el departamento de recursos humanos, departamento comercial, departamento de administración, departamento de producción o departamento de compras. A medida que la empresa crece, su forma de organizarse va cambiando, creando nuevos departamentos o fusionando los ya existentes."+$salto2;
	$ayuda2="La creación de departamentos o departamentalización busca la forma de agrupar las distintas tareas con el objetivo de obtener el mejor resultado posible. Este módulo representa el espacio necesario para crear y registrar dentro del sistema los diferentes departamentos o grupos que conforman una determinada empresa. Es importante resaltar que los departamentos varían en cantidad y denominación dependiendo de las necesidades propias de cada empresa."+$salto2
	;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


