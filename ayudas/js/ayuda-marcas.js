/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Marcas");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1;
	$ayuda1='Una marca es una identificación comercial primordial y/o el conjunto de varios identificadores con los que se relaciona y ofrece un producto o servicio en el mercado. Frecuentemente está vinculado al derecho exclusivo de usar una palabra, frase, imagen o símbolo. La marca, en este caso, es aquello que identifica a lo que se ofrece en el mercado. Por ejemplo: “Mi marca preferida de automóviles es Renault”.'+$salto2;
	$ayuda2="El presente módulo permite identificar la marca de los equipos ingresados al sistema, siendo este un aspecto muy importante para definir la frecuencia del mantenimiento con la que serán tratados dichos equipos, pues dependiendo de las marcas se pueden establecer parámetros de calidad, eficiencia y durabilidad."+$salto2;

	$ayudafin="</p></div>";
   	 /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayudafin);
	});

});


