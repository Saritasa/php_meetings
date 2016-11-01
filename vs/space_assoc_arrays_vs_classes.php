<?php

// Associative arrays vs classes

class A
{
    public $a = 1;
    public $b = 2;
    public $c = 3;
    public $d = 4;
    public $e = 5;
}

$vs->add('array', function()
{
    $a = [];
    for ($i = 0; $i < 100000; ++$i)
    {
        $a[] = [
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
            'e' => 5
        ];
    }
    return $a;
});

$vs->add('class', function()
{
    $a = [];
    for ($i = 0; $i < 100000; ++$i)
    {
        $a[] = new A();
    }
    return $a;
});

$vs->add('stdClass', function()
{
    $a = [];
    for ($i = 0; $i < 100000; ++$i)
    {
        $tmp = new \stdClass;
        $tmp->a = 1;
        $tmp->b = 2;
        $tmp->c = 3;
        $tmp->d = 4;
        $tmp->e = 5;
        $a[] = $tmp;
    }
    return $a;
});

$vs->space('Associative arrays vs classes', true);