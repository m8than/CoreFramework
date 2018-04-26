<?php
namespace Core\PerformanceLogger;

class Stopwatch
{
    private $_starttime;
    private $_totalMs = 0;
    private $_name;
    public function __construct($name)
    {
        $this->_name = $name;
    }
    public function getName()
    {
        return $this->_name;
    }
    public function getTotalMs()
    {
        return $this->_totalMs;
    }
    public function isRunning()
    {
        return isset($this->_starttime);
    }
    public function start()
    {
        if(!$this->isRunning())
        {
            $this->_starttime = microtime(true);
        }
    }
    public function stop()
    {
        if($this->isRunning())
        {
            $this->_totalMs += round((microtime(true) - $this->_starttime)*1000, 2);
            unset($this->_starttime);
        }
    }
    public function reset()
    {
        unset($this->_starttime);
        $this->_totalMs = 0;
    }
}