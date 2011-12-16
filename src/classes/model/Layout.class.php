<?php

class Layout extends AbstractModel {
	public $defaults;

	public function __construct($id) {
		$dbh = PDOFactory::PDO();

		$stmt = $dbh->prepare('SELECT id, uuid, template, created_ts, modified_ts, status FROM layouts WHERE id = :id');
		$stmt->bindParam(':id', $id);

		if ($stmt->execute() && $this->_model_data = $stmt->fetch(PDO::FETCH_OBJ));
		else {
			throw new NotFoundException('Layout not found: '. var_export($id, true));
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

		foreach ($modules as $module_node) {
			$id = trim($module_node->getAttribute('id'));

			$f = $dom->createDocumentFragment();

			//$module = new Module($uuid);
			//$f->appendXML($module->render($this->default->get($id), $request, $config));

			$f->appendXML('<p>'. $module_node->getAttribute('m:uuid') .'</p>');

			$parent = $module_node->parentNode;
			$parent->replaceChild($f, $module_node);
		}

		echo $dom->saveHTML();
	}
}

?>
