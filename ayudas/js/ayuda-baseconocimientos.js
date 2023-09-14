/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Base de Conocimientos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Se trata de una base de datos centralizada que permite recopilar, organizar, buscar y compartir información y datos, ofreciendo respuestas a los usuarios en forma inmediata. Se alimenta automáticamente a medida que se resuelvan incidentes."+$salto2;
	$ayuda2="El módulo “base de conocimiento” se convierte en una herramienta muy fácil de usar y que permite a los usuarios encontrar respuestas sobre la resolución de los incidentes, dicha información se convierte entonces en una guía para encarar próximos incidentes y de ese modo se minimizan las consultas al departamento de soporte sobre como solucionar estos problemas. Es decir, a través de una base de conocimientos bien estructurada, los usuarios pueden obtener las respuestas que necesitan por sí mismos."+$salto2;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


