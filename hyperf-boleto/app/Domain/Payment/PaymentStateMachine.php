<?php

namespace App\Domain\Payment;

use App\Domain\Payment\Exception\InvalidTransitionException;

final class PaymentStateMachine
{
    private static array $allowedTransitions = [
        PaymentStatus::PENDING->value => [PaymentStatus::AUTHORIZED->value, PaymentStatus::FAILED->value],
        PaymentStatus::AUTHORIZED->value => [PaymentStatus::SETTLED->value, PaymentStatus::REVERSED->value],
        PaymentStatus::SETTLED->value => [],
        PaymentStatus::REVERSED->value => [],
        PaymentStatus::FAILED->value => [],
    ];

    public static function assertTransition(PaymentStatus $from, PaymentStatus $to): void
    {
        if(!self::canTransition($from, $to)) {
            throw new InvalidTransitionException(
                "Cannot transition from {$from->value} to {$to->value}"
            );
        }
    }

    public static function canTransition(PaymentStatus $from, PaymentStatus $to): bool
    {
        if(in_array($to->value, self::$allowedTransitions[$from->value], true)) {
            return true;
        }
        return false;
    }

    public static function allowedTransitions(PaymentStatus $status): array
    {
        return array_map(
            fn(string $value) => PaymentStatus::from($value),
            self::$allowedTransitions[$status->value]
        );
    }
}