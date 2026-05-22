<?php
declare(strict_types=1);

final class AdminProductCrudTest extends VoidTestCase
{
    public function testAdminCanCreateProductThroughController(): void
    {
        $this->asAdminSession();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'title' => 'Coat "Rust Veil"',
            'description' => 'Heavy coat with brutal seams and industrial weight.',
            'price' => '199.99',
            'idCategory' => '2',
        ];
        $_FILES = ['picture' => ['tmp_name' => '']];

        $output = $this->captureOutput(static function (): void {
            controllerAdminProduct::productAddResult();
        });

        $this->assertStringContainsString('Product added successfully!', $output);
        $this->assertCount(9, Database::table('product'));

        $created = Database::table('product')[8];
        $this->assertSame('Coat "Rust Veil"', $created['title']);
        $this->assertSame('Heavy coat with brutal seams and industrial weight.', $created['description']);
        $this->assertSame(199.99, (float)$created['price']);
        $this->assertSame(2, (int)$created['category_id']);
        $this->assertSame(1, (int)$created['user_id']);

        $history = Database::history();
        $insertQueries = array_values(array_filter($history, static fn (array $entry): bool => $entry['method'] === 'executePrepared' && str_contains($entry['query'], 'INSERT INTO `product`')));
        $this->assertNotEmpty($insertQueries);
    }

    public function testAdminCanReadProductListThroughController(): void
    {
        $this->asAdminSession();

        $output = $this->captureOutput(static function (): void {
            controllerAdminProduct::ProductList();
        });

        $this->assertNotEmpty($output);

        $history = Database::history();
        $selectQueries = array_values(array_filter($history, static fn (array $entry): bool => $entry['method'] === 'getAll' && str_contains($entry['query'], 'JOIN category ON product.category_id = category.id')));
        $this->assertNotEmpty($selectQueries);

        $joinedProducts = Database::table('product');
        $this->assertSame('T-Shirt "Shards"', $joinedProducts[0]['title']);
        $this->assertSame(1, (int)$joinedProducts[0]['category_id']);
    }

    public function testAdminCanUpdateProductThroughController(): void
    {
        $this->asAdminSession();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'title' => 'Jeans "Abyss Redux"',
            'description' => 'Reworked denim with harsher cuts and denser seams.',
            'price' => '125.50',
            'idCategory' => '3',
        ];
        $_FILES = ['picture' => ['tmp_name' => '']];

        $output = $this->captureOutput(static function (): void {
            controllerAdminProduct::productEditResult(1);
        });

        $this->assertNotEmpty($output);

        $updated = Database::table('product')[0];
        $this->assertSame('Jeans "Abyss Redux"', $updated['title']);
        $this->assertSame('Reworked denim with harsher cuts and denser seams.', $updated['description']);
        $this->assertSame(125.50, (float)$updated['price']);
        $this->assertSame(3, (int)$updated['category_id']);

        $history = Database::history();
        $updateQueries = array_values(array_filter($history, static fn (array $entry): bool => $entry['method'] === 'executePrepared' && str_contains($entry['query'], 'UPDATE `product` SET')));
        $this->assertNotEmpty($updateQueries);
    }

    public function testAdminCanDeleteProductThroughController(): void
    {
        $this->asAdminSession();

        $this->captureOutput(static function (): void {
            controllerAdminProduct::productDeleteResult(1);
        });

        $this->assertCount(7, Database::table('product'));
        $remainingIds = array_column(Database::table('product'), 'id');
        $this->assertNotContains(1, $remainingIds);

        $history = Database::history();
        $deleteQueries = array_values(array_filter($history, static fn (array $entry): bool => $entry['method'] === 'executePrepared' && str_contains($entry['query'], 'DELETE FROM `product` WHERE `id` = :id')));
        $this->assertNotEmpty($deleteQueries);
    }
}