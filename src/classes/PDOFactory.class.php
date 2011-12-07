<?php

class PDOFactory {

	private static $dbh = null;

	public static function PDO() {
		if (self::$dbh === null) {

			$ini = parse_ini_file(PATH .'config/config.ini', true);

			if (!isset(
				$ini['pdo']['driver'],
				$ini['pdo']['host'],
				$ini['pdo']['dbname'],
				$ini['pdo']['user'],
				$ini['pdo']['password'])) {
					throw new Exception('Application not configured properly');
				}

			$ini = $ini['pdo'];

			self::$dbh = new PDO($ini['driver'] .':host='. $ini['host'] .';dbname='. $ini['dbname'], $ini['user'], $ini['password']);
		}

		return self::$dhb;
	}
}

?>
