<?php

use Planck\Core\Network\Request;
use Planck\Core\Controller\Controller;

error_reporting(E_ALL);
ini_set('display_errors', true);

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}

set_error_handler('exceptions_error_handler');

require '../vendor/autoload.php';
require '../src/Core/Utils/Utils.php';

include_once '../Config/services.php';
include_once '../Config/routes.php';

Request::init();

$controller = parse_url(Request::path());
$controller = current(explode('.', $controller['path']));

$params = array();
if (array_key_exists($controller, $routes)) {
    // TODO: Add support for path params
    $controllerArray = $controller;
    $controller = $routes[$controllerArray]['controller'] . 'Controller';
    $action = $routes[$controllerArray]['action'];
} else {
    $controller = explode('/', trim($controller, '/'));
    
    $controller[0] = ucfirst($controller[0]);
    
    if (count($controller) === 1) {
        $controller = "{$controller[0]}Controller";
        $action = 'index';
    } elseif (count($controller) > 1) {
        $action = $controller[1];
        $params = array_slice($controller, 2);
        $controller = "{$controller[0]}Controller";
    } 
}

// store the controller name
$controllerName = $controller;

try {
    include '../src/app/controller/' . $controllerName . '.php';
    
    $fullControllerName = 'Planck\\app\\controller\\' . $controllerName;
    
    // get an instance of the controller
    $controller = new $fullControllerName();
    $controller->setContainer($sack);
    // call the action
    call_user_func_array(array($controller, $action), $params);
    
    if (isset($_serialize)) {
        echo $_serialize;
    } else {
        if (!file_exists('../src/app/view/' . $controllerName . '/' . $action . '.tpl')) {
            throw new Exception("View not found for $controllerName::$action()");
        } else {
            $renderer = new Renderer();
            ob_start();
            include '../src/app/view/' . $controllerName . '/' . $action . '.tpl';
            $out = $renderer->render(ob_get_clean(), $controller->getVars());
            echo $out;
        }
    }
} catch(Exception $e) {
    echo $e->getMessage();
}

echo "done";