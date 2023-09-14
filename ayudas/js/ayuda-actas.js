/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Actas");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Se denomina acta al documento que durante una reunión es escrito por una persona presente en la misma, y a través de la cual se registran los temas que han sido tratados, así como también las conclusiones o acuerdos que han resultado luego de dicha reunión. Un acta, en este sentido, permite certificar y validar lo tratado."+$salto2;
	$ayuda2='Lo habitual es que estos documentos se guarden en un libro de actas, pero en nuestro sistema se guardan de manera electrónica y para ello se creó el módulo de "actas", el cual se alimenta de manera automática cuando las órdenes de trabajo son generadas desde el módulo "Plan de Mantenimiento".'+$salto2;
    $ayuda3='Se trata pues de un recurso formal, que se utiliza para guardar un registro detallado de los trabajos a ser realizados y que se integra con los módulos "plan de mantenimiento" y "preventivos".'+$salto2; 

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayuda3+$ayudafin);
	});

});


