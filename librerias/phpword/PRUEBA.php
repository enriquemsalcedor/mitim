<?php








include("../../conexion.php");
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\Style\TablePosition;

////////////////////////
//SAMPLE HEADER////
////////////////////////
 
require_once __DIR__ . '/bootstrap.php';

use PhpOffice\PhpWord\Settings;

date_default_timezone_set('UTC');
//error_reporting(E_ALL);
define('CLI', (PHP_SAPI == 'cli') ? true : false);
define('EOL', CLI ? PHP_EOL : '<br />');
define('SCRIPT_FILENAME', basename($_SERVER['SCRIPT_FILENAME'], '.php'));
define('IS_INDEX', SCRIPT_FILENAME == 'index'); 

//Settings::loadConfig();

$dompdfPath = $vendorDirPath . '/dompdf/dompdf';
if (file_exists($dompdfPath)) {
    define('DOMPDF_ENABLE_AUTOLOAD', false);
    Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, $vendorDirPath . '/dompdf/dompdf');
}

// Set writers
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');

// Set PDF renderer
if (null === Settings::getPdfRendererPath()) {   
	$writers['ODText'] = null;
	$writers['RTF'] = null;
	$writers['HTML'] = null;
	 $writers['PDF'] = null;
} 

// Turn output escaping on
Settings::setOutputEscapingEnabled(true);

// Return to the caller script when runs by CLI
if (CLI) {
    return;
}

// Set titles and names
$pageHeading = str_replace('_', ' ', SCRIPT_FILENAME);
$pageTitle = IS_INDEX ? 'Welcome to ' : "{$pageHeading} - ";
$pageTitle .= 'PHPWord';
$pageHeading = IS_INDEX ? '' : "<h1>{$pageHeading}</h1>";
 
// Populate samples
$files = '';
if ($handle = opendir('.')) {
    $sampleFiles = array();
    while (false !== ($sampleFile = readdir($handle))) {
        $sampleFiles[] = $sampleFile;
    }
    sort($sampleFiles);
    closedir($handle);

    foreach ($sampleFiles as $file) {
        if (preg_match('/^Sample_\d+_/', $file)) {
            $name = str_replace('_', ' ', preg_replace('/(Sample_|\.php)/', '', $file));
            $files .= "<li><a href='{$file}'>{$name}</a></li>";
        }
    }
} 
 
function write($phpWord, $filename, $writers)
{
    $result = '';

    // Write documents
    foreach ($writers as $format => $extension) {
        //$result .= date('H:i:s') . " Write to {$format} format";
        if (null !== $extension) {
			$targetFile = __DIR__ . "/{$filename}.{$extension}"; 
			
			$phpWord->save($targetFile, $format);
			 
			debugL("RUTA ES:".$targetFile,"DEBUGPRUEBA"); 
			//header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document'); //mime type
			//header("Cache-Control: no-store, no-cache");
			header('Content-Disposition: attachment; filename={$filename}.{$extension}');
			
			$context = stream_context_create(array(
				'http' => array('ignore_errors' => true),
			)); 
			
			echo file_get_contents( __DIR__ . $archivo, false, $context);    
	 
        } else {
            //$result .= ' ... NOT DONE!';
        }
        //$result .= EOL;
    }

    $result .= getEndingNotes($writers, $filename);

    return $result;
} 

/**
 * Get ending notes
 *
 * @param array $writers
 * @param mixed $filename
 * @return string
 */
 function getEndingNotes($writers, $filename)
{
    $result = '';

    // Do not show execution time for index
    if (!IS_INDEX) {
        //$result .= date('H:i:s') . ' Done writing file(s)' . EOL;
        //$result .= date('H:i:s') . ' Peak memory usage: ' . (memory_get_peak_usage(true) / 1024 / 1024) . ' MB' . EOL;
    }

    // Return
    if (CLI) {
        $result .= 'The results are stored in the "results" subdirectory.' . EOL;
    } else {
        if (!IS_INDEX) {
            $types = array_values($writers);
            $result .= '<p>&nbsp;</p>';
            $result .= '<p>Results: ';
            foreach ($types as $type) {
                if (!is_null($type)) {
                    $resultFile = 'results/' . SCRIPT_FILENAME . '.' . $type;
                    if (file_exists($resultFile)) {
                        //$result .= "<a href='{$resultFile}' class='btn btn-primary'>{$type}</a> ";
						$result .= "<a href='{$resultFile}' class='btn btn-primary'>Descargar Informe ok</a> ";
                    }
                }
            }
            $result .= '</p>';
			/*
            $result .= '<pre>';
            if (file_exists($filename . '.php')) {
                $result .= highlight_file($filename . '.php', true);
            }
            $result .= '</pre>';
			*/
        }
    }

    return $result;
}  
 
echo $pageHeading;  

////////////////////////
//SAMPLE HEADER////
////////////////////////

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->createSection();
$section->addText('Hello World!');
$file = 'HelloWorld.docx'; 
//header('Content-Disposition: attachment; filename=' . $file . ''); 
//$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$phpWord->save(__DIR__ .'\HelloWorld.docx'); 
//echo file_get_contents(__DIR__ .'\helloWorld.docx');
//$xmlWriter->save("php://output");
