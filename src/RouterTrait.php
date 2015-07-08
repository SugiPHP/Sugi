<?php
/**
 * Application's Router.
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use Aura\Router\RouterContainer;

trait RouterTrait
{
    /**
     * Define a route and map a callback function to the path
     *
     * @param string $name
     * @param string $path
     * @param callable $callback
     *
     * @return Aura\Router\Route
     */
    public function route($name, $path, callable $callback)
    {
        // Modifying path from colon parameters /show/:id to bracket parameters /show/{id}
        $path = preg_replace("|(:(\w+))|", '{$2}', $path);

        // if the route is anonymous
        if (!$name) {
            $name = "N".uniqid();
        }

        $map = $this["router"]->getMap();
        $route = $map->route($name, $path, $callback);

        return $route;
    }

    public function run()
    {
        $matcher = $this["router"]->getMatcher();
        $route = $matcher->match($this["request"]);
        if ($route) {
            /*
             * Attributes are intended for storing values that are computed from the current request.
             * A common use case is for storing the results of routing (decomposing the URI to key/value pairs).
             * The attributes API includes:
             *   getAttribute($name, $default = null) to retrieve a single named attribute, and return a default value if the attribute is not present.
             *   getAttributes() to retrieve the entire set of attributes currently stored.
             *   withAttribute($name, $value) to return a new ServerRequestInterface instance that composes the given attribute.
             *   withoutAttribute() to return a new ServerRequestInterface instance that does not compose the given attribute.
             */
            foreach ($route->attributes as $key => $val) {
                $this["request"] = $this["request"]->withAttribute($key, $val);
            }
            $callable = $route->handler;
            $callable($this["request"], $route);

            return true;
        }

        return false;
    }

    /**
     * @return Aura\Router\RouterContainer
     */
    protected function prepareRouter($params)
    {
        // $params are not in use for Aura Router
        return new RouterContainer();
    }
}
