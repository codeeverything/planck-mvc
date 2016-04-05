<?php

namespace Planck\Core\Controller;

use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\Core\Event\Event;
use Planck\Core\Event\IEventListener;
use Exception;

/**
 * The base controller for all application controllers
 * 
 * @author Mike Timms
 */
abstract class Controller implements IEventListener {
    /**
     * Holds the data to be returned after the controller action has executed
     */
    protected $vars = array();
    
    public $response = null;
    
    /**
     * Construct our controller and pass to the Event handler to attach any events it listens for
     * 
     * @return void
     */
    public function __construct(Response $response) {
        $this->response = $response;
        
        // TODO: We attach the events before the init() function is called, which now injects dependencies, so we wouldn't have access to these?
        // listen for events
        Event::attach($this);
    }
    
    /**
     * Called after the controller is constructed.
     * This function receives any dependencies. 
     * Void in this base controller
     * 
     * @return void
     */
    // public function init() {
        
    // }
    
    /**
     * Defines the events controllers will listen for, and the function to call when those
     * events occur
     * 
     * @return array
     */
    public function attachedEvents() {
        return [
            'controller.beforeAction' => 'beforeAction',
            'controller.afterAction' => 'afterAction',
        ];
    }
    
    /**
     * Take an array of key, value pairs and push them into the $vars array.
     * This is the data that will be returned from the controller if nothing is explicitly returned from an action
     * 
     * @param array $values - An array of values. Keys = variable name, value = value
     */
    public function set($values) {
        if (!is_array($values)) {
            throw new Exception('Controller::set() expects argument to be an array. Keys = property name, Values = value.');
        }
        
        $this->vars = array_merge($this->getVars(), $values);  
    }
    
    /**
     * Return the value of $this->vars
     * 
     * @return array
     */
    public function getVars() {
        return $this->vars;
    }
    
    /**
     * A callback to do something before a controller action is called
     * 
     * @return void
     */
    public function beforeAction() {
        $this->set([
            'parentController::beforeAction' => true,
        ]);
    }
    
    /**
     * A callback to do something after a controller action is called
     * 
     * @param array $data - The value returned from the action
     * @return void
     */
    public function afterAction($data = array()) {}
}