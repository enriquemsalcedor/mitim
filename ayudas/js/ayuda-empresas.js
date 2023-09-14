/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Empresas");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Una empresa es una unidad económico-social integrada por elementos humanos, materiales y técnicos, que tiene el objetivo de obtener utilidades a través de su participación en el mercado de bienes y servicios. Es pues una entidad en la que intervienen el capital y el trabajo como factores de producción. Esto quiere decir, que se usan factores de trabajo, como puede ser la mano de obra, para crear productos o servicios."+$salto2;
	$ayuda2="El sistema de mantenimiento puede llevar el control de varias empresas a la vez, Por ejemplo, si eres dueño de varias empresas, el sistema te permite gestionar cada una de ellas. De allí la necesidad de conocer todos los controles existentes para cada empresa por separado. En éste módulo se crean, modifican y eliminan las diferentes empresas. "+$salto2
	;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


