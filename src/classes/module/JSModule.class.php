<?php

class JSModule extends Module {
    private $id;

    function __construct($id) {
        $this->id = $id;
    }

    public function render($mode) {
        if ( !$this->isValidRenderMode($mode) ) {
            throw new Exception('Invalid render mode: ' . $mode);
        }
        $command = self::NODE_BINARY . ' ' . PATH . 'js/render_module.js ' . $this->id . ' ' . $mode ;
        return shell_exec($command);
    }

}