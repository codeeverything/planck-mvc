<?php
/**
 * Define services and parameters to be used in your application 
 */

use Burlap\Burlap;
use Planck\Core\Network\Response;

// grab a container. This variable MUST be called "container"
$container = new Burlap();

// setup service to get a random number
$container->rand([function ($c) {
    $r = rand();
    return $r;    
}]);

// TODO: move to config/errors.php
$container->errorResponseBuilder([function ($c) {
    return function ($ex, $response) {
        
        $code = $ex->getCode();
        $response->status($code);
            
        // if unauthorised we should send a challenge in our response, giving the auth scheme
        if ($code === Response::UNAUTHORISED) {
            $response->header('WWW-Authenticate', 'JWT');
        }
        
        // if we don't want to send 403s, mask as a 404
        // if ($code === Response::FORBIDDEN) {
        //     $response->status(Response::NOTFOUND);
        // }
            
        return [
            'error' => [
                'message' => $ex->getMessage(),
                'code' => $ex->getCode(),
            ],
        ];
    };
}]);