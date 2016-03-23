<?php

/**
 * Define some globally accessible utility functions to make life a little easier
 * 
 * @author Mike Timms
 */

/**
 * Handy shorthand function
 * Returns either the $value, if isset(), or the $alt. Equivalent to:
 * return isset($value) ? $value : $alt;
 * 
 * @return mixed
 */
function either($value, $alt) {
    return ($value ? $value : $alt);
}

/**
 * Wrapper around print_r()
 */
function pr($data) {
    print_r($data);
}

/**
 * Wrapper around var_dump()
 * 
 * Can accept multiple values as arguments to the function. E.g.
 * 
 * debug(1,2,3);
 * 
 * @return void
 */
function debug() {
    foreach (func_get_args() as $arg) {
        var_dump($arg);
    }
}
