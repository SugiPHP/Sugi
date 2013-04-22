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
			$fileLocator = new FileLocator(static::$path);
			$nativeLoader = new NativeLoader($fileLocator);
			$yamlLoader = new YamlLoader($fileLocator);
			static::$config = new BaseConfig(array($nativeLoader, $yamlLoader));
		}

		return static::$config;
	}
}
