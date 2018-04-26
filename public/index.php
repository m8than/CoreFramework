<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../Core.php';

use Core\Router\Router;
use Core\Registry\Registry;
use Core\Database\Database;
use Core\Template\Template;
use Core\FunctionCache\FunctionCache;
use Core\PerformanceLogger\PerformanceLogger;

//Load config from files
Core\Config\Config::load();
Core\Config\Routes::load();

$config = Registry::get('config');
$db_config = Registry::get('db_config');

//Connect to database
Registry::set('db', new Database($db_config['DB_HOST'], $db_config['DB_NAME'],
                                $db_config['DB_USER'], $db_config['DB_PASS'],
                                $db_config['DB_PREFIX']));

//Set temporary folders
Template::setCacheDir($config['template_cache_dir']);
FunctionCache::setCacheDir($config['function_cache_dir']);

//Find route
PerformanceLogger::run('Router Logic', function() {
        Router::prepare($_GET['uri']);
    });
    
//Run controller logic
PerformanceLogger::run('Controller Logic', function() {
        Router::dispatch();
    });

Template::assignData('config', $config);
Template::assignData('cur_url', $config['URL'] . '/' . Registry::get('uri'));

//Run template logic
PerformanceLogger::run('Template Logic', function() {
        Template::output();
    });
?>