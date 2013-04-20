<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace Sugi;

use \SugiPHP\Database\Database as DB;
use \SugiPHP\Database\Exception as DBException;
use \SugiPHP\Database\MySqlDriver as mysql;
use \SugiPHP\Database\PgSqlDriver as pgsql;
use \SugiPHP\Database\SQLiteDriver as sqlite;

class Database
{
	/**
	 * @var boolean
	 */
	public static $registerEvents = true;

	/**
	 * Instance of \SugiPHP\Database\Database
	 * 
	 * @var \SugiPHP\Database\Database
	 */
	protected static $db;

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
		if (!static::$db) {
			static::$db = static::factory(Config::get("database"));
			static::$registerEvents = Config::get("database.registerEvents", true);
			
			if (static::$registerEvents) {
				static::$db->hook("pre_open", function ($h) {
					Event::fire("sugi.database.pre_open");
				});
				static::$db->hook("post_open", function ($h) {
					Event::fire("sugi.database.post_open");
				});
				static::$db->hook("pre_query", function ($h, $query) {
					Event::fire("sugi.database.pre_query", array("query" => $query));
				});
				static::$db->hook("post_query", function ($h, $query) {
					Event::fire("sugi.database.post_query", array("query" => $query));
				});
			}
		}

		return static::$db;
	}

	public static function factory($params)
	{
		if (empty($params["type"])) {
			throw new DBException("Required database type parameter is missing", "internal_error");
		}
		$type = $params["type"];
		unset($params["type"]);

		if (isset($params[$type]) and is_array($params[$type])) {
			$params = $params[$type];
		}

		// if we've passed custom Store instance
		if (!is_string($type)) {
			$driver = $type;
		} else {
			$type = strtolower($type);
			if (($type == "mysql") or ($type == "mysqli")) {
				$driver = new mysql($params);
			} elseif ($type == "pgsql") {
				$driver = new pgsql($params);
			} elseif (($type == "sqlite") or ($type == "sqlite3")) {
				$driver = new sqlite($params);
			} else {
				throw new DBException("Unknown database type", "internal_error");
				$driver = DI::reflect($type, $params);
			}
		}

		return new DB($driver);
	}
}
