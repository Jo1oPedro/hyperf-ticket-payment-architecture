<?php

namespace HyperfTest\Unit\Domain\Payments;

use App\Domain\Payment\Barcode;
use App\Exception\InvalidBarcodeException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BarcodeTest extends TestCase
{
    public static function barcodesValidos(): array
    {
        return [
            '43 zeros DV=1'   => ['00001000000000000000000000000000000000000000'],
            '43 uns DV=1'     => ['11111111111111111111111111111111111111111111'],
            '43 doses DV=2'   => ['22222222222222222222222222222222222222222222'],
            '43 cincos DV=5'  => ['55555555555555555555555555555555555555555555'],
            '43 noves DV=9'   => ['99999999999999999999999999999999999999999999'],
            'prefixo Itaú'    => ['34191000000000000000000000000000000000000000'],
        ];
    }

    public static function barcodesInvalidos(): array
    {
        return [
            'DV errado +1'    => ['00002000000000000000000000000000000000000000'],
            'DV errado 0'     => ['00000000000000000000000000000000000000000000'],
            'DV errado Itaú'  => ['34195000000000000000000000000000000000000000'],
            'muito curto'     => ['1111111111111111111111111111111111111111111'],
            'muito longo'     => ['111111111111111111111111111111111111111111111'],
            'contém letra'    => ['1111111111111111111A111111111111111111111111'],
            'contém espaço'   => ['1111 1111111111111111111111111111111111111'],
            'string vazia'    => [''],
        ];
    }

    #[DataProvider("barcodesValidos")]
    public function test_barcodes_are_valid(string $barcodeValue): void
    {
        $barcode = new Barcode($barcodeValue);
        $this->assertInstanceOf(Barcode::class, $barcode);
    }

    #[DataProvider("barcodesInvalidos")]
    public function test_barcodes_are_invalid(string $barcodeValue): void
    {
        $this->expectException(InvalidBarcodeException::class);
        $barcode = new Barcode($barcodeValue);
    }
}