<?php

$data = include('exampleData.php');

function printNestedArray(array $data, int $indentationLevel = 0): void
{
    foreach ($data as $key => $value) {
        $indentation = str_repeat(' ', 4 * $indentationLevel);

        if(is_array($value)) {
            $formattedKey = is_int($key) ? ($key+1) : $key;
            echo $indentation . $formattedKey . ' => ' . PHP_EOL;
            printNestedArray($value, $indentationLevel + 1);
        } else {
            $displayValue = match (true) {
                is_bool($value) => $value ? 'true' : 'false',
                is_null($value) => 'null',
                default => $value,
            };
            echo $indentation . $key . ' => ' . $displayValue . PHP_EOL;
        }
    }
}

printNestedArray($data);
