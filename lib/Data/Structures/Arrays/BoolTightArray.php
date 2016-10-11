<?php

namespace Saritasa\Data\Structures\Arrays;

/**
 * The implementation of tight array of booleans.
 */
class BoolTightArray extends TightArray
{
    /**
     * Constructor.
     *
     * @param int $size The initial capacity of a tight array.
     * @param bool $autoSize Determines whether the array capacity should be automatically increased.
     * @return void
     */
    public function __construct($size = 0, $autoSize = true)
    {
        $this->itemSize = 1;
        $this->format = 'C';
        parent::__construct($size, $autoSize);
    }
    
    /**
     * Returns a regular array reprsentation of the current tight array.
     *
     * @return array
     */
    protected function getArray()
    {
        return array_map('boolval', parent::getArray());
    }
    
    /**
     * Returns an array element with the specified index.
     *
     * @param int $index The element index.
     * @return mixed
     * @throws \OutOfBoundsException If the index is out of array bounds.
     */
    public function offsetGet($index)
    {
        return (bool)parent::offsetGet($index);
    }
    
    /**
     * Assigns new value to an array element with the specified index.
     *
     * @param int $index The element index.
     * @param mixed $value The element value.
     * @return void
     * @throws \OutOfBoundsException If the index is out of array bounds.
     */
    public function offsetSet($index, $value)
    {
        parent::offsetSet($index, $value ? 1 : 0);
    }
}