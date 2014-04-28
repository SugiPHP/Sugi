<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Cron\Cron as BaseCron;

class Cron
{
	/**
	 * Instance of \SugiPHP\Cron\Cron
	 * 
	 * @var \SugiPHP\Cron\Cron
	 */
	protected static $cron;

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

	/**
	 * Creates an instance of Cron and executes it immediately.
	 * Cron config example
	 * <code>
	 *  <?php
	 *  return array(
	 *  	"file" => "/path/to/cron.conf"
	 *  );
	 * </code>
	 */
	public static function start()
	{
		$cron = static::getInstance(Config::get("cron"));
		$cron->proceed();
	}

	public static function getInstance($config = array())
	{
		if (!static::$cron) {
			static::$cron = new BaseCron($config);
			static::$cron->onJobStart(function ($file) {
				Logger::debug("Cron $file start");
			});
			static::$cron->onJobEnd(function ($file) {
				Logger::debug("Cron $file end");
			});
			static::$cron->onJobError(function ($file, $e) {
				Logger::error("Cron $file " . $e->getMessage());
			});
		}

		return static::$cron;
	}
}
