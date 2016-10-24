<?php

namespace Saritasa\Patterns\Traits;

use Saritasa\Patterns\{Memento, Interfaces\IMemento};

/**
 * Implementation of Memento design pattern.
 */
trait Mementable
{
    /**
     * Returns the state of an object.
     *
     * @return mixed
     */
    abstract protected function getState();
    
    /**
     * Sets the state of an object.
     *
     * @param mixed $state
     * @return void
     */
    abstract protected function setState($state);
    
    /**
     * Creates a memento object that encapsulates the internal
     * state of an originator object.
     *
     * @return \Saritasa\Patterns\Interfaces\IMemento
     */
    public function createMemento() : IMemento
    {
        return new Memento($this);
    }
}