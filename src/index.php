<?php

define('PATH', __DIR__ .'/');
define('PATH_LIB', PATH . 'classes/');
define('PATH_CONFIG', '/etc/syncapse/remod/');

require_once PATH_LIB .'Dispatcher.class.php';
require_once PATH_LIB .'model/Page.class.php';

class NotFoundException extends Exception {}

class LayoutController extends Controller {
	public function execute() {
		$slug = $this->request->getSlug();

		$page = new Page($slug);
		echo $page->layout->render();
	}
}

try {
	new Dispatcher($_SERVER['REQUEST_URI']);
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
