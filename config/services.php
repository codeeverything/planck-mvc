<?php
/**
 * Define services and parameters to be used in your application 
 */

use Burlap\Burlap;

// grab a container
$sack = new Burlap();

// setup service to get a random number
$sack->rand([function ($c) {
    $r = rand();
    return $r;    
}]);