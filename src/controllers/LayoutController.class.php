<?php

class LayoutController extends Controller {
	public function execute() {
		$slug = $this->request->getSlug();

		$page = new PageFacebook($slug);
		echo $page->render($_SERVER['REQUEST_URI']);
	}
}

?>
