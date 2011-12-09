<?php

require_once PATH_LIB .'Request.class.php';

class Router {
	protected $routes;

	public function __construct() {
		$this->routes = array(
			'#renderPageEdit/([^/]+)/([^/]+)$#i' => 'renderPageEdit',
			'#renderPage/([^/]+)/([^/]+)/callback/([^/]+)$#i' => 'renderCallback',
			'#renderPage/([^/]+)$#i' => 'renderPage',
			'#renderLayoutPreview/([^/]+)$#i' => 'renderLayoutPreview',
			'#renderPageView/([^/]+)/([^/]+)$#i' => 'renderPageView', //TODO - verify what this is for
			//TODO where is the call to module.renderAdmin?

		);
	}

	public function route($uri) {
		foreach ($this->routes as $route => $callback) {
			if (preg_match($route, $uri, $matches)) {
				return call_user_func(array($this, $callback), $uri, $matches);
			}
		}

		throw new NotFoundException('Unable to route request');
	}

	protected function renderPageEdit($uri, $matches) {
	}

	protected function renderCallback($uri, $matches) {
	}

	protected function renderPage($uri, $matches) {
		return new RenderRequest(explode('/', $matches[1]));
	}

	protected function renderLayoutPreview($uri, $matches) {
	}

	protected function renderPageView($uri, $matches) {
	}
}

?>
