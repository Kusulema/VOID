<?php
declare(strict_types=1);

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
final class AdminControllerCoverageTest extends VoidTestCase
{
    public function testRequireAdminThrowsWithoutAdminSession(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Access denied');

        controllerAdmin::requireAdmin();
    }

    public function testAdminLoginAndDashboardBranchesRender(): void
    {
        $guestOutput = $this->captureOutput(static function (): void {
            controllerAdmin::formLoginSite();
        });

        $this->assertStringContainsString('ARCHITECT LOGIN', $guestOutput);

        $this->asAdminSession();
        $adminOutput = $this->captureOutput(static function (): void {
            controllerAdmin::formLoginSite();
        });

        $this->assertStringContainsString('Admin Panel', $adminOutput);
        $this->assertStringContainsString('Administration dashboard', $adminOutput);
    }

    public function testCommentsModerationListAndActionsMutateMockDatabase(): void
    {
        $this->asAdminSession();

        $output = $this->captureOutput(static function (): void {
            controllerAdmin::commentsList();
        });

        $this->assertStringContainsString('Comments moderation', $output);
        $this->assertStringContainsString('Test comment from Copilot', $output);

        $_GET = ['id' => 1, 'action' => 'deny'];
        $result = controllerAdmin::commentAction();
        $this->assertSame(['redirect' => 'index.php'], $result);
        $this->assertSame(0, Database::table('comments')[0]['approved']);

        $_GET = ['id' => 1, 'action' => 'approve'];
        $result = controllerAdmin::commentAction();
        $this->assertSame(['redirect' => 'index.php'], $result);
        $this->assertSame(1, Database::table('comments')[0]['approved']);

        $_GET = ['id' => 2, 'action' => 'delete'];
        $result = controllerAdmin::commentAction();
        $this->assertSame(['redirect' => 'index.php'], $result);
        $this->assertCount(1, Database::table('comments'));
    }

    public function testProductManagementFlowCoversAddEditDelete(): void
    {
        $this->asAdminSession();

        $listOutput = $this->captureOutput(static function (): void {
            controllerAdminProduct::ProductList();
        });
        $this->assertStringContainsString('Product Management', $listOutput);
        $this->assertStringContainsString('T-Shirt "Shards"', $listOutput);

        $addFormOutput = $this->captureOutput(static function (): void {
            controllerAdminProduct::productAddForm();
        });
        $this->assertStringContainsString('Add New Product', $addFormOutput);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'save' => '1',
            'title' => 'Coat "Ash"',
            'description' => 'Heavy coat with rusted seams.',
            'price' => '199.90',
            'idCategory' => '2',
            'title_en' => 'Coat "Ash"',
            'description_en' => 'Heavy coat with rusted seams.',
            'title_ru' => '',
            'description_ru' => '',
            'title_et' => '',
            'description_et' => '',
        ];
        $_FILES = [];
        controllerAdminProduct::productAddResult();
        $this->assertSame('Coat "Ash"', Database::table('product')[count(Database::table('product')) - 1]['title']);

        $editFormOutput = $this->captureOutput(static function (): void {
            controllerAdminProduct::productEditForm(1);
        });
        $this->assertStringContainsString('Edit Product', $editFormOutput);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'save' => '1',
            'title' => 'T-Shirt "Shards" Updated',
            'description' => 'Updated torn edges, faded black.',
            'price' => '49.50',
            'idCategory' => '1',
            'title_en' => '',
            'description_en' => '',
            'title_ru' => '',
            'description_ru' => '',
            'title_et' => '',
            'description_et' => '',
        ];
        $_FILES = [];
        controllerAdminProduct::productEditResult(1);
        $this->assertSame('T-Shirt "Shards" Updated', Database::table('product')[0]['title']);

        $deleteFormOutput = $this->captureOutput(static function (): void {
            controllerAdminProduct::productDeleteForm(2);
        });
        $this->assertStringContainsString('Delete Product', $deleteFormOutput);

        $deleteResult = controllerAdminProduct::productDeleteResult(2);
        $this->assertSame(['redirect' => 'productAdmin', 'deleted' => true], $deleteResult);
        $remainingIds = array_map(static fn (array $row): int => (int)$row['id'], Database::table('product'));
        $this->assertNotContains(2, $remainingIds);
    }
}