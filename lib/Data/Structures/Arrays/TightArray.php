<?php

namespace Saritasa\Data\Structures\Arrays;

/**
 * The base class for all implementations of "tight" arrays.
 * You can consider a tight array as an example of of the most compact
 * representation of arrays in php.
 */
abstract class TightArray implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * Error message templates.
     */
    const ERR_TIGHT_ARRAY_1 = 'Index "%s" is out of bounds.';
    const ERR_TIGHT_ARRAY_2 = 'Array size cannot be negative.';
    
    /**
     * The tight array capacity.
     *
     * @var int
     */
    protected $size = 0;
    
    /**
     * The size of an array element in bytes.
     *
     * @var int
     */
    protected $itemSize = 0;
    
    /**
     * The total number of array elements.
     *
     * @var int
     */
    protected $numItems = 0;
    
    /**
     * Determines whether the array capacity should be automatically
     * increased.
     */
    protected $autoSize = false;
    
    /**
     * The data format for pack/unpack operations.
     *
     * @var string
     */
    protected $format = '';
    
    /**
     * The memory stream handler.
     *
     * @var resource
     */
    private $stream = null;
    
    /**
     * The internal pointer for the iterator.
     *
     * @var int
     */
    private $pos = 0;
    
    /**
     * Creates a tight array instance from the given regular array.
     *
     * @param array $arr The regular array.
     * @param bool $saveIndexes Determines whether the array keys should be preserved or not.
     * @param bool $autoSize Determines whether the array capacity should be automatically increased.
     * @return \Saritasa\Data\Structures\Arrays\TightArray
     */
    public static function fromArray(array $arr, $saveIndexes = false, $autoSize = true)
    {
        if ($saveIndexes)
        {
            $a = new static(0, true);
            foreach ($arr as $k => $v)
            {
                $a->offsetSet($k, $v);
            }
            $a->setAutoSize($autoSize);
        }
        else
        {
            $a = new static(0, $autoSize);
            $a->setArray($arr);
        }
        return $a;
    }
    
    /**
     * Constructor.
     *
     * @param int $size The initial capacity of a tight array.
     * @param bool $autoSize Determines whether the array capacity should be automatically increased.
     * @return void
     */     
    public function __construct($size = 0, $autoSize = true)
    {
        $this->stream = fopen('php://memory', 'r+');
        $this->setSize($size);
        $this->setAutoSize($autoSize);
    }
    
    /**
     * Destructor.
     *
     * @return void
     */
    public function __destruct()
    {
        fclose($this->stream);
    }
    
    /**
     * Returns the number of array elements.
     *
     * @return int
     */
    public function count()
    {
        return $this->numItems;
    }
    
    /**
     * Resets the internal array pointer.
     *
     * @return void     
     */
    public function rewind()
    {
        $this->pos = 0;
    }
    
    /**
     * Returns the current array element.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->offsetGet($this->pos);
    }
    
    /**
     * Returns the current element index.
     *
     * @return int
     */
    public function key()
    {
        return $this->pos;
    }
    
    /**
     * Increments the internal array pointer.
     *
     * @return void
     */
    public function next()
    {
        ++$this->pos;
    }
    
    /**
     * Checks if the internal array pointer is in array bounds.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->pos < $this->numItems;
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
        fseek($this->stream, $this->getIndex($index));
        return unpack($this->format, fread($this->stream, $this->itemSize))[1];
    }
    
    /**
     * Returns TRUE if an element with the specified index exists and FALSE otherwise.
     *
     * @param int $index The element index
     * @return bool
     */
    public function offsetExists($index)
    {
        return $index >= 0 && $index < $this->numItems;
    }
    
    /**
     * Removes an array element with the specified index.
     *
     * @param int $index The element index.
     * @return void
     */
    public function offsetUnset($index)
    {
        $this->offsetSet($index, 0);
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
        fseek($this->stream, $this->getIndex($index));
        fwrite($this->stream, pack($this->format, $value));
    }
    
    /**
     * Returns TRUE if the auto size mode is enabled, otherwise it returns FALSE.
     *
     * @return bool
     */
    public function getAutoSize()
    {
        return $this->autoSize;
    }
    
    /**
     * Sets the auto size mode.
     *
     * @param bool $autoSize
     * @return void
     */
    public function setAutoSize($autoSize = true)
    {
        $this->autoSize = $autoSize;
    }
    
    /**
     * Returns the array capacity.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }
    
    /**
     * Sets capacity of the array.
     *
     * @param int $size
     * @return void
     * @throws \RuntimeException If size is invalid.
     */
    public function setSize($size)
    {
        if ($size < 0)
        {
            throw new \RuntimeException(static::ERR_TIGHT_ARRAY_2);
        }
        if ($size != $this->size)
        {
            ftruncate($this->stream, $size * $this->itemSize);
            $this->size = $size;
            if ($size < $this->numItems)
            {
                $this->numItems = $size;
            }
        }
    }
    
    /**
     * Converts the instance of tight array to a regular array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getArray();
    }
    
    /**
     * Returns a regular array reprsentation of the current tight array.
     *
     * @return array
     */
    protected function getArray()
    {
        return array_values(unpack($this->format . '*',
               stream_get_contents($this->stream, $this->itemSize * $this->numItems, 0)));
    }
    
    /**
     * Converts a regular array to the current tight array.
     *
     * @param array $arr
     * @return void
     */
    protected function setArray(array $arr)
    {
        fseek($this->stream, 0);
        fwrite($this->stream, pack($this->format . '*', ...$arr));
        $this->numItems = $this->size = count($arr);
    }
    
    /**
     * Returns the position of an array element in the stream.
     *
     * @param int $index The array element index.
     * @return int
     * @throws \OutOfBoundsException If the index is out of array bounds.
     */
    private function getIndex($index)
    {
        if ($index >= 0)
        {
            if ($index > $this->numItems - 1)
            {
                $this->numItems = $index + 1;
            }
            if ($index < $this->size)
            {
                return $index * $this->itemSize;
            }
            if ($this->autoSize)
            {
                $this->setSize(1 << ceil(log($index, 2)));
                return $index * $this->itemSize;
            }
        }
        throw new \OutOfBoundsException(sprintf(static::ERR_TIGHT_ARRAY_1, $index));
    }
} 