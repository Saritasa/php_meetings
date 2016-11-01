<?php

// foreach vs for.

$vs->add('foreach', function()
{
    $a = array_fill(0, 100000, 0);
    foreach ($a as $item);
});

$vs->add('for', function()
{
    $a = array_fill(0, 100000, 0);
    $len = count($a);
    for ($i = 0; $i < $len; ++$i)
    {
        $item = $a[$i];
    }
});

$vs->time('foreach vs for', true);