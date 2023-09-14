











<?php

require_once("IMAP_access.php");
$im = new IMAP_access();

$server = '{outlook.office365.com:993/imap/ssl}INBOX';
$username = 'desarrollomaxia@outlook.com';
$password = 'maxia123';

if(!$im->open($server, $username, $password, $options = 0, $retries = 1)){
 //...failed
 echo "error";
}
$overview = $im->get_overview();
foreach($overview as $msg){
    echo "<br>pasó";
  //...loop through doing stuff
      $cuerpo = $im->get_part(1,3);
      echo $cuerpo;
      //e.g. do something with $im->body_plain
 }
 $im->close();

?>