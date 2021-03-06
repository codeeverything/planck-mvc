<?php

namespace Planck\Core\Exception;

/**
 * A base exception class to handle generic tasks
 * 
 * @author Mike Timms <mike@codeeverything.com>
 */
class BaseException extends \Exception {
    private $errorResponseBuilder = null;
    
    /**
     * Constructor - pass up to parent
     * 
     * This is the catch-all exception, in RESTful apps this is typically
     * a 500 response (SERVER ERROR), with no additional information
     * 
     * @param string $message - The message describing the exception
     * @param int $code - The HTTP code to describe the exception
     * @return void
     */
    public function __construct($message, $code = 500) {
        parent::__construct($message, $code);
    }
    
    /**
     * Helper to set the function to use to structure an error response
     * 
     * @param mixed $service - The service (callable) to use to generate error responses
     * @return void
     */
    public function setErrorResponseBuilder($service) {
        $this->errorResponseBuilder = $service;
    }
    
    /**
     * Build an error response.
     * Either uses the service "errorResponseBuilder" from the container, or a default structure
     * 
     * Must return something if you want to set the response body
     * 
     * @return mixed
     */
    public function buildResponse($response) {
        // if null, use a default structure
        if (is_null($this->errorResponseBuilder)) {
            $response->status($this->getCode());
            
            return [
              'error' => $this->getMessage(),
              'code' => $this->getCode(),
            ];
        } 
        
        // use whatever has been defined in the service
        return call_user_func($this->errorResponseBuilder, $this, $response);
    }
}