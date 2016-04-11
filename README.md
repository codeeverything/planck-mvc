# Planck 
### A small framework for RESTful PHP apps
An experiment in writing a [small] RESTful framework for PHP. Makes use of the [Burlap](https://www.github.com/codeeverything/burlap) container for dependencies.

Named for the [Planck time](https://en.wikipedia.org/wiki/Planck_time) and [length](https://en.wikipedia.org/wiki/Planck_length) - the smallest measurements of time and length, respectively, that have any meaning.

NB: This is an ongoing project in its infancy, so expect much to be missing and much not to work ;)

## Open Source

This is an open source effort, and although its only a pet project of mine contributions are welcome! So fork and PR away :)

Feel free to add issues to leave suggestions and comments.

## Writing a simple app

Planck is designed to be minimal and to help you quickly get working with RESTful responses. 

The routing of RESTful requests is still the responsibility of your router and routes, but with the default Junction router that's easy to achieve.

### App structure

All your application code will live in the ```/src/App``` folder, with the exception of any routes, services or listeners you define.

Planck expects only two things in order to get you up and running:

- A route which returns an array containing entries for the controller to instantiate, the action to call on that controller and any variables to pass from the route.
- A controller matching the name given in the route, with the suffix "Controller" and an action on that controller matching the action given in the route.

For example:

```php
// config/routes.php
$router->add('GET /hello', function () {
    return [
        'controller' => 'Hello',
        'action' => 'hello',
        'vars' => func_get_args(),
    ];
});
```

```php
// src/App/Controller/HelloController.php
namespace Planck\App\Controller;

class HelloController {
    
    public function hello() {
        return 'hello, world';
    }
    
}
```

Controllers in Planck are the entry and exit points for your business logic. Since we're concerned with RESTful applications we expect a controller to return some data, which can be done in two ways:

- By calling ```$this->set('property_name', 'property_value');``` inline within a controller, to set return values in a peacemeal fashion
- By returning a value, or set of values from the controller ```return ['property' => 'value', 'result' => 1234];```
  - Note: This return can be any serializable data type (using the JsonSerializable interface in PHP this could even be a POPO)

### Responses

Planck's default behaviour is to return responses as JSON, but this can be changed by using one of the other built in ```ResponseFormatters```, or rolling your own.

##### Keeping it RESTful

Planck includes a ```RESTController``` which you can extend to get access to some useful convenience methods - for example:

- ```$this->ok()``` - set the response code as 200
- ```$this->created()``` - set the response code as 201 
- ```$this->created(false)``` - set the response code as 202 (accepted, will be created later)
- ```$this->blank()``` - set the response code as 204 (no message body)
- ```$this->unchanged()``` - set the response code as 304 (removes message body)

The RESTController also includes a handy ```afterAction``` callback which will sniff your request method and try to set approriate codes and body content for you. For example:

- GET - 200 OK
- POST - 201 CREATED
- PUT - 201 CREATED
- DELETE - 204 NO CONTENT

If these don't suit your needs you can currently override the behavior by having an empty (or custom) ```afterAction``` function in your controller. In future I aim to make the process more flexible.

### Throw RESTful errors

If there's an error condition in your app (the record with $id doesn't exist for example), throw an exception to capture this and return an appropriate HTTP coded message, all wrapped up in a nice neat package.

Planck includes exceptions for the most common RESTful error responses, covering:

-  Error (400)
-  Not found (404)
-  Not authorized (401)
-  Forbidden (403)
-  Conflict (409)
-  Server error (500)

Errors are returned in a neat package, handled by defining a service with the name ```errorResponseBuilder```, which returns a function accepting a single argument - the exception that has been thrown.

The default package is an array with items for "error" and "code", showing the exception message and HTTP status code respectively. You can change this to be whatever you want simply be redefining the service.

### Services and Dependency Injection

Planck is agnostic about the container you use, though it does expect it to implement the Container Interoperability Interface.

By default Planck uses the lightweight Burlap container.

A controller's dependencies are drawn from the container, and can be injected by simply referencing container services as the arguments for the controller's ```init()``` function, e.g.:

```php
// controller code

// inject the service labelled as "db" into this function
public function init($db) {
    $this->db = $db;
}

// more controller code
```

### Listeners

Planck implements a simple event system, which you can extend with your own app specific events.

Event listeners are defined in the ```config/listeners.php``` file, and by convention Planck expects these to live in the ```src/App/Listener``` folder, but this is up to you.

##### An example listener 

```php
//src/App/Listener/ExampleListener.php
<?php

namespace Planck\App\Listener;

use Planck\Core\Event\IEventListener;

class ExampleListener implements IEventListener {
    public function attachedEvents() {
        return [
            'controller.beforeAction' => 'doSomething', // core Planck event, triggered before the controller function resolved from the route is called
            'app.hello' => 'hello', // app specific event
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
```

##### Example of "emitting" (triggering) an event

```php
// src/App/Controller/HelloController

use Planck\Core\Event\Event;

public function hello($name) {
    echo "Hello, $name";
    // emit an event and pass the current object plus some more stuff. you can pass anything you want here
    Event::emit('app.welcomed', [$this, $moreData]);
}

// more goodness

```

## TODO

An ever changing list of things to look at! :)

- [x] Remove src/App/view
- [x] Remove src/Core/View
- [x] Add Response Formatters instead
- [ ] Make Response static?
- [ ] Add some tests and PHPUnit config
- [x] Integrate Junction router
- [x] Decouple router (some defined interface, or mapping?)
  - Decoupled by providing an app config "app.router.handler" which defines a method to call on the router object that will handle the request and match routes. Would be nice to find a standard like for the container and implement that instead
- [x] Decouple container (container interoperability interface)
- [ ] Pull "core" into separate framework repo. Maybe attach with subtrees, or just via Composer? 
- [x] Push the "dispatching" logic out of index.php and into a framework/application/kernel class
  - Maybe tie this in with the utility functions like pr() and debug() to have them add their output to the response rather than inline at the time of calling (a la CakePHP)?
- [ ] Make Request and Response classes PSR-7 compliant and split into their own repos/packages
  - Maybe allow these to receive an event manager and details on how to emit events with it so we can do something "afterBodySet" for example, to set debug output?
- [ ] Add some RESTful response helpers, for example: 
  - [x] for returning errors
  - [ ] returning "links"
  - [x] specifying default response codes for actions (200 on find, 201 on create...)
- [ ] Move config and public directories under src/App. Then allow plugins to share the App's directory structure
  - [ ] Move config/database.php, config/app.php out into a /config directory. These transcend the app or plugins
- [ ] Define a Plugin class in the core, this will "load" plugins, i.e. process any configs and add any path pointers if needed (for example to public web resources like JS or images)
- [ ] Add PlanckPlugin to composer's list of installable types
- [ ] Add Planck to composer's create-project list, plugin to?
- 