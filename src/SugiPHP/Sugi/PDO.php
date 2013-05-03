<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

class PDO
{
	/**
	 * Instance of \PDO
	 * 
	 * @var \PDO
	 */
	protected static $pdo;

	/**
	 * Handles dynamic static calls to the instance of \PDO.
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
	 * Gets the instance of \PDO.
	 * 
	 * @return \PDO
	 */
	public static function getInstance()
	{
		if (!static::$pdo) {
			static::$pdo = static::factory(Config::get("pdo") ?: Config::get("database"));
		}

		return static::$pdo;
	}

	/**
	 * Creates an instance of PDO.
	 *
	 * @var array $config Configuration options
	 */
	public static function factory(array $config)
	{
		// The Data Source Name, or DSN, contains the information required to connect to the database. 
		if (isset($config["dsn"])) {
			$dsn = $config["dsn"];
		} else {
			$dsn = $config["type"].":";
			if (is_array($config[$config["type"]])) {
				$config = $config[$config["type"]];
			}
			if (isset($config["database"])) {
				$dsn .= "dbname={$config["database"]};";
			}
			if (isset($config["host"])) {
				$dsn .= "host={$config["host"]};";
			}
			if (isset($config["port"])) {
				$dsn .= "port={$config["port"]};";
			}
		}
		$dsn = rtrim($dsn, ";");
		$user = isset($config["user"]) ? $config["user"] : "";
		$pass = isset($config["pass"]) ? $config["pass"] : "";

		return new \PDO($dsn, $user, $pass);
	}
}
