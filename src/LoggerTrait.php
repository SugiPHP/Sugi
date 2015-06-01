<?php
/**
 * Application's Logger.
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use SugiPHP\Logger\Logger;

trait LoggerTrait
{
    /**
     * Saves a message to the log.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        return $this["logger"]->log($level, $message, $context);
    }

    protected function prepareLogger(array $config = [])
    {
        if (!isset($config["level"])) {
            $config["level"] = $this["debug"] ? "debug" : "info";
        }
        if (!isset($config["filename"])) {
            if ($host = $this["uri"]->getHost()) {
                $host .= "-";
            }
            $config["filename"] = $this["log_path"].$host.date("Y-m-d").".log";
        }

        return new Logger($config);
    }
}
