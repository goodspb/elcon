<?php

namespace Common;

use Common\Model\MetaData\InCache;
use Phalcon\Di;
use Phalcon\Db as PhalconDb;
use Phalcon\Db\Adapter\Pdo as Adapter;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Log;

class Db extends PhalconDb
{
    /**
     * @var Adapter|Adapter\Mysql
     */
    protected static $instance;

    /**
     * @return Adapter|Adapter\Mysql
     */
    public static function getInstance()
    {
        static::$instance === null and static::setInstance(Di::getDefault()->getShared('db'));
        return static::$instance;
    }

    public static function register(Di $di)
    {
        $di->remove('modelsMetadata');
        $di->setShared('modelsMetadata', function () {
            return new InCache();
        });
        $di->setShared('db', function () {
            /* debug log */
            if ($isDebug = Config::get('app.debug')) {
                $eventsManager = new Manager();
                /* @var $event Event */
                $eventsManager->attach('db', function ($event, $connection) {
                    if ($event->getType() == 'beforeQuery') {
                        Log::info($connection->getSQLStatement());
                    }
                });
            }
            $default = Config::get('database.default');
            $config = Config::get('database.connections.' . $default);
            $class = $config['adapter'];
            unset($config['adapter']);
            strpos($class, '\\') === false and $class = 'Phalcon\\Db\\Adapter\\Pdo\\' . $class;
            $connection = new $class($config);
            /* debug log */
            if ($isDebug) {
                $connection->setEventsManager($eventsManager);
            }
            return $connection;
        });
    }

    public static function setInstance(Adapter $instance)
    {
        static::$instance = $instance;
    }
}
