/* para la ayuda */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Sistemas");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayuda0="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+
	"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1+
	"Un sistema es un conjunto de elementos relacionados entre sí que funcionan como un todo, el cambio de una parte del sistema afecta a las demás y con esto, al sistema completo, generando patrones predecibles de comportamiento. En un sistema de gestión de mantenimiento el propósito es optimizar la funcionalidad de los diversos componentes para el cumplimiento de los lineamientos y objetivos establecidos por la organización."+$salto2+
	"En este módulo se nombran y describen los diferentes sistemas sobre los cuales se presta un servicio determinado, así como el departamento responsable del sistema, el proyecto y el cliente al cual pertenecen."+$salto2;
	
	$ayuda0_1=""+
	"<span class='titulo'>"+"PANTALLA DEL MÓDULO"+"</span>"+$salto1+
	"Se dividió la pantalla en siete(7) zonas horizontales para una explicación posterior de cada una de ellas."+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/1pagCompleta.PNG' width='80%' /> "+$salto2
	;
	$ayuda1="<span class='titulo'>"+"Zona 1: ENCABEZADO DE LA PÁGINA"+"</span>"+$salto1+
	"Como en todos los módulos del sistema se va a encontrar con un pequeño elemento gráfico en pantalla que al hacer clic con el botón izquierdo sobre esa imágen muestra un menu lateral izquierdo, el cual contiene todas las opciones de que dispone el sistema."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/entornomenu.png'  /> "+$salto2
	+"<span class='titulo'>"+"Ejemplo del menú lateral"+"</span>"+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/menu-lateral.png'  /> "+$salto2;
	$ayuda1_1="<span class='titulo'>"+"Zona 2: MENÚ PRINCIPAL"+"</span>"+$salto1+
	"Posee las opciones generales de la pantalla de sistemas, a las cuales se pueden acceder haciendo clic encima de cada una de ellas."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/entornobarrahor.png' width='90%'  /> "+$salto2+
	"<span class='titulo'>"+"a) SISTEMAS"+"</span>"+$salto1+
	"Es la primera opción del menú princiapal y permite actualizar los datos que aparecen en la pantalla, se debe pulsar sobre:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mpsistemas.PNG'  />"+$salto2+
	"<span class='titulo'>"+"b) NUEVO SISTEMA"+"</span>"+$salto1+
	"Esta opción de un 'nuevo sistema' permite agregar un sistema con los datos nombre y descripción del sistema, siendo obligatorio el nombre del sistema con tres caracteres o más."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mpnuevosistema.png'/> "+$salto2+
	"<span class='titulo'>"+"FORMULARIO DE NUEVO SISTEMA"+"</span>"+$salto1+
	"En éste formulario introduce los datos y pulsa el botón 'Guardar' o 'Cancelar' según lo que desee hacer."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mpnuevosistemapan.png' width='80%'/> "+$salto2+

	"<span class='titulo'>"+"c) EXPORTAR A EXCEL"+"</span>"+$salto1+
	"Este es otro elemento gráfico en la barra de menu principal el cual permite exportar los datos a la hoja de cálculo excel:"+ $salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mpexportaraexcel.PNG'  />"+$salto2+
    "<span class='titulo'>"+"GRABAR ARCHIVO"+"</span>"+$salto1+
	"La opción muestra la pantalla donde pide el nombre y la ubicación del archivo a generar, que después de llenar la información solicitada y pulsar en el botón guardar, el archivo generado puede ser editado."+ $salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mpexportaraexcelpan2.PNG' width='80%'/> "+$salto2+
	"<span class='titulo'>"+"EDITAR ARCHIVO EN EXCEL"+"</span>"+$salto1+
	"En éste paso buscamos el archivo en la ubicación dada en el paso anterior y lo seleccionamos para visualizar y terminar de adaptar el archivo para su impresión de ser necesario."+ $salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mpexportaraexcelpan.PNG'  width='90%'/>"+$salto2
	;
  
	$ayuda2="<span class='titulo'>"+"d) LIMPIAR COLUMNAS"+"</span>"+$salto1+
	"En la cabecera de cada columna de datos se puede escribir y filtrar por lo escrito,"+
	" al pulsar 'Limpiar Columnas' se restablecen los datos mostrándolos como se mostró en la pantalla del módulo al abrir ésta ayuda, "+
	"por ejemplo, si en la columna nombre se filtra por la palabra 'prueba' la imágen sería como la siguiente:"+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mplimpiarcolumaspan.png' width='80%'/> "+$salto2+
	"y al pulsar 'Limpiar Columnas' se restablecen todos los datos eliminando los filtros:"+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mplimpiarcolumaspan2.png' width='80%'/> "+$salto2;

    $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 3: REGISTROS POR PÁGINA"+"</span>"+$salto1+
	"Permite seleccionar el número de líneas que se mostrarán en la pantalla. En la parte inferior en la zona 7 se muestra cuantas pantallas "+
	" son necesarias para mostrar los datos de todos los sistemas."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/numeroderegistros.png'  /> "+$salto2
	; 
 	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 4: ENCABEZADOS DE LAS COLUMNAS"+"</span>"+$salto1+
	"En un conjunto de datos, como aparecen en la pantalla de sistemas, se pueden interpretar formados de filas y columnas, "+
	"donde cada columna es un dato que se quiere almacenar de 'SISTEMAS' y una fila es el registro de un 'SISTEMA'. "+
	"En cada nombre de columna se puede dar un clic izquierdo y escribir un criterio de búsqueda, que al darle "+
	"a la tecla 'Entrar' aparecen en pantalla los datos de acuerdo a los criterios de búsquedas introducidos."+
	"Por ejemplo tipeando la palabra 'prueba' en la columna 'nombre' podría filtrar algo parecido a:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/mplimpiarcolumaspan.PNG' width='80%'/>"+ $salto2;
 
	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 5: LÍNEAS DE DETALLE O DATOS"+"</span>"+$salto1+
	"Cada línea de detalle es un 'SISTEMA', donde en la primera columna de cada línea o fila tiene sus imágenes gráficas o iconos "+
	" de las acciones que pueden afectar a cada fila. "+
	"Un ejemplo de líneas de detalle es: "+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/detalles.PNG' width='80%'/>"+ $salto2;


   $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 6: BARRA DE DESPLAZAMIENTO HORIZONTAL"+"</span>"+$salto1+
	"De ser necesario por la cantidad de datos a mostrar puede aparecer una barra de desplazamiento horizontal, "+
	" se puede dar la posibilidad de una barra de desplazamiento vertical."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/desplazamiento.png' width='80%' /> "+$salto2
	; 

	$ayuda2=$ayuda2+
	"<span class='titulo'>"+"Zona 7: BOTONES DE DESPLAZAMIENTO POR PÁGINAS"+"</span>"+$salto1+
	"En la parte inferior de la pantalla se encuentran los botones para desplazarse a través de todos los datos."+$salto2+
	"<img class='center-block' src='imagenesAyuda/sistemas/entornodesplazamiento.PNG' />"+$salto2
	;
	$ayuda10="</p></div>";
	//alert($ayuda0+$ayuda0_1+$ayuda1+$ayuda2+$ayuda10);
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayuda0+$ayuda0_1+$ayuda1+$ayuda1_1+$ayuda2+$ayuda10);
	});

});


