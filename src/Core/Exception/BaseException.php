<?php

namespace Planck\Core\Exception;

class BaseException extends \Exception {
  public function __construct($message, $code = 500) {
    parent::__construct($message, $code);
  }
  
  public function buildResponse() {
      return [
          'error' => $this->getMessage(),
          'code' => $this->getCode(),
      ];
  }
}