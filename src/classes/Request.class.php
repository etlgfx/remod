<?php

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

?>
