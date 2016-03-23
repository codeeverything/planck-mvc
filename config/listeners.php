<?php

use Planck\Core\Event\Event;
use Planck\App\Listener;

Event::attach(new Listener\ExampleListener());