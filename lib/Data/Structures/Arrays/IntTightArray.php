<?php

namespace Saritasa\Data\Structures\Arrays;

/**
 * The implementation of tight array of integers.
 */
class IntTightArray extends TightArray
{
    /**
     * Constructor.
     *
     * @param int $size The initial capacity of a tight array.
     * @param bool $autoSize Determines whether the array capacity should be automatically increased.
     */
    public function __construct($size = 0, $autoSize = true)
    {
        $this->itemSize = PHP_INT_SIZE;
        $this->format = PHP_INT_SIZE == 8 ? 'q' : 'l';
        parent::__construct($size, $autoSize);
    }
}