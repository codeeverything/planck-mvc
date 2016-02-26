<?php

include_once '../core/controller/Controller.php';

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
            'name' => $name
        ));
    }
    
    public function bye() {
        //
    }
}