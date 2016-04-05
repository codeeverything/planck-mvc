<?php

use Planck\Core\PlanckApp;
use Planck\Core\Lib\Timer;

// keep the global scope clean by wrapping everything in an immediately executing function
call_user_func(function () {
  require '../vendor/autoload.php';

  // handy utility functions
  require '../src/Core/Utils/Utils.php';
  
  // config
  $config = [];
  
  Timer::start('config');
  include_once '../config/app.php';
  include_once '../config/services.php';
  include_once '../config/listeners.php';
  include_once '../config/database.php';
  include_once '../config/routes.php';
  Timer::end('config');
  
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
  
  // Timer::times();
  
  // echo (memory_get_peak_usage(true) / 1024 / 1024) . 'MB';
});
