<?php

class LayoutDefaults {
	public $layout_id;
	public $data;

	public function __construct($id) {
		$this->data = array();

		$dbh = PDOFactory::PDO();

		$stmt = $dbh->prepare('SELECT name, value FROM layouts_defaults_data WHERE layout_id = :id');
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			$this->layout_id = $id;

			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$name = explode('.', $row['name']);

				if (isset($this->data[$name[0]])) {
					$this->data[$name[0]][$name[1]] = $row['value'];
				}
				else {
					$this->data[$name[0]] = array($name[1] => $row['value']);
				}
			}
		}
	}

	public function get($module_name) {
		return isset($this->data[$module_name]) ? $this->data[$module_name] : array();
	}
}

?>
