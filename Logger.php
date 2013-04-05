<?php

namespace SugiPHP\Sugi;

use \Monolog\Logger as Monolog;
use \Monolog\Handler\StreamHandler;

class Logger
{
	public static $ip = "";

	protected static $monolog;

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

	public static function getInstance()
	{
		if (!static::$monolog) {
			static::$monolog = static::factory(
				array(
					// "name"     => "", // default is empty string
					"type"     => "file",
					"filename" => "log/custom-".date("Ymd").".log",
					// "filter"   => "all", // default is "all"
					"filter"   => "none +curl +nsbop",
					"filter"   => "all -debug",
					// "format"   => "[{Y}-{m}-{d} {H}:{i}:{s}] [{ip}] [{level}] {message}", // default
				)
			);
			//static::$monolog = static::factory(array());

			// TODO: this should be in \Request or something...
			if (!static::$ip) {
				if (PHP_SAPI == "cli") {
					static::$ip = "cli"; // The request was started from the command line
				} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
					static::$ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; // If the server is behind proxy
				} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
					static::$ip = $_SERVER["HTTP_CLIENT_IP"];
				} elseif (isset($_SERVER["REMOTE_ADDR"])) {
					static::$ip = $_SERVER["REMOTE_ADDR"];
				}
			}

		}
		
		return static::$monolog;
	}

	public static function factory(array $params)
	{
		$monolog = new Monolog(isset($params["name"]) ? $params["name"] : "sugi");
		$monolog->pushProcessor(function ($message) {
			$message["extra"]["ip"] = static::$ip;

			return $message;
		});

		if (isset($params["type"])) {
			$handler = static::createHandler($params);
			$monolog->pushHandler($handler);
		}
		else {
			foreach ($params as $param) {
				$handler = static::createHandler($param);
				$monolog->pushHandler($handler);
			}
		}

		return $monolog;
	}

	protected static function createHandler(array $config)
	{
		// handler type (file, mail, stdout...)
		if ($type = $config["type"]) {
			unset($config["type"]);
		} else {
			throw new \Exception("Logger type must be set");
		}

		// filter
		if ($filter = isset($config["filter"])) {
			unset($config["filter"]);
		} else {
			$filter = "all";
		}
		 
		// format
		if ($format = isset($config["format"])) {
			unset($config["format"]);
		} else {
			$format = null;
		}

		// cretate a handler
		if ($type == "file") {
			$handler = new StreamHandler($config["filename"], Monolog::DEBUG);
			$handler->setFormatter(new Formatter\LineFormatter($format));
		}

		return $handler;
	}
}
