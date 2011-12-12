<?php

require_once PATH_LIB .'model/AbstractModel.class.php';
require_once PATH_LIB .'model/Layout.class.php';

class Page extends AbstractModel {
	public $layout;

	public function __construct($slug) {
		$dbh = PDOFactory::PDO();

		if (preg_match('#^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$#', $slug)) {
			$stmt = $dbh->prepare('SELECT * FROM pages WHERE pages.uuid = :slug');
		}
		else if (ctype_digit($slug)) {
			$stmt = $dbh->prepare('SELECT * FROM pages WHERE pages.id = :slug');
		}
		else {
			throw new NotFoundException('Unable to fetch page metadata, malformed slug: '. var_export($slug, true));
		}

		$stmt->bindParam(':slug', $slug);

		if ($stmt->execute() && $this->_model_data = $stmt->fetch(PDO::FETCH_OBJ)) {
			$this->layout = new Layout($this->_model_data->layout_id);
		}
		else {
			throw new NotFoundException('Page not found: '. var_export($slug, true));
		}
	}
}

?>
