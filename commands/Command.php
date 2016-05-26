<?php
namespace Commands;

use Phalcon\Di;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Config, Log, Db;

class Command extends SymfonyCommand
{

    public function di()
    {
        return Di::getDefault();
    }

    public function config($key = null, $defaultValue = null)
    {
        return Config::get($key, $defaultValue);
    }

    public function log($message = null, array $context = null)
    {
        return Log::debug($message, $context);
    }

    public function db()
    {
        return Db::getInstance();;
    }

    public function error($text)
    {
        // white text on a red background
        return "<error>{$text}</error>";
    }

    public function info($text)
    {
        // green text
        return "<info>{$text}</info>";
    }

    public function comment($text)
    {
        // yellow text
        return "<comment>{$text}</comment>";
    }

    public function question($text)
    {
        // black text on a cyan background
        return "<question>{$text}</question>";
    }

}