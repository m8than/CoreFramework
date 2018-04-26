<?php
namespace Core\FunctionCache;

class FunctionCache
{
    private static $_cache_dir;
    
    public static function setCacheDir($dir)
    {
        self::$_cache_dir = $dir;
    }
    public static function get($function_name, $params, $cache_expiry_seconds)
    {
        $write_cache = true;
        $cache_path = self::getCachePath($function_name, $params);
        if(is_file($cache_path) && ((time() - filemtime($cache_path)) < $cache_expiry_seconds || $cache_expiry_seconds == 0))
        {
            return unserialize(file_get_contents($cache_path));
        }
        else
        {
            return self::refresh($function_name, $params);
        }
    }
    public static function refresh($function_name, $params)
    {
        $test = call_user_func_array($function_name, $params);
        $cache_path = self::getCachePath($function_name, $params);
        if (!is_dir(dirname($cache_path)))
        {
            mkdir(dirname($cache_path), 777, true);
        }
        file_put_contents($cache_path, serialize($test));
        return $test;
    }
    public static function getCachePath($function_name, $params)
    {
        $identifier = md5(implode('', $params));
        $function_name = str_replace('\\', '_', $function_name);
        $function_name = str_replace('::', '-', $function_name);
        return sprintf( ROOT . '%s/%s_%s.php', self::$_cache_dir, $function_name, $identifier);
    }
}