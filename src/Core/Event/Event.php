<?php

namespace Planck\Core\Event;

class Event {
    
    private static $__events = [];
    
    public static function emit($event, $data = []) {
        foreach (static::$__events[$event] as $listener) {
            call_user_func_array([$listener['object'], $listener['handler']], $data);
        }
    }
    
    public static function attach($listener) {
        $events = $listener->attachedEvents();
        if (!isset($events) || !is_array($events) || empty($events)) {
            throw Exception('No events defined on listener');
        }
        
        // TODO: Register objects in another array so we only need one instance of each to handle event?
        foreach ($listener->attachedEvents() as $event => $handler) {
            static::$__events[$event][] = [
                'object' => $listener,
                'handler' => $handler,
            ];
        }
    }
    
}