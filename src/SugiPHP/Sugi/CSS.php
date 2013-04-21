<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Assets\CssPacker;

/**
 * Facades SugiPHP\Assets\CssPacker
 */
class CSS
{
	/**
	 * Instance of SugiPHP\Assets\CssPacker
	 * 
	 * @var SugiPHP\Assets\CssPacker
	 */
	protected static $cssPacker;

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
	 * Returns the singleton instance of the CssPacker class
	 */
	public static function getInstance()
	{
		if (!static::$cssPacker) {
			static::$cssPacker = new CssPacker(Config::get("css"));
		}

		return static::$cssPacker;
	}
}
