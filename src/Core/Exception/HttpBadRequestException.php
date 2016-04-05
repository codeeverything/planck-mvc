<?php

namespace Planck\Core\Exception;

use Planck\Core\Exception\BaseException;

class HttpBadRequestException extends BaseException {
  public function __construct($message, $code = 400) {
    parent::__construct($message, $code);
  }
}