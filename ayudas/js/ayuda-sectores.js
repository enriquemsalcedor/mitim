/* para la ayuda */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Sectores");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayuda0="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+
	"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1+
	"En la pantalla inicial se observa un listado de todos los 'sectores' disponibles y botones para su mantenimiento: incluir nuevo 'sector', "+
	" eliminación y modificación de los 'sectores' existentes."+$salto2
	;
	$ayuda0_1=""+
	"<span class='titulo'>"+"PANTALLA DEL MÓDULO"+"</span>"+$salto1+
	"Se dividió la pantalla en seis(6) zonas horizontales para una explicación posterior de cada una de ellas."+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/pantallaprincipal.PNG' width='80%' /> "+$salto2
	;
	$ayuda1="<span class='titulo'>"+"Zona 1: ENCABEZADO DE LA PÁGINA"+"</span>"+$salto1+
	"Como en todos los módulos del 'Sistema de Mantenimiento' se va a encontrar con un pequeño elemento gráfico en pantalla que al hacer clic con el botón izquierdo sobre esa imágen muestra un menu lateral izquierdo, el cual contiene todas las opciones de que dispone el sistema."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/iconomenu.png'  /> "+$salto2
	+"<span class='titulo'>"+"Ejemplo del menú lateral"+"</span>"+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/menu-lateral.png'  /> "+$salto2;
	
	$ayuda1_1="<span class='titulo'>"+"Zona 2: MENÚ PRINCIPAL"+"</span>"+$salto1+
	"Posee las opciones generales de la pantalla de 'sectores', a las cuales se pueden acceder haciendo clic encima de cada una de ellas."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/menuprincipal.png' width='90%'  /> "+$salto2+
	"<span class='titulo'>"+"a) SECTORES"+"</span>"+$salto1+
	"Es la primera opción del menú principal y permite actualizar los datos que aparecen en la pantalla, se debe pulsar sobre:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/sectores.PNG'  />"+$salto2+
	"<span class='titulo'>"+"b) NUEVO SECTOR"+"</span>"+$salto1+
	"Esta opción de un nuevo 'sector' permite agregar un 'sector' con los datos nombre y descripción del sector, siendo obligatorio el nombre del sector con tres caracteres o más."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/nuevo.png'/> "+$salto2+
	"<span class='titulo'>"+"FORMULARIO DE NUEVO SECTOR"+"</span>"+$salto1+
	"En éste formulario introduce los datos y pulsa el botón 'Guardar' o 'Cancelar' según lo que desee hacer."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/nuevosector.png' width='80%'/> "+$salto2+
	"<span class='titulo'>"+"c) EXPORTAR A EXCEL"+"</span>"+$salto1+
	"Este es otro elemento gráfico en la barra de menu principal el cual permite exportar los datos a la hoja de cálculo excel:"+ $salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/exportaraexcel.PNG'  />"+$salto2+
	
    "<span class='titulo'>"+"GRABAR ARCHIVO"+"</span>"+$salto1+
	"La opción muestra la pantalla donde pide el nombre y la ubicación del archivo a generar, que después de llenar la información solicitada y pulsar en el botón guardar, el archivo generado puede ser editado."+ $salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/grabarexcel.PNG' width='80%'/> "+$salto2+
	"<span class='titulo'>"+"EDITAR ARCHIVO EN EXCEL"+"</span>"+$salto1+
	"En éste paso buscamos el archivo en la ubicación dada en el paso anterior y lo seleccionamos para visualizar y terminar de adaptar el archivo para su impresión de ser necesario."+ $salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/enexcel.PNG'  width='90%'/>"+$salto2
	; 
	$ayuda2="<span class='titulo'>"+"d) LIMPIAR COLUMNAS"+"</span>"+$salto1+
	"En la cabecera de cada columna de datos se puede escribir y filtrar por lo escrito,"+
	" al pulsar 'Limpiar Columnas' se restablecen los datos mostrándolos tal cual lo mostró en la pantalla del módulo al abrir ésta ayuda, "+
	" por ejemplo, si en la columna nombre se filtra por la palabra 'prueba' la imágen sería como la siguiente:"+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/limpiarcolumnas.png' /> "+$salto2+
	"y al pulsar 'Limpiar Columnas' se restablecen todos los datos eliminando los filtros:"+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/filtro.png' width='80%'/> "+$salto2;
    $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 3: REGISTROS POR PÁGINA"+"</span>"+$salto1+
	"Permite seleccionar el número de líneas que se mostrarán en la pantalla. En la parte inferior en la zona 6 se muestra cuantas pantallas "+
	" son necesarias para mostrar los datos de todos los 'sectores'."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/numeroderegistros.png'  /> "+$salto2
	; 
	
 	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 4: ENCABEZADOS DE LAS COLUMNAS"+"</span>"+$salto1+
	"En un conjunto de datos, como aparecen en la pantalla de 'sectores', se pueden ver formados de filas y columnas, "+
	"donde cada columna es un dato que se quiere almacenar del 'sector' y una fila es el registro de un 'SECTOR'. "+
	"En cada nombre de columna se puede dar un clic izquierdo y escribir un criterio de búsqueda, que al darle "+
	"a la tecla 'Entrar' aparecen en pantalla los datos de acuerdo a los criterios de búsquedas introducidos."+
	"Por ejemplo tipeando la palabra 'prueba' en la columna 'nombre' podría filtrar algo parecido a:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/limpiarcolumas.PNG' width='80%'/>"+ $salto2;
 
	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 5: LÍNEAS DE DETALLE O DATOS"+"</span>"+$salto1+
	"Cada línea de detalle es un 'SECTOR', donde en la primera columna de cada línea o fila tiene sus imágenes gráficas o iconos "+
	" de las acciones que pueden afectar a cada fila. "+
	"Un ejemplo de líneas de detalle es: "+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/detalles.PNG' width='80%'/>"+ $salto2;


  /* $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 6: BARRA DE DESPLAZAMIENTO HORIZONTAL"+"</span>"+$salto1+
	"De ser necesario por la cantidad de datos a mostrar puede aparecer una barra de desplazamiento horizontal, "+
	" se puede dar la posibilidad de una barra de desplazamiento vertical."+$salto2+
	"<img class='center-block' src='imagenesAyuda/servicios/desplazamiento.png' width='80%' /> "+$salto2
	; */

	$ayuda2=$ayuda2+
	"<span class='titulo'>"+"Zona 6: BOTONES DE DESPLAZAMIENTO POR PÁGINAS"+"</span>"+$salto1+
	"En la parte inferior de la pantalla se encuentran los botones para desplazarse a través de todos los datos."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sectores/entornodesplazamiento.PNG' />"+$salto2
	;
	$ayuda10="</p></div>";
	//alert($ayuda0+$ayuda0_1+$ayuda1+$ayuda2+$ayuda10);
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayuda0+$ayuda0_1+$ayuda1+$ayuda1_1+$ayuda2+$ayuda10);
	});

});


