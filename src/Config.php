<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Config\Config as BaseConfig;
use SugiPHP\Config\FileLocator;
use SugiPHP\Config\NativeLoader;
use SugiPHP\Config\YamlLoader;

class Config
{
	public static $fileLocator;

	/**
	 * FileLocator search path.
	 *
	 * @var string|array
	 */
	public static $path = "config";

	/**
	 * Instance of SugiPHP\Config\Config.
	 *
	 * @var SugiPHP\Config\Config
	 */
	protected static $config;

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

	public static function getInstance()
	{
		if (!static::$config) {
			if (!static::$fileLocator) {
				static::$fileLocator = new FileLocator(static::$path);
			}

			$nativeLoader = new NativeLoader(static::$fileLocator);
			$yamlLoader = new YamlLoader(static::$fileLocator);
			static::$config = new BaseConfig(array($nativeLoader, $yamlLoader));
		}

		return static::$config;
	}

	/**
	 * Adds a search paths.
	 *
	 * @param string|array $path or several paths
	 */
	public static function addPath($path)
	{
		return static::$fileLocator->addPath($path);
	}

	/**
	 * Remove last search path.
	 */
	public static function popPath()
	{
		return static::$fileLocator->popPath();
	}

	/**
	 * Prepend one path to the beginning of the search paths.
	 *
	 * @param string $path
	 */
	public static function prependPath($path)
	{
		return static::$fileLocator->prependPath($path);
	}

	/**
	 * Remove first path from the search paths.
	 */
	public static function shiftPath()
	{
		return static::$fileLocator->shiftPath();
	}
}
