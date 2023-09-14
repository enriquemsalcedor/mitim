/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Categorías");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Se denomina categoría a una clase que resulta de una clasificación de personas o cosas según un criterio o jerarquía, un tipo, una condición o una división de algo. Por ejemplo: “Si el año próximo me suben de categoría en mi trabajo, pasaré a ganar más dinero”, “La deportista panameña ganó la medalla de oro en la categoría de hasta 48 kilogramos”."+$salto2;
	$ayuda2="El módulo “Categoría” permite llevar un registro del tipo de proyecto que desarrolla un cliente en específico, de ese modo se clasifican los proyectos por características que sean comunes o afines; por ejemplo, si el proyecto está relacionado a bienes o servicios, si se refiere a trabajos manuales o tecnológicos, si se trata de mantenimiento a instalaciones o construcción de nuevas instalaciones, entre otros."+$salto2;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


