<?php

use Saritasa\Data\Structures\Arrays\IntTightArray;

// Array of integers vs SplFixedArray of integers vs packed array of integers

$vs->add('array', function()
{
    return range(0, 999999);
});

$vs->add('SplFixedArray', function()
{
    return \SplFixedArray::fromArray(range(0, 999999));
});

$vs->add('TightArray', function()
{
    return IntTightArray::fromArray(range(0, 999999));
});

$vs->space('Array of integers vs SplFixedArray of integers vs IntTightArray', true);

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