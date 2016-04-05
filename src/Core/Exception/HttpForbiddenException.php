<?php

namespace Planck\Core\Exception;

use Planck\Core\Exception\BaseException;

class HttpForbiddenException extends BaseException {
  public function __construct($message, $code = 403) {
    parent::__construct($message, $code);
  }
}