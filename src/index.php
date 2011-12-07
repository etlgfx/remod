<?php

class NotFoundException extends Exception {}

class Request {
	protected $controller;

	public function __construct($controller) {
		if (!is_string($controller)) {
			throw new Exception('Controller name argument must be string');
		}

		$this->controller = $controller;
	}

	public function getController() {
		return $this->controller;
	}
}

class RenderRequest extends Request {
	protected $object_slug;

	public function __construct(array $parts) {
		parent::__construct('layout');

		if (count($parts) < 1) {
			throw new NotFoundException('Unable to route request');
		}

		$this->object_slug = $parts[0];

		if (isset($parts[1])) {
			var_export($parts[1]);
		}
	}
}

class Router {
	public function __construct() { }

	public function route() {
		if (preg_match('#renderPage/(.+)$#i', $_SERVER['REQUEST_URI'], $matches)) {
			return new RenderRequest(explode('/', $matches[1]));
		}

		throw new NotFoundException('Unable to route request');
	}
}

class Dispatcher {
	private $request;

	public function __construct() {
		$router = new Router();
		$this->request = $router->route();

		$this->execute();
	}

	public function execute() {
		$controller = Controller::factory($this->request->getController());
		$controller->execute();
	}
}

abstract class Controller {
	public static function factory($class) {
		$class = ucfirst(strtolower($class)) . 'Controller';

		if (class_exists($class)) {
			return new $class();
		}
		else {
			throw new NotFoundException('Unable to instantiate controller class: '. $class);
		}
	}
}

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
}
catch (Exception $e) {
	header('HTTP/1.0 500 Internal Server Error');
}

?>
