<?php
namespace Common\Model;

use Common\Model;
use Library\Cron\Timer;
use Common\Log;

/**
 * Class Cron
 * @method string getClass()
 * @method string getMethod()
 * @method string getName()
 * @method string getType()
 * @package Cron\Model
 */
class Cron extends Model
{

    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_INITIALIZED = 'initialized';
    const STATUS_RUNNING = 'running';

    /**
     *  保存配置信息CLASS
     */
    protected static $cron_classes = array();

    /**
     * @param $class
     * @return mixed  获取配置的 CLASS
     */
    public function getSingleton($class)
    {
        isset(static::$cron_classes[$class]) or static::$cron_classes[$class] = new $class;
        return static::$cron_classes[$class];
    }

    /**
     * @param $cronJob
     * @return $this
     * 初始化 任务状态
     */
    public function cronInitialize($cronJob)
    {
        $this->setData($cronJob);
        $now = date('Y-m-d H:i:s');
        $this->setData('status', self::STATUS_INITIALIZED);
        $this->setData('created_at', $now);
        $this->setData('updated_at', $now);
        $this->setData('error','0');
        $this->setData('type','0');
        $this->setData('ms','0');
        $this->save();
        return $this;
    }

    /**
     * @return $this  run 命令
     */
    public function run()
    {
        $this->setData('run_at', date('Y-m-d H:i:s'))->setData('status', self::STATUS_RUNNING)->save();
        Timer::start();
        try {
            $class = $this->getData('class');
            $method = $this->getData('method');
            if (!class_exists($class)) {
                throw new \Exception(sprintf('Class "%s" not found!', $class));
            }
            if (!method_exists($class, $method)) {
                throw new \Exception(sprintf('Method "%s::%s()" not found!', $class, $method));
            }
            $callback = array($this->getSingleton($class), $method);
            call_user_func($callback);
            Timer::stop();
            $this->setData('ms', round(Timer::diff() * 1000))->setData('status', self::STATUS_COMPLETED)->save();

        } catch (\Exception $e) {
            Timer::stop();
            $this->setData('ms', round(Timer::diff() * 1000))
                ->setData('status', self::STATUS_FAILED)
                ->setData('error', $e->getMessage() . "\nParams:\n" . var_export($this->getData(), true))->save();
            Log::log(Log::INFO, $e->getMessage() . "\n" . $e->getTraceAsString());
        }
        return $this;
    }


}
