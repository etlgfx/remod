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
	protected $parts;

	public function __construct(array $parts) {
		parent::__construct('layout');

		if (count($parts) < 1) {
			throw new NotFoundException('Unable to route request');
		}

		$this->object_slug = $parts[0];
		$this->parts = array_slice($parts, 1);
	}

	public function getSlug() {
		return $this->object_slug;
	}

}

?>
