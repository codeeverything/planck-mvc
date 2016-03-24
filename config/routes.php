<?php

/**
 * Defines the routes to be used by your application
 */
 
use Junction\Router;

// define the router - This can be any router, but it must be set to a variable called "router"
$router = new Router();

$router->add('GET /rand/:num?', function ($num) {
    return [
        'controller' => 'Rand',
        'action' => 'rand',
        'vars' => func_get_args(),
    ];
});

$router->add('GET /hello/:name?', function ($name) {
    return [
        'controller' => 'My',
        'action' => 'hello',
        'vars' => func_get_args(),
    ];
});

$router->add('GET /bye', function ($name) {
    return [
        'controller' => 'My',
        'action' => 'bye',
        'vars' => func_get_args(),
    ];
});

$router->add('GET /', function () {
    return [
        'controller' => 'My',
        'action' => 'hello',
        'vars' => func_get_args(),
    ];
});



// TODO: Pass this responsibility to a Router (Junction for example), and try to keep this decoupled so any router couuld be used
// $routes = array(
//     '/hello' => array(
//         'controller' => 'My',
//         'action' => 'hello',
//     ),
//     '/bye' => array(
//         'controller' => 'My',
//         'action' => 'bye',
//     ),
//     '/rand' => [
//         'controller' => 'Rand',
//         'action' => 'rand',
//     ],
//     '/' => [
//         'controller' => 'My',
//         'action' => 'hello',
//     ],
// );