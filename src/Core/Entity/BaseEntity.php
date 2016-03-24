<?php

namespace Planck\Core\Entity;

class BaseEntity {
    
    public function toArray() {
        return get_object_vars($this);
    }
}