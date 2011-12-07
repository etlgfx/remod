<?php

require_once PATH_LIB .'Router.class.php';
require_once PATH_LIB .'Request.class.php';
require_once PATH_LIB .'Controller.class.php';

class Dispatcher {
	private $request;

	public function __construct() {
		$router = new Router();
		$this->request = $router->route();

		$this->execute();
	}

	public function execute() {
		$controller = Controller::factory($this->request);
		$controller->execute();
	}
}

?>
