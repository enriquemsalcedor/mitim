<?php
$carpetaOrigen = 'incidentescopia/550';
$carpetaDestino = 'incidentes/1';

// Copiar la carpeta y su contenido
copiarCarpeta($carpetaOrigen, $carpetaDestino);

// Renombrar la carpeta
nuevoNombre($carpetaDestino, '1');

function copiarCarpeta($origen, $destino) {
    // Crea la carpeta de destino si no existe
    if (!is_dir($destino)) {
        mkdir($destino, 0777, true);
    }

    // Abre el directorio de origen
    $dir = opendir($origen);

    // Recorre todos los archivos y subdirectorios del directorio de origen
    while (($archivo = readdir($dir)) !== false) {
        if ($archivo == '.' || $archivo == '..') {
            continue;
        }

        // Si es un subdirectorio, copia recursivamente
        if (is_dir($origen . '/' . $archivo)) {
            copiarCarpeta($origen . '/' . $archivo, $destino . '/' . $archivo);
        } else {
            // Copia el archivo
            copy($origen . '/' . $archivo, $destino . '/' . $archivo);
        }
    }

    closedir($dir);
}

function nuevoNombre($carpeta, $nuevoNombre) {
    // Obtiene la ruta del directorio padre
    $directorioPadre = dirname($carpeta);

    // Renombra la carpeta
    rename($carpeta, $directorioPadre . '/' . $nuevoNombre);
}