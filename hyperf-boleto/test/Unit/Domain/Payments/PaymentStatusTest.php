<?php

namespace HyperfTest\Unit\Domain\Payments;

use App\Domain\Payment\PaymentStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PaymentStatusTest extends TestCase
{
    public static function terminalStatusProvider(): array
    {
        return [
            "FAILED is terminal" => [PaymentStatus::FAILED],
            "REVERSED is terminal" => [PaymentStatus::REVERSED],
            "SETTLED is terminal" => [PaymentStatus::SETTLED]
        ];
    }

    public static function nonTerminalStatusProvider(): array
    {
        return [
            "PENDING is terminal" => [PaymentStatus::PENDING],
            "AUTHORIZED is terminal" => [PaymentStatus::AUTHORIZED],
        ];
    }

    #[DataProvider("terminalStatusProvider")]
    public function test_terminal_statuses_are_terminal(PaymentStatus $status): void
    {
        self::assertTrue($status->isTerminal());
    }

    #[DataProvider("nonTerminalStatusProvider")]
    public function test_non_terminal_statuses_are_not_terminal(PaymentStatus $status): void
    {
        self::assertFalse($status->isTerminal());
    }
}