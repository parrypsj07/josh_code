<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
$json = file_get_contents('php://input');
file_put_contents("ticket_data.txt", $json);
$data = json_decode($json,true);

?>