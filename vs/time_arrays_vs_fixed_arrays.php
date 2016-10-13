<?php

use Saritasa\Data\Structures\Arrays\IntTightArray;

// Array of integers vs SplFixedArray of integers vs packed array of integers (random access)

$vs->add('array', function()
{
    $a = range(0, 99999);
    for ($i = 0; $i < 100000; ++$i)
    {
        $x = $a[rand(0, 99999)];
    }
});

$vs->add('SplFixedArray', function()
{
    $a = \SplFixedArray::fromArray(range(0, 99999));
    for ($i = 0; $i < 100000; ++$i)
    {
        $x = $a[rand(0, 99999)];
    }
});

$vs->add('TightArray', function()
{
    $a = IntTightArray::fromArray(range(0, 99999));
    for ($i = 0; $i < 100000; ++$i)
    {
        $x = $a[rand(0, 99999)];
    }
});

$vs->time('Array of integers vs SplFixedArray of integers vs IntTightArray (random access)', true);

// Array of integers vs SplFixedArray of integers vs packed array of integers (sequential access)

$vs->add('array', function()
{
    $a = range(0, 99999);
    for ($i = 0; $i < 100000; ++$i)
    {
        $x = $a[$i];
    }
});

$vs->add('SplFixedArray', function()
{
    $a = \SplFixedArray::fromArray(range(0, 99999));
    for ($i = 0; $i < 100000; ++$i)
    {
        $x = $a[$i];
    }
});

$vs->add('TightArray', function()
{
    $a = IntTightArray::fromArray(range(0, 99999));
    for ($i = 0; $i < 100000; ++$i)
    {
        $x = $a[$i];
    }
});

$vs->time('Array of integers vs SplFixedArray of integers vs IntTightArray (sequential access)', true);