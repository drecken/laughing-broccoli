<?php

namespace App\Services;

class TaxCalculator implements TaxCalculatorInterface
{
    const string TAX_RATE = '0.07';

    public function calculateTax(string $amount): string
    {
        return bcmul($amount, self::TAX_RATE);
    }
}
