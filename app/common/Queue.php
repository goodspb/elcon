<?php
namespace Common;

use Library\Queue\QueueException;
/**
 * Class Queue
 * @package Common
 */
class Queue
{
    /* @var $queueAdapter \Library\Queue\QueueAdapter */
    private $queueAdapter;

    public function __construct()
    {
        $queueConfig = Config::get('queue');
        if (!fnGet($queueConfig, 'enable')) {
            throw new QueueException(' Queue function is not enable. ');
        }
        $default = fnGet($queueConfig, 'default');
        if (is_null($defaultDriver = fnGet($queueConfig, 'drivers/' . $default))) {
            throw new QueueException(' Default queue driver is not found. ');
        }

        $class = "Library\\Queue\\" . ucfirst(strtolower($default));
        if (!class_exists($class)) {
            throw new QueueException(' Default queue driver is not found. ');
        }

        $this->queueAdapter = new $class($defaultDriver);
        if ($this->queueAdapter->init() === false) {
            throw new QueueException(' Queue init field ');
        }
    }

    public static function newQueue()
    {
        return new self();
    }

    public function getAdapter()
    {
        return $this->queueAdapter;
    }

    public function __call($name, $arguments)
    {
        $adapter = $this->getAdapter();
        if (method_exists($adapter, $name)) {
            return call_user_func_array(array($adapter, $name), $arguments);
        }
        throw new QueueException("Queue method not found");
    }
}