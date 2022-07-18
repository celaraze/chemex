<?php

namespace Adldap\Query;

use Closure;
use Psr\SimpleCache\CacheInterface;

class Cache
{
    /**
     * The cache driver.
     *
     * @var CacheInterface
     */
    protected $store;

    /**
     * Constructor.
     *
     * @param CacheInterface $store
     */
    public function __construct(CacheInterface $store)
    {
        $this->store = $store;
    }

    /**
     * Get an item from the cache, or execute the given Closure and store the result.
     *
     * @param string $key
     * @param \DateTimeInterface|\DateInterval|int|null $ttl
     * @param Closure $callback
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     */
    public function remember($key, $ttl, Closure $callback)
    {
        $value = $this->get($key);

        if (!is_null($value)) {
            return $value;
        }

        $this->put($key, $value = $callback(), $ttl);

        return $value;
    }

    /**
     * Get an item from the cache.
     *
     * @param string $key
     *
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     */
    public function get($key)
    {
        return $this->store->get($key);
    }

    /**
     * Store an item in the cache.
     *
     * @param string $key
     * @param mixed $value
     * @param \DateTimeInterface|\DateInterval|int|null $ttl
     *
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     */
    public function put($key, $value, $ttl = null)
    {
        return $this->store->set($key, $value, $ttl);
    }

    /**
     * Delete an item from the cache.
     *
     * @param string $key
     *
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function delete($key)
    {
        return $this->store->delete($key);
    }
}
