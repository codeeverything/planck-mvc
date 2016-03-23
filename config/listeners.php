<?php

/**
 * Define objects that listen for events
 */
 
use Planck\Core\Event\Event;
use Planck\App\Listener;

Event::attach(new Listener\ExampleListener());