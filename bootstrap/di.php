<?php

use Common\Aliases;
use Common\Cache;
use Common\Config;
use Common\Db;
use Common\Log;
use Common\Router;
use Common\Session;
use Common\Cookie;
use Common\Filter;
use Common\View;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;

defined('ROOT_PATH') or define('ROOT_PATH', dirname(__DIR__));

// The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
$di = new FactoryDefault();

// Register class loader
$classMap = include ROOT_PATH . '/vendor/composer/autoload_classmap.php';
$loader = new Loader;
$loader->registerClasses($classMap);
$loader->register();
$di->setShared('loader', $loader);

// Register components
Db::register($di);
Cache::register($di);
Log::register($di);
Config::register($di);
Aliases::register($di);
Router::register($di);
Session::register($di);
Cookie::register($di);
Filter::register($di);
View::register($di);

return $di;
