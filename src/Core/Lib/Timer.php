<?php

namespace Planck\Core\Lib;

class Timer {

    static $times = [];
    
    public static function start($index) {
        static::$times[$index]['start'] = microtime(true);
    }
    
    public static function end($index) {
        static::$times[$index]['end'] = microtime(true);
        static::$times[$index]['time'] = (static::$times[$index]['end'] - static::$times[$index]['start']) * 1000;
    }
    
    public static function times() {
        // if anything is missing an end time, tack it on here (this is the time at which point this was output at least)
        foreach (static::$times as $index => $item) {
            if (!isset($item['end'])) {
                static::end($index);
            }
        }
        
        print_r(static::$times);
    }
    
}