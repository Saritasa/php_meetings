<?php

// Increment

$vs->add('++$i', function()
{
    for ($i = 0; $i < 1000000; ++$i);
});

$vs->add('$i++', function()
{
    for ($i = 0; $i < 1000000; $i++);
});

$vs->add('$i += 1', function()
{
    for ($i = 0; $i < 1000000; $i += 1);
});

$vs->add('$i = $i + 1', function()
{
    for ($i = 0; $i < 1000000; $i = $i + 1);
});

$vs->time('Increment');