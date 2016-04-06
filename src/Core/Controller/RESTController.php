<?php

namespace Planck\Core\Controller;

use Planck\Core\Network\Response;
use Planck\Core\Network\Request;

/**
 * A controller to provide some convenience methods for handling RESTful requests and responses
 * 
 * @author Mike Timms <mike@codeeverything.com>
 */
class RESTController extends Controller {
    
    /**
     * Send an error - deprecated
     */
    public function sendError($message = 'Default error message', $code = 400) {
        throw new HttpBadRequestException('RESTController::sendError() is deprecated, please throw exceptions instead.');
    }
    
    /**
     * Given the request method set certain expected return codes
     * 
     * @param mixed $data - The data being returned in the response
     * @return void
     */
    public function afterAction($data = array()) {
        if (Request::is('post') || Request::is('put')) {
            // should send created response (most likely)
            $this->created();
            return;
        }
        
        if (Request::is('delete')) {
            // should send created response (most likely)
            $this->blank();
            return;
        }
        
        $this->ok();
    }
    
    /**
     * Set the response to have an appropriate code for objects that have 
     * been created.
     * 
     * In RESTful apps this is typically 201 (CREATED), if the resource is created
     * immediately, or 202 (ACCEPTED), if the resource is to be created later 
     * (perhaps pushed onto a message queue or similar)
     * 
     * @param bool $immediate - Whether the object was created immediately, or accepted to be created later
     * @return void
     */
    public function created($immediate = true) {
        // if immediate is true then the object was created, if false then it was accepted to be created later (e.g. put on a MQ)
        $code = $immediate ? Response::CREATED : Response::ACCEPTED;
        $this->response->status($code);
    }
    
    /**
     * Set the response to have a code to represent successful retreival requests
     * where there is some body to the message.
     * 
     * IN RESTful apps this is typically 200 (OK)
     * 
     * @return void
     */
    public function ok() {
        $this->response->status(Response::SUCCESS);
    }
    
    /**
     * Set the response to have a code to represent successful requests
     * where there is NO body to the message.
     * 
     * IN RESTful apps this is typically 204 (NO CONTENT)
     * 
     * @return void
     */
    public function blank() {
        $this->response->status(Response::NOCONTENT);
        $this->response->body('');
    }
    
    /**
     * Set the response to have a code to represent successful requests
     * where the object being requested is unchanged from the local 
     * cached version/since the last request for the object.
     * 
     * IN RESTful apps this is typically 304 (NOT MODIFIED), and the response
     * body is expected to be empty
     * 
     * @return void
     */
    public function unchanged() {
        $this->response->status(Response::NOTMODIFIED);
        $this->response->body('');
    }
    
}