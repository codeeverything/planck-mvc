<?php

use Junction\Router;
$router = new Router();

$router->add('GET /rand/:num?', function () {
    return [
        'controller' => 'Rand',
        'action' => 'rand',
    ];
});

/**
 * Defines the routes to be used by your application
 */

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