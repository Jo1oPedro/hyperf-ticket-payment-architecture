<?php

namespace HyperfTest\Unit\Domain\Payments;

use App\Domain\Payment\Exception\InvalidPayerDocumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use App\Domain\Payment\PayerDocument as PayerDocumentDomain;

class PayerDocumentTest extends TestCase
{
    public static function cpfsValidos(): array
    {
        return [
            'CPF simples'            => ['12345678909'],
            'CPF com máscara'        => ['123.456.789-09'],
            'CPF com pontos'         => ['529.982.247-25'],
            'CPF sem máscara #3'     => ['52998224725'],
            'CPF sem máscara #4'     => ['11144477735'],
            'CPF invertido'          => ['98765432100'],
            'CPF com zeros no início'=> ['10000000523'],
        ];
    }

    public static function cpfsInvalidos(): array
    {
        return [
            'todos zeros'            => ['00000000000'],
            'todos uns'              => ['11111111111'],
            'todos noves'            => ['99999999999'],
            'DV1 errado'             => ['12345678919'],  // DV1 deveria ser 0
            'DV2 errado'             => ['12345678900'],  // DV2 deveria ser 9
            'muito curto'            => ['1234567890'],   // 10 dígitos
            'muito longo'            => ['123456789090'], // 12 dígitos
            'string vazia'           => [''],
        ];
    }

    public static function cnpjsValidos(): array
    {
        return [
            'CNPJ com máscara'       => ['11.222.333/0001-81'],
            'CNPJ sem máscara #1'    => ['11222333000181'],
            'CNPJ sequencial'        => ['12345678000195'],
            'CNPJ todos uns'         => ['11111111000191'],
            'CNPJ invertido'         => ['98765432000198'],
            'CNPJ zeros + 0001'      => ['00000000000191'],
        ];
    }

    public static function cnpjsInvalidos(): array
    {
        return [
            'DV1 errado'             => ['11222333000191'],  // DV1 deveria ser 8
            'DV2 errado'             => ['11222333000182'],  // DV2 deveria ser 1
            'todos uns'              => ['11111111111111'],  // DV deveria ser 8
            'muito curto'            => ['1122233300018'],   // 13 dígitos
            'muito longo'            => ['112223330001810'], // 15 dígitos
            'string vazia'           => [''],
        ];
    }

    #[DataProvider("cpfsValidos")]
    public function test_generate_cpf_correct(string $cpfValido): void
    {
        $document = PayerDocumentDomain::fromRaw($cpfValido);
        $this->assertTrue($document->isCpf());
    }

    #[DataProvider("cpfsInvalidos")]
    public function test_throws_exception_when_cpf_is_wrong(string $cpfInvalido): void
    {
        $this->expectException(InvalidPayerDocumentException::class);
        PayerDocumentDomain::fromRaw($cpfInvalido);
    }

    #[DataProvider("cnpjsValidos")]
    public function test_generate_cnpj_correct(string $cnpjValido): void
    {
        $document = PayerDocumentDomain::fromRaw($cnpjValido);
        $this->assertTrue($document->isCnpj());
    }

    #[DataProvider("cnpjsInvalidos")]
    public function test_throws_exception_when_cnpj_is_wrong(string $cnpjInvalido): void
    {
        $this->expectException(InvalidPayerDocumentException::class);
        PayerDocumentDomain::fromRaw($cnpjInvalido);
    }
}