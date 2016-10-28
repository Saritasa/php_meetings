<?php

error_reporting(E_ALL);

set_time_limit(0);

spl_autoload_register(function($class)
{
    $relativePath = substr($class, strlen('Saritasa\\'));
    $relativePath = str_replace('\\', '/', $relativePath);
    require_once(__DIR__ . "/lib/{$relativePath}.php");
});
