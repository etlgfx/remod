<?php

require_once PATH_LIB .'model/AbstractModel.class.php';
require_once PATH_LIB .'model/LayoutDefaults.class.php';

class Layout extends AbstractModel {
	public $defaults;

	public function __construct($id) {
		$dbh = new PDO('mysql:host=localhost;dbname=social_apps', 'root', '');

		$stmt = $dbh->prepare('SELECT id, uuid, template, created_ts, modified_ts, status FROM layouts WHERE id = :id');
		//$stmt = $dbh->prepare('SELECT id, uuid, created_ts, modified_ts, status FROM layouts WHERE id = :id');
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			$this->_model_data = $stmt->fetch(PDO::FETCH_OBJ);
		}
		else {
			throw new NotFoundException('Layout not found: '. $id);
		}

		$this->defaults = new LayoutDefaults($id);
	}

	public function render() {
		$dom = new DOMDocument('1.0', 'UTF-8');

		if (!$dom->loadXML($this->_model_data->template)) {
			throw new Exception('Error opening the layout document');
		}

		$xpath = new DOMXpath($dom);
		$modules = $xpath->query('//module');

		foreach ($modules as $module) {
			echo $module->getAttribute('m:uuid') . PHP_EOL;
			echo $module->getAttribute('m:name') . PHP_EOL;
			$id = trim($module->getAttribute('id'));

			var_export($this->defaults->get($id));
		}
	}
}

?>
