<?php

namespace Planck\Core\Event;

/**
 * Defines an interface that all "listeners" should implement
 * 
 * @author Mike Timms
 */
interface IEventListener {
    public function attachedEvents();
}