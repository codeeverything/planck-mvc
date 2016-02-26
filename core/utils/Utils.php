<?php

function either($value, $alt) {
    return ($value ? $value : $alt);
}

function pr($data) {
    print_r($data);
}

function debug() {
    foreach (func_get_args() as $arg) {
        var_dump($arg);
    }
}
