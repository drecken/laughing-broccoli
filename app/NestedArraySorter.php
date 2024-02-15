<?php

namespace App;

class NestedArraySorter
{
    
    function sort(array &$data, array $sortKeys): void
    {
        usort($data, function ($firstItem, $secondItem) use ($sortKeys) {
            foreach ($sortKeys as $key) {
                $firstValue = $this->findNestedValue($firstItem, $key);
                $secondValue = $this->findNestedValue($secondItem, $key);

                if ($firstValue != $secondValue) {
                    return $firstValue <=> $secondValue;
                }
            }

            return 0;
        });
    }

    function findNestedValue(array $data, string $searchKey): mixed
    {
        foreach ($data as $key => $value) {
            if ($key == $searchKey) {
                return $value;
            }
            if (is_array($value)) {
                $foundValue = $this->findNestedValue($value, $searchKey);
                if ($foundValue !== null) {
                    return $foundValue;
                }
            }
        }

        return null;
    }

}
