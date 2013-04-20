<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace Sugi;

use SugiPHP\Cache\Cache as BaseCache;
use SugiPHP\Cache\MemcachedStore;
use SugiPHP\Cache\MemcacheStore;
use SugiPHP\Cache\ApcStore;
use SugiPHP\Cache\FileStore;
use SugiPHP\Cache\NullStore;

class Cache
{
	/**
	 * Instance of SugiPHP\Cache\Cache
	 * 
	 * @var SugiPHP\Cache\Cache
	 */
	protected static $cache;

	/**
	 * Handles dynamic static calls to the object.
	 *
	 * @param  string $method
	 * @param  array $parameters
	 * @return mixed
	 */
	public static function __callStatic($method, $parameters)
	{
		$instance = static::getInstance();

		return call_user_func_array(array($instance, $method), $parameters);
	}

	/**
	 * 
	 */
	public static function getInstance()
	{
		if (!static::$cache) {
			static::$cache = static::factory(Config::get("cache"));
		}

		return static::$cache;
	}

	public static function factory($config)
	{
		if (empty($config) or empty($config["store"])) {
			$store = "null";
		} else {
			$store = $config["store"];
		}

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
				// $storeInterface = DI::reflect($store, $storeConfig);
			}
		}

		// creating new SugiPHP\Cache instance
		$cache = new BaseCache($storeInterface);

		// check we want keys prefix
		if (!empty($config["prefix"])) {
			$cache->setPrefix($config["prefix"]);
		}

		return $cache;
	} 
}
