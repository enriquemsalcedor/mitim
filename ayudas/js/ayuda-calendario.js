/* para la ayuda del dashboard */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre calendario");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayudainicio="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Los calendarios son formas visuales de hacer concreto el paso del tiempo y además, sirven para transformarlo en algo específico y visible. El calendario sirve principalmente para permitir la mejor organización de las horas, días y meses que se suceden continuamente."+$salto2;
	$ayuda2='En el caso específico del módulo "calendario" este puede ser considerado como una cuenta sistematizada del transcurso del tiempo, utilizado para la organización cronológica de actividades. Dentro del sistema representa una gran herramienta de trabajo por cuanto refleja todas las actividades, incidentes, órdenes de trabajo y reuniones programadas para cada día de la semana, mes y año.'+$salto2;
    $ayuda3='Este módulo se encuentra integrado a los módulos "Plan de Mantenimiento", "incidentes", "Preventivos" y "gestión de reuniones".'+$salto2;
    
	$ayudafin="</p></div>";
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayudainicio+$ayuda1+$ayuda2+$ayuda3+$ayudafin);
	});

});


