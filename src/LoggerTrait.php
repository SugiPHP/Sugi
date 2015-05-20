<?php
/**
 * Application's Logger.
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use Psr\Log\LogLevel;
use Katzgrau\KLogger\Logger;

trait LoggerTrait
{
    /**
     * Saves a message to the log.
     *
     * @param mixed $level
     * @param string $message
     */
    public function log($level, $message)
    {
        return $this["log"]->log($level, $message);
    }

    protected function prepareLogger(array $config = [])
    {
        if (!isset($config["path"])) {
            $path = $this["log_path"];
        }
        if (empty($config["level"])) {
            $level = $this["debug"] ? LogLevel::DEBUG : LogLevel::INFO;
        }
        unset($config["path"], $config["level"]);
        $params = array_merge(["extension" => "log"], $config);

        return new Logger($path, $level, $params);
    }
}
