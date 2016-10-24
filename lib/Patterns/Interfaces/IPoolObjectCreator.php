<?php

namespace Saritasa\Patterns\Interfaces;

/**
 * Pool object creator interface.
 */
interface IPoolObjectCreator
{
    /**
     * Creates a poolable object.
     *
     * @return \Saritasa\Patterns\Interfaces\IPoolable
     */
    public function createPoolable() : IPoolable;
}