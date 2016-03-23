<?php

namespace Planck\Core\Event;

/**
 * A simple event management class
 * 
 * Objects can listen for events by exposing an attachedEvents() function which returns an array
 * containing keys as event names and values as the name of a function to call on that object
 * when the event occurs, then passing the object to Event::attach()
 * 
 * Events can be emitted from anywhere by calling Event::emit('event.name', $eventData)
 * $eventData should be an array, and this will be forwarded to the handling function as
 * arguments
 * 
 * @author Mike Timms
 */
class Event {
    
    /**
     * Hold a list of event listeners
     */
    private static $__events = [];
    
    /**
     * Emit the event given by $event, passing $data as arguments for the listening function
     * 
     * @param string $event - The name of the event to emit
     * @param array $data - The data to pass onto the handling function
     * 
     * @return void
     */
    public static function emit($event, $data = []) {
        foreach (static::$__events[$event] as $listener) {
            if (!is_array($data)) {
                $data = [$data];
            }
            
            call_user_func_array([$listener['object'], $listener['handler']], $data);
        }
    }
    
    /**
     * Attach the object $listener to the events it exposes in its attachedEvents() function, if any
     * 
     * @param object $listener - The object to attach
     * @return void
     */
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