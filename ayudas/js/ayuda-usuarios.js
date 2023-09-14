/* para la ayuda */
$(document).ready(function () {
	$('#ayu').click(function(){
		$('#myModal').modal('show');
	});	 
	$("#myModalLabel").text("Ayuda sobre Usuarios");	   
	$(function() {
	$elcolor="#d9ebf9";
	$salto1="<br />";
	$salto2="<br /><br />";
	$ayuda0="<div id='especial'><p style='line-height: 1.2; font-size: 1em'>"+
	"<span class='titulo'>"+"GENERAL"+"</span>"+$salto1+
	"Un usuario, en el término más sencillo, es quien usa ordinariamente algo. Hace mención a la persona que utiliza algún tipo de objeto o que es destinataria de un servicio, ya sea privado o público."+$salto2+
	"Los usuarios del sistema de mantenimiento pueden obtener seguridad, acceso al sistema, administración de recursos, etc., dichos usuarios deberán identificarse y para ello necesitan una cuenta (una cuenta de usuario) y una clave, en la mayoría de los casos asociada a una contraseña. Los usuarios utilizan una interfaz de usuario para acceder al sistema, el proceso de identificación es conocido como identificación de usuario o acceso del usuario al sistema (del inglés: 'login')."+$salto2
	;
	$ayuda0_1=""+
	"<span class='titulo'>"+"PANTALLA DEL MÓDULO"+"</span>"+$salto1+
	"Se dividió la pantalla en siete(7) zonas horizontales para una explicación posterior de cada una de ellas."+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/pantallaprincipal.PNG' width='80%' /> "+$salto2
	;
	
	
	$ayuda1="<span class='titulo'>"+"Zona 1: ENCABEZADO DE LA PÁGINA"+"</span>"+$salto1+
	"En el encabezado de la página se va a encontrar con un pequeño elemento gráfico en pantalla que al hacer clic con el botón izquierdo sobre esa imágen muestra un menu lateral izquierdo, el cual contiene todas las opciones de que dispone el sistema."+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/entornomenu.png'  /> "+$salto2
	+"<span class='titulo'>"+"Ejemplo del menú lateral"+"</span>"+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/menu-lateral.png' width='80%' /> "+$salto2;
	
	$ayuda1_1="<span class='titulo'>"+"Zona 2: MENÚ PRINCIPAL"+"</span>"+$salto1+
	"Posee las opciones generales de la pantalla de 'usuarios', a las cuales se pueden acceder haciendo clic encima de cada una de ellas."+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/menu-principal.png' width='80%'  /> "+$salto2+
	"<span class='titulo'>"+"a) USUARIOS"+"</span>"+$salto1+
	"Es la primera opción del menú principal y permite actualizar los datos que aparecen en la pantalla, "+
	" quiere decir que busca los datos guardados y los carga en la rejilla que está en pantalla, para ello se debe pulsar sobre:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/usuarios.PNG'  />"+$salto2+
	"<span class='titulo'>"+"b) NUEVO"+"</span>"+$salto1+
	"Esta opción de un nuevo 'usuario' permite agregar un 'usuario' mostrando para ello un formulario de carga de datos "+
	"donde es obligatorio algunos de ellos (los que tienen el símbolo asterisco) para el buen funcionamiento del sistema, "+
	" ellos son: usuario, clave, nombre, correo, proyectos y nivel."+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/usuarionuevo.png' width='80%'/> "+$salto2
	;	
	$ayuda2="<span class='titulo'>"+"c) LIMPIAR COLUMNAS"+"</span>"+$salto1+
	"En la cabecera de cada columna de datos se puede escribir y filtrar por lo escrito,"+
	" al pulsar 'Limpiar Columnas' se restablecen los datos mostrándolos tal cual lo mostró en la pantalla del módulo al abrir ésta ayuda, "+
	" por ejemplo, si en la columna nombre se filtra por la palabra 'jer' la imágen sería como la siguiente:"+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/filtro-columnas.png' /> "+$salto2+
	"y al pulsar 'Limpiar Columnas' se restablecen todos los datos eliminando los filtros:"+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/sin-filtros-columnas.png' width='80%'/> "+$salto2;
	
	$ayuda2=$ayuda2+"<span class='titulo'>"+"d) MOSTRAR AYUDA"+"</span>"+$salto1+
	"Al pulsar sobre este bóton permite desplegar una ventana emergente con ayuda."+
	$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/ayudadelaayuda.png' /> "+$salto2
	;
	
    $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 3: REGISTROS POR PÁGINA"+"</span>"+$salto1+
	"Permite seleccionar el número de líneas que se mostrarán en la pantalla. En la parte inferior en la zona 7 se muestra cuantas pantallas "+
	" son necesarias para mostrar los datos de todos los 'usuarios'."+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/registros.png'  /> "+$salto2
	; 
 	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 4: ENCABEZADOS DE LAS COLUMNAS"+"</span>"+$salto1+
	"En un conjunto de datos, como aparecen en la pantalla de 'usuarios', se pueden ver que están formados de filas y columnas, "+
	"donde cada columna es un dato que se almacena del 'usuario' y una fila es la información de un 'usuario'. "+
	"En cada nombre de columna se puede dar un clic izquierdo y escribir un criterio de búsqueda e ir pulsando la tecla enter para que se filtre por ese criterio."+
	"<br>Por ejemplo tipeando la palabra 'jer' en la columna 'usuario' podría filtrar algo parecido a:"+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/filtro-columnas1.PNG' width='80%'/>"+ $salto2;
 
	$ayuda2=$ayuda2+
 	"<span class='titulo'>"+"Zona 5: LÍNEAS DE DETALLE O DATOS"+"</span>"+$salto1+
	"Cada línea de detalle es un 'usuario', donde en la primera columna de cada línea o fila tiene sus imágenes gráficas o iconos "+
	" de las acciones que pueden afectar a cada fila. "+
	"Un ejemplo de líneas de detalle es: "+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/lineas-detalle.PNG' width='80%'/>"+ $salto2+
	"En cualquier punto de una fila que se pulse doble clic permite editar los datos del 'usuario', es decir muestra un formulario "+
	"con los datos del usuario para modificar los datos y guardar esa información actualizada. "
	+" Una fila de la figura anterior sería el recuadro rojo en la figura siguiente:"
	+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/lineas-detalle1.PNG' width='80%'/>"+ $salto2+
	"Si pulsamos doble clic en cualquier punto de esa zona roja mostraría el formulario con los datos del usuario y poder modificar y gurdar lo que sea necesario:"
	+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/usuarioeditar.PNG' width='80%'/>"+ $salto2;

   $ayuda2=$ayuda2+"<span class='titulo'>"+"Zona 6: BARRA DE DESPLAZAMIENTO HORIZONTAL"+"</span>"+$salto1+
	"De ser necesario por la cantidad de datos a mostrar puede aparecer una barra de desplazamiento horizontal, "+
	" se puede dar la posibilidad de una barra de desplazamiento vertical."+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/barrasdedesplazamiento.png' width='80%' /> "+$salto2
	; 

	$ayuda2=$ayuda2+
	"<span class='titulo'>"+"Zona 6: BOTONES DE DESPLAZAMIENTO POR PÁGINAS"+"</span>"+$salto1+
	"En la parte inferior de la pantalla se encuentran los botones para desplazarse a través de todos los datos."+$salto2+
	"<img class='center-block' src='imagenesAyuda/usuarios/entornodesplazamiento.PNG' />"+$salto2
	;
	$ayuda10="</p></div>";
	//alert($ayuda0+$ayuda0_1+$ayuda1+$ayuda2+$ayuda10);
    /* #d9ebf9 */
	$('#myModal .modal-body').append($ayuda0+$ayuda0_1+$ayuda1+$ayuda1_1+$ayuda2+$ayuda10);
	});

});


