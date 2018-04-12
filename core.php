<?php
DEFINE('CORE_SECURE', true);
DEFINE('ROOT', __DIR__.'/');

DEFINE('APP','app/');

DEFINE('CONTROLLERS','controllers/');
DEFINE('MODELS','models/');
DEFINE('VIEWS','views/');

DEFINE('C','config/');
DEFINE('H','helpers/');

session_start();


//Essentials 
require_once ROOT . 'registry.php';
require_once ROOT . 'database.php';
require_once ROOT . 'router.php';
require_once ROOT . 'template.php';

require_once ROOT . APP . 'controller.php';
require_once ROOT . APP . 'model.php';

//Config
require_once ROOT . C . 'config.php';
require_once ROOT . C . 'routes.php';

//Helpers
require_once ROOT . H . 'general.php';

//$db_config = Registry::get('db_config');
//Registry::set('db', new Database($db_config['DB_HOST'], $db_config['DB_NAME'],
//                                $db_config['DB_USER'], $db_config['DB_PASS'],
//                                $db_config['DB_PREFIX']));
?>