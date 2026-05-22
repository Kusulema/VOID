<?php
declare(strict_types=1);

final class AuthorizationGuardTest extends VoidTestCase
{
    public function testUnauthorisedUserCannotOpenProductAddForm(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Access denied');

        controllerAdminProduct::productAddForm();
    }

    public function testUnauthorisedUserCannotModerateComments(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Access denied');

        $_GET = ['id' => 1, 'action' => 'approve'];
        controllerAdmin::commentAction();
    }

    public function testAdminSessionIsRecognisedByGuard(): void
    {
        $this->asAdminSession();

        $this->assertTrue(controllerAdmin::isAdminSession());
        $this->assertTrue(controllerAdmin::requireAdmin());
    }
}