<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Only POST allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

$time = $data['time'] ?? date('Y-m-d H:i:s');
$answers = json_encode($data['answers'], JSON_UNESCAPED_UNICODE);

$line = $time . ' | ' . $answers . "\n";

// ذخیره در فایل
if (file_put_contents('results.txt', $line, FILE_APPEND | LOCK_EX) !== false) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save']);
}
?>
