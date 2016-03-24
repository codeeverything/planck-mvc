<?php

namespace Planck\App\Controller;

use Planck\Core\Controller\Controller;
use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\Core\Network\ResponseFormatters\XMLFormatter;

class RandController extends Controller {
    
    public function init($rand = null) {
        $this->rand = $rand;
    }
    
    public function rand($num = 1) {
        if (is_null($num)) $num = 1;
        
        $rand = [];
        for ($i=0; $i < $num; $i++) {
            $rand[] = $this->rand[0];
        }
        
        return join(', ', $rand);
    }
}