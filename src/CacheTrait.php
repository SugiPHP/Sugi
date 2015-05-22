<?php
/**
 * App Trait for caching
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use SugiPHP\Cache\Cache;
use SugiPHP\Cache\ApcStore;
use SugiPHP\Cache\FileStore;
use SugiPHP\Cache\MemcacheStore;
use SugiPHP\Cache\MemcachedStore;
use SugiPHP\Cache\NullStore;
use InvalidArgumentException;

trait CacheTrait
{
    /**
     * Cache method has 2 forms:
     *
     *  cache(key); // returns value of the key
     *  cache(key, value, ttl = 0); // sets the value of the key
     *
     * @return mixed
     */
    public function cache()
    {
        $paramCount = func_num_args();

        if (1 === $paramCount) {
            return $this["cache"]->get(func_get_arg(0));
        }

        if (2 === $paramCount) {
            return $this["cache"]->set(func_get_arg(0), func_get_arg(1));
        }

        if (3 === $paramCount) {
            return $this["cache"]->set(func_get_arg(0), func_get_arg(1), func_get_arg(2));
        }

        throw new InvalidArgumentException("App::cache() method expects 1 or 2(3) parameters. {$paramCount} given.");
    }

    /**
     * @return SugiPHP\Cache\Cache
     */
    protected function prepareCache($config = [])
    {
        $store = empty($config["store"]) ? "null" : $config["store"];

        // if we've passed custom Store instance
        if (!is_string($store)) {
            $storeInterface = $store;
        } else {
            $storeConfig = isset($config[$store]) ? $config[$store] : array();
            if ($store == "memcached") {
                $storeInterface = MemcachedStore::factory($storeConfig);
            } elseif ($store == "memcache") {
                $storeInterface = MemcacheStore::factory($storeConfig);
            } elseif ($store == "apc") {
                $storeInterface = new ApcStore($storeConfig);
            } elseif ($store == "file") {
                $storeInterface = new FileStore($storeConfig["path"]);
            } elseif ($store == "null") {
                $storeInterface = new NullStore();
            } else {
                throw new \Exception("Unknown Cache Store $store");
            }
        }

        // creating new SugiPHP\Cache instance
        $cache = new Cache($storeInterface);
        // check we want keys prefix
        if (!empty($config["prefix"])) {
            $cache->setPrefix($config["prefix"]);
        }

        return $cache;
    }
}
