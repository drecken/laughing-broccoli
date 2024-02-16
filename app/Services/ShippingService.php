<?php

namespace App\Services;

use App\Address;

class ShippingService implements ShippingServiceInterface
{

    public function rate(Address $to): string
    {
        return '9.99';
    }

}