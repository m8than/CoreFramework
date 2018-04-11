<?php
DEFINE('ROOT', __DIR__.'/');
DEFINE('APP','app/');
DEFINE('CONTROLLERS','controllers/');
DEFINE('MODELS','models/');
DEFINE('VIEWS','views/');

DEFINE('C','config/');
DEFINE('H','helpers/');

session_start();

require_once ROOT.'registry.php';
require_once ROOT . APP . 'controller.php';
require_once ROOT . APP . 'model.php';
require_once ROOT.'router.php';

//Load Files
$confighelperfiles = array_merge(glob(ROOT.C.'*.php'), glob(ROOT.H.'*.php'));
foreach($confighelperfiles as $file)
{
    include_once $file;
}

//Load Config/Helper Classes
foreach($confighelperfiles as $file)
{
    $loaded_file = basename($file);
    $class_name = ucfirst(substr($loaded_file, 0, -4));
    if(method_exists($class_name, 'initstatic'))
    {
       $class_name::initstatic();
    }
    else
    {
        Registry::add('warnings', ucfirst($class_name) . '::initstatic() doesn\'t exist');
    }
}
?>
