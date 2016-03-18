<?php

namespace Planck\Core\Network;

class Response {
    
    private $__status = null;
    
    private $__body = null;
    
    public function __construct($body = '', $status = 200) {
        $this->body($body);
        $this->status($status);
    }
    
    public function status($status) {
        if (isset($status)) {
            $this->__status = $status;
        }
        
        return $this->__status;
    }
    
    public function body($body) {
        if (isset($body)) {
            $this->__body = $body;
            return;
        }
        
        return $this->__body;
    }
    
    public function send() {
        header("HTTP/1.1 $this->status() ");
        echo $this->body();
    }
}