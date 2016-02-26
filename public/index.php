<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

class Request {
    
    static $pathParams = null;
    
    static $ext = null;
    
    static public function init() {
        static::$pathParams = static::__pathParams();
    }
    
    static public function data($query) {
        $query = explode('.', $query);
        if (!$query || count($query) !== 2) {
            throw new Exception('Query is invalid');
        }
        
        $method = strtolower($query[0]);
        $index = $query[1];
        
        $call = "__{$method}Param";
        
        return static::$call($index);
    }
    
    static private function __getParam($param = null) {
        if (!$param) {
            return $_GET;
        }
        
        if (!isset($_GET[$param])) {
            return null;
        }
        
        return $_GET[$param];
    }
    
    static private function __postParam($param = null) {
        return $_POST[$param];
    }
    
    static private function __serverParam($param = null) {
        return $_SERVER[$param];
    }
    
    static private function __headerParam($param = null) {
        $header = null;
        
        $header = array_filter($_SERVER, function ($item) use ($param) { 
            $key = key($_SERVER);
            next($_SERVER);
            return (strpos($key, 'HTTP_') === 0 && strpos($key, 'COOKIE') === false && ($param == null || substr($key, 5) === $param));
        });
        
        if (count($header) === 1) {
            $header = current($header);
        }
        
        // var_dump($header);
        
        return $header;
    }
    
    static private function __pathParams() {
        $path = static::path();
        $path = parse_url($path);
        // print_r($path);
        $ext = explode('.', $path['path']);
        // debug($ext);
        if (count($ext) === 2) {
            $ext = $ext[1];
        } else {
            $ext = '';
        }
        
        static::$ext = $ext;
        return explode('/', $path['path']);
    }
    
    static public function path() {
        return static::__serverParam('REQUEST_URI');
    }
}

set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}

include_once '../core/utils/Utils.php';


Request::init();
// echo Request::data('GET.foo');
// echo Request::data('GET.bar');
// echo Request::data('SERVER.REQUEST_URI');
// print_r( Request::data('HEADER.HOST') );
// print_r( Request::path() );

$routes = array(
    '/hello' => array(
        'controller' => 'My',
        'action' => 'hello',
    ),
    '/bye' => array(
        'controller' => 'My',
        'action' => 'bye',
    ),
);

$controller = parse_url(Request::path());
$controller = current(explode('.', $controller['path']));

// debug($controller);
// die();

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
    include '../app/controller/' . $controllerName . '.php';
    
    // get an instance of the controller
    $controller = new $controller();
    // call the action
    call_user_func_array(array($controller, $action), $params);
    
    // extract any variables for use in the view
    // extract($controller->getVars());

    if (isset($_serialize)) {
        echo $_serialize;
    } else {
        if (!file_exists('../app/view/' . $controllerName . '/' . $action . '.tpl')) {
            throw new Exception("View not found for $controllerName::$action()");
        } else {
            ob_start();
            include '../app/view/' . $controllerName . '/' . $action . '.tpl';
            $out = ob_get_clean();
            foreach ($controller->getVars() as $var => $value) {
                $out = preg_replace('/{{\s?' . $var . '\s?}}/m', $value, $out);
            }
            
            echo $out;
        }
    }
} catch(Exception $e) {
    echo $e->getMessage();
}

echo "done";