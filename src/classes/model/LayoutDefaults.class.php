<?php

require_once PATH_LIB .'PDOFactory.class.php';

class LayoutDefaults {
	public $layout_id;
	public $data;

	public function __construct($layout_id) {
		$this->data = array();

		$dbh = PDOFactory::PDO();

		$stmt = $dbh->prepare('SELECT name, value FROM layouts_defaults_data WHERE layout_id = :id');
		$stmt->bindParam(':id', $layout_id);

		if ($stmt->execute()) {
			$this->layout_id = $layout_id;

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
		else {
			throw new NotFoundException('Error fetching layout defaults: '. var_export($layout_id, true));
		}
	}

	public function get($module_name) {
		return isset($this->data[$module_name]) ? $this->data[$module_name] : array();
	}
}

?>
