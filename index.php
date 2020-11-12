<?php

error_reporting(E_ALL);
session_start();
ob_start();

if(file_exists('config.php')) {
	require('config.php');
} else {
	header('Location: install.php');
}

define('ROOT', $_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR);
define('CONTROLLER', ROOT.'controller'.DIRECTORY_SEPARATOR);
define('CORE', ROOT.'core'.DIRECTORY_SEPARATOR);
define('DATA', ROOT.'data'.DIRECTORY_SEPARATOR);
define('MODEL', ROOT.'model'.DIRECTORY_SEPARATOR);
define('VIEW', ROOT.'view'.DIRECTORY_SEPARATOR);

define('WEB_URL', 'http://'.$_SERVER['SERVER_NAME']);
define('ADMIN_EMAIL', 'cristea_gabriel@yahoo.com');

$modules = [ROOT, CONTROLLER, CORE, DATA];

set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, $modules));
spl_autoload_register('spl_autoload', false);

new Application;
