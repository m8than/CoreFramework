<?php
require_once "global.php";


$db_config = Registry::get('db_config');

//set DB object
/*Registry::set('db', new Database($db_config['DB_HOST'], $db_config['DB_NAME'],
                                $db_config['DB_USER'], $db_config['DB_PASS'],
                                $db_config['DB_PREFIX']));
                                */
Router::prepare();
Router::dispatch();

//$config = Registry::get('config');
//Template::assignData('config', $config);
//Template::assignData('cur_url', $config['URL'] . '/' . Registry::get('uri'));

Template::output();
?>