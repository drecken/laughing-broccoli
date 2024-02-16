<?php

namespace App;

class Customer
{
    protected array $addresses = [];

    public function __construct(
        public string $first_name,
        public string $last_name,
        protected Address $defaultAddress,
    ) {
        $this->addresses[] = $defaultAddress;
    }

    public function name(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function addAddress(Address $address, bool $makeDefault = false): self
    {
        $this->addresses[] = $address;
        if ($makeDefault) {
            $this->defaultAddress = $address;
        }
        return $this;
    }

    public function addresses(): array
    {
        return $this->addresses;
    }

    public function defaultAddress(): Address
    {
        return $this->defaultAddress;
    }

}