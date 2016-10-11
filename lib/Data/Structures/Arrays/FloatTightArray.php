<?php

namespace Saritasa\Data\Structures\Arrays;

/**
 * The implementation of tight array of floats.
 */
class FloatTightArray extends TightArray
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
        $this->itemSize = strlen(pack('d', 1.5));
        $this->format = 'd';
        parent::__construct($size, $autoSize);
    }
}