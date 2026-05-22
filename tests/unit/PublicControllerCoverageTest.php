<?php
declare(strict_types=1);

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
final class PublicControllerCoverageTest extends VoidTestCase
{
    public function testStartSiteRendersHomepageWithLatestProductsAndReviews(): void
    {
        $this->asAdminSession();
        $_SESSION['wishlist'] = json_encode([6, 7]);

        $output = $this->captureOutput(static function (): void {
            Controller::StartSite();
        });

        $this->assertStringContainsString('Three products we would put on the front wall', $output);
        $this->assertStringContainsString('Test comment from Copilot', $output);
        $this->assertStringContainsString('SHOP NOW', $output);
    }

    public function testAllProductsRendersCatalogShell(): void
    {
        $this->asUserSession();

        $output = $this->captureOutput(static function (): void {
            Controller::AllProducts();
        });

        $this->assertStringContainsString('Catalog', $output);
        $this->assertStringContainsString('THE VOID', $output);
        $this->assertStringContainsString('ADD TO CART', $output);
    }

    public function testAccountProfileUpdatePersistsToMockDatabase(): void
    {
        $this->asAdminSession();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'profile_update' => '1',
            'gender' => 'male',
            'address' => 'Tallinn, Void Street 13',
            'card_name' => 'Void Admin',
            'card_number' => '4111 1111 1111 1111',
            'card_expiry' => '12/29',
            'card_cvv' => '123',
            'bank_account' => 'EE123456789',
        ];

        $output = $this->captureOutput(static function (): void {
            Controller::Account();
        });

        $this->assertStringContainsString('Profile saved successfully.', $output);
        $this->assertStringContainsString('Account dashboard', $output);

        $user = Database::table('users')[0];
        $this->assertSame('Tallinn, Void Street 13', $user['address']);
        $this->assertSame('Void Admin', $user['card_name']);
        $this->assertSame('4111111111111111', $user['card_number']);
        $this->assertSame('12/29', $user['card_expiry']);
        $this->assertSame('123', $user['card_cvv']);
    }

    public function testCategoryListRendersCategoryShell(): void
    {
        $output = $this->captureOutput(static function (): void {
            Controller::AllCategory();
        });

        $this->assertStringContainsString('VOID BIOS v0.13', $output);
        $this->assertStringContainsString('DEAD SOULS', $output);
    }

    public function testCategoryProductsRenderAccessoriesDrop(): void
    {
        $output = $this->captureOutput(static function (): void {
            Controller::ProductByCatID(3);
        });

        $this->assertStringContainsString('ACCESSORIES', $output);
        $this->assertStringContainsString('Chain &quot;Choke&quot;', $output);
    }

    public function testSingleProductPageRendersDetailCard(): void
    {
        $output = $this->captureOutput(static function (): void {
            Controller::ProductByID(7);
        });

        $this->assertStringContainsString('Ring &quot;Rusty Nail&quot;', $output);
        $this->assertStringContainsString('Go to cart', $output);
    }

    public function testCartPageRendersCheckoutShell(): void
    {
        $output = $this->captureOutput(static function (): void {
            Controller::Cart();
        });

        $this->assertStringContainsString('Your selection', $output);
        $this->assertStringContainsString('buyNowBtn', $output);
        $this->assertStringContainsString('orderWarningModal', $output);
    }

    public function testReviewsPageRendersFeedbackSection(): void
    {
        $output = $this->captureOutput(static function (): void {
            Controller::Reviews();
        });

        $this->assertStringContainsString('Real comments, styled as a proper section', $output);
        $this->assertStringContainsString('Test comment from Copilot', $output);
    }

    public function testNewsletterSubscribeRejectsInvalidEmail(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = ['email' => 'not-an-email'];

        $output = $this->captureOutput(static function (): void {
            Controller::NewsletterSubscribe();
        });

        $this->assertStringContainsString('Please enter a valid email address.', $output);
    }

    public function testRegisterFormRendersSignupTerminal(): void
    {
        $output = $this->captureOutput(static function (): void {
            Controller::registerForm();
        });

        $this->assertStringContainsString('VOID INITIATION TERMINAL', $output);
        $this->assertStringContainsString('COMPLETE INITIATION', $output);
    }

    public function testError404RendersFallbackPage(): void
    {
        $output = $this->captureOutput(static function (): void {
            Controller::error404();
        });

        $this->assertStringContainsString('Error 404', $output);
    }

    public function testLogoutActionShowsLoginForm(): void
    {
        $this->asUserSession();

        $output = $this->captureOutput(static function (): void {
            Controller::logoutAction();
        });

        $this->assertStringContainsString('VOID ACCESS TERMINAL', $output);
        $this->assertArrayNotHasKey('sessionId', $_SESSION);
        $this->assertArrayNotHasKey('userId', $_SESSION);
    }
}