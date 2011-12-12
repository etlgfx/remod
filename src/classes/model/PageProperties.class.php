<?php

class PageProperties {
	public $page_id;
	public $data;

	public function __construct($page_id) {
		$this->data = array();

		$dbh = PDOFactory::PDO();

		$stmt = $dbh->prepare('SELECT name, value FROM page_properties WHERE page_id = :id');
		$stmt->bindParam(':id', $page_id);

		if ($stmt->execute()) {
			$this->page_id = $page_id;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$this->data[$row['name']] = $row['value'];
			}
		}
		else {
			throw new NotFoundException('Error fetching page properties: '. var_export($page_id, true));
		}
	}

	public function get($property_name) {
		return isset($this->data[$property_name]) ? $this->data[$property_name] : null;
	}
}

?>
