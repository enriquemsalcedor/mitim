/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre el módulo SCAFID");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"Sistema de seguimiento, control, acceso y fiscalización de documentos"+"</span>"+$salto2;
	$ayuda1="Desde el año 2013 La Contraloría de la República de Panamá coloca a disposición del usuario un sistema web para que este pueda hacerle seguimiento, control, acceso y fiscalización a un conjunto de documentos relacionados a contratos con organismos gubernamentales. El SCAFID se convierte entonces en un instrumento de uso obligatorio para el registro de los documentos que tramita la entidad fiscalizadora a nivel nacional. Desde su plataforma tecnológica, el sistema Toolkit le presta al usuario dicha asistencia, sin que sea necesario entrar a las páginas web del gobierno."+$salto2;
	$ayuda2="El módulo registra en su tabla de datos el número de trámite y aunado a ello presenta el número scafid asignado a dicho trámite. De igual forma presenta una información precisa sobre el asunto, la institución y muy importante, el estado en que se encuentra el trámite."+$salto2;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


