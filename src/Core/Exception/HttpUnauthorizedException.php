<?php

namespace Planck\Core\Exception;

use Planck\Core\Exception\BaseException;

class HttpUnauthorizedException extends BaseException {
  public function __construct($message, $code = 401) {
    parent::__construct($message, $code);
  }
}