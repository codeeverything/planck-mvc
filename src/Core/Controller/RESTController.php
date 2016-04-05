<?php

namespace Planck\Core\Controller;

use Planck\Core\Network\Response;
use Planck\Core\Network\Request;

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
     * been created
     * 
     * @param bool $immediate - Whether the object was created immediately, or accepted to be created later
     * @return void
     */
    public function created($immediate = true) {
        // if immediate is true then the object was created, if false then it was accepted to be created later (e.g. put on a MQ)
        $code = $immediate ? Response::CREATED : Response::ACCEPTED;
        $this->response->status($code);
    }
    
    public function ok() {
        $this->response->status(Response::SUCCESS);
    }
    
    public function blank() {
        $this->response->status(Response::NOCONTENT);
        $this->response->body('');
    }
    
    public function unchanged() {
        $this->response->status(Response::NOTMODIFIED);
        $this->response->body('');
    }
    
}