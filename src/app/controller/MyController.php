<?php

namespace Planck\app\controller;

use Planck\Core\Controller\Controller;
use Planck\Core\Network\Request;
use Planck\Core\Network\Response;

class MyController extends Controller {
    
    public function __construct($rand = null) {
        $this->rand = $rand;
    }
    
    public function index($what = 'wut!') {
        $name = either(Request::data('GET.name'), 'World');
        
        if ($name == 'Mike') {
            $name .=  ' (Great Name!)';
        }
        
        // debug(Request::$ext);
        $data = array(
            'name' => $name,
            'what' => $what,
            'else' => array(
                'more' => true,
            ),
        );
        
        debug($data);
        
        if (Request::$ext === 'json') {
            $this->json($data)    ;
        } else {
            $this->set($data);
        }
    }
    
    public function foo() {
        //
    }
    
    public function hello() {
        $name = either(Request::data('GET.name'), 'there');
        $this->set(array(
            'rand' => $this->rand,
        ));
    }
    
    public function bye() {
        //
    }
}