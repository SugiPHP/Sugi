<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Routing\Route;

class URI
{
	/**
	 * Builds an URL based on route with name $name.
	 *
	 * @param  string $name
	 * @param  array  $params
	 * @return string
	 */
	public static function build($name, array $params = array(), $pathType = Route::PATH_AUTO)
	{
		$router = Router::getInstance();

		return $router->build($name, $params, $pathType);
	}

	/**
	 * Builds and URL based on the current route.
	 *
	 * @param  array  $params Parameters that should be set
	 * @return string
	 */
	public static function current(array $params = array())
	{
		$router = Router::getInstance();

		return $router->build(Router::getRouteName(), $params);
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
		$router = Router::getInstance();

		$build_params = array();
		foreach (Router::getParams() as $param => $value) {
			if ($param !== "_name") {
				$build_params[$param] = isset($params[$param]) ? $params[$param] : $value;
			}
		}

		return $router->build(Router::getRouteName(), $build_params);
	}
}
