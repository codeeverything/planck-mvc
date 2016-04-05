<?php

namespace Planck\Core\Exception;

use Planck\Core\Exception\BaseException;

class HttpNotFoundException extends BaseException {
  public function __construct($message, $code = 404) {
    parent::__construct($message, $code);
  }
}