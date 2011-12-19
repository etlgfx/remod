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

	public function render($uri, $request) {
		$dom = new DOMDocument('1.0', 'UTF-8');

		if (!$dom->loadXML($this->_model_data->template)) {
			throw new Exception('Error opening the layout document');
		}

		$xpath = new DOMXpath($dom);
		$modules = $xpath->query('//module');

		foreach ($modules as $module_node) {
			$id = trim($module_node->getAttribute('id'));
			$uuid = '4eb436c8-38fc-426f-a53f-2b5c0acc4267' /*$module_node->getAttribute('m:uuid')*/;

			$f = $dom->createDocumentFragment();

			$module = new JSModule($uuid);
			//TODO get content sets 
			if (!$f->appendXML($module->render('view', array(), $request, $this->defaults->get($id)))) {
				throw new Exception('There was an error appending the XML to the document');
			}

			$parent = $module_node->parentNode;
			$parent->replaceChild($f, $module_node);
		}

		return $dom->saveHTML();
	}
}

?>
