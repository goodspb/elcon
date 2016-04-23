<?php

define('ROOT_PATH', dirname(__DIR__));
if (empty($_FILES) && !empty($_SERVER['USE_SERVICE'])) {
    require ROOT_PATH . '/bootstrap/service.php';
}

error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('UTC');

/**
 * Read function
 */
include ROOT_PATH . '/bootstrap/functions.php';

profilerStart();

set_exception_handler('exceptionHandler');
set_error_handler('errorHandler');
/**
 * Read services
 */
include ROOT_PATH . '/bootstrap/di.php';

/**
 * Handle the request
 */
$app = new Phalcon\Mvc\Application($di);
$di->setShared('app', $app);

Router::dispatch($app);

profilerStop();
