<?php

use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\Core\Controller\Controller;
use Planck\Core\Event\Event;

require '../vendor/autoload.php';

// handy utility functions
require '../src/Core/Utils/Utils.php';

// config
$config = [];

include_once '../config/services.php';
include_once '../config/routes.php';
include_once '../config/listeners.php';
include_once '../config/app.php';

error_reporting($config['app.errorlevel']);
ini_set('display_errors', $config['app.debug']);

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}

set_error_handler('exceptions_error_handler');

// init the request
Request::init();
// init the response
$response = new Response();

// call the handling function configured for the router
// TODO: Maybe wrap this sort of thing in an invoke() function? Then can check object, 
// check method exists, handle errors more generally and gracefully?
$routingData = call_user_func_array([$router, $config['app.router.handler']], []);
$controller = $routingData['controller'] . 'Controller';
$action = either($routingData['action'], 'index');
$params = either($routingData['vars'], []);

// store the controller name
$controllerName = $controller;

try {
    $fullControllerName = 'Planck\\App\\Controller\\' . $controllerName;
    
    // get an instance of the controller
    $class = new ReflectionClass($fullControllerName);
    $controllerArgs = $class->getMethod('init')->getParameters();
    
    // TODO: This is tightly coupled to the container, try to decouple this so you could use any container
    $initArgs = [];
    foreach ($controllerArgs as $arg) {
        $varName = $arg->name;
        if ($container->has($varName)) {
            $initArgs[] = $container->get($varName);
        }
    }
    
    // we don't deal with the constructor to inject, we use the init function instead
    // we want to call the parent Controller classes constructor for generic setup of controllers
    $controller = $class->newInstanceArgs([$response]);
    $controller->init($initArgs);
    
    // emit the controller.beforeAction
    Event::emit('controller.beforeAction', [$controller]);
    
    // call the action
    $res = call_user_func_array(array($controller, $action), $params);
    
    Event::emit('controller.afterAction', [$res]);
    
    // if controller response is null then set Response::body($controller->getVars())
    if (is_null($res)) {
        $response->body($controller->getVars());
    } else {
        // else set to response
        $response->body($res);
    }
    
    // Response::send()
    $response->send();
} catch(Exception $e) {
    $response->status(400);
    $response->body($e->getMessage());
    $response->send();
}