/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Reuniones");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="La gestión de reuniones es un mecanismo del cual dispone un líder o conductor de una reunión con el fin de garantizar una dinámica productiva y eficaz antes, durante y posterior al desarrollo de la misma. Entre sus bondades se reconocen la eficiente administración del tiempo, los correctos procesos de convocatoria, la definición de asistentes y el esclarecimiento de los propósitos de la reunión, a fin de evitar confusiones posteriores."+$salto2;
	$ayuda2="El presente módulo tiene en si mismo una funcionalidad excepcional, por cuanto permite coordinar uno de los procesos de mayor relevancia en la administración del recurso humano: las reuniones de personal. Entre sus características destacan la posibilidad convocar la reunión, definir los puntos a tratar, participantes, responsable, lugar, fecha y hora, así como los pasos que pueden ejecutarse posterior a la reunión."+$salto2;
    $ayuda3="Una de las particularidades de este módulo, es que permite notificar al personal que participará en las reuniones mediante un correo electrónico y de presentarse cambios relacionados a cualquier aspecto de la reunión, el sistema envía una segunda notificación la cual llega por la misma vía."+$salto2;
    
	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayuda3+$ayudafin);
	});

});


