<?php
/**
 * @package    SugiPHP
 * @subpackage Sugi
 * @author     Plamen Popov <tzappa@gmail.com>
 * @license    http://opensource.org/licenses/mit-license.php (MIT License)
 */

namespace Sugi;

use SugiPHP\FileSystem\Files as BaseFile;

/**
 * Facades SugiPHP\FileSystem\Files
 */
class File
{
	/**
	 * Instance of SugiPHP\FileSystem\Files
	 * 
	 * @var SugiPHP\FileSystem\Files
	 */
	protected static $file;

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
	 * Returns the singleton instance of the Files class
	 */
	public static function getInstance()
	{
		if (!static::$file) {
			static::$file = new BaseFile();
		}

		return static::$file;
	}
}
