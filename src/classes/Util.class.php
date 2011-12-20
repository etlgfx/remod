<?php

class Util {
	public static function contentSetToArray(PDOStatement $stmt) {
		$data = array();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$name = explode('.', $row['name']);

			if (isset($data[$name[0]])) {
				$data[$name[0]][$name[1]] = $row['value'];
			}
			else {
				$data[$name[0]] = array($name[1] => $row['value']);
			}
		}

		return $data;
	}
}

?>
