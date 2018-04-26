<?php
namespace Core\Config;

use Core\Registry\Registry;

class Config
{
    public static function load()
    {
        $db_config = array
        (
            "DB_HOST" => "localhost",
            "DB_USER" => "",
            "DB_PASS" => "",
            "DB_NAME" => "",
            "DB_PREFIX" => ""
        );
        $config = array
        (
            "URL" => "",
        
            "name" => "CoreFramework",
            "description" => "CoreFramework is a simple MVC framework built for speed",
            "author" => "Nathan Wilce",
        
            "template_cache_dir" => "Temp/template_cache",
            "function_cache_dir" => "Temp/function_cache"
        );
        Registry::set("db_config", $db_config);
        Registry::set("config", $config);
    }
}