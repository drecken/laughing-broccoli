<?php

namespace Tests;

use App\NestedArraySorter;
use PHPUnit\Framework\TestCase;

class NestedArraySorterTest extends TestCase
{
    public function test_sorting(): void
    {
        $data = include __DIR__ . '/../storage/exampleData.php';

        $array1 = [
            'last_name' => 'Apple',
            'guest_account' => [
                [
                    'account_id' => 1,
                ],
            ],
        ];

        $array2 = [
            'last_name' => 'Apple',
            'guest_account' => [
                [
                    'account_id' => 2,
                ],
            ],
        ];

        $array3 = [
            'last_name' => 'Apple',
            'guest_account' => [
                [
                    'account_id' => 3,
                    'account_limit' => 10,
                ],
            ],
        ];

        $array4 = [
            'last_name' => 'Apple',
            'guest_account' => [
                [
                    'account_id' => 3,
                    'account_limit' => 20,
                ],
            ],
        ];

        $array5 = [
            'last_name' => 'Apple',
            'guest_account' => [
                [
                    'account_id' => 3,
                    'account_limit' => 30,
                ],
            ],
        ];

        $arrayLast = [
            'last_name' => 'Zebra',
        ];

        $data[] = $arrayLast;
        $data[] = $array5;
        $data[] = $array2;
        $data[] = $array1;
        $data[] = $array4;
        $data[] = $array3;

        $sorter = new NestedArraySorter();
        $sorter->sort($data, ['last_name', 'account_id', 'account_limit']);

        $this->assertEquals($array1, $data[0]);
        $this->assertEquals($array2, $data[1]);
        $this->assertEquals($array3, $data[2]);
        $this->assertEquals($array4, $data[3]);
        $this->assertEquals($array5, $data[4]);
        $this->assertEquals($arrayLast, $data[count($data) - 1]);
    }

}
