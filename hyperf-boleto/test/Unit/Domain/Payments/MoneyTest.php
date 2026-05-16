<?php

namespace HyperfTest\Unit\Domain\Payments;

use App\Domain\Payment\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_money_throws_exception_when_cents_is_negative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Money(-1);
    }

    public function test_can_create_money_class_successfully(): void
    {
        $money = new Money(1);
        $this->assertInstanceOf(Money::class, $money);
    }

    public function test_return_right_amount_in_cents_from_decimal(): void
    {
        $money = Money::fromDecimal("10.50");
        $this->assertEquals(1050, $money->cents);
    }

    public function test_return_right_amount_in_decimal(): void
    {
        $money = new Money(150);
        $this->assertEquals("1.50", $money->toDecimal());
    }

    public function test_two_values_from_same_currency_return_equal(): void
    {
        $money = Money::fromDecimal("10.50");
        $otherMoney = Money::fromDecimal("10.50");
        $this->assertTrue($money->equals($otherMoney));
    }

    public function test_two_values_from_different_currency_return_different(): void
    {
        $money = Money::fromDecimal("10.50");
        $otherMoney = Money::fromDecimal("10.50", "USD");
        $this->assertFalse($money->equals($otherMoney));
    }

    public function test_money_to_string_returns_correct_money_formatation(): void
    {
        $money = Money::fromDecimal("10.50");
        $this->assertEquals("R$ 10.50", (string) $money);
    }
}