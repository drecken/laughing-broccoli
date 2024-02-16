<?php

namespace App\Services;

use App\Address;

interface ShippingServiceInterface
{
    public function rate(Address $to): string;
}