<?php
session_start();
if (version_compare(phpversion(), '5.6.0', '<') == true) {
    die ('Your php version is old');
}

require "config.php";
require site_path . "libs/smarty/Smarty.class.php";

function __autoload($class_name) {
        $parts = explode("_", $class_name);
        $filename = "";
        foreach ($parts as $part) {
            $filename .= DIRSEP . $part;
        }
        $filename .= ".php";
        $filename = site_path . 'classes' . $filename;
        if (file_exists($filename) == false) {
                return false;
        }
        include ($filename);
}

$registry = Registry::getInstance();

$router = new Router($registry);
$registry->set ('router', $router);

$userManager = new UserManager();
$registry->set(Registry::USER_OBJECT, $userManager);

$router->setPath (site_path . 'controllers');

if (cache_enable) $router->delegate_cache();
else $router->delegate();
?>