<?php

abstract class Controller {
	protected $request;

	public static function factory(Request $request) {
		$class = ucfirst(strtolower($request->getController())) . 'Controller';

		if (class_exists($class)) {
			return new $class($request);
		}
		else {
			throw new NotFoundException('Unable to instantiate controller class: '. $class);
		}
	}

	public function __construct(Request $request) {
		$this->request = $request;
	}

	abstract public function execute();
}

?>
