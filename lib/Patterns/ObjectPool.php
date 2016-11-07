<?php

namespace Saritasa\Patterns;

use Saritasa\Patterns\Interfaces\{IPoolObjectCreator, IPoolable};

/**
 * The generalized implementation of the object pool.
 */
class ObjectPool implements \Countable
{
    /**
     * The object container.
     *
     * @var \SplStack
     */
    private $pool = null;
    
    /**
     * Object creator instance.
     *
     * @var \Saritasa\Patterns\Interfaces\IPoolObjectCreator
     */
    private $creator = null;
    
    /**
     * The maximum number of poolable objects that can be stored
     * in the pool. The negative integer means that the pool can
     * store any number of poolable objects.
     *
     * @var int
     */
    private $maxPoolSize = -1;
    
    /**
     * Constructor.
     *
     * @param \Saritasa\Patterns\Interfaces\IPoolObjectCreator $creator
     * @param int $maxPoolSize The maximum pool size.
     */
    public function __construct(IPoolObjectCreator $creator, int $maxPoolSize = -1)
    {
        $this->pool = new \SplStack();
        $this->creator = $creator;
        $this->maxPoolSize = $maxPoolSize;
    }

    /**
     * Returns number of objects currently being in the pool.
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->pool);
    }
    
    /**
     * Gets an object from the pool.
     *
     * @return \Saritasa\Patterns\Interfaces\IPoolable
     * @throws \RuntimeException If max number of poolable objects is reached.
     */
    public function acquireObject() : IPoolable
    {
        if ($this->pool->isEmpty())
        {
            if ($this->maxPoolSize >= 0 && count($this->pool) >= $this->maxPoolSize)
            {
                throw new \RuntimeException('The maximum number of poolable objects is reached.');
            }
            return $this->creator->createPoolable();
        }
        return $this->pool->pop();
    }
    
    /**
     * Moves a poolable object to an unusable state and puts it to the pool.
     *
     * @param \Saritasa\Patterns\Interfaces\IPoolable $obj
     * @return void
     */
    public function releaseObject(IPoolable $obj)
    {
        $obj->makeUnusable();
        $this->pool->push($obj);
    }
    
    /**
     * Returns the maximum pool size.
     *
     * @return int
     */
    public function getMaxPoolSize() : int
    {
        return $this->maxPoolSize;
    }
    
    /**
     * Sets the maximum pool size. If the size is negative the pool
     * will be able to store any number of poolable objects.
     *
     * @param int $size
     * @return void
     */
    public function setMaxPoolSize(int $size)
    {
        $this->maxPoolSize = $size;
    }
}