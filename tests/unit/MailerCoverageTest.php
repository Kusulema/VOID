<?php
declare(strict_types=1);

final class MailerCoverageTest extends VoidTestCase
{
    private string $mailLogFile;
    private string $mailLogBackup = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailLogFile = $this->getMailLogPath();
        if (file_exists($this->mailLogFile)) {
            $this->mailLogBackup = (string) file_get_contents($this->mailLogFile);
        } else {
            $this->mailLogBackup = '';
        }

        file_put_contents($this->mailLogFile, '');
    }

    protected function tearDown(): void
    {
        file_put_contents($this->mailLogFile, $this->mailLogBackup);
        parent::tearDown();
    }

    public function testMailerSendWritesJsonLineToTestLog(): void
    {
        $sent = Mailer::send('test@example.com', 'Void subject', '<p>Void body</p>', true);

        $this->assertTrue($sent);

        $lines = array_values(array_filter(array_map('trim', file($this->mailLogFile, FILE_IGNORE_NEW_LINES) ?: [])));
        $this->assertCount(1, $lines);

        $entry = json_decode($lines[0], true);
        $this->assertSame('test@example.com', $entry['to']);
        $this->assertSame('Void subject', $entry['subject']);
        $this->assertSame('<p>Void body</p>', $entry['body']);
        $this->assertTrue($entry['isHtml']);
        $this->assertNotEmpty($entry['time']);
    }

    public function testResolveSmtpConfigUsesEnvOverridesAndPreset(): void
    {
        $envBackup = [
            'VOID_MAIL_PROVIDER' => getenv('VOID_MAIL_PROVIDER'),
            'VOID_MAIL_HOST' => getenv('VOID_MAIL_HOST'),
            'VOID_MAIL_USERNAME' => getenv('VOID_MAIL_USERNAME'),
            'VOID_MAIL_PASSWORD' => getenv('VOID_MAIL_PASSWORD'),
            'VOID_MAIL_PORT' => getenv('VOID_MAIL_PORT'),
            'VOID_MAIL_SECURE' => getenv('VOID_MAIL_SECURE'),
            'VOID_MAIL_FROM_EMAIL' => getenv('VOID_MAIL_FROM_EMAIL'),
            'VOID_MAIL_FROM_NAME' => getenv('VOID_MAIL_FROM_NAME'),
        ];

        putenv('VOID_MAIL_PROVIDER=gmail');
        putenv('VOID_MAIL_HOST=');
        putenv('VOID_MAIL_USERNAME=mailer@void.com');
        putenv('VOID_MAIL_PASSWORD=secret');
        putenv('VOID_MAIL_PORT=2525');
        putenv('VOID_MAIL_SECURE=ssl');
        putenv('VOID_MAIL_FROM_EMAIL=postmaster@void.com');
        putenv('VOID_MAIL_FROM_NAME=VOID POST');

        try {
            $config = $this->invokePrivateStatic(Mailer::class, 'resolveSmtpConfig');

            $this->assertSame('gmail', $config['provider']);
            $this->assertSame('smtp.gmail.com', $config['host']);
            $this->assertSame('mailer@void.com', $config['username']);
            $this->assertSame('secret', $config['password']);
            $this->assertSame(2525, $config['port']);
            $this->assertSame('ssl', $config['secure']);
            $this->assertSame('postmaster@void.com', $config['from']['email']);
            $this->assertSame('VOID POST', $config['from']['name']);
        } finally {
            foreach ($envBackup as $key => $value) {
                if ($value === false || $value === null) {
                    putenv($key);
                } else {
                    putenv($key . '=' . $value);
                }
            }
        }
    }

    private function getMailLogPath(): string
    {
        return dirname(__DIR__) . '/E2E/test_mail_log.jsonl';
    }
}