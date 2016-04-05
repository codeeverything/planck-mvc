<?php
/**
 * Define services and parameters to be used in your application 
 */

use Burlap\Burlap;

// grab a container. This variable MUST be called "container"
$container = new Burlap();

// setup service to get a random number
$container->rand([function ($c) {
    $r = rand();
    return $r;    
}]);

// TODO: move to config/errors.php
$container->errorResponseBuilder([function ($c) {
    return function ($ex) {
        return [
            'badness' => $ex->getMessage(),
        ];
    };
}]);