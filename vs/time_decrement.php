<?php

// Decrement

$vs->add('--$i', function()
{
    for ($i = 1000000; $i > 0; --$i);
});

$vs->add('$i--', function()
{
    for ($i = 1000000; $i > 0; $i--);
});

$vs->add('$i -= 1', function()
{
    for ($i = 1000000; $i > 0; $i -= 1);
});

$vs->add('$i = $i - 1', function()
{
    for ($i = 1000000; $i > 0; $i = $i - 1);
});

$vs->time('Decrement');