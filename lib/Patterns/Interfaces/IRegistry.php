<?php
namespace Saritasa\Patterns\Interfaces;

/**
 * Global storage of application custom data
 *
 * Patterns: Singleton, Registry + additional features
 */
interface IRegistry
{
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
    public function set(string $key, $value, bool $rewrite = false);

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
    public function get(string $key, bool $allowNonExist = false);

    /**
     * Check if the key exists in the storage
     * @param string $key the key in the internal storage
     * @return bool
     */
    public function isExists(string $key);

    /**
     * Delete the key (and value) from the storage
     * @param string $key the key in the internal storage
     */
    public function delete(string $key);
    /**
     * Delete the whole data from the storage
     */
    public function drop();
}
