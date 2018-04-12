<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../core.php';

Router::prepare($_GET['uri']);
Router::dispatch();

//$config = Registry::get('config');
//Template::assignData('config', $config);
//Template::assignData('cur_url', $config['URL'] . '/' . Registry::get('uri'));

Template::output();
?>
