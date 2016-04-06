<?php

namespace Planck\Core\Network;

use Planck\Core\Network\ResponseFormatters\JsonFormatter;

/**
 * Abstract the HTTP response to give
 * 
 * @author Mike Timms
 */
class Response {
    
    // TODO: Maybe this should be static like Request?
    
    private $__status = null;
    
    private $__body = null;
    
    private $__headers = [];
    
    public $responseFormatter = null;
    
    const NOTFOUND = 404;
    
    const BADREQUEST = 400;
    
    const FORBIDDEN = 403;
    
    const UNAUTHORISED = 401;
    
    const CONFLICT = 409;
    
    const SUCCESS = 200;
    
    const CREATED = 201;
    
    const ACCEPTED = 202;
    
    const NOCONTENT = 204;
    
    const NOTMODIFIED = 304;
    
    public function __construct($responseFormatter = null) {
        $this->responseFormatter = is_null($responseFormatter) ? new JsonFormatter() : $responseFormatter;
        
        $this->body('');
        $this->status(200);
    }
    
    /**
     * Get or Set the HTTP status code of the response
     * 
     * @param int $status - The HTTP status code to apply
     * @return int
     */
    public function status($status = null) {
        if ($status !== null) {
            if (!is_int($status)) {
                trigger_error('HTTP status code must be an integer');
            }
            
            $this->__status = $status;
        }
        
        return $this->__status;
    }
    
    /**
     * Get or set the response body
     * 
     * @return string
     */
    public function body($body = null) {
        if ($body !== null) {
            $this->__body = $this->responseFormatter->format($body);
            return;
        }
        
        return $this->__body;
    }
    
    /**
     * Get or set the HTTP response header given by $header
     * 
     * @return string
     */
    public function header($header = null, $value = null) {
        if ($header !== null) {
            $this->__headers[$header] = $value;
            return;
        }
        
        return $this->headers[$header];
    }
    
    /**
     * Send the HTTP response
     * 
     * @return void
     */
    public function send() {
        header("HTTP/1.1 " . $this->status());
        
        foreach ($this->responseFormatter->getHeaders() as $header => $value) {
            header("$header: $value");
        }
        
        foreach ($this->__headers as $header => $value) {
            header("$header: $value");
        }
        
        echo $this->body();
    }
}