<?php

namespace Planck\App\Controller;

use Planck\Core\Controller\Controller;
use Planck\Core\Network\Request;
use Planck\App\Entity\Todo;

/**
 * Manage Todo tasks
 * 
 * @author Mike Timms <mike@codeeverything.com>
 */
class TodoController extends Controller {
    /**
     * Store our database access object
     */
    protected $db;
    
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
    
    public function index() {
        //
        $productRepository = $this->db->getRepository('Planck:Todo');
        $products = $productRepository->findAll();
        
        $res = [];
        foreach ($products as $product) {
            $res[] = $product->toArray();
        }

        return $res;
    }
    
    public function view($id) {
        //   
        $productRepository = $this->db->getRepository('Planck:Todo');
        $product = $productRepository->find($id);
        return $product->toArray();
    }
    
    public function add() {
        //
        $raw = json_decode(file_get_contents('php://input'), true);
        
        $product = new Todo();
        $product->setName($raw['name']);
        
        $this->db->persist($product);
        $this->db->flush();
        
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
        
        return $product->toArray();
    }
    
    public function delete($id) {
        $product = $this->db->getReference('Planck:Todo', $id);
        $this->db->remove($product);
        $this->db->flush();
    }
}