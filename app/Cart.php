<?php

namespace App;

use App\Services\ShippingServiceInterface;
use App\Services\TaxCalculatorInterface;
use Exception;

class Cart
{
    public Address $shippingAddress;
    protected array $items = [];

    public function __construct(
        public Customer $customer,
        protected ShippingServiceInterface $shippingService,
        protected TaxCalculatorInterface $taxCalculator,
    ) {
        $this->shippingAddress = $customer->defaultAddress();
    }

    public function addItem(Item $item): self
    {
        if (($cartItem = $this->getItemById($item->id)) !== null) {
            $cartItem->quantity += $item->quantity;

            return $this;
        }

        $this->items[] = $item;

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if (($cartItem = $this->getItemById($item->id)) === null) {
            return $this;
        }

        if ($cartItem->quantity > $item->quantity) {
            $cartItem->quantity -= $item->quantity;

            return $this;
        }

        $this->items = array_filter($this->items, fn($cartItem) => $cartItem->id !== $item->id);

        return $this;
    }

    protected function getItemById(int $id): ?Item
    {
        foreach ($this->items as $item) {
            if ($item->id === $id) {
                return $item;
            }
        }
        return null;
    }

    public function items(): array
    {
        return $this->items;
    }

    /**
     * @throws Exception
     */
    public function itemCost(Item $item): string
    {
        if (($cartItem = $this->getItemById($item->id)) === null) {
            throw new Exception('Item not in cart.');
        }

        return bcadd(bcadd($cartItem->total(), $this->shippingCost()), $this->tax());
    }

    public function total(): string
    {
        return bcadd(bcadd($this->subtotal(), $this->shippingCost()), $this->tax());
    }

    public function subtotal(): string
    {
        return array_reduce($this->items, fn($carry, $item) => bcadd($carry, $item->total()), 0);
    }

    public function shippingCost(): string
    {
        return $this->shippingService->rate($this->shippingAddress);
    }

    public function tax(): string
    {
        return $this->taxCalculator->calculateTax($this->subtotal());
    }

}