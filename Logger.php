<?php

namespace SugiPHP\Sugi;

use \SugiPHP\Logger\Logger as SugiLogger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\FirePHPHandler;
use \Monolog\Handler\NativeMailerHandler;
use \Psr\Log\InvalidArgumentException;

class Logger
{
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
			static::$monolog = static::factory(Config::get("logger"));
		}
		
		return static::$monolog;
	}

	/**
	 * Extending log method, since it might be in an old format
	 * 
	 * @param  string $level
	 * @param  string $message
	 */
	public static function log($level, $message)
	{
		$instance = static::getInstance();

		// for historical reasons parameters were in reverse order. We'll try to fix them
		// if the level has a white space and the message has not we'll assume that they
		// are in old order format.
		if ((strpos($level, " ") > 0) and (strpos($message, " ") === false)) {
			$instance->log($message, $level);
		} else {
			$instance->log($level, $message);
		}
	}

	public static function factory(array $params)
	{
		$monolog = new SugiLogger();
		$monolog->pushProcessor(function ($message) {
			// TODO: this should be in \Request or something...
			if (PHP_SAPI == "cli") {
				$ip = "cli"; // The request was started from the command line
			} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; // If the server is behind proxy
			} elseif (isset($_SERVER["HTTP_CLIENT_IP"])) {
				$ip = $_SERVER["HTTP_CLIENT_IP"];
			} elseif (isset($_SERVER["REMOTE_ADDR"])) {
				$ip = $_SERVER["REMOTE_ADDR"];
			}
			$message["extra"]["ip"] = $ip;

			return $message;
		});
		if (isset($params["type"])) {
			$handler = static::createHandler($params);
			$filter = (isset($params["filter"])) ? $params["filter"] : "all";
			$monolog->addHandler($handler, $filter);
		}
		else {
			foreach ($params as $param) {
				$handler = static::createHandler($param);
				$filter = (isset($param["filter"])) ? $param["filter"] : "all";
				$monolog->addHandler($handler, $filter);
			}
		}

		return $monolog;
	}

	protected static function createHandler(array $config)
	{
		// handler type (file, mail, stdout, console...)
		if ($type = $config["type"]) {
			unset($config["type"]);
		} else {
			throw new \Exception("Logger type must be set");
		}

		// create a handler
		if ($type == "file") {
			$handler = new StreamHandler($config["filename"]);
			// format
			$format = isset($config["format"]) ? $config["format"] : null;
			$handler->setFormatter(new Logger\Formatter\LineFormatter($format));
		} elseif ($type == "firephp") {
			$handler = new FirePHPHandler();
		} elseif ($type == "stdout") {
			$handler = new StreamHandler("php://stdout");
			// format
			$format = isset($config["format"]) ? $config["format"] : null;
			$handler->setFormatter(new Logger\Formatter\LineFormatter($format));
		} elseif ($type == "mail") {
			// to, from , subject
			$to = $config["to"];
			$subject = isset($config["subject"]) ? $config["subject"] : "Logger Message";
			$from = isset($config["from"]) ? $config["from"] : $_SERVER["SERVER_ADMIN"];
			$handler = new NativeMailerHandler($to, $subject, $from);
			// format
			$format = isset($config["format"]) ? $config["format"] : null;
			$handler->setFormatter(new Logger\Formatter\LineFormatter($format));
		}

		return $handler;
	}
}
