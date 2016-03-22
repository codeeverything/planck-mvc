<?php

namespace Planck\Core\Controller;

use Planck\Core\Network\Request;
use Planck\Core\Event\Event;
use Planck\Core\Event\EventListener;

abstract class Controller implements EventListener {
    protected $vars = array();
    
    public function __construct() {
        // TODO: We attach the events before the init() function is called, which now injects dependencies, so we wouldn't have access to these?
        // listen for events
        Event::attach($this);
    }
    
    public function init() {}
    
    protected function json($value) {
        $this->set(array('_serialize' => json_encode($value)));
    }
    
    public function set($values) {
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
    
    public function beforeAction() {
        $this->set([
            'parentController::beforeAction' => true,
        ]);
    }
    
    public function afterAction($data = array()) {}
}