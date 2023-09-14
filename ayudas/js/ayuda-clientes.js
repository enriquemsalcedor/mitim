/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Clientes");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="El término “cliente” es un término que puede tener diferentes significados, de acuerdo a la perspectiva en la que se analice. En economía, por ejemplo, el concepto permite referirse a la persona que accede a un producto o servicio a partir de un pago. Existen clientes constantes, que acceden a dicho bien de forma asidua, u ocasionales, aquellos que lo hacen en un determinado momento o por una necesidad puntual."+$salto2;
	$ayuda2="En este contexto, el término es utilizado como sinónimo de comprador (la persona que compra el producto), usuario (la persona que usa el servicio) o consumidor (quien consume un producto o servicio). Este módulo permite identificar cuales son los clientes que pertenecen a cada empresa y gestionar la información que se tiene sobre cada uno de ellos de forma óptima, de este modo, si eres el dueño de una empresa, puedes ingresar al sistema cuales son los clientes con quienes mantienes relación comercial."+$salto2
	;

	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


