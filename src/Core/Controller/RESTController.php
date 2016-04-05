<?php

namespace Planck\Core\Controller;

use Planck\Core\Network\Response;
use Planck\Core\Network\Request;

class RESTController extends Controller {
    
    // TODO: Deprecate this - throw exceptions instead
    public function sendError($message = 'Default error message', $code = 400) {
        $this->set([
            'error' => [
                'message' => $message,
                'status' => $code,
            ],
        ]);
        
        $this->response->status($code);
    }
    
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
    
    public function created() {
        $this->response->status(Response::CREATED);
    }
    
    public function ok() {
        $this->response->status(Response::SUCCESS);
    }
    
    public function blank() {
        $this->response->status(Response::NOCONTENT);
    }
    
    public function unchanged() {
        $this->response->status(Response::NOTMODIFIED);
    }
    
}