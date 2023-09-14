<?php
$fechacambio = '2018-10-02';
$fechahoy = date('Y-m-d');
$date1 = new DateTime($fechahoy);
$date2 = new DateTime($fechacambio);
$diff = $date1->diff($date2);

echo $diff->days;

?>