<?php

class LayoutDefaults {
	public $layout_id;
	public $data;

	public function __construct($layout_id) {
		$dbh = PDOFactory::PDO();

		$stmt = $dbh->prepare('SELECT name, value FROM layouts_defaults_data WHERE layout_id = :id');
		$stmt->bindParam(':id', $layout_id);

		if ($stmt->execute()) {
			$this->layout_id = $layout_id;

			$this->data = Util::contentSetToArray($stmt);
		}
		else {
			throw new NotFoundException('Error fetching layout defaults: '. var_export($layout_id, true));
		}
	}

	public function get($module_name) {
		return isset($this->data[$module_name]) ? $this->data[$module_name] : array();
	}
}

?>
