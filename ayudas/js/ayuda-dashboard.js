/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre el Dashboard");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Un indicador de gestión, es una medida del nivel del rendimiento de un proceso que debe informar, controlar, evaluar y por último ayudar a que se tomen decisiones. El valor del indicador está directamente relacionado con un objetivo fijado previamente y normalmente se expresa en valores porcentuales."+$salto2;
	$ayuda2="En consecuencia, el Dashboard es una representación gráfica de los principales indicadores de gestión que intervienen en la consecución de los objetivos de una estrategia previamente definida. En este módulo, por ejemplo, se muestran de manera gráfica las órdenes de trabajo que existen actualmente en el sistema, desglosándolas en preventivas, finalizadas, pendientes, correctivas, casos cerrados y abiertos, permitiendo así evaluar de manera rápida y oportuna los avances en dichas áreas."+$salto2;
    $ayuda3="Una característica versátil del módulo es que se puede modificar constantemente dependiendo de las necesidades del cliente y del levantamiento continuo de información."+$salto2+$salto2+$salto2+$salto2+$salto2;
	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayuda3+$ayudafin);
	});

});


