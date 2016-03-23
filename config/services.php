<?php

use Burlap\Burlap;

$sack = new Burlap();

$sack->rand([function ($c) {
    $r = rand();
    return $r;    
}]);