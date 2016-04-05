<?php

namespace Planck\App\Controller;

use Planck\Core\Controller\RESTController;
use Planck\Core\Network\Request;
use Planck\Core\Network\Response;
use Planck\App\Entity\Todo;
use Planck\Core\Exception\HttpNotFoundException;

/**
 * Manage Todo tasks
 * 
 * @author Mike Timms <mike@codeeverything.com>
 */
class TodoController extends RESTController {
    /**
     * Store our database access object
     */
    protected $db;
    
    public function test() {
        return 'hello, world';
    }
    
    /**
     * Init the controller. Arguments should match entries in the container and will be injected
     * 
     * @param EntityManager $db - The Doctrine Entity Manager
     * @return void
     */
    public function init(\Doctrine\ORM\EntityManager $db) {
        //
        $this->db = $db;
    }
    
    /**
     * Read and return a list of all Todo items
     * 
     * @return array
     */
    public function index() {
        //
        $todoRepository = $this->db->getRepository('Planck:Todo');
        $todos = $todoRepository->findAll();
        
        $res = [];
        foreach ($todos as $todo) {
            $res[] = $todo->toArray();
        }

        return $res;
    }
    
    /**
     * Read and return the details of the todo item given by $id
     * 
     * @param int $id - The ID of the Todo item to read
     * @return array
     */
    public function view($id) {
        //   
        $todoRepository = $this->db->getRepository('Planck:Todo');
        $todo = $todoRepository->find($id);
        
        if ($todo === null) {
            throw new HttpNotFoundException('Todo not found');
        }
        
        return $todo->toArray();
    }

    /**
     * Add a new Todo item based on the data sent in the request
     * 
     * @return array
     */
    public function add() {
        //
        $raw = json_decode(Request::raw(), true);
        
        if ($raw === false) {
            throw new HttpBadRequestException('Error creating Todo - Could not decode input.');
        }
        
        $product = new Todo();
        $product->setName($raw['name']);
        
        $this->db->persist($product);
        $this->db->flush();
        
        // $this->created();
        return $product->toArray();
    }
    
    public function edit($id) {
        //
        $raw = json_decode(file_get_contents('php://input'), true);
        
        $productRepository = $this->db->getRepository('Planck:Todo');
        $product = $productRepository->find($id);
        
        foreach ($raw as $key => $value) {
            $func = 'set' . ucfirst($key);
            $product->{$func}($value);
        }
        
        $this->db->persist($product);
        $this->db->flush();
        
        // $this->created();
        return $product->toArray();
    }
    
    public function delete($id) {
        $product = $this->db->getReference('Planck:Todo', $id);
        $this->db->remove($product);
        $this->db->flush();
        // $this->blank();
    }
}