<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Sugi\Router;

class URI
{
	/**
	 * Builds an URL based on route with name $name.
	 * 
	 * @param  string $name
	 * @param  array  $params
	 * @return string
	 */
	public static function build($name, array $params = array())
	{
		$router = Router::getInstance();
		return $router->get($name)->build($params);
	}

	/**
	 * Builds and URL based on current route and using current request as default,
	 * modifying those parameters that are given.
	 * 
	 * @param  array  $params Parameters that should be changed
	 * @return string
	 */
	public static function modify(array $params)
	{
		$build_params = array();

		foreach (Router::getParams() as $param => $value) {
			if ($param !== "_name") {
				$build_params[$param] = isset($params[$param]) ? $params[$param] : $value;
			}
		}

		return Router::getRoute()->build($build_params);
	}
}
