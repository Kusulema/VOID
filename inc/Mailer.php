<?php
class Mailer
{
    /**
     * Runtime SMTP configuration. Values may be overridden from environment
     * variables (see resolveSmtpConfig) or by loading a .env file when
     * phpdotenv is available. Defaults provide a safe fallback for tests.
     */
    private static $smtp = [
        'provider' => 'custom',
        'host' => '',
        'username' => '',
        'password' => '',
        'port' => 587,
        'secure' => 'tls',
        'from' => [
            'email' => 'no-reply@void.com',
            'name' => 'VOID & IRON',
        ],
    ];

    private static function loadDotenvIfAvailable()
    {
        // If Composer autoload and phpdotenv are available, load .env into getenv()
        $autoload = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($autoload)) {
            require_once $autoload;
            if (class_exists('Dotenv\\Dotenv')) {
                try {
                    $dot = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
                    $dot->load();
                } catch (Throwable $e) {
                    // ignore dotenv errors and continue to getenv()
                }
            }
        }
    }

    private static function readEnv(string $name): string
    {
        $value = getenv($name);
        if ($value !== false && $value !== '') {
            return (string) $value;
        }

        if (isset($_ENV[$name]) && $_ENV[$name] !== '') {
            return (string) $_ENV[$name];
        }

        if (isset($_SERVER[$name]) && $_SERVER[$name] !== '') {
            return (string) $_SERVER[$name];
        }

        return '';
    }

    public static function bootstrapEnv()
    {
        self::loadDotenvIfAvailable();
    }

    private static function resolveSmtpConfig()
    {
        self::loadDotenvIfAvailable();

        $config = self::$smtp;
        $provider = strtolower((string)($config['provider'] ?? 'custom'));

        $presets = [
            'gmail' => ['host' => 'smtp.gmail.com', 'port' => 587, 'secure' => 'tls'],
            'yandex' => ['host' => 'smtp.yandex.com', 'port' => 587, 'secure' => 'tls'],
            'mailru' => ['host' => 'smtp.mail.ru', 'port' => 465, 'secure' => 'ssl'],
        ];

        // Apply provider presets (e.g., gmail) when provider set and the
        // explicit host/port/secure are not already configured.
        if (isset($presets[$provider])) {
            foreach ($presets[$provider] as $key => $value) {
                if (empty($config[$key])) {
                    $config[$key] = $value;
                }
            }
        }

        $envMap = [
            'provider' => 'VOID_MAIL_PROVIDER',
            'host' => 'VOID_MAIL_HOST',
            'username' => 'VOID_MAIL_USERNAME',
            'password' => 'VOID_MAIL_PASSWORD',
            'port' => 'VOID_MAIL_PORT',
            'secure' => 'VOID_MAIL_SECURE',
            'from_email' => 'VOID_MAIL_FROM_EMAIL',
            'from_name' => 'VOID_MAIL_FROM_NAME',
        ];

        // Overlay environment variables when present. This enables CI/test
        // overrides without changing code. Ports are cast to int.
        foreach ($envMap as $key => $envName) {
            $value = self::readEnv($envName);
            if ($value !== '') {
                if ($key === 'from_email') {
                    $config['from']['email'] = $value;
                } elseif ($key === 'from_name') {
                    $config['from']['name'] = $value;
                } else {
                    $config[$key] = $key === 'port' ? (int) $value : $value;
                }
            }
        }

        if (($config['from']['email'] ?? 'no-reply@void.com') === 'no-reply@void.com' && !empty($config['username'])) {
            $config['from']['email'] = $config['username'];
        }

        return $config;
    }

    public static function send($to, $subject, $body, $isHtml = false)
    {
        // TEST HOOK: CI and E2E tests create a JSONL file path that, when present,
        // receives all outbound emails. This avoids sending real email during tests
        // and provides a simple assertion point for E2E suites.
        $testLog = __DIR__ . '/../tests/E2E/test_mail_log.jsonl';
        if (file_exists($testLog)) {
            $entry = [
                'to' => $to,
                'subject' => $subject,
                'body' => $body,
                'isHtml' => (bool)$isHtml,
                'time' => date(DATE_ATOM),
            ];
            // Attempt to append as a JSON line. Ignore failure silently.
            @file_put_contents($testLog, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
            return true;
        }

        // Resolve the runtime SMTP config and verify we have enough
        // information to attempt an SMTP send. If not, fall back to PHP `mail()`.
        $smtp = self::resolveSmtpConfig();
        $smtpReady = !empty($smtp['host'])
            && !empty($smtp['username'])
            && !empty($smtp['password'])
            && !empty($smtp['from']['email'])
            && $smtp['from']['email'] !== 'no-reply@void.com';

        $autoload = __DIR__ . '/../vendor/autoload.php';
        // When PHPMailer is available and the SMTP config looks usable,
        // prefer SMTP for better reliability and authentication support.
        if ($smtpReady && file_exists($autoload)) {
            require_once $autoload;
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $smtp['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $smtp['username'];
                $mail->Password = $smtp['password'];
                $mail->Port = (int) $smtp['port'];
                if (!empty($smtp['secure'])) {
                    $mail->SMTPSecure = $smtp['secure'];
                }
                $mail->setFrom($smtp['from']['email'], $smtp['from']['name']);
                $mail->addAddress($to);
                $mail->isHTML($isHtml);
                $mail->Subject = $subject;
                $mail->Body = $body;
                $mail->AltBody = $isHtml ? trim(strip_tags($body)) : $body;
                $mail->send();
                return true;
            } catch (Throwable $e) {
                error_log('Mailer SMTP error: ' . $e->getMessage());
            }
        }

        // Last-resort: use PHP's `mail()` with basic headers. This path is
        // intentionally tolerant because some environments (shared hosts)
        // do not provide SMTP capability.
        $headers = [];
        $headers[] = 'From: ' . $smtp['from']['name'] . ' <' . $smtp['from']['email'] . '>';
        $headers[] = 'Reply-To: ' . $smtp['from']['email'];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = $isHtml ? 'Content-Type: text/html; charset=utf-8' : 'Content-Type: text/plain; charset=utf-8';

        return @mail($to, $subject, $body, implode("\r\n", $headers));
    }
}
?>
