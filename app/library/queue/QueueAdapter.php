<?php
namespace Library\Queue;

abstract class QueueAdapter
{
    protected $config;

    public function __construct($config)
    {
        return $this->config = $config;
    }

    public function getConfig($key = '', $default = null)
    {
        return $key == '' ? $this->config : (fnGet($this->config, $key, $default));
    }

    abstract public function init();
    abstract public function put($tube, $data);
    abstract public function delete($key);
}