<?php

namespace Planck\App\Controller;

use Planck\Core\Controller\Controller;
use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\Core\Event\Event;

class MyController extends Controller {
    
    public function init($rand = null) {
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
        
        Event::emit('app.hello', [$this, $name]);
        
        $this->set(array(
            'rand' => $this->rand,
        ));
    }
    
    public function bye() {
        //
    }
    
    public function beforeAction() {
        parent::beforeAction();
        $this->set([
            'beforeAction' => true,
        ]);
    }
    
    public function afterAction($data = array()) {
        $this->set([
            'afterAction' => true,
        ]);
    }
}