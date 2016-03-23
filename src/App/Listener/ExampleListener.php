<?php

namespace Planck\App\Listener;

use Planck\Core\Event\IEventListener;

class ExampleListener implements IEventListener {
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