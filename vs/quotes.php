<?php

// Single quotes vs double quotes.

$vs->add('Single quotes', function()
{
    for ($i = 100000; $i > 0; --$i)
    {
       $s = 'abcde' . '12345' . '$#@';
       $s = $s . '890' . '!' . '';
    }
});

$vs->add('Double quotes (without substitutions)', function()
{
    for ($i = 100000; $i > 0; --$i)
    {
       $s = "abcde" . "12345" . "$#@";
       $s = $s . "890" . "!" . "";
    }
});

$vs->add('Double quotes (with substitutions)', function()
{
    $a = '123';
    $b = 'abc';
    for ($i = 100000; $i > 0; --$i)
    {
       $s = "ab{$a}cde" . "12345{$a}{$b}" . "$#@{$a}";
       $s = $s . "890{$b}" . "!" . "";
    }
});

$vs->time('Single vs double quotes');