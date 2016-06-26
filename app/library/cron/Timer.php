<?php
namespace Library\Cron;

class Timer
{
    protected static $_start = array(0, 0);
    protected static $_stop = array(0, 0);

    public static function diff($start = null, $stop = null)
    {
        $start and self::start($start);
        $stop and self::stop($stop);
        return (self::$_stop[0] - self::$_start[0]) + (self::$_stop[1] - self::$_start[1]);
    }

    public static function start($microtime = null)
    {
        $microtime or $microtime = microtime();
        self::$_start = explode(' ', $microtime);
    }

    public static function stop($microtime = null)
    {
        $microtime or $microtime = microtime();
        self::$_stop = explode(' ', $microtime);
    }
}
