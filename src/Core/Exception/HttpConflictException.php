<?php

namespace Planck\Core\Exception;

use Planck\Core\Exception\BaseException;

class HttpConflictException extends BaseException {
  public function __construct($message, $code = 409) {
    parent::__construct($message, $code);
  }
}