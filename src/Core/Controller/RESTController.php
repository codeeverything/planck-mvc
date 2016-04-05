<?php

namespace Planck\Core\Controller;

use Planck\Core\Network\Response;

class RESTController extends Controller {
    
    public function sendError($message = 'Default error message', $code = 400) {
        $this->set([
            'error' => [
                'message' => $message,
                'status' => $code,
            ],
        ]);
        
        $this->response->status($code);
    }
    
    public function sendCreated($data) {
        $this->response->status(Response::CREATED);
        return $data;
    }
    
}