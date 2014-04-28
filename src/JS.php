<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Assets\JsPacker;

/**
 * Facades SugiPHP\Assets\JsPacker
 */
class JS
{
	/**
	 * Instance of SugiPHP\Assets\JsPacker
	 * 
	 * @var SugiPHP\Assets\JsPacker
	 */
	protected static $jsPacker;

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
	 * Returns the singleton instance of the JsPacker class
	 */
	public static function getInstance()
	{
		if (!static::$jsPacker) {
			static::$jsPacker = static::factory(Config::get("js"));
		}

		return static::$jsPacker;
	}

	public static function factory(array $config)
	{
		return new JsPacker($config);
	}
}
