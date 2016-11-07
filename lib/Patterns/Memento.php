<?php

namespace Saritasa\Patterns;

use Saritasa\Patterns\Interfaces\{IMemento, IMementable};

/**
 * Implementation of memento object for Memento design pattern.
 */
class Memento implements IMemento
{
    /**
     * The originator object.
     *
     * @var object
     */
    private $originator = null;
    
    /**
     * The originator saved state.
     *
     * @var mixed
     */
    private $state = null;
    
    /**
     * Constructor.
     *
     * @param \Saritasa\Patterns\Interfaces\IMementable $originator
     */
    public function __construct(IMementable $originator)
    {
        $this->originator = $originator;
        $this->state = $this->call($originator, 'getState');
    }
    
    /**
     * Restores the originator state.
     *
     * @return void     
     */
    public function restore()
    {
        $this->call($this->originator, 'setState', $this->state);
    }

    /**
     * Invokes the given method.
     *
     * @param object $object An object that uses Mementable trait.
     * @param string $method The method to be invoked.
     * @param array $params The method parameters.
     * @throws \ReflectionException If the given method does not exist.
     * @return mixed
     */
    private function call($object, $method, ...$params)
    {
        $m = new \ReflectionMethod($object, $method);
        $m->setAccessible(true);
        return $m->invokeArgs($object, $params);
    }
    
    /**
     * Protects against serialization through "serialize".
     *
     * @return void
     */
    private function __sleep() {}
    
    /**
     * Protects against creation through "unserialize".
     *
     * @return void
     */
    private function __wakeup() {}
}