<?php

use Planck\Core\PlanckApp;

require '../vendor/autoload.php';

// handy utility functions
require '../src/Core/Utils/Utils.php';

// config
$config = [];

include_once '../config/services.php';
include_once '../config/routes.php';
include_once '../config/listeners.php';
include_once '../config/database.php';
include_once '../config/app.php';

error_reporting($config['app.errorlevel']);
ini_set('display_errors', $config['app.debug']);

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}

set_error_handler('exceptions_error_handler');

PlanckApp::run($router, $container, $config);
