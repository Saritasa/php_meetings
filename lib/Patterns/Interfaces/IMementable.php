<?php

namespace Saritasa\Patterns\Interfaces;

/**
 * Implementation of Memento design pattern.
 * The interface for an originator class. 
 */
interface IMementable
{
    /**
     * Creates a memento object that encapsulates the internal
     * state of an originator object.
     *
     * @return \Saritasa\Patterns\Interfaces\IMemento
     */
    public function createMemento() : IMemento;
}