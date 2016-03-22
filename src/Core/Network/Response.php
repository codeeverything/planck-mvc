<?php

namespace Planck\Core\Network;

class Response {
    
    private $__status = null;
    
    private $__body = null;
    
    private $__headers = [];
    
    public function __construct($body = '', $status = 200) {
        $this->body($body);
        $this->status($status);
        $this->header('Content-Type', 'application/json');
    }
    
    public function status($status = null) {
        if ($status !== null) {
            $this->__status = $status;
        }
        
        return $this->__status;
    }
    
    public function body($body = null) {
        if ($body !== null) {
            $this->__body = $body;
            return;
        }
        
        return $this->__body;
    }
    
    public function header($header = null, $value = null) {
        if ($header !== null) {
            $this->__headers[$header] = $value;
            return;
        }
        
        return $this->headers[$header];
    }
    
    public function send() {
        header("HTTP/1.1 " . $this->status());
        foreach ($this->__headers as $header => $value) {
            header("$header: $value");
        }
        
        echo $this->body();
    }
}