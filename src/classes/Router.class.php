<?php

require_once PATH_LIB .'Request.class.php';

class Router {
	public function __construct() { }

	public function route() {
		if (preg_match('#renderPage/(.+)$#i', $_SERVER['REQUEST_URI'], $matches)) {
			return new RenderRequest(explode('/', $matches[1]));
		}

		throw new NotFoundException('Unable to route request');
	}
}

?>
