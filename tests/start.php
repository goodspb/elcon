<?php

define('ROOT_PATH', dirname(__DIR__));

error_reporting(E_ALL | E_STRICT);

include ROOT_PATH . '/bootstrap/functions.php';
include ROOT_PATH . '/bootstrap/di.php';

$loader->registerNamespaces([
    'Tests' => ROOT_PATH . '/tests',
], true);
