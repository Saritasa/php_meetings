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
     * @param \Saritasa\Patterns\Interfaces\IMementable $originator
     * @return void
     */
    public function __construct(IMementable $originator);
    
    /**
     * Restores the originator state.
     *
     * @return void     
     */
    public function restore();
}