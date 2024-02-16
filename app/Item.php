<?php

namespace App;

class Item
{
    public function __construct(
        public int $id,
        public string $name,
        public int $quantity,
        public int $price,
    ) {
    }

    public function total(): string
    {
        return bcmul($this->price, $this->quantity);
    }

}