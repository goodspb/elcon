<?php
namespace Common;

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
        foreach ($queueConfig as $queue => $config) {
            $class = "Library\\Queue\\".ucfirst(strtolower($queue));
            if (class_exists($class) && fnGet($config, 'enable')) {
                $this->queueAdapter = new $class($config);
                if ($this->queueAdapter->init()) {
                    break;
                }
            }
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
        if (method_exists($adapter,$name)) {
            return call_user_func_array(array($adapter,$name),$arguments);
        }
        throw new \Exception("method not found");
    }
}