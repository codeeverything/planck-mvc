<?php

namespace Planck\app\listener;

use Planck\Core\Event\EventListener;

class ExampleListener implements EventListener {
    public function attachedEvents() {
        return [
            'controller.beforeAction' => 'doSomething',
            'app.hello' => 'hello',
        ];
    }
    
    public function doSomething($controller) {
        $controller->set([
            'externalListener' => true,
        ]);
    }
    
    public function hello($controller, $name) {
         $controller->set([
             "hello.message" => "Why hello $name! How you doin'? :)"
         ]);
    }
}