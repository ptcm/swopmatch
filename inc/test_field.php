<?php
include_once 'connection.php';

date_default_timezone_set('Africa/Harare');

$date = date('d-m-Y h:i:s');

$dateTime = new DateTime($date);
$dateTime = $dateTime->modify('+7 days');
$expDate = $dateTime->format("d-m-Y h:i:s");

echo $date.'<br>';

echo $expDate;
  
?>