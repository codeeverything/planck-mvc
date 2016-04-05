<?php

/**
 * Defines an interface that all Response Formatter classes must implement
 * 
 * format() receives some ecodable data, manipulates it and returns the encoded representation
 * getHeaders() returns an array of HTTP headers to be set for this format. For example, a JSON formatter 
 * would set the "Content-Type" header to "application/json"
 * 
 */
namespace Planck\Core\Network\ResponseFormatters;

interface IResponseFormatter {
    
    /**
     * Encode the value $data and return this encoded representation
     * 
     * @param mixed $data - The data to encode/format
     * @return mixed
     */
    public function format($data);
    
    /**
     * Return an array of appropriate HTTP headers, if any for the format
     * 
     * @return array
     */
    public function getHeaders();
    
}