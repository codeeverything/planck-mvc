<?php

namespace Planck\App\Controller;

use Planck\Core\Controller\Controller;
use Planck\Core\Network\Request;
use Planck\App\Entity\Todo;

class TodoController extends Controller {
    protected $db;
    
    public function init($db) {
        //
        $this->db = $db[0];
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