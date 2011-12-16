<?php

class LayoutController extends Controller {
	public function execute() {
		$slug = $this->request->getSlug();

		$page = new Page($slug);
		echo $page->layout->render();
	}
}

?>
