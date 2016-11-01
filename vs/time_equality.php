<?php

// === vs == (the both operands are integers)

$vs->add('===', function()
{
    $j = 5000;
    for ($i = 0; $i < 1000000; ++$i)
    {
        $j === 5000;
    }
});

$vs->add('==', function()
{
    $j = 5000;
    for ($i = 0; $i < 1000000; ++$i)
    {
        $j == 5000;
    }
});

$vs->time('=== vs == (the both operands are integers)');

// === vs == (the both operands have different types)

$vs->add('===', function()
{
    $j = '5000';
    for ($i = 0; $i < 1000000; ++$i)
    {
        $j === '5000';
    }
});

$vs->add('==', function()
{
    $j = '5000';
    for ($i = 0; $i < 1000000; ++$i)
    {
        $j == 5000;
    }
});

$vs->time('=== vs == (the both operands have different types)');