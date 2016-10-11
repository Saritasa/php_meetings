<?php

error_reporting(E_ALL);

set_time_limit(0);

spl_autoload_register(function($class)
{
    require_once(__DIR__ . '/lib' . substr($class, strlen('Saritasa')) . '.php');
});