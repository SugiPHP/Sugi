<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Filter\Filter as BaseFilter;

/**
 * Filter - a helper class which wraps a filter_var() function.
 */
class Filter
{
	/**
	 * Instance of SugiPHP\Filter\Filter
	 *
	 * @var SugiPHP\Filter\Filter
	 */
	protected static $filter;

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
	 * Returns the singleton instance of the Filter class
	 */
	public static function getInstance()
	{
		if (!static::$filter) {
			static::$filter = new BaseFilter();
		}

		return static::$filter;
	}
}
