<?php

namespace Saritasa\Patterns\Traits;

/**
 * Implementation of the multiton pattern.
 */
trait Multiton
{
    /**
     * Instances of multiton objects.
     *
     * @var static[]
     */
    private static $instance = [];

    /**
     * Protects against creation through "new".
     */
    private function __construct(){}
    
    /**
     * Protects against creation through "clone".
     *
     * @return void
     */
    private function __clone(){}
    
    /**
     * Protects against creation through "unserialize".
     *
     * @return void
     */
    private function __wakeup(){}

    /**
     * Returns an instance of a class.
     *
     * @param mixed $key The instance key.
     * @param array $params Constructor's arguments.
     * @return static
     */
    public static function getInstance($key, ...$params)
    {
        return static::$instance[$key] ?? (static::$instance[$key] = new static($key, ...$params));
    }
}