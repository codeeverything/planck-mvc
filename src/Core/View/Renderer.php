<?php

namespace Planck\Core\View;

class Renderer {
    
    public function render($content, $vars = []) {
        foreach ($vars as $var => $value) {
            if (is_string($value)) {
                $content = preg_replace('/{{\s?' . $var . '\s?}}/m', $value, $content);
            }
        }
        
       return $content;
    }
    
}