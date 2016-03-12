<?php

namespace Planck\Core\Network;

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