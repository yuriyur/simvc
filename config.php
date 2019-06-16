<?php
define ('DIRSEP', DIRECTORY_SEPARATOR);
$site_path = realpath(dirname(__FILE__) . DIRSEP ) . DIRSEP;
define ('site_path', $site_path);

define ('first_value', 1);

define ('DB_HOST', 'localhost');
define ('DB_NAME', 'simvc');
define ('DB_USER', 'test');
define ('DB_PASSWORD', 'test');

define ('SITE_URL', 'http://simvc.test');

define ('cache_enable', false);

?>