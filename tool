#!/usr/bin/env php
<?php

define('ROOT_PATH', __DIR__);

require ROOT_PATH.'/vendor/autoload.php';

use Commands\Application;
use Phalcon\Di\FactoryDefault\Cli as CliDI;

$di = new CliDI();
include ROOT_PATH . '/bootstrap/functions.php';

\Common\Db::register($di);
\Common\Cache::register($di);
\Common\Log::register($di);
\Common\Config::register($di);
\Common\Aliases::register($di);

$consoles = require ROOT_PATH. '/commands/consoles.php';

$application = new Application();
//$application->setDi($di);

foreach ($consoles as $console) {
    $application->add(new $console);
}

$application->run();