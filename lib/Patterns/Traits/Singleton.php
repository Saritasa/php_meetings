<?php

namespace Saritasa\Patterns\Traits;

/**
 * Implementation of singleton pattern.
 */
trait Singleton
{
    /**
     * The instance of singleton object.
     *
     * @var static
     */
    private static $instance = null;

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
     * @param array $params Constructor's arguments.
     * @return static
     */
    public static function getInstance(...$params)
    {
        return static::$instance ?? (static::$instance = new static(...$params));
    }
}