/* para la ayuda */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Base de Conocimientos");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayuda0="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+
	"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1+
	"Las bases de conocimiento se han clasificado en dos grandes grupos: Bases de conocimiento legibles por máquinas, diseñadas para almacenar conocimiento en una forma legible por el computador y"+
    " la que nos ocupa, bases de conocimiento legibles por humanos las cuales están están diseñadas para permitir a las personas acceder al conocimiento que ellas contienen, principalmente para propósitos de solución de problemas"+
    " que ya anteriormente han sucedido, estas son comúnmente usadas para obtener y manejar conocimiento explicito de las organizaciones. El principal beneficio que proveen las bases de conocimiento es proporcionar medios de "+
	"descubrir soluciones a problemas ya resueltos, los cuales podrían ser aplicados como base a otros problemas dentro o fuera de la misma área de conocimiento."+
	$salto2
	;
	$ayuda0_1=""+
	"<span class='titulo'>"+"PANTALLA DEL MÓDULO"+"</span>"+$salto1+
	"<br>En éste módulo se observa un listado de todas las 'incidencias' resueltas disponibles y un cajón de texto para filtrar las de interés según el problema que se tiene. "+
	"Se dividió la pantalla en siete(7) zonas horizontales para una explicación posterior de cada una de ellas."+
	$salto2+
	/*"<a href='imagenesAyuda/basedeconocimientos/pantallaprincipal.JPG'><img src='imagenesAyuda/basedeconocimientos/pantallaprincipal.JPG' width='80' height='80'></a>"+*/
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/pantallaprincipal.JPG' width='90%' /> "+$salto2
	;
	
	
	$ayuda1="<span class='titulo'>"+"Zona 1: ENCABEZADO DE LA PÁGINA"+"</span>"+$salto1+
	"Existe un pequeño elemento gráfico en pantalla que al hacer clic con el botón izquierdo sobre esa imágen muestra un menu lateral izquierdo, el cual contiene todas las opciones de que dispone el sistema."+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/iconomenu.png'  /> "+$salto2
	+"<span class='titulo'>"+"Ejemplo del menú lateral"+"</span>"+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/menu-lateral.png'  /> "+$salto2;
	
	$ayuda1_1="<span class='titulo'>"+"Zona 2: MENÚ PRINCIPAL"+"</span>"+$salto1+
	"Posee las opciones generales de la pantalla de 'base de conocimientos', a las cuales se pueden acceder haciendo clic encima de cada una de ellas."+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/menuprincipal.JPG' width='80%'  /> "+$salto2+
	"<span class='titulo'>"+"a) BASE DE CONOCIMIENTOS"+"</span>"+$salto1+
	"Es la primera opción del menú principal y permite actualizar los datos que aparecen en la pantalla, se debe pulsar sobre:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/basedeconocimiento.JPG'  />"+$salto2;
	$ayuda2="<span class='titulo'>"+"b) LIMPIAR COLUMNAS"+"</span>"+$salto1+
	"En la cabecera de cada columna de datos se puede escribir y filtrar por lo escrito,"+
	" al pulsar 'Limpiar Columnas' se restablecen los datos mostrándolos tal cual lo mostró en la pantalla del módulo al abrir ésta ayuda, "+
	" por ejemplo, si en la columna nombre se filtra por la palabra 'cloro' la imágen sería como la siguiente:"+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/limpiarcolumnas.JPG' /> "+$salto2+
	"y al pulsar 'Limpiar Columnas' se restablecen todos los datos eliminando los filtros:"+
	$salto2+
	
	
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/filtrocolumnas.JPG' width='80%'/> "+$salto2;
    $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 3: BÚSQUEDA EN LA BASE DE CONOCIMIENTOS"+"</span>"+$salto1+
	"Se tienen dos elementos en ésta zona, el primer elemento permite seleccionar el número de líneas que se mostrarán en la pantalla. En la parte inferior en la zona 7 se muestra cuantas pantallas "+
	" son necesarias para mostrar los datos de todos las filas de datos de la 'base de conocimientos'."+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/numeroregistros.png'  /> "+$salto2+
	"<br>El segundo elemento es donde se teclea la palabra la cual será buscada en la 'Base de Conocimientos' para que filtre casos que pueden ser de ayuda. "+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/filtroconocimiento.JPG' width='80%'/>"+ $salto2;
	; 
	
 	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 4: ENCABEZADOS DE LAS COLUMNAS"+"</span>"+$salto1+
	"En el conjunto de datos que aparecen en la pantalla de 'Base de Conocimientos', se pueden ver que están formados por filas y columnas, "+
	"donde cada columna es un dato que está almacenado en la 'Base de Conocimientos' y una fila es el registro de la misma. "+
	"En cada nombre de columna se puede dar un clic izquierdo y escribir un criterio de búsqueda, que al darle "+
	"a la tecla 'Entrar' aparecen en pantalla los datos de acuerdo a los criterios de búsquedas introducidos."+
	"Por ejemplo tipeando la palabra 'cloro' en la columna 'titulo' podría filtrar algo parecido a:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/filtrocolumnas.JPG' width='80%'/>"+ $salto2;
 
 
 
 
	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 5: LÍNEAS O FILAS DE DATOS"+"</span>"+$salto1+
	"Cada línea de detalle es un elemento de la 'Base de Conocimientos', donde existe una incidencia a través de la cual se filtrará "+
	" para la búsqueda de casos semejantes al colocado en la barra de búsqueda. "+
	"Un ejemplo de líneas de detalle es: "+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/detalle.PNG' width='80%'/>"+ $salto2+
	"En cualquier punto de una fila que se pulse doble clic permite mostrar los datos del elemento 'Base de Conocimientos' seleccionado, es decir muestra un formulario "+
	"con los datos de la fila donde se hizo el doble clic para analizar los datos. "
	+$salto2+
	"Al pulsar doble clic en cualquier fila mostraría un formulario como el siguiente:"
	+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/editar.jpg' width='90%'/>"+ $salto2;

    $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 6: BARRA DE DESPLAZAMIENTO HORIZONTAL"+"</span>"+$salto1+
	"De ser necesario por la cantidad de datos a mostrar puede aparecer una barra de desplazamiento horizontal, "+
	" se puede dar la posibilidad de una barra de desplazamiento vertical."+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/desplazamiento.png' width='80%' /> "+$salto2
	; 

	$ayuda2=$ayuda2+
	"<span class='titulo'>"+"Zona 7: BOTONES DE DESPLAZAMIENTO POR PÁGINAS"+"</span>"+$salto1+
	"En la parte inferior de la pantalla se encuentran los botones para desplazarse a través de todos los datos."+$salto2+
	"<img class='center-block' src='imagenesAyuda/basedeconocimientos/entornodesplazamiento.PNG' />"+$salto2
	;
	$ayuda10="</p></div>";
	//alert($ayuda0+$ayuda0_1+$ayuda1+$ayuda2+$ayuda10);
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayuda0+$ayuda0_1+$ayuda1+$ayuda1_1+$ayuda2+$ayuda10);
	});

});


