<?php

class JSSocketModule extends Module {
	private $id;
	private $module_path;

	function __construct($module_uuid) {
		$this->id = $module_uuid;
		$this->module_path = $this->getModulePath($module_uuid);
	}

	public function render($mode, $data = array(), $request = array(), $config = array()) {
		if ( !$this->isValidRenderMode($mode) ) {
			throw new Exception('Invalid render mode: ' . $mode);
		}

		if (!$f = fsockopen('unix:///tmp/remod.sock', -1, $errno, $errstr)) {
			throw new Exception("Unable to open socket: $errno - $errstr");
		}

		fwrite($f, json_encode(array(
			'mode' => $mode,
			'module' => $this->id,
			'data' => $data,
			'config' => $config,
			'request' => $request,
		)));

		$return = stream_get_contents($f);
		fclose($f);

		return $return;
	}
}

?>
