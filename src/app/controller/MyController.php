<?php

namespace Planck\app\controller;

use Planck\Core\Controller\Controller;

class MyController extends Controller {
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
        echo $this->container->rand();
        echo "<br>";
        echo $this->container->rand();
        die();
        $name = either(Request::data('GET.name'), 'there');
        $this->set(array(
            'name' => $name
        ));
    }
    
    public function bye() {
        //
    }
}