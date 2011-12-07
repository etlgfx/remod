<?php

define('PATH', __DIR__ .'/');
define('PATH_LIB', PATH . 'classes/');

require_once PATH_LIB .'Dispatcher.class.php';

class NotFoundException extends Exception {}

class LayoutController extends Controller {
	public function execute() {
		echo 'execute';
	}
}

try {
	new Dispatcher();
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
