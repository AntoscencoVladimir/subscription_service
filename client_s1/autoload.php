<?php
define('MAIL_CLIENT_ROOT_DIR', dirname(__FILE__));

mb_internal_encoding('UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

set_include_path(get_include_path() . PATH_SEPARATOR . 'core' . PATH_SEPARATOR . 'entities');
spl_autoload_register(function ($class) {
    include $class . '.php';
});
Entity::setDb(new DataBase(Config::DB_FULL_PATH, Config::DB_NAME, Config::DB_SYM_QUERY));