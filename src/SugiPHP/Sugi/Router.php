<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Routing\Router as BaseRouter;
use SugiPHP\Routing\Route;
use SugiPHP\HTTP\Request;

class Router
{
	/**
	 * Router instance.
	 * 
	 * @var SugiPHP\Routing\Router
	 */
	protected static $router;

	/**
	 * Registered functions/closures attached to each route.
	 * 
	 * @var array Array of Closures
	 */
	protected static $callables = array();

	/**
	 * Returned array for the route that matched the request.
	 * 
	 * @var array|null NULL if no route matches request or the method match was not executed
	 */
	protected static $match;

	/**
	 * Gets the instance of SugiPHP\Routing\Router.
	 * 
	 * @return SugiPHP\Routing\Router
	 */
	public static function getInstance()
	{
		if (!static::$router) {
			static::$router = new BaseRouter();
			$routes = Config::get("routes");
			if ($routes) {
				foreach ($routes as $name => $route) {
					static::add(
						$name, 
						$route["path"], 
						isset($route["defaults"]) ? $route["defaults"] : array(), 
						isset($route["requisites"]) ? $route["requisites"] : array()
					);
				}
			}
		}
		
		return static::$router;
	}

	/**
	 * Creates a route and adds it in the registered routes.
	 * 
	 * @param  string  $name Route's name
	 * @param  string  $path
	 * @param  array   $defaults
	 * @param  array   $requisites
	 * @param  Closure $closure The classure that will be fired if the route matches request
	 * @return \SugiPHP\Routing\Route
	 */
	public static function add($name, $path, array $defaults = array(), array $requisites = array(), $closure = null)
	{
		$router = static::getInstance();
		$route = new Route($path, $defaults, $requisites);
		$router->add($name, $route);

		// saves the callable that will be executed if the route matches the request
		static::$callables[$name] = $closure;

		return $route;
	}

	/**
	 * Walks through all registered routes and returns first route that matches 
	 * the given parameters. If the route was added with a closure it will be executed.
	 * 
	 * @param  SugiPHP\HTTP\Request $request
	 * @return array|null
	 */
	public static function match(Request $request = null)
	{
		// instantiate base router
		$router = static::getInstance();

		// default request is current request
		if (is_null($request)) {
			$request = Request::real();
		}

		// match first route that matches the request
		static::$match = $router->match($request->getPath(), $request->getMethod(), $request->getHost(), $request->getScheme());

		if (static::$match !== false) {
			$name = static::getName();
			if (isset(static::$callables[$name])) {
				$callable = static::$callables[$name];
				if (is_callable($callable)) {
					// fire the closure
					$callable(static::$match);
				}
			}
		}

		return static::$match;
	}

	/**
	 * Builds an URL based on route with name $name.
	 * 
	 * @param  string $name
	 * @param  array  $params
	 * @return string
	 */
	public static function build($name, array $params = array())
	{
		return static::$router->get($name)->build($params);
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

		foreach (static::$match as $param => $value) {
			if ($param !== "_name") {
				$build_params[$param] = isset($params[$param]) ? $params[$param] : $value;
			}
		}

		return static::getRoute()->build($build_params);
	}

	/**
	 * Returns all request variables.
	 * 
	 * @return array
	 */
	public static function getParams()
	{
		return static::$match;
	}

	/**
	 * Returns variable that matches current route.
	 * 
	 * @param  string $param Parameter (variable) name
	 * @return mixed
	 */
	public static function getParam($param)
	{
		return isset(static::$match[$param]) ? static::$match[$param] : null;
	}

	/**
	 * Returns current route.
	 * 
	 * @return \SugiPHP\Routing\Route
	 */
	public static function getRoute()
	{
		return static::$router->get(static::getName());
	}

	/**
	 * Returns the name of the current route.
	 * 
	 * @return string
	 */
	public static function getName()
	{
		return static::getParam("_name");
	}
}
