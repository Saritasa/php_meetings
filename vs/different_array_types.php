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