<?php

class JSModule extends Module {
    private $id;
    private $module_path;

    function __construct($module_uuid) {
        $this->id = $module_uuid;
        $this->module_path = $this->getModulePath($module_uuid);
    }

    public function render($mode) {
        if ( !$this->isValidRenderMode($mode) ) {
            throw new Exception('Invalid render mode: ' . $mode);
        }
        $command = self::NODE_BINARY . ' ' . PATH . 'js/render_module.js ' . $this->module_path . ' ' . $mode ;
        return shell_exec($command);
    }

}