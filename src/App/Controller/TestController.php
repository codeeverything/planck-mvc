<?php

namespace Planck\App\Controller;

use Planck\Core\Controller\RESTController;
use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\Core\Exception\HttpNotFoundException;
use Planck\Core\Exception\HttpUnauthorizedException;
use Planck\Core\Exception\HttpForbiddenException;

/**
 * Manage Todo tasks
 * 
 * @author Mike Timms <mike@codeeverything.com>
 */
class TestController extends RESTController {
    
    // public function init($db) {
        
    // }
    
    public function test() {
        trigger_error("Number cannot be larger than 10");
        throw new HttpUnauthorizedException('Could not find that resource');
        $this->ok();
        return 'hello, world';
    }
    
}