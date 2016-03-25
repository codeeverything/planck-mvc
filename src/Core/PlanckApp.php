<?php

namespace Planck\Core;

use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\Core\Controller\Controller;
use Planck\Core\Event\Event;

/**
 * The starting point of the Planck app
 */
class PlanckApp {
    
    public static function run($router, $container, $config) {
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
            $class = new \ReflectionClass($fullControllerName);
            $controllerArgs = $class->getMethod('init')->getParameters();
            
            // Build a list of services to inject into the controller (if any)
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
            call_user_func_array([$controller, 'init'], $initArgs);
            
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
    }
    
    /**
     * Basically a wrapper around call_user_func_array() but with additional checks and error handling
     */
    public static function invoke($object, $method, $args = []) {
        // do somethin'
    }
    
}