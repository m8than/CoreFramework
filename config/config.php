<?php
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

    "template_cache_dir" => "helpers/template_cache"
);
Registry::set("db_config", $db_config);
Registry::set("config", $config);
