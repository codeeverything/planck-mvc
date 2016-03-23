<?php

namespace Planck\App\Controller;

use Planck\Core\Controller\Controller;
use Planck\Core\Network\Request;
use Planck\Core\Network\Response;

class RandController extends Controller {
    
    public function init($rand = null) {
        $this->rand = $rand;
    }
    
    public function rand() {
        return $this->rand[0];
    }
}