<?php

namespace HyperfTest\Unit\Domain\Payments;

use App\Domain\Payment\PaymentStateMachine;
use App\Domain\Payment\PaymentStatus;
use PHPUnit\Framework\TestCase;

class PaymentStateMachineTest extends TestCase
{
    public function test_all_transitions(): void
    {
        $valid = [
            [PaymentStatus::PENDING, PaymentStatus::AUTHORIZED],
            [PaymentStatus::PENDING, PaymentStatus::FAILED],
            [PaymentStatus::AUTHORIZED, PaymentStatus::SETTLED],
            [PaymentStatus::AUTHORIZED, PaymentStatus::REVERSED],
        ];

        foreach(PaymentStatus::cases() as $from) {
            foreach(PaymentStatus::cases() as $to) {
                $shouldBeValid = false;
                foreach($valid as [$f, $t]) {
                    if($f === $from && $t === $to) {
                        $shouldBeValid = true;
                        break;
                    }
                }

                if($shouldBeValid) {
                    self::assertTrue(
                        PaymentStateMachine::canTransition($from, $to),
                        "Esperava que {$from->value} → {$to->value} fosse VÁLIDA"
                    );
                } else {
                    self::assertFalse(
                        PaymentStateMachine::canTransition($from, $to),
                        "Esperava que {$from->value} → {$to->value} fosse INVÁLIDA"
                    );
                }
            }
        }
    }
}