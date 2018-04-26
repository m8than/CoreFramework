<?php
namespace Core\PerformanceLogger;

class PerformanceLogger
{
    private static $_logs = array();
    public static function add($stopwatch)
    {
        self::$_logs[$stopwatch->getName()] = $stopwatch->getTotalMs();
    }
    public static function run($name, $function)
    {
        $stopwatch = new Stopwatch($name);
        $stopwatch->start();
        $return = $function();
        $stopwatch->stop();
        self::add($stopwatch);
        return $return;
    }
    public static function getLogs()
    {
        return self::$_logs;
    }
    public static function dump()
    {
        print_r(self::$_logs);
    }
}