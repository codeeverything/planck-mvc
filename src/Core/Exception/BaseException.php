<?php

namespace Planck\Core\Exception;

class BaseException extends \Exception {
    private $errorResponseBuilder = null;
    
    public function __construct($message, $code = 500) {
        parent::__construct($message, $code);
    }
    
    public function setErrorResponseBuilder($service) {
        $this->errorResponseBuilder = $service;
    }
    
    // TODO: Make this customisable/overridable so people can have their own error response format
    // maybe declare a service that builds the error message and inject that here?
    public function buildResponse() {
        if (is_null($this->errorResponseBuilder)) {
            return [
              'error' => $this->getMessage(),
              'code' => $this->getCode(),
            ];
        } 
        
        return call_user_func($this->errorResponseBuilder, $this);
    }
}