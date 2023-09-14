<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(1);
require_once("../conexion.php");
require_once("class.imap.php");

$mailbox = 'outlook.office365.com';
$port = '993';
$username = 'toolkit@maxialatam.com';
$password = '9uL!JeWCAG3nzMNV'; 

$imap = new Imap();
$connection_result = $imap->connect('{outlook.office365.com:993/imap/ssl}INBOX', $username, $password);
if ($connection_result !== true) {
	echo 'Error:'.$connection_result; 
	exit;
}

$inbox = imap_open('{outlook.office365.com:993/imap/ssl}INBOX', $username, $password) or die('Ha fallado la conexión: ' . imap_last_error());
$header = imap_search($inbox, 'SUBJECT "Incidente #12695 - Comentario" ');

function getinlineattachment($ninbox, $nemail_number, $dir, $i, $name) {
	$body = imap_fetchbody($ninbox, $nemail_number, $i);
	$imgdata = base64_decode($body);
	$k = $i - 1;
	$filename = $k."-".$name;
	$dst = $dir."/".$filename;
	$fp = fopen($dst, 'w');
	fputs($fp, $imgdata);
	fclose($fp);
}

if($header) {   
	$val = '';   
	foreach($header as $val) {    
		$v = imap_fetch_overview($inbox, $val);
		foreach ($v as $ov) {
			$strsubject = $ov->subject;  // subject
			$strto = $ov->to;  // to
			$strdate = $ov->date;  // date
			
			$salida .= 'Subject: '.$strsubject.'<br>';
			$salida .= 'To: '.$strto.'<br>';
			$salida .= 'Date: '.$strdate.'<br>';
			
			$arrincc = explode('#',$strsubject);
			$arrinc = explode(' ',$arrincc[1]);
			$incid = $arrinc[0];
			$salida .= 'Incidente: '.$incid.'<br>';
			$dir = '../incidentes/'.$incid;
			
			$structure = imap_fetchstructure($inbox, $val);
			$attachments = array();
			if(isset($structure->parts) && count($structure->parts)) {
				for($i = 0; $i < count($structure->parts); $i++) {
					$attachments[$i] = array(
						'is_attachment' => false,
						'filename' => '',
						'name' => '',
						'attachment' => ''
					);
					if($structure->parts[$i]->ifdparameters) {
						foreach($structure->parts[$i]->dparameters as $object) {
							if(strtolower($object->attribute) == 'filename') {
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['filename'] = $object->value;
							}
						}
					}
					if($structure->parts[$i]->ifparameters) {
						foreach($structure->parts[$i]->parameters as $object) {
							if(strtolower($object->attribute) == 'name') {
								$attachments[$i]['is_attachment'] = true;
								$attachments[$i]['name'] = $object->value;
							}
						}
					}
					if($attachments[$i]['is_attachment']) {
						$attachments[$i]['attachment'] = imap_fetchbody($imap, $m, $i+1);
						if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
							$attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
						}
						elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
							$attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
						}
					}
				}
			}
			
			$i = 2;
			foreach ($attachments as $attachment) {
				$name = $attachment['name'];
				if($name != ''){
					getinlineattachment($inbox, $val, $dir, $i, $name);
					$salida .= $name.'<br>';
					$i++;
				}				
			}
		}
		echo $salida; 
	}
}

imap_close($inbox);
?>