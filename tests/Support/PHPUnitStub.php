<?php
declare(strict_types=1);

namespace PHPUnit\Framework;

abstract class TestCase
{
    protected function setUp(): void
    {
    }

    protected function assertTrue($condition, string $message = ''): void
    {
        if (!$condition) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that condition is true.');
        }
    }

    protected function assertFalse($condition, string $message = ''): void
    {
        if ($condition) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that condition is false.');
        }
    }

    protected function assertSame($expected, $actual, string $message = ''): void
    {
        if ($expected !== $actual) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that two values are the same.');
        }
    }

    protected function assertArrayHasKey($key, array $array, string $message = ''): void
    {
        if (!array_key_exists($key, $array)) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that array has the specified key.');
        }
    }

    protected function assertCount(int $expectedCount, $haystack, string $message = ''): void
    {
        if (count($haystack) !== $expectedCount) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting count.');
        }
    }

    protected function assertNotEmpty($value, string $message = ''): void
    {
        if (empty($value)) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that value is not empty.');
        }
    }

    protected function assertStringContainsString(string $needle, string $haystack, string $message = ''): void
    {
        if (strpos($haystack, $needle) === false) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that string contains substring.');
        }
    }

    protected function assertContains($needle, array $haystack, string $message = ''): void
    {
        if (!in_array($needle, $haystack, true)) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that array contains value.');
        }
    }

    protected function assertNotContains($needle, array $haystack, string $message = ''): void
    {
        if (in_array($needle, $haystack, true)) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that array does not contain value.');
        }
    }

    protected function assertNotNull($value, string $message = ''): void
    {
        if ($value === null) {
            throw new \RuntimeException($message !== '' ? $message : 'Failed asserting that value is not null.');
        }
    }

    protected function expectException(string $className): void
    {
        // No-op shim for static analysis fallback.
    }

    protected function expectExceptionMessage(string $message): void
    {
        // No-op shim for static analysis fallback.
    }
}