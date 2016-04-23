#!/usr/bin/env php
<?php

error_reporting(E_ALL);

use Phalcon\Di\FactoryDefault\Cli as CliDI,
    Phalcon\Cli\Console as ConsoleApp;

$di = new CliDI();

defined('ROOT_PATH') || define('ROOT_PATH', dirname(__DIR__));

include ROOT_PATH .'/commands/loader.php';

include ROOT_PATH . '/bootstrap/functions.php';

\Common\Db::register($di);
\Common\Cache::register($di);
\Common\Log::register($di);
\Common\Config::register($di);
\Common\Aliases::register($di);

$di->set('dispatcher',function(){
    $dispatcher = new \Phalcon\Cli\Dispatcher();
    $dispatcher->setDefaultNamespace('Commands\Tasks');
    return $dispatcher;
});

$console = new ConsoleApp();
$console->setDI($di);

$arguments = array();
foreach ($argv as $k => $arg) {
    if ($k == 1) {
        $arguments['task'] = $arg;
    } elseif ($k == 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

define('CURRENT_TASK',   (isset($argv[1]) ? $argv[1] : null));
define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

try {
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage()."\n\n";
    exit(255);
}

