<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace SugiPHP\Sugi;

use Pimple;

class Container extends Pimple
{
	protected static $pimple;

	public static function getInstance()
	{
		if ( ! static::$pimple) {
			static::$pimple = new Pimple();
		}

		return static::$pimple;
	}

	public static function get($id)
	{
		$container = static::getInstance();

		return $container->offsetGet($id);
	}

	public static function set($id, $value)
	{
		$container = static::getInstance();

		$container->offsetSet($id, $value);
	}

	public static function has($id)
	{
		$container = static::getInstance();

		return $container->offsetExists($id);
	}

	public static function delete($id)
	{
		$container = static::getInstance();

		return $container->offsetUnset($id);
	}
}
