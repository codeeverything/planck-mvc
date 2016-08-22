<?php

namespace Planck\Core;

use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\Core\Controller\Controller;
use Planck\Core\Event\Event;
use Planck\Core\Lib\Timer;
use Planck\Core\Exception\BaseException;

/**
 * The starting point of the Planck app
 */
class PlanckApp {
    
    // TODO: Pull the router out of the container?
    public static function run($router, $container, $config) {
        echo "This is a build test! Bad for the code though!";
        die();
        
        Timer::start('planck:init');
        
        // init the request
        Request::init();
        
        // init the response
        $response = new Response();
        
        // set exception handler
        set_exception_handler(function ($exception) use ($response, $container) {
            // get the error response builder
            if (method_exists($exception, 'setErrorResponseBuilder')) {
                $exception->setErrorResponseBuilder($container->get('errorResponseBuilder'));
            } else {
                $exception = new BaseException($exception->getMessage() . ' Exception code given: ' . $exception->getCode());    
            }
            
            // set the response body
            // the status, headers and other response properties might be modified in the errorResponseBuilder
            // but the body is set here only
            $response->body($exception->buildResponse($response));
            
            // send it on
            $response->send();
            
            // gather performance metrics
            Timer::times();
            echo (memory_get_peak_usage(true) / 1024 / 1024) . 'MB';
        });
        
        // set error handler
        set_error_handler(function ($severity, $message, $filename, $lineno) {
            if (error_reporting() == 0) {
                return;
            }
            
            if (error_reporting() & $severity) {
                throw new BaseException($message, 500);
            }
        });
        
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
            
            // declare some initilisation args (empty)
            $initArgs = [];
            
            // check if we need to build on that list
            if ($class->hasMethod('init')) {
                $controllerArgs = $class->getMethod('init')->getParameters();
                
                // Build a list of services to inject into the controller (if any)
                foreach ($controllerArgs as $arg) {
                    $varName = $arg->name;
                    if ($container->has($varName)) {
                        $initArgs[] = $container->get($varName);
                    }
                }
            }
            
            // we don't deal with the constructor to inject, we use the init function instead
            // we want to call the parent Controller classes constructor for generic setup of controllers
            $controller = $class->newInstanceArgs([$response]);
            if (count($initArgs) > 0) {
                call_user_func_array([$controller, 'init'], $initArgs);
            }
            
            Timer::end('planck:init');
            Timer::start('planck:controller');
            
            // emit the controller.beforeAction
            Event::emit('controller.beforeAction', [$controller]);
            
            // call the action
            $res = call_user_func_array(array($controller, $action), $params);
            
            Event::emit('controller.afterAction', [$res]);
            
            Timer::end('planck:controller');
            
            Timer::start('planck:response');
            
            // if controller response is null then set Response::body($controller->getVars())
            if (is_null($res)) {
                $response->body($controller->getVars());
            } else {
                // else set to response
                $response->body($res);
            }
            
            // Response::send()
            $response->send();
            
            Timer::end('planck:response');
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