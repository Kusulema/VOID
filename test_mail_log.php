<?php
header('Content-Type: application/json');
$log = __DIR__ . '/tests/E2E/test_mail_log.jsonl';
$entries = [];
if (file_exists($log)) {
    $lines = file($log, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $decoded = json_decode($line, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $entries[] = $decoded;
        }
    }
}
if (isset($_GET['clear']) && $_GET['clear']) {
    @file_put_contents($log, '');
}
echo json_encode($entries, JSON_UNESCAPED_UNICODE);
exit;
?>
