<?php
/**
 * Config files reader
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use SugiPHP\Config\Config;
use SugiPHP\Config\FileLocator;
use SugiPHP\Config\NativeLoader;

trait ConfigTrait
{
    /**
     * Used for getting and setting (not recommended) of configuration options.
     * Second parameter is used only to set a value to the corresponding key.
     *
     * @param string $name
     * @param mixed $value
     *
     * @return mixed
     */
    public function config($name, $value = null)
    {
        if (func_num_args() === 1) {
            return $this["config"]->get($name);
        }

        return $this["config"]->set($name, $value);
    }

    /**
     * @return SugiPHP\Config\Config
     */
    protected function prepareConfig($path)
    {
        $fileLocator = new FileLocator($path);
        $nativeLoader = new NativeLoader($fileLocator);

        return new Config(array($nativeLoader));
    }
}
