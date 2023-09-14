<?php





header('Content-Type: text/html; charset=utf-8');
error_reporting(1);
require_once("../conexion.php");
require_once("class.imap.php"); 
ini_set('default_charset', 'utf-8'); 

$mailbox = 'outlook.office365.com';
$port = '993'; 

$imap = new Imap(); 

$hostname = '{outlook.office365.com:993/imap/ssl}INBOX';
$username = 'toolkit@maxialatam.com';
$password = '9uL!JeWCAG3nzMNV';
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to server: ' . imap_last_error()); 
 

/**
  * Get messages according to a search criteria
  * 
  * @param	string	search criteria (RFC2060, sec. 6.4.4). Set to "UNSEEN" by default
  *					NB: Search criteria only affects IMAP mailboxes.
  * @param	string	date format. Set to "Y-m-d H:i:s" by default
  * @return	mixed	array containing messages
  */
 
 /*     $msgs = imap_search($inbox, 'UNSEEN');
     $no_of_msgs = $msgs ? count($msgs) : 0;
     $messages = array();
     for ($i = 0; $i < $no_of_msgs; $i++) {
         // Get Message Unique ID in case mail box changes
         // in the middle of this operation
         $message_id = imap_uid($inbox, $msgs[$i]);
         $header = imap_header($inbox, $message_id);
         $date = date($date_format, $header->udate);
         $from = $header->from;
         $fromname = "";
         $fromaddress = "";
         $subject = "";
         foreach ($from as $id => $object) {
             if (isset($object->personal)) {
                 $fromname = $object->personal;
             }
             $fromaddress = $object->mailbox . "@" . $object->host;
             if ($fromname == "") {
                 // In case from object doesn't have Name
                 $fromname = $fromaddress;
             }
         }
         if (isset($header->subject)) {
             $subject = $this->_mime_decode($header->subject);
         }
         $structure = imap_fetchstructure($inbox, $message_id);
         $body = '';
         if (!empty($structure->parts)) {
             for ($j = 0, $k = count($structure->parts); $j < $k; $j++) {
                 $part = $structure->parts[$j];
                 if ($part->subtype == 'PLAIN') {
                     $body = imap_fetchbody($inbox, $message_id, $j + 1);
                 }
             }
         } else {
             $body = imap_body($inbox, $message_id);
         }
         // Convert quoted-printable strings (RFC2045)
         $body = imap_qprint($body);
         array_push($messages, array('msg_no' => $message_id, 'date' => $date, 'from' => $fromname, 'email' => $fromaddress, 'subject' => $subject, 'body' => $body));
         // Mark Message As Read
         imap_setflag_full($inbox, $message_id, "\\Seen");
     }  
	 var_dump($messages); */