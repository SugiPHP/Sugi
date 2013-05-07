<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\HTTP\Request as BaseRequest;

class Request
{
	/**
	 * Request instance.
	 * 
	 * @var SugiPHP\HTTP\Request
	 */
	protected static $request;

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
	 * Creates a custom request.
	 * 
	 * @param  string $uri
	 * @param  string $method
	 * @param  array  $params
	 * @param  array  $cookies
	 * @return SugiPHP\HTTP\Request
	 */
	public static function custom($uri, $method = "GET", array $params = array(), array $cookies = array())
	{
		// make a new request and override static request
		static::$request = BaseRequest::custom($uri, $method, $params, $cookies);

		return static::$request;
	}
	
	/**
	 * Gets the instance of SugiPHP\HTTP\Request.
	 * 
	 * @return SugiPHP\HTTP\Request
	 */
	public static function getInstance()
	{
		if (!static::$request) {
			static::$request = BaseRequest::real();
		}
		
		return static::$request;
	}
}
