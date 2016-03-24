<?php

namespace Planck\Core\Network\ResponseFormatters;

class JsonFormatter {
    
    public function format($data) {
        return json_encode($data);
    }
    
    public function getHeaders() {
        return [
            'Content-Type' => 'application/json',
        ];
    }
    
}