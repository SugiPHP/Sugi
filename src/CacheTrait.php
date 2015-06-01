<?php
/**
 * App Trait for caching
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use SugiPHP\Cache\Cache;
use SugiPHP\Cache\ArrayStore;
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
        // check store type - use ArrayStore as default
        $store = isset($config["store"]) ? $config["store"] : "array";
        unset($config["store"]);
        // check we want keys prefix
        $prefix = isset($config["prefix"]) ? $config["prefix"] : $this["uri"]->getHost();
        unset($config["prefix"]);

        // if we've passed custom Store instance
        if (!is_string($store)) {
            $storeInterface = $store;
        } else {
            if ($store == "memcached") {
                $storeInterface = MemcachedStore::factory($config);
            } elseif ($store == "memcache") {
                $storeInterface = MemcacheStore::factory($config);
            } elseif ($store == "apc") {
                $storeInterface = new ApcStore($config);
            } elseif ($store == "file") {
                $path = isset($config["path"]) ? $config["path"] : $this["temp_path"];
                $storeInterface = new FileStore();
            } elseif ($store == "array") {
                $storeInterface = new ArrayStore();
            } else {
                // If there is no match, or "null" is specified -> use NullStore. No cache is done!
                $storeInterface = new NullStore();
            }
        }

        // creating new SugiPHP\Cache instance
        $cache = new Cache($storeInterface);
        // setting prefix
        $cache->setPrefix($prefix);

        return $cache;
    }
}
