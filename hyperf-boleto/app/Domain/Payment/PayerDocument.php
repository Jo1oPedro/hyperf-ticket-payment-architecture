<?php

namespace App\Domain\Payment;

use App\Domain\Payment\Exception\InvalidPayerDocumentException;

readonly final class PayerDocument
{
    private function __construct(
        private(set) string $document
    ) {}

    public static function fromRaw(string $document): self
    {
        $clean = preg_replace('/\D/', '', $document);

        if (strlen($clean) === 11) {
            self::validarCpf($clean);
        } elseif (strlen($clean) === 14) {
            self::validarCnpj($clean);
        } else {
            throw new InvalidPayerDocumentException(
                'Document must be 11 digits (CPF) or 14 digits (CNPJ)'
            );
        }

        return new self($clean);
    }

    public function isCpf(): bool
    {
        return strlen($this->document) === 11;
    }

    public function isCnpj(): bool
    {
        return strlen($this->document) === 14;
    }

    public function masked(): string
    {
        if ($this->isCpf()) {
            // 000.000.000-00
            return substr($this->document, 0, 3) . '.' .
                substr($this->document, 3, 3) . '.' .
                substr($this->document, 6, 3) . '-' .
                substr($this->document, 9, 2);
        }

        // 00.000.000/0000-00
        return substr($this->document, 0, 2)  . '.' .
            substr($this->document, 2, 3)  . '.' .
            substr($this->document, 5, 3)  . '/' .
            substr($this->document, 8, 4)  . '-' .
            substr($this->document, 12, 2);
    }

    private static function validarCpf(string $cpf): void
    {
        // CPFs com todos os dígitos iguais são inválidos por convenção
        if (strlen(count_chars($cpf, 3)) === 1) {
            throw new InvalidPayerDocumentException('Invalid CPF');
        }

        // 1º dígito verificador
        $dv1 = self::calcularDvCpf($cpf, 9, 10);
        if ($dv1 !== (int) $cpf[9]) {
            throw new InvalidPayerDocumentException('Invalid CPF');
        }

        // 2º dígito verificador
        $dv2 = self::calcularDvCpf($cpf, 10, 11);
        if ($dv2 !== (int) $cpf[10]) {
            throw new InvalidPayerDocumentException('Invalid CPF');
        }
    }

    private static function calcularDvCpf(string $cpf, int $length, int $pesoInicial): int
    {
        $soma = 0;
        for ($i = 0; $i < $length; $i++) {
            $soma += (int) $cpf[$i] * ($pesoInicial - $i);
        }

        $resto = ($soma * 10) % 11;

        return $resto === 10 ? 0 : $resto;
    }

    private static function validarCnpj(string $cnpj): void
    {
        $dv1 = self::calcularDvCnpj($cnpj, [5,4,3,2,9,8,7,6,5,4,3,2]);
        if ($dv1 !== (int) $cnpj[12]) {
            throw new InvalidPayerDocumentException('Invalid CNPJ');
        }

        $dv2 = self::calcularDvCnpj($cnpj, [6,5,4,3,2,9,8,7,6,5,4,3,2]);
        if ($dv2 !== (int) $cnpj[13]) {
            throw new InvalidPayerDocumentException('Invalid CNPJ');
        }
    }

    private static function calcularDvCnpj(string $cnpj, array $pesos): int
    {
        $soma = 0;
        foreach ($pesos as $i => $peso) {
            $soma += (int) $cnpj[$i] * $peso;
        }

        $resto = ($soma * 10) % 11;

        return $resto === 10 ? 0 : $resto;
    }
}