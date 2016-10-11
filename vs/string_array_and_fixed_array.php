<?php

// Array of strings vs SplFixedArray of strings

$vs->add('array', function()
{
    $a = [];
    for ($i = 0; $i < 1000000; ++$i)
    {
        $a[] = 'i' . $i;
    }
    return $a;
});

$vs->add('SplFixedArray', function()
{
    $a = new \SplFixedArray(1000000);
    for ($i = 0; $i < 1000000; ++$i)
    {
        $a[$i] = 'i' . $i;
    }
    return $a;
});

$vs->space('Array of strings vs SplFixedArray of strings', true);