<?php

namespace App\Domain\Payment;

use App\Domain\Payment\Exception\InvalidBarcodeException;

readonly final class Barcode
{
    public function __construct(
        string $barcode
    ) {
        $this->validarFormato($barcode);
        $this->validarChecksum($barcode);
    }

    private function validarFormato(string $barcode): void
    {
        if(!ctype_digit($barcode)) {
            throw new InvalidBarcodeException("Barcode must contain only digits");
        }

        if(strlen($barcode) !== 44) {
            throw new InvalidBarcodeException('Barcode must be exactly 44 digits');
        }
    }

    private function validarChecksum(string $barcode): void
    {
        $dvEsperado = (int) $barcode[4];

        // remove o DV da string pra calcular sobre os 43 restantes
        $semDv = substr($barcode, 0, 4) . substr($barcode, 5);
        $digits = str_split($semDv);

        $soma = $this->somarComPesos($digits);

        $dv = 11 - ($soma % 11);

        // DV 0, 10 ou 11 → considera 1 (regra Febraban)
        if ($dv === 0 || $dv >= 10) {
            $dv = 1;
        }

        if ($dv !== $dvEsperado) {
            throw new InvalidBarcodeException('Invalid barcode checksum');
        }
    }

    private function somarComPesos(array $digits): int
    {
        $soma = 0;
        $peso = 2; // começa em 2, alterna 2,1,2,1 da direita pra esquerda

        foreach (array_reverse($digits) as $digit) {
            $produto = (int) $digit * $peso;

            // se produto > 9, soma os dígitos (ex: 16 → 1+6 = 7)
            if ($produto > 9) {
                $produto = intdiv($produto, 10) + ($produto % 10);
            }

            $soma += $produto;
            $peso = $peso === 2 ? 1 : 2; // alterna peso
        }

        return $soma;
    }
}