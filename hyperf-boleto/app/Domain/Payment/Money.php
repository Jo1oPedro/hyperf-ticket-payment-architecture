<?php

namespace App\Domain\Payment;

class Money
{
    public function __construct(
        private(set) int $cents,
        private(set) string $currency = "BRL",
    ) {
        if($this->cents < 0) {
            throw new \InvalidArgumentException("Cents cannot be negative");
        }
    }

    public static function fromDecimal(float|string $reais, string $currency = "BRL"): self
    {
        $cents = (int) bcmul((string) $reais, "100", 0);
        return new self($cents, $currency);
    }

    public function toDecimal(): string
    {
        return bcdiv((string) $this->cents, "100", 2);
    }

    public function equals(Money $other): bool
    {
        return $this->cents === $other->cents
            && $this->currency === $other->currency;
    }

    public function __toString(): string
    {
        $moneyCurrency = match ($this->currency) {
            'BRL' => "R$",
        };
        return $moneyCurrency . " " . $this->toDecimal();
    }
}