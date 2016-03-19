<?php

namespace Planck\Core\Controller;

use Planck\Core\Network\Request;
use Planck\Core\Event\Event;
use Planck\Core\Event\EventListener;

abstract class Controller implements EventListener {
    protected $vars = array();
    
    public function __construct() {
        // listen for events
        Event::attach($this);
    }
    
    public function init() {}
    
    protected function json($value) {
        $this->set(array('_serialize' => json_encode($value)));
    }
    
    protected function set($values) {
        $this->vars = array_merge($this->getVars(), $values);  
    }
    
    public function getVars() {
        return $this->vars;
    }
    
    public function attachedEvents() {
        return [
            'controller.beforeAction' => 'beforeAction',
            'controller.afterAction' => 'afterAction',
        ];
    }
    
    public function beforeAction() {}
    
    public function afterAction() {}
}