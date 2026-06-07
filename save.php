<?php
header('Content-Type: application/json');

$storeFile = __DIR__ . '/submissions.json';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON payload.']);
    exit;
}

$entry = [
    'reasonForVisit' => trim((string)($data['reasonForVisit'] ?? '')),
    'firstAndLastName' => trim((string)($data['firstAndLastName'] ?? '')),
    'company' => trim((string)($data['company'] ?? '')),
    'idState' => trim((string)($data['idState'] ?? '')),
    'idNumber' => trim((string)($data['idNumber'] ?? '')),
    'dob' => trim((string)($data['dob'] ?? '')),
    'phone' => trim((string)($data['phone'] ?? '')),
    'email' => trim((string)($data['email'] ?? '')),
    'submittedAt' => gmdate('c')
];

$entries = [];
if (file_exists($storeFile)) {
    $existing = file_get_contents($storeFile);
    $decoded = json_decode($existing, true);
    if (is_array($decoded)) {
        $entries = $decoded;
    }
}

$entries[] = $entry;

if (file_put_contents($storeFile, json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Unable to write to submissions.json.']);
    exit;
}

echo json_encode(['ok' => true, 'saved' => count($entries)]);
