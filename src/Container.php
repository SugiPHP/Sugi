<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Container\Container as BaseContainer;

/**
 * Facade for a SugiPHP Container
 */
class Container
{
	/**
	 * Instance of a SugiPHP\Container\Container
	 */
	protected static $container;

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
		if ( ! static::$container) {
			static::$container = new BaseContainer();
		}

		return static::$container;
	}
}
