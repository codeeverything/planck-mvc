<?php
/**
 * A Response Formatter for JSON
 * 
 * Take some set of data (can be any data type encodable as JSON), and encodes it as JSON
 */

namespace Planck\Core\Network\ResponseFormatters;

class JsonFormatter implements IResponseFormatter {
    
    public function format($data) {
        $status = json_encode($data);
        
        if ($status === false) {
            throw Exception('Value could not be encoded as JSON.');
        }
        
        return $status;
    }
    
    public function getHeaders() {
        return [
            'Content-Type' => 'application/json',
        ];
    }
    
}