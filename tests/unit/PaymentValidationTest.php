<?php
declare(strict_types=1);

final class PaymentValidationTest extends VoidTestCase
{
    public function testValidPaymentProfileIsAcceptedAndNormalized(): void
    {
        [$isValid, $errors, $normalized] = $this->invokePrivateStatic(Register::class, 'validatePaymentFields', [[
            'address' => '  Tallinn  ',
            'card_name' => ' Void Holder ',
            'card_number' => '4111 1111 1111 1111',
            'card_expiry' => '12/29',
            'card_cvv' => '123',
        ]]);

        $this->assertTrue($isValid);
        $this->assertSame([], $errors);
        $this->assertSame('Tallinn', $normalized['address']);
        $this->assertSame('Void Holder', $normalized['card_name']);
        $this->assertSame('4111111111111111', $normalized['card_number']);
        $this->assertSame('12/29', $normalized['card_expiry']);
        $this->assertSame('123', $normalized['card_cvv']);
    }

    /**
     * @dataProvider invalidCardNumberProvider
     */
    public function testCardNumberValidationRejectsNumbersWithWrongDigitCount(string $cardNumber): void
    {
        [$isValid, $errors] = $this->invokePrivateStatic(Register::class, 'validatePaymentFields', [[
            'address' => 'Tallinn',
            'card_name' => 'Void Holder',
            'card_number' => $cardNumber,
            'card_expiry' => '12/29',
            'card_cvv' => '123',
        ]]);

        $this->assertFalse($isValid);
        $this->assertArrayHasKey('card_number', $errors);
        $this->assertSame('Card number must contain 13 to 19 digits.', $errors['card_number']);
    }

    public function invalidCardNumberProvider(): array
    {
        return [
            'twelve digits' => ['123456789012'],
            'twenty digits' => ['12345678901234567890'],
            'letters removed then too short' => ['4111-1111-111'],
        ];
    }

    /**
     * @dataProvider invalidExpiryProvider
     */
    public function testExpiryValidationRejectsFormatsOutsideMmYy(string $expiry): void
    {
        [$isValid, $errors] = $this->invokePrivateStatic(Register::class, 'validatePaymentFields', [[
            'address' => 'Tallinn',
            'card_name' => 'Void Holder',
            'card_number' => '4111 1111 1111 1111',
            'card_expiry' => $expiry,
            'card_cvv' => '123',
        ]]);

        $this->assertFalse($isValid);
        $this->assertArrayHasKey('card_expiry', $errors);
        $this->assertSame('Expiry date must use MM/YY format.', $errors['card_expiry']);
    }

    public function invalidExpiryProvider(): array
    {
        return [
            'single-digit month' => ['1/29'],
            'month zero' => ['00/29'],
            'month 13' => ['13/29'],
            'four-digit year' => ['12/2029'],
            'wrong separator' => ['12-29'],
        ];
    }

    public function testMissingAddressAndCardholderAreRejected(): void
    {
        [$isValid, $errors] = $this->invokePrivateStatic(Register::class, 'validatePaymentFields', [[
            'address' => ' ',
            'card_name' => '',
            'card_number' => '4111111111111111',
            'card_expiry' => '11/29',
            'card_cvv' => '123',
        ]]);

        $this->assertFalse($isValid);
        $this->assertArrayHasKey('address', $errors);
        $this->assertArrayHasKey('card_name', $errors);
    }

    public function testThreeAndFourDigitSecurityCodesAreAccepted(): void
    {
        [$isValidThree, $errorsThree] = $this->invokePrivateStatic(Register::class, 'validatePaymentFields', [[
            'address' => 'Tallinn',
            'card_name' => 'Void Holder',
            'card_number' => '4111111111111111',
            'card_expiry' => '11/29',
            'card_cvv' => '123',
        ]]);

        [$isValidFour, $errorsFour] = $this->invokePrivateStatic(Register::class, 'validatePaymentFields', [[
            'address' => 'Tallinn',
            'card_name' => 'Void Holder',
            'card_number' => '4111111111111111',
            'card_expiry' => '11/29',
            'card_cvv' => '1234',
        ]]);

        $this->assertTrue($isValidThree);
        $this->assertTrue($isValidFour);
        $this->assertSame([], $errorsThree);
        $this->assertSame([], $errorsFour);
    }
}