<?php

namespace App;

class Address
{
    public function __construct(
        public string $line_1,
        public string $city,
        public string $state,
        public string $zip,
        public string $line_2 = '',
    ) {
    }

}