<?php
/**
 * Event Trait for App
 *
 * @package SugiPHP.Sugi
 */

namespace SugiPHP\Sugi;

use SugiPHP\Events\Dispatcher;
use SugiPHP\Events\Event;

trait EventsTrait
{
    /**
     * Registers an event listener(s).
     *
     * @param string|array $eventName or array of event names
     * @param function $callback
     */
    public function listen($eventName, $callback)
    {
        if (is_array($eventName)) {
            foreach ($eventName as $evnt) {
                $this["dispatcher"]->addListener($evnt, $callback);
            }
        } else {
            $this["dispatcher"]->addListener($eventName, $callback);
        }
    }

    /**
     * Fires an event.
     *
     * @param string $eventName
     * @param array $params
     *
     * @return Event object
     */
    public function fire($eventName, array $params = array())
    {
        $event = new Event($eventName, $params);
        $this["dispatcher"]->dispatch($event);

        return $event;
    }

    /**
     * @return SugiPHP\Events\Dispatcher
     */
    protected function prepareDispatcher($params)
    {
        // $params are not used
        return new Dispatcher();
    }
}
