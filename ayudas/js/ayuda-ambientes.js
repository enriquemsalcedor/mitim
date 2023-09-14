/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Ambientes");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1;
	$ayuda1="El término ambiente se refiere a los espacios modificados por el ser humano que proporcionan el escenario para las actividades diarias. Al igual que una infraestructura, el ambiente presenta el conjunto de elementos o servicios que están considerados como necesarios para que una organización pueda funcionar o bien para que una actividad se desarrolle efectivamente."+$salto2;
	$ayuda2='El módulo "ambiente" se utiliza para identificar área donde han sido emplazados los diferentes equipos registrados en el sistema, para ello se especifican datos tales como: empresa, cliente, departamento, entre otros. Se integra principalmente con los módulos de "equipos" y "subambientes".'+$salto2
	;

	$ayudafin="</p></div>";
   	 /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


