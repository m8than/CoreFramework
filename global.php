<?php
DEFINE('ROOT', __DIR__.'/');
DEFINE('APP','app/');
DEFINE('CONTROLLERS','controllers/');
DEFINE('MODELS','models/');
DEFINE('VIEWS','views/');

DEFINE('C','config/');
DEFINE('H','helpers/');

session_start();

require_once ROOT . 'registry.php';
require_once ROOT . APP . 'controller.php';
require_once ROOT . APP . 'model.php';

require_once ROOT . C . 'config.php';
require_once ROOT . C . 'routes.php';

require_once ROOT . H . 'database.php';
require_once ROOT . H . 'general.php';
require_once ROOT . H . 'template.php';

require_once ROOT . 'router.php';

$db_config = Registry::get('db_config');
Registry::set('db', new Database($db_config['DB_HOST'], $db_config['DB_NAME'],
                                $db_config['DB_USER'], $db_config['DB_PASS'],
                                $db_config['DB_PREFIX']));
?>
