<?php
namespace Core\Autoloader;

class Autoloader
{
    public static function loadModule($_class)
    {
        $file_path = ROOT . str_replace('\\', '/', $_class) . '.php';
        require_once $file_path;
    }
    public static function register()
    {
        spl_autoload_register('self::loadModule');
    }
}