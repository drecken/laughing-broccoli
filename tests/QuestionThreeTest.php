<?php

namespace Tests;

use App\Address;
use App\Cart;
use App\Customer;
use App\Item;
use App\Services\ShippingServiceInterface;
use App\Services\TaxCalculatorInterface;
use Exception;
use PHPUnit\Framework\TestCase;

class QuestionThreeTest extends TestCase
{
    private array $addresses;
    private Customer $customer;
    private array $items;
    private Cart $cart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->addresses = [
            new Address(line_1: '742 Evergreen Terrace', city: 'Springfield', state: 'IL', zip: '62701'),
            new Address(line_1: '456 Maple Lane', city: 'Shelbyville', state: 'IN', zip: '46176'),
            new Address(line_1: '890 Oak Street', city: 'Capital City', state: 'TX', zip: '73301'),
        ];

        $this->customer = new Customer(
            first_name: 'Homer',
            last_name: 'Simpson',
            defaultAddress: $this->addresses[0],
        );

        $this->items = [
            new Item(id: 1, name: 'Duff Beer', quantity: 6, price: 100),
            new Item(id: 2, name: 'Krusty-O\'s Cereal', quantity: 4, price: 350),
            new Item(id: 3, name: 'Squishee', quantity: 8, price: 200),
            new Item(id: 4, name: 'Buzz Cola', quantity: 10, price: 125),
            new Item(id: 5, name: 'Frostillicus Ice Cream', quantity: 3, price: 475),
        ];

        $shippingService = $this->getMockBuilder(ShippingServiceInterface::class)->getMock();
        $shippingService->method('rate')->willReturn('9.99');
        $taxCalculator = $this->getMockBuilder(TaxCalculatorInterface::class)->getMock();
        $taxCalculator->method('calculateTax')->willReturn('1.23');

        $this->cart = new Cart($this->customer, $shippingService, $taxCalculator);
    }

    public function test_customer_name_setting_and_retrieval()
    {
        $this->assertEquals('Homer Simpson', $this->customer->name());

        $this->customer->first_name = 'Fred';
        $this->customer->last_name = 'Flintstone';
        $this->assertEquals('Fred Flintstone', $this->customer->name());
    }

    public function test_customer_addresses_setting_and_retrieval()
    {
        $this->assertEquals('742 Evergreen Terrace', $this->customer->defaultAddress()->line_1);

        $this->customer->addAddress($this->addresses[1]);
        $this->assertEquals('742 Evergreen Terrace', $this->customer->defaultAddress()->line_1);

        $this->customer->addAddress($this->addresses[2], true);
        $this->assertEquals('890 Oak Street', $this->customer->defaultAddress()->line_1);

        $this->addresses[0]->line_1 = 'tes';
        $this->assertEquals($this->addresses, $this->customer->addresses());
    }

    public function test_items_in_cart_setting_and_retrieval()
    {
        $this->assertEquals([], $this->cart->items());

        $this->cart->addItem($this->items[0]);
        $this->assertEquals([$this->items[0]], $this->cart->items());

        $this->cart->addItem($this->items[1])
            ->addItem($this->items[2])
            ->addItem($this->items[3])
            ->addItem($this->items[4]);
        $this->assertEquals($this->items, $this->cart->items());

        $item = new Item(id: 4, name: 'Buzz Cola', quantity: 5, price: 125);
        $this->cart->addItem($item);
        $this->assertEquals(15, $this->cart->items()[3]->quantity);

        $item->quantity = 10;
        $this->cart->removeItem($item);
        $this->assertEquals(5, $this->cart->items()[3]->quantity);

        $this->assertCount(5, $this->cart->items());
        $this->cart->removeItem($item);
        $this->assertCount(4, $this->cart->items());
    }

    public function test_where_order_ships()
    {
        $this->assertEquals('742 Evergreen Terrace', $this->cart->shippingAddress->line_1);

        $this->customer->addAddress($this->addresses[1]);
        $this->assertEquals('742 Evergreen Terrace', $this->cart->shippingAddress->line_1);

        $this->customer->addAddress($this->addresses[2], true);
        $this->assertEquals('742 Evergreen Terrace', $this->cart->shippingAddress->line_1);

        $this->cart->shippingAddress = $this->addresses[1];
        $this->assertEquals('456 Maple Lane', $this->cart->shippingAddress->line_1);
    }

    public function test_cost_of_item_in_cart()
    {
        $this->cart->addItem($this->items[0]);
        $this->assertEquals('600.00', $this->items[0]->total());
        //600 + 9.99 + 1.23 = 611.22
        $this->assertEquals('611.22', $this->cart->itemCost($this->items[0]));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Item not in cart.');
        $this->cart->itemCost($this->items[1]);
    }

    public function test_subtotal_and_total_for_all_items_in_cart()
    {
        $this->cart->addItem($this->items[0])
            ->addItem($this->items[1])
            ->addItem($this->items[2])
            ->addItem($this->items[3])
            ->addItem($this->items[4]);

        //600 + 1400 + 1600 + 1250 + 1425 = 6275
        $this->assertEquals('6275.00', $this->cart->subtotal());
        //6275 + 9.99 + 1.23 = 6286.22
        $this->assertEquals('6286.22', $this->cart->total());

        $item = new Item(id: 4, name: 'Buzz Cola', quantity: 5, price: 125);
        $this->cart->addItem($item);
        //6275 + 625 = 6900
        $this->assertEquals('6900.00', $this->cart->subtotal());
        //6900 + 9.99 + 1.23 = 6911.22
        $this->assertEquals('6911.22', $this->cart->total());

        $item->quantity = 100;
        $this->cart->removeItem($item);
        //6900 - 1875 = 5025
        $this->assertEquals('5025.00', $this->cart->subtotal());
        //5025 + 9.99 + 1.23 = 5036.22
        $this->assertEquals('5036.22', $this->cart->total());
    }

}
