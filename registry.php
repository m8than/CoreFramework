<?php

class Registry
{
    private static $_keyvalues;
    public static function add($key, $value)
    {
        self::$_keyvalues[$key][] = $value;
    }
    public static function set($key, $value)
    {
        self::$_keyvalues[$key] = $value;
    }
    public static function get($key)
    {
        if(isset(self::$_keyvalues[$key]))
            return self::$_keyvalues[$key];
        else
            return false;
    }
    public static function dump()
    {
        die(print_r(self::$_keyvalues));
    }
}