<?php
/**
 * @package Chocolife.me
 * @author  Kamet Aziza <kamet.a@chocolife.kz>
 */

namespace Helper;

use Phalcon\Cache\BackendInterface;

class CacheMock implements BackendInterface
{
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    /**
     * Starts a cache. The keyname allows to identify the created fragment
     *
     * @param int|string $keyName
     * @param int        $lifetime
     *
     * @return mixed
     */
    public function start($keyName, $lifetime = null)
    {
        // TODO: Implement start() method.
    }

    /**
     * Stops the frontend without store any cached content
     *
     * @param boolean $stopBuffer
     */
    public function stop($stopBuffer = true)
    {
        // TODO: Implement stop() method.
    }

    /**
     * Returns front-end instance adapter related to the back-end
     *
     * @return mixed
     */
    public function getFrontend()
    {
        // TODO: Implement getFrontend() method.
    }

    /**
     * Returns the backend options
     *
     * @return array
     */
    public function getOptions()
    {
        // TODO: Implement getOptions() method.
    }

    /**
     * Checks whether the last cache is fresh or cached
     *
     * @return bool
     */
    public function isFresh()
    {
        // TODO: Implement isFresh() method.
    }

    /**
     * Checks whether the cache has starting buffering or not
     *
     * @return bool
     */
    public function isStarted()
    {
        // TODO: Implement isStarted() method.
    }

    /**
     * Sets the last key used in the cache
     *
     * @param string $lastKey
     */
    public function setLastKey($lastKey)
    {
        // TODO: Implement setLastKey() method.
    }

    /**
     * Gets the last key stored by the cache
     *
     * @return string
     */
    public function getLastKey()
    {
        // TODO: Implement getLastKey() method.
    }

    /**
     * Returns a cached content
     *
     * @param string $keyName
     * @param int    $lifetime
     *
     * @return mixed|null
     */
    public function get($keyName, $lifetime = null)
    {
        return $this->data[$keyName] ?? null;
    }

    /**
     * Stores cached content into the file backend and stops the frontend
     *
     * @param int|string $keyName
     * @param string     $content
     * @param int        $lifetime
     * @param boolean    $stopBuffer
     *
     * @return bool
     */
    public function save($keyName = null, $content = null, $lifetime = null, $stopBuffer = true)
    {
        $this->data[$keyName] = $content;
        return true;
    }

    /**
     * Deletes a value from the cache by its key
     *
     * @param int|string $keyName
     *
     * @return boolean
     */
    public function delete($keyName)
    {
        unset($this->data[$keyName]);

        return true;
    }

    /**
     * Query the existing cached keys
     *
     * @param string $prefix
     *
     * @return array
     */
    public function queryKeys($prefix = null)
    {
        // TODO: Implement queryKeys() method.
    }

    /**
     * Checks if cache exists and it hasn't expired
     *
     * @param string $keyName
     * @param int    $lifetime
     *
     * @return boolean
     */
    public function exists($keyName = null, $lifetime = null)
    {
        // TODO: Implement exists() method.
    }
}
