<?php

require_once PATH_LIB .'Config.class.php';

class Autoload {
	const AUTOLOAD_CONFIG_KEY = 'autoload';

	private $classes;

	public function __construct() {
		$this->classes = Config::read(self::AUTOLOAD_CONFIG_KEY);

		spl_autoload_register(array($this, 'load'), true);
	}

	public function load($class) {
		if (isset($this->classes[$class])) {
			require PATH . $this->classes[$class];
		}
		else {
			throw new Exception('Autoloader cannot find this class: '. $class);
		}
	}

}

?>
