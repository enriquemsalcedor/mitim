/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Niveles");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$lista="<i class='fa fa-check'></i>";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="El sistema de mantenimiento tiene muchas funciones, entre ellas: "+$salto1+$lista+"- Consulta de datos."+$salto1+$lista+"- Modificación de datos."+$salto1+$lista+"- Eliminación de datos."+$salto1+$lista+"- Alta/Modificación de usuarios."+$salto1+$lista+"- Eliminación de usuarios, etc..";

	
    $ayuda2="Para para controlar quien puede realizar alguna actividad se le asigna un nivel de identifiación a cada usuario en particular."+$salto2;
	$ayuda3="Por ejemplo, algunos usuarios podrían tener un nivel 1, correspondiente a los administradores, otros con el nivel 2, correspondiente a los operadores, y otros con el nivel 3 los usuarios comunes o invitados. El nivel de Administradores posee todos los privilegios, el nivel Operadores está autorizado sólo a consultar y modificar datos, y el nivel Invitados sólo tiene permiso para consultar datos. Según el nivel asignado a cada usuario podría decírsele al sistema qué permisos tiene."+$salto2;
	
	$ayuda4="Esto lo permite la utilización de grupos de usuarios según el nivel. El sistema reconoce los privilegios de cada usuario dependiendo del grupo al que pertenezca (Gerentes, Ingenieros, Administrador, etc.).";
	
	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayuda3+$ayuda4+$ayudafin);
	});

});


