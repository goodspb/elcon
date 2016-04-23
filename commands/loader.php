<?php

$loader = new \Phalcon\Loader();
$defaultLoader = include ROOT_PATH . "/vendor/composer/autoload_classmap.php";
$loader->registerClasses($defaultLoader);
$namespaces = include_once ROOT_PATH . '/vendor/composer/autoload_namespaces.php';
foreach ($namespaces as $k => $values) {
    $k = trim($k, '\\');
    if (!isset($namespaces[$k])) {
        $dir = '/' . str_replace('\\', '/', $k) . '/';
        $namespaces[$k] = implode($dir . ';', $values) . $dir;
    }
}
$loader->registerNamespaces($namespaces);
$loader->register();
