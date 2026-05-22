<?php
// Usage: php tools/send_test_mail.php recipient@example.com "Subject" "Body"
require_once __DIR__ . '/../inc/Mailer.php';

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    echo "vendor/autoload.php not found. Run composer install in the project root first.\n";
}

$to = $argv[1] ?? null;
$subject = $argv[2] ?? 'Test message from VOID';
$body = $argv[3] ?? 'This is a test message sent using inc/Mailer.php';

if (!$to) {
    echo "Usage: php tools/send_test_mail.php recipient@example.com \"Subject\" \"Body\"\n";
    exit(1);
}

echo "Sending test mail to: $to\n";
$ok = Mailer::send($to, $subject, nl2br($body), true);
if ($ok) {
    echo "Mail sent (Mailer returned true).\n";
} else {
    echo "Mail NOT sent. Check SMTP config, vendor/autoload.php and SMTP server access.\n";
}
