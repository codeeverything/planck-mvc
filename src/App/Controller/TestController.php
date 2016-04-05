<?php

namespace Planck\App\Controller;

use Planck\Core\Controller\RESTController;
use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\Core\Exception\HttpNotFoundException;

/**
 * Manage Todo tasks
 * 
 * @author Mike Timms <mike@codeeverything.com>
 */
class TestController extends RESTController {
    
    // public function init($db) {
        
    // }
    
    public function test() {
        throw new HttpNotFoundException('Could not find that resource');
        return 'hello, world';
    }
    
}