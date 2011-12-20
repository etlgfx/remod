<?php

class ContentSet extends AbstractModel {
	public $data;

	public function __construct($page_id) {
		$dbh = PDOFactory::PDO();

		$stmt = $dbh->prepare('SELECT * FROM content_sets WHERE page_id = :id AND status = 0');
		$stmt->bindParam(':id', $page_id);

		if ($stmt->execute() && $this->_model_data = $stmt->fetch(PDO::FETCH_OBJ));
		else {
			throw new NotFoundException('Content Set not found, page_id: '. var_export($page_id, true));
		}

		$stmt = $dbh->prepare('SELECT name, value FROM content_sets_data WHERE content_set_id = :id');
		$stmt->bindParam(':id', $this->_model_data->id);

		if (!$stmt->execute()) {
			throw new Exception('Unable to fetch content set data for: '. $this->_model_data->id);
		}

		$this->data = Util::contentSetToArray($stmt);
	}
}

?>
