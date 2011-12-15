<?php

class PHPModule extends Module {
    private $module_path;
    private $javascript_code;
    private $javascript_header;

    const LIB_PATH = '/lib';
    const JS_PATH = '/js';

    function __construct($module_uuid) {
        $this->module_path = $this->getModulePath($module_uuid);

        $this->javascript_code = '';
        $this->javascript_header = '';

        $this->loadModule();
    }

    public function render($mode) {
        if( !$this->isValidRenderMode($mode) ) {
            throw new Exception('Invalid module render mode');
        }

        $code_file = $this->writeJavascriptCode();
        $header_file = $this->writeJavascriptHeader();

        return $this->runJavascript($code_file, $mode);
    }

    public function getJavascriptCode() {
        return $this->javascript_code;
    }

    public function getJavascriptHeader() {
        return $this->javascript_header;
    }

    protected function writeToTempFile($data) {
        $tmp_file = tempnam(sys_get_temp_dir(), uniqid());
        $fp = fopen($tmp_file, 'w');

        if ( !fwrite($fp, $data) ) {
            throw new Exception('Error writing JS to file');
        }
        return $tmp_file;
    }

    protected function writeJavascriptCode() {
        return $this->writeToTempFile($this->javascript_code);
    }

    protected function writeJavascriptHeader() {
        return $this->writeToTempFile($this->javascript_header);
    }

    private function loadModule() {
        // First we need the module.js code
        $this->javascript_code = file_get_contents($this->module_path . '/module.js');

        // Now load the libs
        $this->javascript_code .= $this->getLibs();

        // Grab the JS source to be placed in a <script> tag along with module output
        $this->javascript_header = $this->getHeader();
    }

    private function getLibs() {
        return $this->loadSource($this->module_path . self::LIB_PATH);
    }

    private function getHeader() {
        return $this->loadSource($this->module_path . self::JS_PATH);
    }

    private function loadSource($path, $data = null) {
        if ( !is_dir($path) ) {
            throw new Exception('Invalid JS source path');
        }

        $handle = opendir($path);
        if ( !$handle ) {
            throw new Exception('Unable to open path: ' . $path);
        }

        while (false !== ($file = readdir($handle))) {
            if ( $file == '.' || $file == '..' ) {
                continue;
            }

            if( is_dir($path . '/' . $file) ) {
                return $this->loadSource($path . '/' . $file, $data);
            }
            $javascript_code = file_get_contents($path . '/' . $file);

            if( !$javascript_code ) {
                throw new Exception('Error reading file: ' . $path . '/' . $file);
            }
            $data .= $javascript_code;
        }
        return $data;
    }

    private function runJavascript($code, $mode) {
        $command = self::NODE_BINARY . ' ' . PATH . 'js/module.js ' . $code . ' ' . $mode ;
        return shell_exec($command);
    }
}

?>