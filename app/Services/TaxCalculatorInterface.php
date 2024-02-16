<?php

namespace App\Services;

interface TaxCalculatorInterface
{
    public function calculateTax(string $amount): string;
}