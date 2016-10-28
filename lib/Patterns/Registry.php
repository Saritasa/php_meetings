<?php

namespace Saritasa\Patterns;

use Saritasa\Patterns\Interfaces\IRegistry;
use Saritasa\Patterns\Traits\Singleton;

/**
 * Global storage of application custom data
 *
 * Patterns: Singleton, Registry + additional features
 */
final class Registry implements \Serializable, IRegistry
{
    use Singleton;

    /**
     * Internal storage
     * @var array
     */
    private $storage = [];

    /**
     * Add value into the storage. Magic setter
     *
     * No reaction on rewrite an existing data.
     *
     * @param string $prop  in the context of this class - the key in the internal storage
     * @param mixed  $value value to add into storage
     */
    public function __set(string $prop, $value)
    {
        $this->storage[$prop] = $value;
    }

    /**
     * Get the value from the storage. Magic getter
     *
     * No reaction to the absence of data.
     *
     * @param string $prop in the context of this class - the key in the internal storage
     * @return mixed
     */
    public function __get(string $prop)
    {
        return $this->storage[$prop] ?? null;
    }

    /**
     * Add value into the storage
     *
     * The principal difference with the magic setter: if rewrite prohibited and the value is already there - throw
     * an exception. For milder reaction see self::isExists()
     *
     * @param string $key     the key in the internal storage
     * @param mixed  $value   value to add into storage
     * @param bool   $rewrite allow rewrite
     * @return $this
     * @throws \RuntimeException
     */
    public function set(string $key, $value, bool $rewrite = false)
    {
        if ($this->isExists($key) && !$rewrite) {
            throw new \RuntimeException("The registry already have the value with key '{$key}' and rewrite prohibited");
        }
        $this->storage[$key] = $value;

        return $this;
    }

    /**
     * Get the value from the storage.
     *
     * The principal difference with the magic getter: if the key doesn't exists - throw an exception. For milder
     * reaction see self::isExists()
     *
     * Note: if "not exists" is allowed and the value not found, this method returns the NULL. It can be unambiguously
     * understood, so you should write the right strategy in the client code.
     *
     * @param string $key           the key in the internal storage
     * @param bool   $allowNonExist FALSE = strict reaction to the absence of data.
     * @return mixed
     * @throws \RuntimeException
     */
    public function get(string $key, bool $allowNonExist = false)
    {
        if (!$this->isExists($key) && !$allowNonExist) {
            throw new \RuntimeException("The key '{$key}' not found in the storage and set strict reaction.");
        }
        return $this->storage[$key] ?? null;
    }

    /**
     * Check if the key exists in the storage
     * @param string $key the key in the internal storage
     * @return bool
     */
    public function isExists(string $key)
    {
        return isset($this->storage[$key]);
    }

    /**
     * Delete the key (and value) from the storage
     * @param string $key the key in the internal storage
     */
    public function delete(string $key)
    {
        unset($this->storage[$key]);
    }

    /**
     * Delete the whole data from the storage
     */
    public function drop()
    {
        $this->storage = [];
        return $this;
    }

    /**
     * Apply usual PHP functions of arrays to the Registry internal storage
     *
     * You can use it like this:
     *
     * <code>
     * Registry::getInstance()->array_keys()
     * </code>
     *
     * Note: you don't need to pass the "array" parameter into calls, only additional parameters if any.
     *
     * @link http://php.net/manual/ru/class.arrayobject.php#107079
     * @param string $func name of array function
     * @param array  $argv additional arguments
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call(string $func, array $argv)
    {
        if (!is_callable($func) || substr($func, 0, 6) !== 'array_') {
            throw new \BadMethodCallException(__CLASS__ . '->' . $func);
        }
        array_unshift($argv, $this->storage);
        return call_user_func_array($func, $argv);
    }

    /**
     * Support of the serialization of the Registry
     * @see http://php.net/manual/ru/class.serializable.php
     * @return string
     */
    public function serialize()
    {
        return serialize($this->storage);
    }

    /**
     * Support of the deserialization of the Registry
     * @param string $data
     * @return string
     */
    public function unserialize($data)
    {
        $this->storage = unserialize($data);
    }
}
