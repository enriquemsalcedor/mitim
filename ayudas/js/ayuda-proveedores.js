/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Proveedores");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Los proveedores son aquellas empresas que abastecen a otras con bienes o servicios necesarios para el correcto funcionamiento del negocio. Por definición, el proveedor se encarga de abastecer a terceros de distintos recursos con los que él cuenta. De manera profesional otorga a terceros dichos recursos para el desarrollo de sus actividades comerciales o económicas."+$salto2;
	$ayuda2='En ese orden de ideas, el módulo de "proveedores" permite guardar un registro de las empresas que suministran los diferentes bienes y servicios así como los contratos que se generan con cada una de ellas y que resultan de dicha interacción comercial. Este módulo se integra con el módulo de "Equipos".'+$salto2;

	$ayudafin="</p></div>";
   	 /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


