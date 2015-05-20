<?php
/**
 * Application class.
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use SugiPHP\Container\Container;
use Phly\Http\ServerRequestFactory;

class App extends Container
{
    use ConfigTrait;
    use LoggerTrait;

    /**
     * Instance of a SugiPHP\Container\Container
     * This will be the first instance created.
     */
    protected static $container;

    public static function getInstance()
    {
        if (!static::$container) {
            new static();
        }

        return static::$container;
    }

    public function __construct(array $settings = [])
    {
        parent::__construct();

        foreach ($settings as $key => $value) {
            $this->set($key, $value);
        }

        // Set the default encoding
        mb_internal_encoding("UTF-8");

        // Set output encoding
        mb_http_output("UTF-8");

        // The base path is essential, and should be set on creation, but if it is not - try do guess it.
        $this->conditionalSet("base_path", function () {
            return dirname(dirname(dirname(dirname(__DIR__)))) . "/";
        });
        // web root path
        $this->conditionalSet("www_path", function () {
            return rtrim($this["base_path"], "/")."/www/";
        });
        // application path
        $this->conditionalSet("app_path", function () {
            return rtrim($this["base_path"], "/")."/app/";
        });
        // the path to store temporary files
        $this->conditionalSet("temp_path", function () {
            return rtrim($this["base_path"], "/")."/tmp/";
        });
        // log path
        $this->conditionalSet("log_path", function () {
            return rtrim($this["base_path"], "/")."/log/";
        });
        // config path
        $this->conditionalSet("config_path", function () {
            return rtrim($this["app_path"], "/")."/config/";
        });

        // ServerRequest based on PSR-7 ServerRequestInterface
        $this->conditionalSet("request", function () {
            // ServerRequest instance, using values from superglobals
            return ServerRequestFactory::fromGlobals();
        });

        // URI based on PSR-7 UriInterface
        $this->conditionalSet("uri", function () {
            return $this["request"]->getUri();
        });

        // Config file reader
        $this->conditionalSet("config", function () {
            return $this->prepareConfig($this["config_path"]);
        });

        // Logger
        $this->conditionalSet("logger", function () {
            return $this->prepareLogger((array) $this->config("logger"));
        });

        //  Are we on development or on production server
        $this->conditionalSet("mode", function () {
            /*
             * To set development environment add the following code in your Apache configuration file
             * <code>
             *  SetEnv APPLICATION_ENV development
             * </code>
             *
             * When PHP runs from CLI (Linux bash) you can set it with
             * export APPLICATION_ENV=development
             * this can be also added in your ~/.bashrc file
             */
            if (defined("APPLICATION_ENV")) {
                return APPLICATION_ENV;
            } elseif ($envMode = getenv("APPLICATION_ENV")) {
                return $envMode;
            } else {
                return "production";
            }
        });

        // Set debug based on setting and/or environment
        $this->conditionalSet("debug", function () {
            if (defined("DEBUG")) {
                return (bool) DEBUG;
            } else {
                return (bool) ($this["mode"] == "development");
            }
        });

        if (!static::$container) {
            static::$container = $this;
        }
    }

    /**
     * Set a value in a container only if it is not defined already.
     *
     * @param string $key
     * @param mixed $value
     */
    protected function conditionalSet($key, $value)
    {
        if (!$this->has($key)) {
            $this->set($key, $value);
        }
    }
}
