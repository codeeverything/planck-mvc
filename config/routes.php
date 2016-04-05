<?php

/**
 * Defines the routes to be used by your application
 * 
 * You can use any router, providing the following conventions are followed:
 * 
 * 1) The router must be set into a variable called "$router" in this file
 * 2) A matched route must return an array containing indexes for:
 *     "controller" - The controller to instantiate
 *     "action" (optional) - The [public] method on the controller to call
 *     "vars" (optional) - An array of values to pass to the controller action as arguments
 */
 
use Junction\Router;

// define the router - This can be any router, but it must be set to a variable called "router"
$router = new Router();

$router->add('GET /test', function () {
    return [
        'controller' => 'Test',
        'action' => 'test',
        'vars' => func_get_args(),
    ];
});

$router->add('GET /api/todo/:id', function ($id) {
    return [
        'controller' => 'Todo',
        'action' => 'view',
        'vars' => func_get_args(),
    ];
});

$router->add('GET /api/todo', function () {
    return [
        'controller' => 'Todo',
        'action' => 'index',
        'vars' => func_get_args(),
    ];
});


$router->add('POST /api/todo', function () {
    return [
        'controller' => 'Todo',
        'action' => 'add',
        'vars' => func_get_args(),
    ];
});

$router->add('PUT /api/todo/:id', function ($id) {
    return [
        'controller' => 'Todo',
        'action' => 'edit',
        'vars' => func_get_args(),
    ];
});

$router->add('DELETE /api/todo/:id', function ($id) {
    return [
        'controller' => 'Todo',
        'action' => 'delete',
        'vars' => func_get_args(),
    ];
});

$router->add('GET /rand/:num?', function ($num) {
    return [
        'controller' => 'Rand',
        'action' => 'rand',
        'vars' => func_get_args(),
    ];
});

$router->add('GET /api/rand/:num?', function ($num) {
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