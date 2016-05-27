<?php
namespace Library\Queue\Adapter;

use Library\Queue\QueueAdapter;

class Beanstalk extends QueueAdapter
{
    private $pheanstalk;

    public function init()
    {
        if (!class_exists("\\Pheanstalk\\Pheanstalk")) {
            return false;
        }

        $host = $this->getConfig('host');
        $port = $this->getConfig('port');
        $this->pheanstalk = new \Pheanstalk\Pheanstalk($host, $port);
        return true;
    }

    /**
     * @return \Pheanstalk\Pheanstalk
     */
    public function getHandler()
    {
        return $this->pheanstalk;
    }

    public function put($tube, $data)
    {
        return $this->getHandler()->useTube($tube)->put(serialize($data));
    }

    public function delete($job)
    {
        return $this->getHandler()->delete($job);
    }

    public function reserve()
    {
        return $this->getHandler()->reserve();
    }

}