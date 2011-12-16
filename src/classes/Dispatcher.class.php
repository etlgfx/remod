<?php

class Dispatcher {
	private $request;

	public function __construct($uri) {
		$router = new Router();
		$this->request = $router->route($uri);

		$this->execute();
	}

	public function execute() {
		$controller = Controller::factory($this->request);
		$controller->execute();
	}
}

?>
