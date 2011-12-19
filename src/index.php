<?php

define('PATH', __DIR__ .'/');
define('PATH_LIB', PATH . 'classes/');
define('PATH_CONFIG', '/etc/syncapse/remod/');

require_once PATH_LIB .'Autoload.class.php';

new Autoload();

try {
	new Dispatcher(
		strpos($_SERVER['REQUEST_URI'], '?') ?
			strstr($_SERVER['REQUEST_URI'], '?', true) :
			$_SERVER['REQUEST_URI']
	);
}
catch (NotFoundException $e) {
	header('HTTP/1.0 404 Not Found');
	header('Content-type: text/plain');
	echo $e;
}
catch (Exception $e) {
	header('HTTP/1.0 500 Internal Server Error');
	header('Content-type: text/plain');
	echo $e;
}

?>
