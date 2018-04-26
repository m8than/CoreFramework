<?php
DEFINE('CORE_SECURE', true);
DEFINE('ROOT', dirname(__FILE__).'/');

DEFINE('APP','App/');
DEFINE('ENGINE','Core/');

DEFINE('CONTROLLERS','Controllers/');
DEFINE('MODELS','Models/');
DEFINE('VIEWS','Views/');

session_start();

//Autoloader
require_once ROOT . ENGINE . 'Autoloader/Autoloader.php';
Core\Autoloader\Autoloader::register();

//use Core\Database\Database;
//use Core\Registry\Registry;


?>
