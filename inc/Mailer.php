<?php
class Mailer
{
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
        $smtp = self::resolveSmtpConfig();
        $smtpReady = !empty($smtp['host'])
            && !empty($smtp['username'])
            && !empty($smtp['password'])
            && !empty($smtp['from']['email'])
            && $smtp['from']['email'] !== 'no-reply@void.com';

        $autoload = __DIR__ . '/../vendor/autoload.php';
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

        $headers = [];
    $headers[] = 'From: ' . $smtp['from']['name'] . ' <' . $smtp['from']['email'] . '>';
    $headers[] = 'Reply-To: ' . $smtp['from']['email'];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = $isHtml ? 'Content-Type: text/html; charset=utf-8' : 'Content-Type: text/plain; charset=utf-8';

        return @mail($to, $subject, $body, implode("\r\n", $headers));
    }
}
?>
