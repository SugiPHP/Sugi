<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use SugiPHP\Events\Event as BaseEvent;
use SugiPHP\Events\Dispatcher;

class Event extends Dispatcher
{
	/**
	 * Instance of SugiPHP\Events\Dispatcher
	 *
	 * @var SugiPHP\Events\Dispatcher
	 */
	protected static $dispatcher;

	public static function getInstance()
	{
		if (!static::$dispatcher) {
			static::$dispatcher = new Dispatcher();
		}

		return static::$dispatcher;
	}

	/**
	 * Registers an event listener(s).
	 *
	 * @param string|array $eventName or array of event names
	 * @param function $callback
	 */
	public static function listen($eventName, $callback)
	{
		$instance = static::getInstance();
		if (is_array($eventName)) {
			foreach ($eventName as $en) {
				$instance->addListener($en, $callback);
			}
		} else {
			$instance->addListener($eventName, $callback);
		}
	}

	/**
	 * Fires an event.
	 *
	 * @param string $eventName
	 * @param array $params
	 */
	public static function fire($eventName, array $params = array())
	{
		$instance = static::getInstance();
		$instance->dispatch(new BaseEvent($eventName, $params));
	}
}
