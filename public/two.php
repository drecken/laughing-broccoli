<?php

require __DIR__ . '/../vendor/autoload.php';
$data = include(__DIR__ . '/../storage/exampleData.php');

$sorter = new \App\NestedArraySorter();
$printer = new \App\NestedArrayPrinter();

$sorter->sort($data, ['last_name', 'account_id']);
$printer->print($data);
