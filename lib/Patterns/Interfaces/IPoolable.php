<?php

namespace Saritasa\Patterns\Interfaces;

/**
 * The poolable object interface.
 */
interface IPoolable
{
    /**
     * Resets the object's state.
     *
     * @return void
     */
    public function makeUnusable();
}