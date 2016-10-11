<?php

namespace Saritasa\Patterns\Interfaces;

/**
 * Implementation of Memento design pattern.
 */
interface IMemento
{
    /**
     * Constructor.
     *
     * @param object $originator
     * @return void
     */
    public function __construct($originator);
    
    /**
     * Restores the originator state.
     *
     * @return void     
     */
    public function restore();
}