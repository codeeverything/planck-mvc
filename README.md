# Planck 
### A small framework for RESTful PHP apps
An experiment in writing a [small] RESTful framework for PHP. Makes use of the [Burlap](https://www.github.com/codeeverything/burlap) container for dependencies.

Named for the [Planck time](https://en.wikipedia.org/wiki/Planck_time) and [length](https://en.wikipedia.org/wiki/Planck_length) - the smallest measurements of time and length, respectively, that have any meaning.

NB: This is an ongoing project in its infancy, so expect much to be missing and much not to work ;)

## Open Source

This is an open source effort, and although its only a pet project of mine contributions are welcome! So fork and PR away :)

Feel free to add issues to leave suggestions and comments.

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
- [ ] Push the "dispatching" logic out of index.php and into a framework/application/kernel class
  - Maybe tie this in with the utility functions like pr() and debug() to have them add their output to the response rather than inline at the time of calling (a la CakePHP)?