<?php

define('PATH', 'src/');
define('PATH_LIB', PATH . 'classes/');
define('PATH_CONFIG', '/etc/syncapse/remod/');

require_once PATH_LIB .'Autoload.class.php';

new Autoload();