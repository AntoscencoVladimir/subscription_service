<?php
define('API_SERVER_ROOT_DIR', dirname(__FILE__));

mb_internal_encoding("UTF-8");
error_reporting(E_ALL);
ini_set("display_errors", 1);

set_include_path(get_include_path() . PATH_SEPARATOR . "core" . PATH_SEPARATOR . "entities");
spl_autoload_register();

Entity::setDb(new DataBase(Config::DB_HOST, Config::DB_USER,Config::DB_PASSWORD, Config::DB_NAME, Config::DB_SYM_QUERY));