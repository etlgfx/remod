<?php

class JSModule extends Module {
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

		$temp_data = array(
			'data' => $data,
			'request' => $request,
			'config' => $config,
		);
		$temp_file = $this->writeTempData(json_encode($temp_data));

		$command = self::NODE_BINARY . " " . PATH . "js/render_module.js " . $this->module_path . " $mode $temp_file";
		return shell_exec($command);
	}
}
