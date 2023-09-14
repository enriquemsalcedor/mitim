/* para la ayuda */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Listas de Correos");	   
	$(function() {
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayuda0="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+
	"<span class='titulo'>"+"GENERAL"+"</span>"+$salto2;
	$ayuda1="Las listas de correo electrónico son una función especial del correo electrónico que permite la distribución de mensajes entre múltiples usuarios de Internet de forma simultánea. En una lista de correo, al enviar un mensaje a la dirección de la lista, este llegará a la dirección de todas las personas inscritas en ella. Dependiendo de la forma en que esté configurada la lista de correo, el receptor podrá tener o no la posibilidad de enviar mensajes."+$salto2;
    $ayuda1=$ayuda1+
	"En ésta sección se permite incluir y mantener actualizado los datos de los usuarios con sus correos electrónicos, de este modo el sistema se comunica con cada uno de los usuarios en las diferentes tareas llevadas a cabo, de ahí la importancia de que  los datos consignados en el se correspondan exactamente a los valores reales."+$salto2
	;
	$ayuda2="<span class='titulo'>"+"MENU LATERAL"+"</span>"+$salto1+
	"En el entorno de trabajo de 'Lista de Correos' se va a encontrar con un pequeño elemento gráfico en pantalla que identifica el mostrar el menu lateral, el cual contiene todas las opciones de que dispone el sistema."+$salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornomenu.png'  /> "+$salto2+
	"<span class='titulo'>"+"MENU HORIZONTAL"+"</span>"+$salto1+
	"Posee las opciones generales de la pantalla de lista de correos, a las cuales se acceden haciendo clic sobre el botón deseado."+$salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornomenuhor.png' width='80%'  /> "+$salto2+
	"<span class='titulo'>"+"1.- LISTA DE CORREOS"+"</span>"+$salto1+
	"Nos muestra las opcion de poder refrescar los datos que aparecen en la pantalla con:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornorefrescar.PNG'  />"+$salto2+
	"<span class='titulo'>"+"2.- NUEVO"+"</span>"+$salto1+
	"Esta opción de nuevo correo permite asignarle a un usuario los datos de un correo y el cargo que ocupa ese usuario, utilizando para ello el elemento gráfico: "+$salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/nuevo.png'  /> "+$salto2+
	"Los datos no pueden estar vacíos ni poseer menos de tres caracteres, todo esto a través de la pantalla:"+ $salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/nuevodatos.png' width='80%'/> "+$salto2+
	"<span class='titulo'>"+"3.- EXPORTAR A EXCEL"+"</span>"+$salto1+
	"Este otro elemento gráfico en la barra de menu horizontal permite exportar los datos a la hoja de cálculo excel:"+ $salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornoaexcel.PNG'  />"+$salto2+

	"La opción muestra la pantalla siguiente, que después de llenar la información solicitada y pulsar en el botón guardar, el archivo generado puede ser editado."+ $salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornoaexcelgrabar.PNG' width='80%'/> "+$salto2

	;

	$ayuda3="<span class='titulo'>"+"4.- LIMPIAR COLUMNAS"+"</span>"+$salto1+
	"Esta opción tiene sentido cuando se a realizado búsquedas por columnas, es decir, al pulsar en el encabezado de una columna, como se muestra en la imagen siguiente y al escribir en su recuadro se filtran los datos usando ese criterio: "+$salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornolimpiarbusquedas.png'  /> "+$salto2+

	"<img class='center-block' src='imagenesAyuda/listacorreos/entornofiltradoporcolumnas.png' width='80%'  /> "+$salto2+
	"<span class='titulo'>"+"ELIMINAR REGISTRO"+"</span>"+$salto1+
	"Existe en la pantalla un icono para <strong>eliminar</strong> los datos de la fila seleccionada:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornoeliminar.PNG'  /> "+$salto2+

	"Que al seleccionarlo muestra la pantalla de confirmación siguiente:"+ $salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornoeliminarlinea.PNG' width='80%'/>"+ $salto2+
	"<span class='titulo'>"+"BOTONES DE DESPLAZAMIENTO"+"</span>"+$salto1+
	"En la parte inferior de la pantalla se encuentran los botones para desplazarse a través de todos los datos."+$salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/entornodesplazamiento.PNG' />"+$salto2+
	"<span class='titulo'>"+"MODIFICAR REGISTRO"+"</span>"+$salto1+
	"Una opción que no está visible es la de modificar los datos de una persona (una fila de la tabla), eso se logra dándole <strong>doble clic</strong> sobre la fila deseada, al igual que la opción <strong>nuevo correo</strong> "+
	"puede salir una pantalla emergente mostrando un mensaje de error si los tres datos no han sido llenados o alguno de ellos tiene menos de tres caracteres"+$salto2+
	"<img class='center-block' src='imagenesAyuda/listacorreos/editar.PNG' width='80%'/>"+ $salto2

	$ayuda10="</p></div>";
	$('#myModal .modal-body').append($ayuda0+$ayuda1+$ayuda2+$ayuda3+$ayuda10);
	});
	/* #d9ebf9 */
});


