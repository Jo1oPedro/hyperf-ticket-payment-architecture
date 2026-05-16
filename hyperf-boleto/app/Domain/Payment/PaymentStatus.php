<?php

namespace App\Domain\Payment;

enum PaymentStatus: string
{
    case PENDING = "PENDING";
    case AUTHORIZED = "AUTHORIZED";
    CASE SETTLED = "SETTLED";
    CASE FAILED = "FAILED";
    CASE REVERSED = "REVERSED";

    public function isTerminal(): bool
    {
        return match($this) {
            self::SETTLED, self::REVERSED, self::FAILED => true,
            self::PENDING, self::AUTHORIZED => false,
        };
    }
}