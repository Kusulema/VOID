<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

abstract class VoidTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Database::reset();
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
        $_FILES = [];
        $_REQUEST = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_REFERER'] = 'index.php';

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    protected function asAdminSession(): void
    {
        $_SESSION['sessionId'] = 'test-session';
        $_SESSION['userId'] = 1;
        $_SESSION['name'] = 'voidadmin';
        $_SESSION['email'] = 'admin@void.com';
        $_SESSION['status'] = 'admin';
        $_SESSION['gender'] = 'male';
        $_SESSION['wishlist'] = json_encode([6, 7]);
    }

    protected function asUserSession(int $userId = 2): void
    {
        $_SESSION['sessionId'] = 'test-session';
        $_SESSION['userId'] = $userId;
        $_SESSION['name'] = 'voiduser';
        $_SESSION['email'] = 'user@void.com';
        $_SESSION['status'] = 'user';
        $_SESSION['gender'] = 'female';
        $_SESSION['wishlist'] = json_encode([]);
    }

    protected function captureOutput(callable $callback): string
    {
        ob_start();
        $callback();
        return (string)ob_get_clean();
    }

    protected function invokePrivateStatic(string $className, string $methodName, array $arguments = [])
    {
        $reflection = new ReflectionClass($className);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs(null, $arguments);
    }
}