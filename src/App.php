<?php
/**
 * Application class.
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use SugiPHP\Container\Container;

class App extends Container
{
    /**
     * Instance of a SugiPHP\Container\Container
     */
    protected static $container;

    public static function getInstance()
    {
        if (!static::$container) {
            static::$container = new static();
        }

        return static::$container;
    }

    public function __construct(array $settings = [])
    {
        parent::__construct();

        // Set the default encoding
        mb_internal_encoding("UTF-8");

        // Set output encoding
        mb_http_output("UTF-8");

        /*
         * Are we on development or on production server
         * To set development environment add the following code in your Apache configuration file
         * <code>
         *  SetEnv APPLICATION_ENV development
         * </code>
         *
         * When PHP runs from CLI (Linux bash) you can set it with
         * export APPLICATION_ENV=development
         * this can be also added in your ~/.bashrc file
         */
        if (!empty($settings["mode"])) {
            $this->mode = $settings["mode"];
        } elseif (defined("APPLICATION_ENV")) {
            $this["mode"] = APPLICATION_ENV;
        } elseif ($envMode = getenv("APPLICATION_ENV")) {
            $this["mode"] = $envMode;
        } else {
            $this["mode"] = "production";
        }

        // Set debug based on setting and/or environment
        if (isset($settings["debug"])) {
            $this["debug"] = (bool) $settings["debug"];
        } elseif (defined("DEBUG")) {
            $this["debug"] = (bool) DEBUG;
        } else {
            $this["debug"] = (bool) ($this["mode"] == "development");
        }
    }
}
