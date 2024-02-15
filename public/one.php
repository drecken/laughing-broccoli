<?php

require __DIR__ . '/../vendor/autoload.php';
$data = include(__DIR__ . '/../storage/exampleData.php');

$printer = new \App\NestedArrayPrinter();
$printer->print($data);
