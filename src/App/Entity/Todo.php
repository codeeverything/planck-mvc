<?php

namespace Planck\App\Entity;

use Planck\Core\Entity\BaseEntity;

/**
 * @Entity
 * @Table(name="todos")
 */
class Todo extends BaseEntity {
    
    /** 
     * @Id
     * @Column(type="integer") 
     * @GeneratedValue 
     */
    public $id;
    
    /** 
     * @Column(type="string") 
     */
    public $name;
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
}