<?php
namespace Commands;

use Phalcon\Cli\Task;
use Common\Db;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Common\Config;

class BaseTask extends Task
{
    protected function getDB()
    {
        return Db::getInstance();
    }

    protected function _log($message, $isError = false)
    {
        $filePath = ROOT_PATH . '/storage/logs/commands/';
        if (!is_dir($filePath)) mkdir($filePath);
        $filename = 'phalcon-commands.log';
        $logger = new FileAdapter($filePath . $filename);
        $level = $isError ? Logger::ERROR : Logger::INFO;
        $className = get_called_class();
        $logger->log("[[ {$className} ]]{$level}: {$message}", $level);
        return $this;
    }

    protected function getConfig($key, $default = null)
    {
        return Config::get($key, $default);
    }

}
